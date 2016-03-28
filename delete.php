<?php
    session_start();
    require('dbconnect.php');

    // $_SESSION['id']が存在するかどうかを検証
    // ユーザーがログインしているかどうかを見ている
    if (isset($_SESSION['id'])) {
        $id = $_REQUEST['id'];

        $sql = sprintf('SELECT * FROM tweets WHERE tweet_id=%d',
          mysqli_real_escape_string($db, $id)
        );

        $record = mysqli_query($db, $sql) or die(mysqli_error($db));
        $table = mysqli_fetch_assoc($record);

        // $table ← 削除したいtweetのデータ一件が格納されている
        // 削除したいtweetデータのmember_idと、
        // ログインしているユーザーのmember_idが一致していれば、
        // 削除処理を正常に実行する
        if ($table['member_id'] == $_SESSION['id']) {
            $sql = sprintf('DELETE FROM tweets WHERE tweet_id=%d',
              mysqli_real_escape_string($db, $id)
            );
            mysqli_query($db,$sql) or die(mysqli_error($db));
        }
    }


    header('Location: index.php');
    exit();
?>









