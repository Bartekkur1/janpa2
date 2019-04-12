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
        login: <input value="<?=$login?>" type="text" name="login"></br>
        new password: <input type="password" name="password"></br>
        email: <input value="<?=$email?>" type="email" name="email"></br>
        <input type="submit" value="Save">
    </form>
    <?=$error?>
</body>
</html>