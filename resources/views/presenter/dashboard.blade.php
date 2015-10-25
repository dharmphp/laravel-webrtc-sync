<!DOCTYPE html>
<html>
<head>
    <title>{{ $presentation->title }} — Presenter Deck</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body { background: #1a1a24; color: #fff; padding-top: 20px; }
        .slide-stage { background: #000; height: 500px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 28px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <h3>{{ $presentation->title }} <small>Room PIN: {{ $presentation->access_code }}</small></h3>
            <div id="slideViewport" class="slide-stage">
                Slide 1: Welcome
            </div>
            <div style="margin-top: 15px;">
                <button id="prevBtn" class="btn btn-default btn-lg">&laquo; Prev</button>
                <button id="nextBtn" class="btn btn-primary btn-lg">Next &raquo;</button>
                <span id="slideCounter" style="margin-left: 20px;">Slide 1 / {{ count($presentation->slides) }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <h4>Connected Venues</h4>
            <ul id="venueList" class="list-group" style="color: #333;">
                <li class="list-group-item">Mumbai Hall (Waiting)</li>
                <li class="list-group-item">Vadodara Hall (Waiting)</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
