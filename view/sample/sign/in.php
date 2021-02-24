<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <style>
        #wrap {max-width:300px;margin:0 auto;text-align:center;}
        #wrap input {width:90px;}
    </style>
</head>
<body>
    <div id="wrap">
        <form action="/login" method="post">
            <input type="hidden" name="_method" value="put">
            <input type="text" name="id" placeholder="id" />
            <input type="password" name="pw" placeholder="password" />
            <input type="submit" value="login" />
        </form>
    </div>
</body>
</html>