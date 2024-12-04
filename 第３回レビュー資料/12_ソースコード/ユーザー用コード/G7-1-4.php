<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: G7.php");
    exit;
}

$name = $_SESSION['form_data']['name'];
$email = $_SESSION['form_data']['email'];
$phone = $_SESSION['form_data']['phone'];
$postal = $_SESSION['form_data']['postal'];
$address = $_SESSION['form_data']['address'];
$password = $_SESSION['form_data']['password'];
$confirm_password = $_SESSION['form_data']['confirm_password'];

require_once 'db.php';
$sql = $pdo->prepare('INSERT INTO user (password, name, postal_code, address, email, phonenumber) VALUES (?, ?, ?, ?, ?, ?)');
$sql->execute([$password,$name,$postal,$address,$email,$phone]);


$sql = $pdo->prepare('SELECT * FROM user WHERE email = ? AND password = ?');
        $sql->execute([$email, $password]);

        $user = $sql->fetch();
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['user_id'] = $user['user_id'];

        $pdo = null;

?>

<!DOCTYPE html>
<html lang="ja">
<header>
    <?php require_once 'Kheader.php'; ?>
        <div class="header-text">
            <span>会員登録</span>
        </div>
    </div>
 </header>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント新規登録</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/G7-1-4.css">
</head>
<body>
            <div class="touroku">
                <h1>登録完了</h1>
            </div>
            <div class="arigatou">
                <h3>ありがとうございます！</h3>
            </div>
            <div class="form-submit">
            <a href="G7-1.php"><button type="submit">マイページ</button>
            </div>
        </section>
    </main>
</body>
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
    
</html>
