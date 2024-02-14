const { createServer } = require("http");
const { Server } = require("socket.io");
const Redis = require("ioredis");

const httpServer = createServer();
const io = new Server(httpServer, {
    cors: {
        origin: "*",
    }
});

const redis = new Redis({
    host: "redis",
    port: 6379,
});

redis.psubscribe('*', function(err, count) {
    console.log(err);
});

redis.on("pmessage", (channel, message) => {
    console.log(`Received message from ${channel}: ${message}`);
    io.emit(channel, message);
});

io.listen(3000);

io.on("connection", (socket) => {
    console.log("A user connected");
});

console.log("Socket.io server listening on port 3000");
