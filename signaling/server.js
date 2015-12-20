// Node.js Signaling & WebRTC Relay Server
const express = require('express');
const http = require('http');
const socketIo = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

const PORT = process.env.PORT || 3000;

const rooms = {};

io.on('connection', (socket) => {
  console.log('Client connected:', socket.id);

  socket.on('join-room', (roomId, clientType) => {
    socket.join(roomId);
    if (!rooms[roomId]) rooms[roomId] = { presenter: null, venues: [], audience: [] };

    if (clientType === 'presenter') {
      rooms[roomId].presenter = socket.id;
    } else if (clientType === 'venue') {
      rooms[roomId].venues.push(socket.id);
      // notify presenter about new physical venue
      if (rooms[roomId].presenter) {
        io.to(rooms[roomId].presenter).emit('venue-connected', { socketId: socket.id });
      }
    } else {
      rooms[roomId].audience.push(socket.id);
    }
  });

  // WebRTC SDP Offer / Answer relay
  socket.on('webrtc-offer', (data) => {
    io.to(data.targetSocketId).emit('webrtc-offer', {
      fromSocketId: socket.id,
      offer: data.offer
    });
  });

  socket.on('webrtc-answer', (data) => {
    io.to(data.targetSocketId).emit('webrtc-answer', {
      fromSocketId: socket.id,
      answer: data.answer
    });
  });

  socket.on('webrtc-ice-candidate', (data) => {
    io.to(data.targetSocketId).emit('webrtc-ice-candidate', {
      fromSocketId: socket.id,
      candidate: data.candidate
    });
  });

  socket.on('disconnect', () => {
    console.log('Client disconnected:', socket.id);
  });
});

server.listen(PORT, () => {
  console.log('WebRTC signaling server listening on port ' + PORT);
});
