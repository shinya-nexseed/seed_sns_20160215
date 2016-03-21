<?php
    session_start();
    require('dbconnect.php');

    if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time() ) {
        // ログインしている
        $_SESSION['time'] = time();

        $sql = sprintf('SELECT * FROM members WHERE id=%d',
            mysqli_real_escape_string($db, $_SESSION['id'])
        );

        $record = mysqli_query($db, $sql) or die(mysqli_error($db));
        $member = mysqli_fetch_assoc($record);
    } else {
        // ログインしていない
        header('Location: login.php');
        exit();
    }

    // 投稿を記録する
    if (!empty($_POST)) {
        if ($_POST['message'] != '') {
            $sql = sprintf('INSERT INTO posts SET member_id=%d, message="%s", reply_post_id=%d, created=NOW()',
                mysqli_real_escape_string($db, $member['id']),
                mysqli_real_escape_string($db, $_POST['message']),
                mysqli_real_escape_string($db, $_POST['reply_post_id'])
            );

            mysqli_query($db,$sql) or die(mysqli_error($db));

            header('Location: index.php');
            exit();
        }
    }

    // 投稿を取得する
    $sql = sprintf('SELECT m.nick_name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');

    $posts = mysqli_query($db,$sql) or die(mysqli_error($db));
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ひとこと掲示版</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div id="wrap">
    <div id="head">
      <h1>ひとこと掲示版</h1>
    </div>
    <div id="content">
      <form action="" method="post">
        <dl>
          <dt><?php echo htmlspecialchars($member['nick_name']); ?>メッセージをどうぞ</dt>
          <dd>
            <textarea name="message" cols="50" rows="5"></textarea>
          </dd>
        </dl>
        <div>
          <input type="submit" value="投稿する">
        </div>
      </form>

      <?php while ($post = mysqli_fetch_assoc($posts)): ?>
          <div class="msg">
            <img src="member_picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES, 'UTF-8'); ?>" width="48" height="48" alt="<?php echo htmlspecialchars($post['nick_name'], ENT_QUOTES, 'UTF-8'); ?>">
            <p>
              <?php echo htmlspecialchars($post['message'], ENT_QUOTES, 'UTF-8'); ?><span class="name"> (<?php echo htmlspecialchars($post['nick_name'], ENT_QUOTES, 'UTF-8'); ?>) </span>
            </p>
            <p class="day">
              <?php echo htmlspecialchars($post['created'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
          </div>
      <?php endwhile; ?>

    </div>
  </div>
</body>
</html>






















