<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOARD READ</title>
    <style>
        #wrap {max-width:300px;margin:0 auto;text-align:center;}
        #wrap input, #wrap button {width:100%;}
        #wrap textarea {width:100%;resize: none;}
    </style>
</head>
<body>
    <div id="wrap">
        <h1>BOARD READ</h1>

        <form action="/board/write" method="post">
            <input type="hidden" name="_method" value="post">
            <input type="hidden" name="idx" value="<?php echo $res['idx']; ?>">
            <label>
                subject : <br/>
                <input type="text" name="subject" value="<?php echo $res['subject'] ?>" title="subject" maxlength="250" required disabled>
            </label>

            <br/>

            <label>
                content : <br/>
                <textarea name="content" cols="30" rows="10" title="content" required disabled><?php echo $res['content'] ?></textarea>
            </label>

            <br/>

            <label>
                writer : <?php echo $res['create_by']; ?>
            </label>

            <br/>

            <label>
                create at : <?php echo $res['create_at'] ?>
            </label>

            <br/>

            <button type="button" onclick="location.href='/board'">go to board list</button>
            
            <?php if ($_SESSION['LOGIN_ID'] === $res['create_by']) { ?>
                <input id="update" type="button" value="update">
                <input id="delete" type="button" value="delete">
            <?php } ?>

        </form>
    </div>


    <script>
    var update_button = document.getElementById('update');
    update_button.addEventListener('click', function(event) {
        if (this.type === 'button') {
            event.preventDefault();
            document.querySelector('input[name=subject]').disabled = '';
            document.querySelector('textarea[name=content]').disabled = '';

            update_button.type = 'submit';
            document.querySelector('input[name=_method]').value = 'patch';
            document.querySelector('form').action = '/board/update';
        }
    });

    var delete_button = document.getElementById('delete');
    delete_button.addEventListener('click', function(event) {
        if (this.type === 'button' && confirm('Are you sure you want to delete the post?')) {

            delete_button.type = 'submit';
            document.querySelector('input[name=_method]').value = 'delete';
            document.querySelector('form').action = '/board/delete';
        }
    });
    </script>
</body>
</html>