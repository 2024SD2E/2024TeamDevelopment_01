<?php
session_start();
require_once 'db.php';

$admin_id=$_SESSION['admin_id'];
if(isset($admin_id)){
    header('Location: KG2.php');
    exit;
};

$error_message = ""; // エラーメッセージを格納する変数

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = $pdo->prepare('SELECT * FROM administrators WHERE email = ? AND password = ?');
        $sql->execute([$email, $password]);

        $admin = $sql->fetch();
        if ($admin) {
            $_SESSION['email'] = $admin['email'];
            $_SESSION['name'] = $admin['name'];
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['password'] = $admin['password'];

            // 認証成功後にリダイレクト
            header('Location: KG2.php'); // リダイレクト先のページを指定
            exit;
        } else {
            $error_message = "メールアドレスまたはパスワードが違います。"; // エラーメッセージを設定
        }
    }
    $pdo = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
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
