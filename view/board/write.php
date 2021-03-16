<div id="wrap">
    <!-- header -->
    <div id="header">
        <a href="" class="title">BOARD WRITE</a>
    </div>

    <!-- content -->
    <div id="content">
        <form action="/board" method="post">
            <input type="hidden" name="_method" value="post">

            <table class="write-board">
                <tr>
                    <th>subject</th>
                    <td>
                        <input type="text" name="subject" title="subject" maxlength="250" required>
                    </td>
                </tr>

                <tr>
                    <th>content</th>
                    <td>
                        <textarea name="content" cols="30" rows="10" title="content" required></textarea>
                    </td>
                </tr>

                <tr>
                    <th>writer</th>
                    <td>
                        <?php echo $_SESSION['LOGIN_NAME']; ?>
                    </td>
                </tr>
            </table>

            <button type="button" class="btn" onclick="location.href='/board'">go to board list</button>
            <input type="submit" class="btn" value="write">
        </form>

        <!-- footer -->
        <div id="footer">
            <label>&copy; <a href="javascript:void(0)">Jiwon Min</a>. All rights reserved.</label>
        </div>
    </div>
</div>