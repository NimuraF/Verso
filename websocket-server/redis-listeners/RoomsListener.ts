/**
 * Callback function for redis room channel listener
 * @param io 
 * @returns Function
 */
export default function roomsListener(io : any) : Function {
    return (message : any, channel : any) => {
        console.log(channel);
        io.to(channel.split(":")[1]).emit('new-message', { message: message });
    }
}