<?php
    
    // PDOを使ってDBに接続する方法以外に各DB用の関数を
    // 使用して接続する方法がある
    
    // mysql用の接続するための関数であるmysqli_~~~関数

    $db = mysqli_connect('localhost', 'root', 'mysql', 'seed_sns')
    // mysqli_connect('ホスト名', 'ユーザー名', 'パスワード', 'DB名')
    or die(mysqli_connect_error());
    // もしmysqli_connect関数の処理が失敗(DBへの接続ができない)した場合、
    // mysqlが吐き出したエラーを取得するためのmysqli_connect_error()関数と、
    // それを表示して処理を停止するためのdie()関数を使用

    mysqli_set_charset($db,'utf8');
    // 文字コードセット


?>
