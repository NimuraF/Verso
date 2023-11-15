const { createClient } = require('redis');

const server = require('http').createServer()
const options = {
    cors: {
        origin: ["http://verso.ru", "http://verso.ru:5173"],
        methods: ["GET", "POST"]
    }
}

const io = require('socket.io')(server, options)

server.listen(8080);

async function main() {

    /**
     * Create sub-client for REDIS PUB/SUB
     */
    const sub = await createClient()
    .on('error', err => console.log('Redis Client Error', err))
    .connect();

    const listener = (message, channel) => console.log(message, channel);

    await sub.subscribe('new-messages', listener);

    console.log('START SERVER!');

    io.on('connection', socket => {

        socket.on('sendik', function () {
            socket.broadcast.emit("privet", { name: 30 })
            console.log(socket.handshake.headers);
        })

        io.emit("Пользователь присоединился!");
    })

}

main();