<?php
    session_start();

    echo '<pre>';
    var_dump($_SESSION);
    echo '</pre>';
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
      </dd>

    </dl>
    <div>
      <a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a>
      <input type="submit" value="登録する">
    </div>

  </form>
</body>
</html>










