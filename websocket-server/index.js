const { createClient } = require('redis');

const server = require('http').createServer()
const options = {}
const io = require('socket.io')(server, options)

async function main() {

    const sub = await createClient()
    .on('error', err => console.log('Redis Client Error', err))
    .connect();

    const listener = (message, channel) => console.log(message, channel);

    await sub.subscribe('message', listener);

    console.log('START SERVER!');

    //console.log(await sub.get('verso_database_goggg'));

    io.on('connection', socket => {
        console.log(socket.id)
    })

    server.listen(3000)
}

main();