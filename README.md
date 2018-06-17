# Laravel WebRTC Multi-Location Presentation Sync

A real-time presentation synchronization and audience voting platform.
Allows a speaker in one location to synchronously control slides across multiple physical halls using WebRTC data channels and Redis sockets, while remote audience members can participate in live questionnaires and vote from mobile phones.

## Features Built
- **Slide Synchronization**: Millisecond sync between speaker deck and remote venue display screens across cities.
- **Audience Live Voting**: Mobile web interface where attendees enter 6-digit access code to answer quizzes in real-time.
- **Hybrid Networking**: WebRTC data channels for low-latency peer transmission with Redis Pub/Sub WebSocket fallback for firewalled networks.
- **Real-Time Poll Visualizer**: Live bar charts updating as responses arrive from hundreds of mobile devices.

## Tech Stack
- Laravel 5.x PHP Framework
- Node.js + Express + Socket.io (Signaling & Fallback Engine)
- Redis Pub/Sub
- WebRTC DataChannel API
- Bootstrap 4 Responsive Mobile UI

Created in 2015-2018 by Dharmesh Patel.
