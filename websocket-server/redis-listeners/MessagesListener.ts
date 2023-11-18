/**
 * Callback function for redis messages channel listener
 * @param io 
 * @returns Function
 */
export default function roomsListener(io : any) : Function {
    return async (message : any, channel : any) => {
        await io.to("chat:" + String(JSON.parse(message).chat_id)).emit('new-message', { message: message });
    }
}