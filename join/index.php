<?php
    // 各ページセッションを使用したい場合は
    // session_start()が必要
    session_start();

    // サニタイズのテスト
    // echo '<h1>ほげほげ</h1>'; // ← デカ文字で表示されます。
    // $str = '<h1>ほげほげ</h1>';
    // echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8');

    // バリデーションエラーがあった際に
    // そのエラーを蓄えるための配列を用意
    $error = array();

    if (!empty($_POST)) { // フォーム送信時のみ処理
        // エラー項目の確認
        if ($_POST['nick_name'] == '') {
            // もし$_POST内のnick_name部分が空だったら処理
            $error['nick_name'] = 'blank';
            // $error配列のnick_nameキーにblankという値を代入
        }

        if ($_POST['email'] == '') {
            $error['email'] = 'blank';
        }

        if (strlen($_POST['password']) < 4) {
            // strlen()関数とは
            // 指定した文字列の文字数をカウントして返す
            $error['password'] = 'length';
        }

        if ($_POST['password'] == '') {
            $error['password'] = 'blank';
        }

        // 写真のエラー文
        $fileName = $_FILES['image']['name'];
        // $_FILESはinputタグのtypeがfileの時に生成される
        // スーパーグローバル変数です
        // echo $fileName;
        if (!empty($fileName)) {
            $ext = substr($fileName, -3);
            // substr()関数は指定した文字列から指定した数分だけ文字を
            // 取得する
            // 今回は-3と指定することで文字列の最後から3つ分取得
            // echo $ext;

            // 画像ファイルの拡張子がjpgもしくはpngでなければ
            // typeというエラーを出す
            if ($ext != 'jpg' && $ext != 'png') {
                $error['image'] = 'type';
            }
        }

        // もしエラーが何もなかったら($errorが空だったら)
        if (empty($error)) {
            // 画像をサーバーへアップロードする処理
            // 単に登録する画像の名前の文字列を他と絶対にかぶらない形で
            // 変数に代入する
            $image = date('YmdHis') . $_FILES['image']['name'];
            // date()関数とは、指定したフォーマットで現在の日時を返す
            // echo $image;

            move_uploaded_file($_FILES['image']['tmp_name'],
                               '../member_picture/' . $image
                              );
            // move_uploaded_file()関数とは、
            // 指定したファイルを指定した場所にアップロードする

            // セッションのjoinに$_POSTの情報を入れる
            $_SESSION['join'] = $_POST;
            $_SESSION['join']['image'] = $image;

            // $_SESSIONとは
            // 任意の情報をブラウザが閉じられるまで保持する場所を
            // セッションと言う

            // check.phpに遷移して処理を終了する
            header('Location: check.php');
            exit();
        }

    }

    // 書き直し
    if (isset($_REQUEST['action'])) {
        // $_REQUESTとは、$_GET,$_POSTなどを保持するスーパーグローバル変数
        // $_REQUEST['action']が存在すればif文処理する

        if ($_REQUEST['action'] == 'rewrite') {
            // $_REQUEST['action']の値が'rewrite'だったら処理する

            $_POST = $_SESSION['join'];
            // $_POSTに$_SESSIONの値を代入してフォームに表示

            $error['rewrite'] = true;
            // 画像を再度登録するように促すエラー用
        }
    }

?>

<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Seed SNS</title>

  <!-- CSSの読み込み -->
  <link rel="stylesheet" type="text/css" href="../assets/css/main.css">
</head>
<body>
  <h1>会員登録</h1>
  <p>次のフォームに必要事項をご記入ください。</p>
  

  <!-- フォームの入力値に対してコードの読み込みを防止する処理を
  サニタイズを言う -->
  <form action="" method="post" enctype="multipart/form-data">
  <!-- actionに空が指定されている
  これは自分自身 (index.php) にデータを送るのと同義

  enctype="multipart/form-data"について
  formタグ内にinput type="file"が存在し、ファイルデータを
  送信する場合かならず付けるオプション -->

    <dl>
      <dt>ニックネーム <span class="required">必須</span></dt>
      <dd>
        <?php if(!empty($_POST['nick_name'])): ?>
            <input type="text" name="nick_name" value="<?php echo htmlspecialchars($_POST['nick_name'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php else: ?>
            <input type="text" name="nick_name" value="">
        <?php endif; ?>

        <!-- PHPでエラー内容出力 -->
        <?php if(!empty($error['nick_name'])): ?>
            <?php if($error['nick_name'] == 'blank'): ?>
                <p class="error">ニックネームを入力してください。</p>
            <?php endif; ?>
        <?php endif; ?>
      </dd>

      <dt>メールアドレス <span class="required">必須</span></dt>
      <dd>
        <?php if(!empty($_POST['email'])): ?>
            <input type="text" name="email" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php else: ?>
            <input type="text" name="email" value="">
        <?php endif; ?>

        <?php if(!empty($error['email'])): ?>
            <?php if($error['email'] == 'blank'): ?>
                <p class="error">メールアドレスを入力してください。</p>
            <?php endif; ?>
        <?php endif; ?>
      </dd>

      <dt>パスワード <span class="required">必須</span></dt>
      <dd>
        <input type="password" name="password">
        
        <?php if(!empty($error['password'])): ?>
            
            <?php if($error['password'] == 'blank'): ?>
                <p class="error">パスワードを入力してください。</p>
            <?php endif; ?>

            <?php if($error['password'] == 'length'): ?>
                <p class="error">パスワードを4文字以上で入力してください。</p>
            <?php endif; ?>

        <?php endif; ?>
      </dd>

      <dt>写真 <span class="required">必須</span></dt>
      <dd>
        <input type="file" name="image">
        <?php if(!empty($error['image'])): ?>
            <?php if($error['image'] == 'type'): ?>
                <p class="error">写真は「jpg」または「png」の形式で指定してください。</p>
            <?php endif; ?>
        <?php endif; ?>
      </dd>

    </dl>
    <div><input type="submit" value="入力内容を確認する"></div>

  </form>
</body>
</html>










