<?php
    session_start();
    require('dbconnect.php');
    require('functions.php');

    // $_SESSIONに必要な値が入っていればログインしているので、
    // if文を処理
    if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time() ) {


        $_SESSION['time'] = time();

        $sql = sprintf('SELECT * FROM members WHERE member_id=%d',
            mysqli_real_escape_string($db, $_SESSION['id'])
        );

        $record = mysqli_query($db, $sql) or die(mysqli_error($db));

        // ログインしているのユーザーのデータ
        $member = mysqli_fetch_assoc($record);
    } else {
        header('Location: login.php');
        exit();
    }

    // 投稿を記録する
    if (!empty($_POST)) {
        if ($_POST['tweet'] != '') {
            $sql = sprintf('INSERT INTO tweets SET member_id=%d, tweet="%s", created=NOW()',
                mysqli_real_escape_string($db, $member['member_id']),
                mysqli_real_escape_string($db, $_POST['tweet'])
            );

            mysqli_query($db,$sql) or die(mysqli_error($db));

            header('Location: index.php');
            exit();
        }
    }

    // 投稿を取得する
    // $sql = sprintf('SELECT m.nick_name, m.picture_path, p.* FROM members m, tweets p WHERE m.member_id=p.member_id ORDER BY p.created DESC');

    // membersテーブルからmember_idが1のレコードを取得する
    // SELECT * FROM members WHERE member_id=1
    
    // membersテーブルに別名「m」をつけている
    // SELECT * FROM members m WHERE m.member_id=1

    // テーブルを複数個指定し、データを繋げて一気に取得
    // SELECT * FROM members, tweets WHERE members.member_id=1 ORDER BY tweets.created DESC

    // membersテーブルのmember_idと,
    // tweetsテーブルのmember_idが等しければ
    // データを取得する (存在するtweetsデータ全件取得)
    $sql = 'SELECT m.nick_name, m.picture_path, t.*
            FROM members m, tweets t 
            WHERE m.member_id=t.member_id
            ORDER BY t.created DESC';


    $tweets = mysqli_query($db,$sql) or die(mysqli_error($db));

    // tweets時点ではまだobject
    // 実際に使用するにはmysqli_fetch_assoc()関数で配列に置き換える
    special_var_dump($tweets);
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
            <textarea name="tweet" cols="50" rows="5"></textarea>
          </dd>
        </dl>
        <div>
          <input type="submit" value="投稿する">
        </div>
      </form>

      <?php while ($tweet = mysqli_fetch_assoc($tweets)): ?>
          <div class="msg">
            <img src="member_picture/<?php echo htmlspecialchars($tweet['picture_path'], ENT_QUOTES, 'UTF-8'); ?>" width="48" height="48" alt="<?php echo htmlspecialchars($tweet['nick_name'], ENT_QUOTES, 'UTF-8'); ?>">
            <p>
              <?php echo htmlspecialchars($tweet['tweet'], ENT_QUOTES, 'UTF-8'); ?><span class="name"> (<?php echo htmlspecialchars($tweet['nick_name'], ENT_QUOTES, 'UTF-8'); ?>) </span>
            </p>
            <p class="day">
              <?php echo htmlspecialchars($tweet['created'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
          </div>
      <?php endwhile; ?>

    </div>
  </div>
</body>
</html>






















