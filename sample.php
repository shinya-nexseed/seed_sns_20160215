<?php
// 投稿を取得する
if (isset($_REQUEST['page'])) {
    // index.php?page=1
    // index.php?page=
    $page = $_REQUEST['page'];
} else {
    // index.php
    $page = 1;
}

if ($page == '') {
    // index.php?page=
    $page = 1;
}

$page = max($page,1);

// 最終ページを取得する
$sql = 'SELECT COUNT(*) AS cnt FROM posts';
$recordSet = mysqli_query($db,$sql);
$table = mysqli_fetch_assoc($recordSet);

// ページ指定に手打ちで1.8などの小数点を入力された場合四捨五入で返す
$maxPage = ceil($table['cnt'] / 5);
// ページ数の最大を超えないよう
// もしページ数に3と指定していてtweet件数が9件しかなかった時、
// 最大ページ数は2なので最小値の2を取る
$page = min($page, $maxPage);

// SELECT文開始の基準となる数字を格納する$startを用意
// 1ページ目なら0、2ページ目なら5という数値が入る
$start = ($page - 1) * 5;
// 0以下だとシステム的にまずいので比較
$start = max(0, $start);
