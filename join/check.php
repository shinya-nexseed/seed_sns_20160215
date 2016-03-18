<?php
    session_start();
    require('../dbconnect.php');
    require('../functions.php');

    special_var_dump($_SESSION);

    echo '<pre>';
    var_dump($_SESSION);
    echo '</pre>';

    // セッション情報がindex.phpを正規に通って登録されていない場合は
    // index.phpに強制的に遷移する
    if (!isset($_SESSION["join"])) {
        header("Location: index.php");
        exit();
    }


    if (!empty($_POST)) {

        $sql = sprintf('INSERT INTO members SET nickname="%s",
            email="%s", password="%s", picture="%s", created=NOW()',
            mysqli_real_escape_string($db,$_SESSION['join']['nickname']),
            mysqli_real_escape_string($db,$_SESSION['join']['email']),
            mysqli_real_escape_string($db,sha1($_SESSION['join']['password'])),
            mysqli_real_escape_string($db,$_SESSION['join']['image'])
        );

        mysqli_query($db,$sql) or die(mysqli_error($db));
        unset($_SESSION['join']);

        header('Location: thanks.php');
        exit();
    }
?>

<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Seed SNS</title>
</head>
<body>
  <h1>会員登録</h1>
  <p>記入した入力内容を確認して、「登録する」ボタンをクリックしてください</p>

  <form action="" method="post">
    <input type="hidden" name="action" value="submit">
    <dl>

      <dt>ニックネーム</dt>
      <dd>
        <?php echo htmlspecialchars($_SESSION['join']['nick_name'], ENT_QUOTES, 'UTF-8'); ?>
      </dd>

      <dt>メールアドレス</dt>
      <dd>
        <?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES, 'UTF-8'); ?>
      </dd>

      <dt>パスワード</dt>
      <dd>
        【表示されません】
      </dd>

      <dt>写真</dt>
      <dd>
        <img src="../member_picture/<?php echo htmlspecialchars($_SESSION["join"]["image"], ENT_QUOTES, 'UTF-8'); ?>" width="100" height="100">
      </dd>

    </dl>
    <div>
      <!-- データを書き直すため、index.phpに戻った際にurlのパラメータとして
      action=rewriteをつけておく
      index.php側で$_GET['action']の中身がrewriteだったら
      前回入力されたデータをフォーム上に表示する処理をする -->
      <a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a>
      <input type="submit" value="登録する">
    </div>

  </form>
</body>
</html>










