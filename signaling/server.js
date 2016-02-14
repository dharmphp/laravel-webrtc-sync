// Node.js Signaling & WebRTC Relay Server with Redis Pub/Sub
const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const redis = require('redis');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

const PORT = process.env.PORT || 3000;
const redisSub = redis.createClient();

redisSub.subscribe('presentation-events');

redisSub.on('message', (channel, message) => {
  try {
    const payload = JSON.parse(message);
    const roomId = payload.presentation_id;
    // Broadcast instantly to all sockets in this presentation room
    io.to('room-' + roomId).emit(payload.event, payload.data);
  } catch (err) {
    console.error('Error parsing redis message', err);
  }
});

io.on('connection', (socket) => {
  socket.on('join-presentation', (presentationId, clientType) => {
    const room = 'room-' + presentationId;
    socket.join(room);
    socket.clientType = clientType;
    console.log(`Client ${socket.id} (${clientType}) joined ${room}`);
  });

  // Slide changed event from presenter socket
  socket.on('change-slide', (data) => {
    // broadcast directly to peers in room
    socket.to('room-' + data.presentationId).emit('slide-updated', data);
  });

  // WebRTC signaling
  socket.on('webrtc-signal', (data) => {
    io.to(data.target).emit('webrtc-signal', {
      sender: socket.id,
      signal: data.signal
    });
  });
});

server.listen(PORT, () => {
  console.log('WebRTC Signaling + Redis subscriber running on port ' + PORT);
});
