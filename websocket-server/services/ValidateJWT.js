const jose = require('jose');

async function validateJWT(jwt_token, secret_key) {

    console.log(jwt_token);
    const claims = await jose.decodeJwt(jwt_token);
    console.log(claims);
}

module.exports = validateJWT;