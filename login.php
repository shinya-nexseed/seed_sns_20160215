<?php
    require('dbconnect.php');
    require('functions.php');

    session_start();

    // setcookie('email', $_POST['email'], time()+60*60*24*14);
    // が14日以内に一度でも処理されていれば、
    // このif文を処理
    if (isset($_COOKIE['email'])) {
        if ($_COOKIE['email'] != '') {

            // $_POSTに$_COOKIEの値を渡している
            // フォームから値の送信処理を擬似的につくっている
            $_POST['email'] = $_COOKIE['email'];
            $_POST['password'] = $_COOKIE['password'];
            $_POST['save'] = 'on';
        }
    }

    // ログインボタンが押され、post送信があれば処理
    if (!empty($_POST)) {

        // ログインの処理
        // メールアドレスとパスワードが入力されているかを確認し、されていれば処理
        if ($_POST['email'] != '' && $_POST['password'] != '') {

            // WHEREでmembersテーブルの中からメールアドレスとパスワード共に一致するデータがあるかをSELECT文で取得
            $sql = sprintf('SELECT * FROM members WHERE email="%s" AND password="%s"',
                mysqli_real_escape_string($db, $_POST['email']),
                mysqli_real_escape_string($db, sha1($_POST['password']))
            );
            // SQL文のAND句
            // WHERE句など条件を指定する際に、AND句を使用して複数の条件を指定できる

            // passwordを検索条件にして比較する際、テーブル内には
            // sha1関数を使用して暗号化された値が保存されているので、
            // SQL文で比較する際もsha1関数で暗号化した値で比較する

            // クエリ関数にかけた結果を$recordに格納
            $record = mysqli_query($db, $sql) or die(mysqli_error($db));

            // $recordに格納されているデータを一件ずつ$tableに取り出してif文の中の処理を実行
            if ($table = mysqli_fetch_assoc($record)) {
                // mysqli_fetch_assoc関数とは、mysqli関数群の中で、
                // query関数で取得したobjectデータからデータをフェッチする関数

                // この時、ユーザーは被らないデータとしてひとつしか存在しないので、
                // $recordの中にも一致するひとつのユーザーのデータが入る

                // ログイン成功
                
                // ユーザーのidをセッションに保存
                // $table = array('member_id' => '1',
                //                'nick_name' => 'Seed',
                //                'email' => 'seed@kun.net',
                //                'password' => 'hogehoge',
                //                'picture_path' => 'image.jpg',
                //                'created' => '日付',
                //                'modified' => '日付'
                //                );

                $_SESSION['id'] = $table['member_id'];
                // 次のページ以降で絶対にかぶらないmember_idを元に
                // ユーザーの情報を取得するため (SELECT)

                // 最終ログイン時から14日間経過していた場合、
                // ログイン処理を終了する
                // 次回から自動でログインするチェックボックスに
                // チェックを入れている場合のみ上記処理をする

                // ログインした時間をセッションに保存
                $_SESSION['time'] = time();

                // ログイン情報を記録する
                // フォームのチェックボックスにチェックが
                // ついている場合に処理
                if ($_POST['save'] == 'on') {
                    
                    // 入力されたメールアドレス情報
                    // time()+60*60*24*14
                    // 現在時刻+60秒×60分×24時間×14日間

                    setcookie('email', $_POST['email'], time()+60*60*24*14);

                    setcookie('password', $_POST['password'], time()+60*60*24*14);
                }

                header('Location: index.php');
                exit(); 
            } else {
                $error['login'] = 'failed';
            }

        } else {
            $error['login'] = 'blank';
        }
    }

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ログイン</title>


</head>
<body>
  <div id="lead">
    <p>メールアドレスとパスワードを記入してログインしてください。</p>
    <p>入会手続きがまだの方はこちらからどうぞ。</p>
    <p>&raquo;<a href="join/">入会手続きをする</a></p>  
  </div>
  <form action="" method="post">
    <dl>
      
      <dt>メールアドレス</dt>
      <dd>
        
        <!-- メールアドレス用inputタグの表示分岐 -->
        <?php if(!empty($_POST['email'])): ?>
            <input type="text" name="email" value="<?php echo h($_POST['email']); ?>">
        <?php else: ?>
            <input type="text" name="email" value="">
        <?php endif; ?>

        <!-- メールアドレスのバリデーションエラー文の表示分岐 -->
        <?php if(!empty($error['login'])): ?>
            <?php if ($error['login'] == 'blank'): ?>
                <p class="error">* メールアドレスとパスワードをご記入ください。</p>
            <?php endif; ?>
            <?php if ($error['login'] == 'failed'): ?>
                <p class="error">* ログインに失敗しました。正しく情報をご記入ください。</p>
            <?php endif; ?>
        <?php endif; ?>

      </dd>

      <dt>パスワード</dt>
      <dd>
        <?php if(!empty($_POST['password'])): ?>
            <input type="password" name="password" value="<?php echo h($_POST['password']); ?>">
        <?php else: ?>
            <input type="password" name="password" value="">
        <?php endif; ?>
      </dd>

      <dt>ログイン情報の記録</dt>
      <dd>
        <input type="checkbox" id="save" name="save" value="on">
        <label for="">次回から自動でログインする</label>
      </dd>

    </dl>

    <div>
      <input type="submit" value="ログインする">
    </div>
  </form>
</body>
</html>







