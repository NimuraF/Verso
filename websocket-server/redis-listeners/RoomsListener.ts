/**
 * Callback function for redis room channel listener
 * @param io 
 * @returns Function
 */
export default function roomsListener(io : any, redisClient : any) : Function {
    return async (message : any, channel : any) => {
        let channelMessage : any = JSON.parse(message);
        let socketArray : Array<string> = await redisClient.sendCommand(['LRANGE', 'user_' + channelMessage.user.id, '0', '-1']);

        switch (channelMessage.action){

            case("connect-to-chat"): {
                for(let i : number = 0; i < socketArray.length; i++) {
                    io.in(socketArray[i]).socketsJoin('chat:' + channelMessage.chat.id);
                    io.to(socketArray[i]).emit("add_to_chat", { chat: channelMessage.chat });
                }
            }

            case("disconnect-from-chat"): {
                for(let i : number = 0; i < socketArray.length; i++) {
                    io.in(socketArray[i]).socketsLeave('chat:' + channelMessage.chat.id);
                    io.to(socketArray[i]).emit("remove_from_chat", { chat: channelMessage.chat });
                }
            }
        }

        io.to(channel.split(":")[1]).emit('new-message', { message: message });
    }
}