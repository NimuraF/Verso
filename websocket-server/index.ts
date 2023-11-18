const { createClient } = require('redis');
import validateJWT from "./services/ValidateJWT";

/**
 * Redis listeners
 */
import roomListener from './redis-listeners/RoomsListener';
import messagesListener from './redis-listeners/MessagesListener'

const server = require('http').createServer()
const options = {
    cors: {
        origin: ["http://verso.ru", "http://verso.ru:5173"],
        methods: ["GET", "POST"]
    }
}

const io = require('socket.io')(server, options);

const jwt_key = "websocket-key";

/* Start server */
server.listen(8080, () => {
    console.log('Server start on port 8080');
});

async function main() : Promise<void> {

    /**
     * Create sub-client for messages REDIS PUB/SUB
     */
    const messages = await createClient().on('error', err => console.log('Redis Client Error', err)).connect();
    await messages.subscribe('messages', messagesListener(io));

    /**
     * Create sub-client for rooms REDS PUB/SUB
     */
    const rooms = await createClient().on('error', err => console.log('Redis Client Error', err)).connect();
    await rooms.subscribe('rooms', roomListener(io));

    /**
     * Create redis client for websocket-sessions list
     */
    const redisTokensWebsocket = await createClient({database: 3}).on('error', err => console.log('Redis Client Error', err)).connect();

    io.on('connection', async function (socket : any) {

        /**
         * Current socket validation status
         */
        let validationStatus : string|null = null;

        /**
         * If an authorization header containing a jwt token is received, 
         * then we call the user authorization function
         */
        if (socket.handshake.headers.authorization) 
        {
            validationStatus = await validateJWT(socket.handshake.headers.authorization, jwt_key);
        }

        /* If it was not possible to validate the user, then close the connection */
        if(validationStatus === null) 
        {
            socket.disconnect();
        }

        /* Add socket-session for current user */
        await redisTokensWebsocket.sendCommand(['LPUSH', 'user_' + validationStatus, socket.id]);
        await redisTokensWebsocket.sendCommand(['expire', 'user_' + validationStatus, '86400']);

        /* Send new event when successfuly authenticated */
        socket.emit('successful', { socket_id: socket.id });

        /**
         * Subscribe to all received chats
         */
        socket.on('subscribe_all', function (chats : any) {
            for (let i : number  = 0; i < chats.data.length; i++) {
                socket.join("chat:" + String(chats.data[i].id));
            }
        });

        socket.on('disconnect', async function (info : any) {
            await redisTokensWebsocket.sendCommand(['LREM', 'user_' + validationStatus, '0', socket.id]);
        })

        

        io.emit("Пользователь присоединился!");
    })

}

/**
 * START APP
 */
main();