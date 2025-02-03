<!DOCTYPE html>
<html>

<head>
    <title>Crypto Tracker</title>
</head>

<body>
    <h1>Welcome to Crypto Tracker</h1>
    <p>Your cryptocurrency monitoring dashboard</p>

    @auth
    <a href="/dashboard">Go to Dashboard</a>
    @else
    <a href="/login">Login</a>
    <a href="/register">Register</a>
    @endauth
</body>

</html>