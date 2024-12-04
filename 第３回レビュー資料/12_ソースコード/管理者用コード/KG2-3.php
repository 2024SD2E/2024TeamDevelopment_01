<?php
    session_start();
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $admin_id = $_SESSION['admin_id'];
    $password = $_SESSION['password'];
    unset($_SESSION['addname']);
    unset($_SESSION['addemail']);
    unset($_SESSION['addpass']);
    unset($_SESSION['changename']);
    unset($_SESSION['changeemail']);
    unset($_SESSION['changepass']);
    unset($_SESSION['deletepass']);
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
    <title>管理者アカウント管理</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/KG2-3.css">
</head>
<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
            <div class="header-text">
                <span>管理者サイト</span>
            </div>
        </div>
    </header>

    <div class="button-container">
        <button class="button" onclick="location.href='KG2-3-1-1.php'">管理者追加</button>
    
        <button class="button" onclick="location.href='KG2-3-2-1.php'">管理者情報の修正</button>
    
        <button class="button" onclick="location.href='KG2-3-3-1.php'">管理者削除</button>

        <button class="button" onclick="location.href='KG2.php'">トップ</button>

    </div>

    <!--フッター-->
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