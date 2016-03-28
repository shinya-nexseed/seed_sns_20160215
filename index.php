<?php
    session_start();
    require('dbconnect.php');
    require('functions.php');

    // $str = "PHP入門";
    // $str2 = str_replace("PHP", "PHP5", $str);
    // echo $str;
    // echo "<br>";
    // echo $str2;

    function makeLink($value) {
        return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)",'<a href="\1\2">\1\2</a>', $value);
    }

    // $_SESSIONに必要な値が入っていればログインしているので、
    // if文を処理
    if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time() ) {


        $_SESSION['time'] = time();

        $sql = sprintf('SELECT * FROM members WHERE member_id=%d',
            m($db, $_SESSION['id'])
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
            $sql = sprintf('INSERT INTO tweets SET member_id=%d, tweet="%s", reply_tweet_id=%d, created=NOW()',
                m($db, $member['member_id']),
                m($db, $_POST['tweet']),
                m($db, $_POST['reply_tweet_id'])
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

    // 返信の場合
    if (isset($_REQUEST['res'])) {

        // 返信元データを$_REQUEST['res']を使って取得し、
        // textareaに表示するための文字列を作成する
        $sql = sprintf('SELECT m.nick_name, m.picture_path, t.* FROM members m, tweets t 
            WHERE m.member_id=t.member_id AND t.tweet_id=%d ORDER BY t.created DESC',
            m($db, $_REQUEST['res'])
        );

        $record = mysqli_query($db, $sql) or die(mysqli_error($db));

        $table = mysqli_fetch_assoc($record);

        $reply_tweet = '@' . $table['nick_name'] . ' ' . $table['tweet'];
        // @Seedほげほげ
    }
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
      <div style="text-align: right;">
        <a href="logout.php">ログアウト</a>
      </div>
      <form action="" method="post">
        <dl>
          <dt><?php echo h($member['nick_name']); ?>メッセージをどうぞ</dt>
          <dd>
              
            <!-- 
              返信の場合は、返信元データから作成した文字列が格納されている
              $reply_tweet変数をtextarea内に出力
            -->
            <?php if(!empty($reply_tweet)): ?>
                <textarea name="tweet" cols="50" rows="5"><?php echo h($reply_tweet) ?></textarea>
            <?php else: ?>
                <textarea name="tweet" cols="50" rows="5"></textarea>
            <?php endif; ?>
            
            <?php if(!empty($_REQUEST['res'])): ?>
                <!-- 画面上には表示せず、任意の値をフォームで送信するためのhidden -->
                <input type="hidden" name="reply_tweet_id" value="<?php echo h($_REQUEST['res']); ?>">
                <!-- echo $_POST['reply_tweet_id'] ⇒ 返信元に選んだtweet_id -->
            <?php endif; ?>

          </dd>
        </dl>
        <div>
          <input type="submit" value="投稿する">
        </div>
      </form>

      <?php while ($tweet = mysqli_fetch_assoc($tweets)): ?>
          <div class="msg">
            <img src="member_picture/<?php echo h($tweet['picture_path']); ?>" width="48" height="48" alt="<?php echo h($tweet['nick_name']); ?>">
            <p>
              <?php echo makeLink(h($tweet['tweet'])); ?><span class="name"> (<?php echo h($tweet['nick_name']); ?>) </span>
              [<a href="index.php?res=<?php echo h($tweet['tweet_id']) ?>">Re</a>]
            </p>

            <p class="day">

              <a href="view.php?id=<?php echo h($tweet['tweet_id']); ?>">
                <?php echo h($tweet['created']); ?>
              </a>

              <?php if($tweet['reply_tweet_id'] > 0): ?>
                <a href="view.php?id=<?php echo h($tweet['reply_tweet_id']); ?>">送信元のメッセージ</a>
              <?php endif; ?>

              <?php if ($_SESSION['id'] == $tweet['member_id']): ?>
                  [<a href="delete.php?id=<?php echo h($tweet['tweet_id']) ?>" style="color: #F33;">削除</a>]
              <?php endif; ?>
            </p>

          </div>
      <?php endwhile; ?>

    </div>
  </div>
</body>
</html>






















