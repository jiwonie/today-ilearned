<div id="wrap">
    <!-- header -->
    <div id="header">
        <a href="" class="title">BOARD LIST</a>
    </div>

    <!-- content -->
    <div id="content">
        <table class="board-list">
            <tr>
                <th>no.</th>
                <th>subject</th>
                <th>writer</th>
                <th>created</th>
            </tr>
            <?php if (is_array($res['boards']) && !empty($res['boards'])){ ?>
                <?php foreach ($res['boards'] as $no => $board) { ?>
                <tr>
                    <td><?php echo $board['idx']; ?></td>
                    <td><a href="/board/<?php echo $board['idx']; ?>" class="subject"><?php echo $board['subject']; ?></a></td>
                    <td><?php echo $board['create_by']; ?></td>
                    <td><?php echo $board['create_at']; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="4">
                        <a href="/board/page/<?php echo $res['paging']['prev']; ?>" class="paging">&lt;</a>

                        <?php for ($i = $res['paging']['first']; $i <= $res['paging']['last']; $i++) { ?>
                            <a href="/board/page/<?php echo $i; ?>" class="paging <?php echo ($res['paging']['now'] ?? '1') == $i ? 'act' : '' ?>"><?php echo $i; ?></a>
                        <?php } ?>

                        <a href="/board/page/<?php echo $res['paging']['next']; ?>" class="paging">&gt;</a>
                    </td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No content</td>
                </tr>
            <?php } ?>
        </table>

        <div>
            <button type="button" class="btn" onclick="location.href='/'">go to index page</button>

            <?php if ($_SESSION['IS_LOGIN']) { ?>
            <button type="button" class="btn" onclick="location.href='/board/write'" title="write">write</button>
            <?php } ?>
        </div>

        <!-- footer -->
        <div id="footer">
            <label>&copy; <a href="javascript:void(0)">Jiwon Min</a>. All rights reserved.</label>
        </div>
    </div>
</div>