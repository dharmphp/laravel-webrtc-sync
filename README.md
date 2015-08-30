# Laravel WebRTC Multi-Location Presentation Sync

A real-time presentation synchronization and audience voting platform.
Allows a speaker in one location to synchronously control slides across multiple physical halls using WebRTC data channels and Redis sockets, while remote audience members can participate in live questionnaires and vote from mobile phones.

## Tech Stack
- Laravel 5.x Backend
- Node.js + Socket.io Signaling Server
- WebRTC DataChannels for P2P sync between venue projectors
- Redis Pub/Sub for cross-server event broadcasting
- Mobile-responsive Audience Web View
