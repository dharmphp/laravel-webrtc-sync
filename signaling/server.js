// Handled connection dropouts and automatic room resync
const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const redis = require('redis');

const app = express();
const server = http.createServer(app);
const io = socketIo(server, {
  pingTimeout: 5000,
  pingInterval: 10000
});

const redisSub = redis.createClient();
redisSub.subscribe('presentation-events');

redisSub.on('message', (channel, message) => {
  try {
    const payload = JSON.parse(message);
    const room = 'room-' + payload.presentation_id;
    io.to(room).emit(payload.event, payload.data);
  } catch (err) {
    console.error('Redis message handling error:', err);
  }
});

io.on('connection', (socket) => {
  socket.on('join-presentation', (presentationId, clientType) => {
    const room = 'room-' + presentationId;
    socket.join(room);
    socket.currentRoom = room;
    socket.clientType = clientType;
    console.log(`Socket ${socket.id} connected to ${room} (${clientType})`);
  });

  socket.on('disconnect', () => {
    if (socket.currentRoom) {
      io.to(socket.currentRoom).emit('peer-disconnected', { socketId: socket.id, type: socket.clientType });
    }
  });
});

server.listen(3000, () => {
  console.log('Signaling server online with heartbeat on port 3000');
});
