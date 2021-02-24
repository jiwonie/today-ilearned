<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOARD LIST</title>
    <style>
        #wrap {max-width:700px;margin:0 auto;text-align:center;}
        table {width:100%;table-layout:fixed;}
    </style>
</head>
<body>
    <div id="wrap">
        <h1>BOARD LIST</h1>

        <table>
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
                    <td><a href="/board/<?php echo $board['idx']; ?>"><?php echo $board['subject']; ?></a></td>
                    <td><?php echo $board['create_by']; ?></td>
                    <td><?php echo $board['create_at']; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="4">
                        <a href="/board/page/<?php echo $res['paging']['prev']; ?>">&lt;</a>

                        <?php for ($i = $res['paging']['first']; $i <= $res['paging']['last']; $i++) { ?>
                        <a href="/board/page/<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php } ?>

                        <a href="/board/page/<?php echo $res['paging']['next']; ?>">&gt;</a>
                    </td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No content</td>
                </tr>
            <?php } ?>
        </table>

        <div>
            <button type="button" onclick="location.href='/'">go to index page</button>

            <?php if ($_SESSION['IS_LOGIN']) { ?>
            <button type="button" onclick="location.href='/board/write'" title="write">write</button>
            <?php } ?>
        </div>
    </div>
</body>
</html>