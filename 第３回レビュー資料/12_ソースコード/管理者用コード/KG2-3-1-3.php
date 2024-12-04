<?php
    session_start();
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $admin_id = $_SESSION['admin_id'];
    $password = $_SESSION['password'];
    if (!isset($admin_id)) {
        header('Location: KG1.php');
        exit;
    };
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/KG2-3-1-3.css">
    <link rel="stylesheet" href="./css/kcommon.css">
    <title>登録完了</title>
</head>

<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
        <div class="header-text">
            <span>管理者サイト</span>
        </div>
        </div>
    </header>
    
    <main>
        <?php
            $addname = $_POST['addname'];
            $addemail = $_POST['addemail'];
            $addpass = $_POST['addpass'];

        require 'db.php';
        try {
            $stmt = $pdo->prepare('INSERT INTO administrators(password, name, email)VALUES(?, ?, ?)');
            $stmt->execute([$addpass, $addname, $addemail]);
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
        }
        ?>

        <h2>登録完了</h2>

        <div class="area">
            <form action="KG2-3.php" method="post">
                <button class="back" name="">管理者TOP</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <a href="#">
                <div class="footer-logo">
                    <span>chocolate</span>
                    <span>GARDEN</span>
                </div>
            </a>
            <div class="footer-text">
                <span>生チョコレート専門店オンラインショップ</span>
            </div>
        </div>
    </footer>
</body>

</html>