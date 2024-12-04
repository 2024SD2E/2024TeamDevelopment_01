<?php
    session_start();
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $admin_id = $_SESSION['admin_id'];
    $password = $_SESSION['password'];

    if (!isset($admin_id)) {
        header('Location: KG1.php');
        exit();
    };

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $addpass1 = $_POST['addpass1'];
        $addpass2 = $_POST['addpass2'];
        $_SESSION['addname'] = $_POST['addname'];
        $_SESSION['addemail'] = $_POST['addemail'];
        $_SESSION['addpass'] = $addpass1;

        if ($addpass1 !== $addpass2) {
            $error_message = "パスワードが一致していません。";
            // } elseif ($addpass1 !== $password) {
            //     echo "<p style='color:red;'>パスワードが間違っています。</p>";
        } else {
            // ログイン成功時にリダイレクト
            header("Location: KG2-3-1-2.php");
        };
    };
?>
<!DOCTYPE html>
<html lang="ja">

<body>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/KG2-3-1-1.css">
        <link rel="stylesheet" href="./css/kcommon.css">
        <title>管理者追加</title>
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
        </header>
        <main>
            <div class="header-inner">
                <a href="KG2-3.php" class="header-back">≺戻る</a>
                <h2 class="header-title">管理者情報入力</h2>
            </div>

            <form action="" method="POST">
                <label>
                    <div class="text-lable">
                        <span class="textbox-label">名前</span>
                        <input type="text" class="textbox" name="addname"
                        value="<?= isset($_SESSION['addname']) ? htmlspecialchars($_SESSION['addname'], ENT_QUOTES, 'UTF-8') : ''; ?>" 
                        required data-maxlength="10"><br>
                        <span class="textbox-label">メールアドレス</span>
                        <input type="email" class="textbox" name="addemail"
                        value="<?= isset($_SESSION['addemail']) ? htmlspecialchars($_SESSION['addemail'], ENT_QUOTES, 'UTF-8') : ''; ?>"  
                        required><br>
                    </div>
                </label>
                <hr>
                <h2>パスワード</h2>
                <label>
                    <div class="text-lable">
                        <span class="textbox-label">パスワード</span>
                        <input type="password" class="textbox" name="addpass1" required><br>
                        <span class="textbox-label">パスワード(確認)</span>
                        <input type="password" class="textbox" name="addpass2" required><br>
                    </div>
                </label>

                <!-- エラーメッセージを表示 -->
                <div class="passcheck">
                    <?php if (!empty($error_message)) : ?>
                        <p class="error-message"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </div>

                <div class="area">
                    <button type="submit" class="check" name="check">確認画面へ</button>
                </div>
            </form>
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
        <!--<script src="./script/script.js"></script>-->
    </body>

</html>