function roomsListener(message, channel) {

    $roomData = JSON.parse(message);

    console.log($roomData);

    io.sockets.emit('rooms', { data: $roomData });
}

exports.roomsListener;