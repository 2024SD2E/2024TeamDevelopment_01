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
    <link rel="stylesheet" href="./css/KG2-3-1-2.css">
    <link rel="stylesheet" href="./css/kcommon.css">
    <title>追加確認</title>

    <style>
        .text-label {
            display: flex;
            flex-direction: column;
            /* 縦に並べる */
            align-items: center;
            /* 中央揃え */
        }
    </style>
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
        <div class="header-inner">
            <a href="KG2-3-1-1.php" class="header-back">≺戻る</a>
            <h2 class="header-title">管理者情報確認</h2>
        </div>

        <label>
            <div class="text-lable">
                <span class="textbox-label">名前</span>
                <input type="text" class="textbox" name="addname" value="<?php echo $_SESSION['addname']; ?>" readonly><br>
                <span class="textbox-label">メールアドレス</span>
                <input type="email" class="textbox" name="addmail" value="<?php echo $_SESSION['addemail']; ?>" readonly><br>
            </div>
        </label>
        <hr>
        <h2>パスワード</h2>

        <label>
            <div class="text-lable">
                <span class="textbox-label">パスワード</span>
                <input type="password" class="textbox" name="addpass1" value="<?php echo $_SESSION['addpass']; ?>" readonly><br>
                <span class="textbox-label">パスワード(確認)</span>
                <input type="password" class="textbox" name="addpass2" value="<?php echo $_SESSION['addpass']; ?>" readonly><br>
            </div>
        </label>

        <div class="area">
            <form action="KG2-3-1-3.php" method="post">
                <input type="hidden" name="addname" value="<?php echo $_SESSION['addname']; ?>">
                <input type="hidden" name="addemail" value="<?php echo $_SESSION['addemail']; ?>">
                <input type="hidden" name="addpass" value="<?php echo $_SESSION['addpass']; ?>">
                <button class="kakunin" name="">登録</button>
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