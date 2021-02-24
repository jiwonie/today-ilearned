<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join</title>
    <style>
        #wrap {max-width:300px;margin:0 auto;}
    </style>
</head>
<body>
    <div id="wrap">
        <form action="/join" method="post" onsubmit="validation()">
            <input type="hidden" name="_method" value="post">
            <table>
                <tr>
                    <td>id </td>
                    <td><input type="text" name="id" title="id" maxlength="20" required /></label></td>
                </tr>
                <tr>
                    <td>password </td>
                    <td><input type="password" name="pw" title="password" required /></label></td>
                </tr>
                <tr>
                    <td>retype </td>
                    <td><input type="password" name="pw_confirm" title="password_confirm" required /></label></td>
                </tr>
                <tr>
                    <td>name </td>
                    <td><input type="text" name="name" title="name" maxlength="20" required /></label></td>
                </tr>
            </table>

            <button type="button" onclick="location.href='/'">go to index page</button>
            <input type="submit" value="join" />
        </form>
    </div>


    <script type="text/javascript">
        function validation()
        {
            // ! validation
        }
    </script>
</body>
</html>