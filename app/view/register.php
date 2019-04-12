<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>janpa2</title>
</head>
<body>
    <form method="post">
        login: <input type="text" name="login"></br>
        password: <input type="password" name="password"></br>
        repeat password: <input type="password" name="repeatPassword"></br>
        email: <input type="email" name="email"></br>
        <input type="submit" value="Register">
    </form>
    <?=$error?>
</body>
</html>