<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" type="text/css" href="css/app.css">
</head>
<body>
    <div id="app"></div>
    <script>
        var Laravel={};
        Laravel.csrfToken="{{csrf_token()}}";
    </script>
    <script src="js/app.js"></script>
</body>
</html>