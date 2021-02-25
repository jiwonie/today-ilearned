<div id="wrap">
    <!-- header -->
    <div id="header">
        <a href="" class="title">INDEX PAGE</a>
    </div>

    <!-- content -->
    <div id="content">
        <button type="button" class="btn" onclick="location.href='/board'" title="board">view board list</button>

        <?php if (!$_SESSION['IS_LOGIN']) { ?>
            <button type="button" class="btn" onclick="location.href='/login'" title="Sign In">sign in</button>
            <button type="button" class="btn" onclick="location.href='/join'" title="Sign Up">sign up</button>
        <?php } else { ?>
            <button type="button" class="btn" onclick="location.href='/logout'" title="Sign Out">sign out</button>
        <?php } ?>

        <!-- footer -->
        <div id="footer">
            <label>&copy; <a href="javascript:void(0)">Jiwon Min</a>. All rights reserved.</label>
        </div>
    </div>
</div>