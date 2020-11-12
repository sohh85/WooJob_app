<?php
session_start();
// require_once 'pdo_connect.php';
require_once 'function.php';

if (!isset($_SESSION['join'])) {
	header('Location: register.php');
	exit();
}

if (!empty($_POST)) {
	$stmt = $dbh->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
	$stmt->execute(array(
		$_SESSION['join']['name'],
		$_SESSION['join']['email'],
		sha1($_SESSION['join']['password']),
		$_SESSION['image']
	));
	unset($_SESSION['join']);
	unset($_SESSION['image']);

	header('Location: thanks.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="../style.css" />
</head>

<body>
	<div id="wrap">
		<!-- header読み込み -->
		<?php include("header.php"); ?>
		<div class="content">
			<div id="head">
				<h1>会員登録</h1>
			</div>

			<div id="content">
				<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>

				<p><?= var_dump($_SESSION['image']); ?></p>

				<form action="" method="post">
					<input type="hidden" name="action" value="submit" />
					<dl>
						<dt>ニックネーム</dt>
						<?php echo (h($_SESSION['join']['name'])); ?>
						<dd>
						</dd>
						<dt>メールアドレス</dt>
						<?php echo (h($_SESSION['join']['email'])); ?>
						<dd>
						</dd>
						<dt>パスワード</dt>
						<dd>
							【表示されません】
						</dd>
						<dt>写真など</dt>
						<dd>

							<?php if (!empty($_SESSION['image'])) : ?>
								<img src="member_picture/<?= (h($_SESSION['image'])); ?>" style="width:200px;">
							<?php endif; ?>

						</dd>
					</dl>
					<div><a href="register.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" />
					</div>
				</form>
			</div>
			<footer class="footer_bottom">
				<p>Copyright - 赤坂 壮, 2020 All Rights Reserved.</p>
			</footer>
		</div>
	</div>
</body>

</html>