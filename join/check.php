<?php
    session_start();
    require('../dbconnect.php');
    require('../functions.php');
    // 似た処理ができる
    // include()
    // require_once()
    // include_once()

    special_var_dump($_SESSION);

    // 上のspecial_var_dump()と同じ
    // echo '<pre>';
    // var_dump($_SESSION);
    // echo '</pre>';

    // セッション情報がindex.phpを正規に通って登録されていない場合は
    // index.phpに強制的に遷移する
    if (!isset($_SESSION["join"])) {
        header("Location: index.php");
        exit();
    }

    // 送信があった際に処理される
    if (!empty($_POST)) {

        // メンバーのデータをインサートするためのSQL文を作成
        // mysqli_real_escape_string関数とは、ユーザーがフォームに入力した値を
        // SQL文で問題なく処理できるようにエスケープする関数

        // SQLインジェクション攻撃
        // フォーム内にSQL文を直書きしてデータの改ざんや破壊、奪取をする攻撃

        // 構文
        // mysqli_real_escape_string(DB情報,エスケープしたい文字列);

        $sql = sprintf('INSERT INTO members SET nick_name="%s",
            email="%s", password="%s", picture_path="%s", created=NOW()',
            mysqli_real_escape_string($db,$_SESSION['join']['nick_name']),
            mysqli_real_escape_string($db,$_SESSION['join']['email']),
            mysqli_real_escape_string($db,sha1($_SESSION['join']['password'])),
            mysqli_real_escape_string($db,$_SESSION['join']['image'])
        );

        // sha1関数
        // 指定した文字列を自動的に暗号化してくれる関数

        mysqli_query($db,$sql) or die(mysqli_error($db));
        // PDOの場合
        // 1. SQL文を作成
        // 2. prepare
        // 3. executeで実行
        // mysqli_関数群の場合
        // 1. SQL文を作成
        // 2. mysqli_queryで実行

        // mysqli_query関数とは、指定したDB情報とSQL文を使って
        // データの処理を実行する関数
        // mysqli_query(DB情報,SQL文);

        // mysqli_error関数とは、mysqli_query関数を使って
        // データ処理をした際、SQL文が間違っているなどで処理が正常に
        // できなかった時のエラーをだす関数

        unset($_SESSION['join']);

        // unset関数とは、指定した変数を破棄します。

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
        <?php echo h($_SESSION['join']['nick_name']); ?>
      </dd>

      <dt>メールアドレス</dt>
      <dd>
        <?php echo h($_SESSION['join']['email']); ?>
      </dd>

      <dt>パスワード</dt>
      <dd>
        【表示されません】
      </dd>

      <dt>写真</dt>
      <dd>
        <img src="../member_picture/<?php echo h($_SESSION["join"]["image"]); ?>" width="100" height="100">
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










