<?php 
    // session_start();

    // 関数の定義の仕方
    // function 関数名() {
    //     処理;
    // }

    // 関数の実行の仕方
    // 関数名();



    // 引数有り関数の定義の仕方
    // function 関数名(引数) {
    //     処理;
    //     引数をこの中で変数のように使うことができる
    // }

    // 引数有り関数の実行の仕方
    // 関数名(引数として送りたい値);

    function sayHello() {
        echo 'Hello';
        echo '<br>';
    }

    // sayHello(); // ブラウザ上にHello と出力される
    // sayHello();
    // sayHello();
    // sayHello();
    // sayHello();

    // 様々な配列データをvar_dumpで綺麗に表示したい
    function special_var_dump($val) {
        echo '<pre>';
        var_dump($val);
        echo '</pre>';
    }

    // special_var_dump('ほげ');

    // $ary = array("ai", "kaai", "shoma");
    // special_var_dump($ary);
    // special_var_dump($_SESSION);


    // htmlspecialcharsのショートカット
    function h($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // mysqli_real_escape_stringのショートカット
    function m($db, $value) {
        return mysqli_real_escape_string($db, $value);
    }

?>



















