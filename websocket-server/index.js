"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (g && (g = 0, op[0] && (_ = 0)), _) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
Object.defineProperty(exports, "__esModule", { value: true });
var createClient = require('redis').createClient;
var ValidateJWT_1 = require("./services/ValidateJWT");
/**
 * Redis listeners
 */
var RoomsListener_1 = require("./redis-listeners/RoomsListener");
var MessagesListener_1 = require("./redis-listeners/MessagesListener");
var server = require('http').createServer();
var options = {
    cors: {
        origin: ["http://verso.ru", "http://verso.ru:5173"],
        methods: ["GET", "POST"]
    }
};
var io = require('socket.io')(server, options);
var jwt_key = "websocket-key";
/* Start server */
server.listen(8080, function () {
    console.log('Server start on port 8080');
});
function main() {
    return __awaiter(this, void 0, void 0, function () {
        var messages, rooms, redisTokensWebsocket;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0: return [4 /*yield*/, createClient().on('error', function (err) { return console.log('Redis Client Error', err); }).connect()];
                case 1:
                    messages = _a.sent();
                    return [4 /*yield*/, messages.subscribe('messages', (0, MessagesListener_1.default)(io))];
                case 2:
                    _a.sent();
                    return [4 /*yield*/, createClient().on('error', function (err) { return console.log('Redis Client Error', err); }).connect()];
                case 3:
                    rooms = _a.sent();
                    return [4 /*yield*/, rooms.subscribe('rooms', (0, RoomsListener_1.default)(io))];
                case 4:
                    _a.sent();
                    return [4 /*yield*/, createClient({ database: 3 }).on('error', function (err) { return console.log('Redis Client Error', err); }).connect()];
                case 5:
                    redisTokensWebsocket = _a.sent();
                    io.on('connection', function (socket) {
                        return __awaiter(this, void 0, void 0, function () {
                            var validationStatus;
                            return __generator(this, function (_a) {
                                switch (_a.label) {
                                    case 0:
                                        validationStatus = null;
                                        if (!socket.handshake.headers.authorization) return [3 /*break*/, 2];
                                        return [4 /*yield*/, (0, ValidateJWT_1.default)(socket.handshake.headers.authorization, jwt_key)];
                                    case 1:
                                        validationStatus = _a.sent();
                                        _a.label = 2;
                                    case 2:
                                        /* If it was not possible to validate the user, then close the connection */
                                        if (validationStatus === null) {
                                            socket.disconnect();
                                        }
                                        /* Add socket-session for current user */
                                        return [4 /*yield*/, redisTokensWebsocket.sendCommand(['LPUSH', 'user_' + validationStatus, socket.id])];
                                    case 3:
                                        /* Add socket-session for current user */
                                        _a.sent();
                                        return [4 /*yield*/, redisTokensWebsocket.sendCommand(['expire', 'user_' + validationStatus, '86400'])];
                                    case 4:
                                        _a.sent();
                                        /* Send new event when successfuly authenticated */
                                        socket.emit('successful', { socket_id: socket.id });
                                        /**
                                         * Subscribe to all received chats
                                         */
                                        socket.on('subscribe_all', function (chats) {
                                            for (var i = 0; i < chats.data.length; i++) {
                                                socket.join("chat:" + String(chats.data[i].id));
                                            }
                                        });
                                        socket.on('disconnect', function (info) {
                                            return __awaiter(this, void 0, void 0, function () {
                                                return __generator(this, function (_a) {
                                                    switch (_a.label) {
                                                        case 0: return [4 /*yield*/, redisTokensWebsocket.sendCommand(['LREM', 'user_' + validationStatus, '0', socket.id])];
                                                        case 1:
                                                            _a.sent();
                                                            return [2 /*return*/];
                                                    }
                                                });
                                            });
                                        });
                                        io.emit("Пользователь присоединился!");
                                        return [2 /*return*/];
                                }
                            });
                        });
                    });
                    return [2 /*return*/];
            }
        });
    });
}
/**
 * START APP
 */
main();
