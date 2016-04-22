<?php
    session_start();
    require('dbconnect.php');
    require('functions.php');

    // URLのパラメータにid=数字の記述がなければ不正なアクセスとみなし
    // index.phpにリダイレクト (遷移) する
    // 例 : 192.168.33.10/seed_sns/view.php?id=1
    if (empty($_REQUEST['id'])) {
        header('Location: index.php');
        exit();
    }

    // いいね!のロジック実装
    if (!empty($_POST)) {
        if ($_POST['like'] === 'like') {
            // いいね!データの登録
            $sql = sprintf('INSERT INTO `likes` SET member_id=%d, tweet_id=%d',
                  $_SESSION['id'],
                  $_REQUEST['id']
              );
            mysqli_query($db, $sql) or die(mysqli_error($db));
        } else {
            // いいね!データの削除
            $sql = sprintf('DELETE FROM `likes` WHERE member_id=%d AND tweet_id=%d',
                  $_SESSION['id'],
                  $_REQUEST['id']
              );
            mysqli_query($db, $sql) or die(mysqli_error($db));
        }
    }

    // member_id同士でmembersテーブルとtweetsテーブルを結合し、
    // tweetsのtweet_idとURLパラメータのidの値が一致するデータを
    // createdの新しい順に取得
    $sql = sprintf('SELECT m.nick_name, m.picture_path, t.* 
                    FROM members m, tweets t
                    WHERE m.member_id=t.member_id 
                    AND t.tweet_id=%d 
                    ORDER BY t.created DESC',
        mysqli_real_escape_string($db, $_REQUEST['id'])
    );
    $tweets = mysqli_query($db, $sql) or die(mysqli_error($db));

    // いいね!済みかどうかの判定
    $sql = sprintf('SELECT * FROM `likes` WHERE member_id=%d AND tweet_id=%d',
          $_SESSION['id'],
          $_REQUEST['id']
      );
    // $likes変数には、いいね!データがあれば1件、なければ0件のデータが入る
    $likes = mysqli_query($db, $sql) or die(mysqli_error($db));

    // いいね!データの有無による条件分岐のテスト
    // if ($like = mysqli_fetch_assoc($likes)) {
    //     echo 'いいね!済み';
    //     echo '<br>';
    // } else {
    //     echo '未いいね!';
    //     echo '<br>';
    // }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ひとこと掲示版</title>
</head>
<body>
  <div id="wrap">
    <div id="head">
      <h1>ひとこと掲示版</h1>
    </div>
    <div id="content">
      <p>&laquo;<a href="index.php">一覧にもどる</a></p>
      <?php if ($tweet = mysqli_fetch_assoc($tweets)): ?>
        <div class="msg">
          <img src="member_picture/<?php echo h($tweet['picture_path']); ?>" 
          width="48" height="48" alt="<?php echo h($tweet['nick_name']); ?>">
          <p><?php echo h($tweet['tweet']); ?><span class="name"><?php echo h($tweet['nick_name']);?></span></p>
          <p class="day"><?php echo h($tweet['created']);?></p>
        </div>

        <form action="" method="post">

          <?php if ($like = mysqli_fetch_assoc($likes)): ?>
          
              <!-- $_POSTの内容を作る -->
              <input type="hidden" name="like" value="unlike">
              <!-- $_POST['like'] = 'like' -->
              <input type="submit" value="いいね!取り消し">

          <?php else: ?>

              <!-- $_POSTの内容を作る -->
              <input type="hidden" name="like" value="like">
              <!-- $_POST['like'] = 'like' -->
              <input type="submit" value="いいね!">

          <?php endif; ?>

        </form>

      <?php else: ?>
        <p>その投稿は削除されたか、URLが間違っています。</p>
      <?php endif; ?>
    </div>
    <div id="footer">
      <!-- フッター内容 -->
    </div>
  </div>
</body>
</html>
