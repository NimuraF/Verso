"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
/**
 * Callback function for redis room channel listener
 * @param io
 * @returns Function
 */
function roomsListener(io) {
    return function (message, channel) {
        console.log(channel);
        io.to(channel.split(":")[1]).emit('new-message', { message: message });
    };
}
exports.default = roomsListener;
