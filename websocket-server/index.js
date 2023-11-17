const { createClient } = require('redis');
const { roomListener } = require('./redisListeners/roomsListener.js');
const validateJWT = require('./services/ValidateJWT.js');

const server = require('http').createServer()
const options = {
    cors: {
        origin: ["http://verso.ru", "http://verso.ru:5173"],
        methods: ["GET", "POST"]
    }
}

const io = require('socket.io')(server, options);

const jwt_key = "websocket-key";

server.listen(8080, () => {
    console.log('Server start on port 8080');
});

async function main() {

    /**
     * Create sub-client for messages REDIS PUB/SUB
     */
    // const messages = await createClient().on('error', err => console.log('Redis Client Error', err)).connect();
    // await messages.subscribe('messages', messageListener);

    /**
     * Create sub-client for rooms REDS PUB/SUB
     */
    const rooms = await createClient().on('error', err => console.log('Redis Client Error', err)).connect();
    await rooms.subscribe('rooms', roomListener);

    /**
     * Create redis client for websocket map
     */
    const redisTokensWebsocket = await createClient({
        database: 3
    }).on('error', err => console.log('Redis Client Error', err)).connect();

    io.on('connection', socket => {

        validateJWT(socket.handshake.headers.authorization, jwt_key);

        redisTokensWebsocket.set(socket.id, 'SGSGSGSS');

        io.emit("Пользователь присоединился!");
    })

}

main();

// function messageListener(message, channel) { 
//     io.sockets.e
// };