<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Live Audience Poll — {{ $presentation->title }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #0f172a; color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .header { background: #1e293b; padding: 14px 16px; border-bottom: 1px solid #334155; }
        .header h1 { font-size: 16px; margin: 0; color: #38bdf8; }
        .content { padding: 16px; }
        .slide-status { background: #1e293b; border-radius: 8px; padding: 12px; margin-bottom: 16px; font-size: 14px; text-align: center; }
        .poll-card { background: #1e293b; border-radius: 12px; padding: 18px; border: 1px solid #38bdf8; box-shadow: 0 4px 14px rgba(0,0,0,0.4); }
        .question-title { font-size: 18px; font-weight: bold; margin-bottom: 16px; }
        .btn-option { display: block; width: 100%; background: #334155; color: #fff; border: 1px solid #475569; padding: 14px 16px; border-radius: 8px; font-size: 16px; margin-bottom: 10px; text-align: left; cursor: pointer; transition: background 0.2s; }
        .btn-option:active { background: #0284c7; }
        .voted-msg { display: none; text-align: center; color: #4ade80; font-size: 18px; padding: 20px 0; }
    </style>
</head>
<body>
<div class="header">
    <h1>{{ $presentation->title }}</h1>
</div>
<div class="content">
    <div id="slideInfo" class="slide-status">Speaker is currently presenting Slide <span id="currentSlideNum">1</span></div>
    
    <div id="pollArea" class="poll-card" style="display: none;">
        <div id="qTitle" class="question-title">What is your favorite design pattern?</div>
        <div id="optionsList"></div>
        <div id="votedAlert" class="voted-msg">&#10003; Vote Recorded! Watch projector for live results.</div>
    </div>
</div>

<script src="/js/socket.io.js"></script>
<script>
    var presentationId = "{{ $presentation->id }}";
    var deviceId = localStorage.getItem('voter_device_id') || ('dev-' + Math.random().toString(36).substr(2, 9));
    localStorage.setItem('voter_device_id', deviceId);

    var socket = io('//' + window.location.hostname + ':3000');
    socket.emit('join-presentation', presentationId, 'audience');

    socket.on('slide-updated', function(data) {
        document.getElementById('currentSlideNum').textContent = data.slideIndex + 1;
    });

    socket.on('poll-activated', function(data) {
        var pollArea = document.getElementById('pollArea');
        document.getElementById('qTitle').textContent = data.question;
        var optList = document.getElementById('optionsList');
        optList.innerHTML = '';
        document.getElementById('votedAlert').style.display = 'none';

        data.options.forEach(function(opt) {
            var btn = document.createElement('button');
            btn.className = 'btn-option';
            btn.textContent = opt.text;
            btn.onclick = function() {
                submitVote(data.questionId, opt.id);
            };
            optList.appendChild(btn);
        });
        pollArea.style.display = 'block';
    });

    function submitVote(qId, optId) {
        fetch('/api/presentations/' + presentationId + '/vote', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ question_id: qId, option_id: optId, device_id: deviceId })
        }).then(function() {
            document.getElementById('optionsList').innerHTML = '';
            document.getElementById('votedAlert').style.display = 'block';
        });
    }
</script>
</body>
</html>
