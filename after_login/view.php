<?php
session_start();
// require_once '../pdo_connect.php';
require_once '../function.php';

if (empty($_REQUEST['id'])) {
    header('location: index.php');
    exit();
}

$posts = $dbh->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
$posts->execute(array($_REQUEST['id']));
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>オーストラリア仕事情報共有掲示板</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/../style.css" />
</head>

<body>
    <div id="wrap">
        <!-- header読み込み -->
        <?php include("../header.php"); ?>
        <div class="content">
            <div id="head">
                <h1>ひとこと掲示板</h1>
            </div>
            <div id="content">
                <p>&laquo;<a href="index.php">一覧にもどる</a></p>

                <?php if ($post = $posts->fetch()) : ?>
                    <div class="msg">
                        <img src="/../member_picture/<?php print(h($post['picture'])); ?>" width="250" height="250">
                        <p><?= h($post['message']); ?><span class="name">（<?= h($post['name']); ?>）</span></p>
                        <p class="day"><?= h($post['created']); ?></p>
                    </div>
                <?php else : ?>
                    <p>その投稿は削除されたか、URLが間違えています</p>
                <?php endif; ?>
            </div>
            <footer class="footer_bottom">
                <p>Copyright - 赤坂 壮, 2020 All Rights Reserved.</p>
            </footer>
        </div>
    </div>
</body>

</html>