<?php
    session_start();

    // 登録していたセッションをすべて破棄したい場合、
    // サーバーやPHPの必要なデータもろとも削除する必要があるため、
    // 下の定型文を使うのが一般的
    
    // 単に$_SESSIONに登録しているデータを空にしたい場合は、
    // array()を格納してあげる
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

    // クッキーを空にする書き方
    // 値の部分に''で空の文字列を格納し、保持期間の部分に
    // - 3600 と入れることでそもそも保持期間が終了しているように
    // 定義している
    setcookie('email', '', time() - 3600);
    setcookie('password', '', time() - 3600);

    // ユーザーデータを削除などはせず、保存していたセッションと
    // クッキーを削除するのみなので、SQL文のDELETEなども不要

    header('Location: login.php');
    exit();
?>







