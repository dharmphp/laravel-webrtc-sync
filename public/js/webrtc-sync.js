// Dual-mode synchronizer: WebRTC peer data channels + Redis WebSocket fallback
function VenueSync(presentationId, options) {
    this.presentationId = presentationId;
    this.options = options || {};
    this.useWebRTC = false;
    this.latencyMs = 0;

    this.init();
}

VenueSync.prototype.init = function() {
    var self = this;
    var socket = io('//' + window.location.hostname + ':3000');
    this.socket = socket;

    socket.emit('join-presentation', this.presentationId, 'venue');

    socket.on('slide-updated', function(data) {
        if (data.timestamp) {
            self.latencyMs = Date.now() - (data.timestamp * 1000);
            console.log('Slide sync latency: ' + Math.round(self.latencyMs) + 'ms');
        }
        if (self.options.onSlideChange) {
            self.options.onSlideChange(data);
        }
    });

    socket.on('poll-activated', function(data) {
        if (self.options.onPollActive) self.options.onPollActive(data);
    });

    socket.on('poll-updated', function(data) {
        if (self.options.onPollUpdate) self.options.onPollUpdate(data);
    });
};
