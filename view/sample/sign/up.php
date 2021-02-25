<div id="wrap">
    <!-- header -->
    <div id="header">
        <a href="" class="title">SIGN-UP</a>
    </div>

    <!-- content -->
    <div id="content">
        <form action="/join" method="post" onsubmit="validation()">
            <input type="hidden" name="_method" value="post">
            <table class="join-table">
                <tr>
                    <td>id </td>
                    <td><input type="text" name="id" title="id" maxlength="20" required /></label></td>
                </tr>
                <tr>
                    <td>name </td>
                    <td><input type="text" name="name" title="name" maxlength="20" required /></label></td>
                </tr>
                <tr>
                    <td>password </td>
                    <td><input type="password" name="pw" title="password" required /></label></td>
                </tr>
                <tr>
                    <td>retype </td>
                    <td><input type="password" name="pw_confirm" title="password_confirm" required /></label></td>
                </tr>
            </table>

            <button type="button" class="btn" onclick="location.href='/'">go to index page</button>
            <input type="submit" class="btn" value="join" />
        </form>

        <!-- footer -->
        <div id="footer">
            <label>&copy; <a href="javascript:void(0)">Jiwon Min</a>. All rights reserved.</label>
        </div>
    </div>
</div>


<script type="text/javascript">
    function validation()
    {
        // ! validation
    }
</script>