<?php
session_start();

$previousUrl = $_SERVER['HTTP_REFERER'] ?? null;

if ($previousUrl && strpos($previousUrl, 'G7-1.php') !== false) {
    $previousUrl = 'G1.php';
}

require_once 'db.php';

$user_id=$_SESSION['user_id'];
if(isset($user_id)){
    header('Location: G7-1.php');
    exit;
};

$error_message = ""; // エラーメッセージを格納する変数

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = $pdo->prepare('SELECT * FROM user WHERE email = ? AND password = ?');
        $sql->execute([$email, $password]);

        $user = $sql->fetch();
        if ($user) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_id'] = $user['user_id'];

            // 認証成功後にリダイレクト
            header('Location: G7-1.php'); // リダイレクト先のページを指定
            exit;
        } else {
            $error_message = "メールアドレスまたはパスワードが違います。"; // エラーメッセージを設定
        }
    }
    $pdo = null;
}
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/G7.css">
</head>
<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
            <div class="header-text">
                <span>ログイン</span>
            </div>
        </div>
    </header>

    <form action="" method="post" class="kg1form">
        <div class="kg1">
            メールアドレス<br>
        </div>
        <div class="kg1-1">
            <input type="email" class="kg1email" name="email" required><br><br>
        </div>
        <div class="kg1">
            パスワード<br>
        </div>
        <div class="kg1-1">
            <input type="password" class="kg1email" name="password" required><br>
            <!-- エラーメッセージをここに表示 -->
            <?php if ($error_message): ?>
                <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>
            <br>
        </div>
      
        <div class="kg1-1"> 
            <div class="kg1button-container">
                <button type="submit" class="kg1button">login</button><br><br>
                <a href="./G7-1-2.php"><button type="button" class="kg1button">新規作成</button></a><br><br>
                <a href="<?= htmlspecialchars($previousUrl); ?>"><button type="button" class="kg1button">戻る</button></a><br><br><br>
            </div>
        </div>
    </form>

    <footer>
        <div class="footer-container">
            <a href="#"><div class="footer-logo">
                <span>chocolate</span>
                <span>GARDEN</span>
            </div></a>
            <div class="footer-text">
                <span>生チョコレート専門店オンラインショップ</span>
            </div>
        </div>
    </footer>    
    
</body>
</html>
