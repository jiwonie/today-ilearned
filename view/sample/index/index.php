<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INDEX PAGE</title>
    <style>
        #wrap {max-width:300px;margin:0 auto;text-align:center;}
    </style>
</head>
<body>
    <div id="wrap">
        <h1>INDEX PAGE</h1>

        <button type="button" onclick="location.href='/board'" title="board">view board list</button>

        <?php if (!$_SESSION['IS_LOGIN']) { ?>
            <button type="button" onclick="location.href='/login'" title="Sign In">sign in</button>
            <button type="button" onclick="location.href='/join'" title="Sign Up">sign up</button>
        <?php } else { ?>
            <button type="button" onclick="location.href='/logout'" title="Sign Out">sign out</button>
        <?php } ?>
    </div>
</body>
</html>