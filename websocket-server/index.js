const { createClient } = require('redis');

const server = require('http').createServer()
const options = {
    cors: {
        origin: "http://verso.ru",
        methods: ["GET", "POST"]
    }
}
const io = require('socket.io')(server, options)

server.listen(3000);

async function main() {

    const sub = await createClient()
    .on('error', err => console.log('Redis Client Error', err))
    .connect();

    const listener = (message, channel) => console.log(message, channel);

    await sub.subscribe('message', listener);

    console.log('START SERVER!');

    io.on('connection', socket => {
        console.log(socket.id)
    })

}

main();