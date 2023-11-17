
export default async function saveNewToken(redisClient, user, token) {

    await redisClient.set(user, token);

}