<div id="wrap">
    <!-- header -->
    <div id="header">
        <a href="" class="title">BOARD READ</a>
    </div>

    <!-- content -->
    <div id="content">
        <form action="/board" method="post">
            <input type="hidden" name="_method" value="post">
            <input type="hidden" name="idx" value="<?php echo $res['idx']; ?>">

            <table class="read-board">
                <tr>
                    <th>subject</th>
                    <td>
                        <input type="text" name="subject" value="<?php echo $res['subject'] ?>" title="subject" maxlength="250" required disabled>
                    </td>
                </tr>

                <tr>
                    <th>content</th>
                    <td>
                        <textarea name="content" cols="30" rows="10" title="content" required disabled><?php echo $res['content'] ?></textarea>
                    </td>
                </tr>

                <tr>
                    <th>writer</th>
                    <td>
                        <?php echo $res['create_by']; ?>
                    </td>
                </tr>

                <tr>
                    <th>created</th>
                    <td>
                        <?php echo $res['create_at'] ?>
                    </td>
                </tr>
            </table>

            <button type="button" class="btn" onclick="location.href='/board'">go to board list</button>
            
            <?php if ($_SESSION['LOGIN_ID'] === $res['create_by']) { ?>
                <input id="update" class="btn" type="button" value="update">
                <input id="delete" class="btn" type="button" value="delete">
            <?php } ?>
        </form>

        <!-- footer -->
        <div id="footer">
            <label>&copy; <a href="javascript:void(0)">Jiwon Min</a>. All rights reserved.</label>
        </div>
    </div>
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
        document.querySelector('form').action = '/board';
    }
});

var delete_button = document.getElementById('delete');
delete_button.addEventListener('click', function(event) {
    if (this.type === 'button' && confirm('Are you sure you want to delete the post?')) {

        delete_button.type = 'submit';
        document.querySelector('input[name=_method]').value = 'delete';
        document.querySelector('form').action = '/board';
    }
});
</script>