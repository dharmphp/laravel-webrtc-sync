<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Venue Projector Display — {{ $presentation->title }}</title>
    <style>
        body, html { margin: 0; padding: 0; width: 100%; height: 100%; background: #000; overflow: hidden; font-family: 'Helvetica Neue', Arial, sans-serif; color: #fff; }
        #canvasArea { width: 100vw; height: 100vh; display: flex; align-items: center; justify-content: center; position: relative; }
        .slide-content { max-width: 90%; max-height: 90%; font-size: 3.5rem; text-align: center; }
        .sync-badge { position: absolute; bottom: 20px; right: 20px; background: rgba(0,255,100,0.2); border: 1px solid #00ff64; color: #00ff64; padding: 6px 14px; border-radius: 20px; font-size: 14px; }
    </style>
</head>
<body>
<div id="canvasArea">
    <div id="slideContent" class="slide-content">
        Loading presentation stream...
    </div>
    <div id="syncStatus" class="sync-badge">Connecting WebRTC...</div>
</div>

<script src="/js/socket.io.js"></script>
<script src="/js/webrtc-sync.js"></script>
<script>
    var presentationId = "{{ $presentation->id }}";
    var sync = new VenueSync(presentationId, {
        onSlideChange: function(slide) {
            document.getElementById('slideContent').innerHTML = slide.html || slide.title;
            document.getElementById('syncStatus').textContent = 'Synced (Slide ' + slide.index + ')';
        }
    });
</script>
</body>
</html>
