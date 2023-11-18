import { jwtVerify } from 'jose';

export default async function validateJWT(jwt_token : string, secret_key : string) : Promise<string|null> {

    /* Separate the bearer header */
    let token : string = jwt_token.split(" ")[1];

    /* Create new secret key object */
    const secret : Uint8Array = new TextEncoder().encode(
        secret_key
    )

    /* Verify jwt and get payload */
    const { payload } = await jwtVerify(token, secret);

    let user_id : string|null = payload.user_id ? String(payload.user_id) : null;

    return user_id;
}