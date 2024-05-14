<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Ticketing System</title>
    <link href="../kiosk/kiosk.css" rel="stylesheet">
    
</head>
<body>  

<body>
    <div id="header">
        <button onclick="startOver()">Start Over</button>
        <div id="dateTime"></div>
    </div>
    <h1>Welcome to Our Service Ticketing System</h1>
    <button id="startButton">Get Started</button>

    <div id="categories" style="display:none;"></div>
    <div id="services" style="display:none;"></div>
    <div id="printButton" style="display:none;"></div>
    <div id="feedback" style="display:none;">
        <h2>Feedback</h2>
        <p>How was your experience?</p>
        <button onclick="submitFeedback('😊 Happy')">😊 Happy</button>
        <button onclick="submitFeedback('😐 Okay')">😐 Okay</button>
        <button onclick="submitFeedback('😞 Unhappy')">😞 Unhappy</button>
    </div>

    <script src="../kiosk/kiosk.js"></script>
</body>
</html>