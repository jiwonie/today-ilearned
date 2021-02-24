<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOARD WRITE</title>
    <style>
        #wrap {max-width:300px;margin:0 auto;text-align:center;}
        #wrap input, #wrap button {width:100%;}
        #wrap textarea {width:100%;resize: none;}
    </style>
</head>
<body>
    <div id="wrap">
        <h1>BOARD WRITE</h1>

        <form action="/board/write" method="post">
            <input type="hidden" name="_method" value="post">
            <label>
                subject : <br/>
                <input type="text" name="subject" title="subject" maxlength="250" required>
            </label>

            <br/>

            <label>
                content : <br/>
                <textarea name="content" cols="30" rows="10" title="content" required></textarea>
            </label>

            <br/>

            <label>
                writer : <?php echo $_SESSION['LOGIN_NAME']; ?>
            </label>

            <br/>

            <button type="button" onclick="location.href='/board'">Go to List</button>
            <input type="submit" value="write">
        </form>
    </div>
</body>
</html>