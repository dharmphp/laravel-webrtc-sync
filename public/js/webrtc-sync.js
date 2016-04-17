// WebRTC DataChannel + WebSocket Synchronizer
function VenueSync(presentationId, options) {
    this.presentationId = presentationId;
    this.options = options || {};
    this.peerConnection = null;
    this.dataChannel = null;

    this.init();
}

VenueSync.prototype.init = function() {
    var self = this;
    var socket = io('//' + window.location.hostname + ':3000');
    this.socket = socket;

    socket.emit('join-presentation', this.presentationId, 'venue');

    socket.on('slide-updated', function(data) {
        console.log('Received slide update via Redis socket:', data);
        if (self.options.onSlideChange) {
            self.options.onSlideChange(data);
        }
    });

    socket.on('poll-activated', function(data) {
        if (self.options.onPollActive) {
            self.options.onPollActive(data);
        }
    });
};
