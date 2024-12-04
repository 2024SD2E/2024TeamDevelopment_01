<?php
    session_start();
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $admin_id = $_SESSION['admin_id'];
    $password = $_SESSION['password'];
    if(!isset($admin_id)){
        header('Location: KG1.php');
        exit;
    };
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者トップ</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/KG2.css">
</head>

<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
        <div class="header-text">
            <span>管理者サイト</span>
        </div>
    </header>

    <div class="button-container">
        <form action="KG2-1-1.php" method="post">
            <button class="button" name="">会員管理</button>
        </form>

        <form action="KG2-2.php" method="post">
            <button class="button" name="">商品管理</button>
        </form>


        <form action="KG2-3.php" method="post">
            <button class="button" name="">アカウント管理</button>
        </form>
    </div>

    <!--フッター-->
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