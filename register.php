<?php
session_start();
require_once 'pdo_connect.php';
require_once 'function.php';

if (!empty($_POST)) {

    if ($_POST['name'] === '') {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] === '') {
        $error['email'] = 'blank';
    } elseif (!preg_match('/^[0-9a-z_.\/?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$/', $_POST['email'])) {
        $error['email'] = 'unfit';
    }

    if (strlen($_POST['password'] < 8)) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] === '') {
        $error['password'] = 'blank';
    }

    $fileName = $_FILES['image']['name'];
    if (!empty($fileName)) {
        $ext = substr($fileName, -3); //拡張子を得る為に
        if ($ext != 'JPG' && $ext != 'gif' && $ext != 'png') {
            $error['image'] = 'type';
        }
    }

    // エラーがない場合は次の処理へ。メールが登録されたものと重複してないかチェック
    if (empty($error)) {
        $member = $dbh->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        $member->execute(array($_POST['email']));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }
    }


    if (empty($error)) {
        $image = 0;
        if (!empty($_FILES['image']['name'])) {
            $image = date('YmdHis') . $_FILES['image']['name']; //日付とファイル名合わせて被り防止
            move_uploaded_file($_FILES['image']['tmp_name'], 'member_picture/' . $image);
            //tmp_nameは一時的に保存してる場所。move_uploaded_file関数でちゃんと保存。一つ目のパラメータが今ある場所、二つ目が新たに保存する場所
        }
        $_SESSION['image'] = $image;
        $_SESSION['join'] = $_POST;
        header('Location: check.php');
        exit();
    }
}

if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {
    $_POST = $_SESSION['join'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>会員登録</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="wrap">
        <!-- header読み込み -->
        <?php include("header.php"); ?>
        <div class="content">

            <div id="head">
                <h1>Register</h1>
            </div>
            <div id="content">
                <p>次のフォームに必要事項をご記入ください。</p>
                <p>既に登録済の方はこちらからどうぞ。</p>
                <p class="mb-3">&raquo;<a href="index.php">ログインする</a></p>

                <form action="" method="post" enctype="multipart/form-data">
                    <dl>
                        <dt>ニックネーム<span class="required">必須</span></dt>
                        <dd>
                            <input type="text" name="name" size="35" maxlength="255" value="<?php echo (h($_POST['name'])); ?>" />
                            <?php if ($error['name'] === 'blank') : ?>
                                <p class="error">*ニックネームを入力してください</p>
                            <?php endif; ?>
                        </dd>
                        <dt>メールアドレス<span class="required">必須</span></dt>
                        <dd>
                            <input type="text" name="email" size="35" maxlength="255" value="<?php echo (h($_POST['email'])); ?>" />
                            <?php if ($error['email'] === 'blank') : ?>
                                <p class="error">*メールアドレスを入力してください</p>
                            <?php elseif ($error['email'] === 'unfit') : ?>
                                <p class="error">*正しい形式で入力してください</p>
                            <?php endif; ?>
                            <?php if ($error['email'] === 'duplicate') : ?>
                                <p class="error">*指定されたメールアドレスは既に登録されています</p>
                            <?php endif; ?>
                            <?php if ($error['email'] === 'unfit') : ?>
                                <p class="error">*「メールアドレス」は正しい形式で入力してください</p>
                            <?php endif; ?>
                        <dt>パスワード<span class="required">必須</span></dt>
                        <dd>
                            <input type="password" name="password" size="10" maxlength="20" value="<?php echo (h($_POST['password'])); ?>" />
                            <?php if ($error['password'] === 'length') : ?>
                                <p class="error">*パスワードは8文字以上入力してください</p>
                            <?php endif; ?>
                            <?php if ($error['password'] === 'blank') : ?>
                                <p class="error">*パスワードを入力してください</p>
                            <?php endif; ?>
                        </dd>
                        <dt>写真など</dt>
                        <dd>
                            <input type="file" name="image" size="35" value="test">
                            <?php if ($error['image'] === 'type') : ?>
                                <p class="error">*「.gif」「.png」「.jpg」の写真を使用してください</p>
                            <?php endif; ?>
                            <?php if (!empty($error)) : ?>
                                <p class="error">*もう一度ファイルを指定してください</p>
                            <?php endif; ?>
                        </dd>
                    </dl>
                    <div><input type="submit" value="入力内容を確認する" /></div>
                </form>
                <footer class="footer_bottom">
                    <p>Copyright - 赤坂 壮, 2020 All Rights Reserved.</p>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>