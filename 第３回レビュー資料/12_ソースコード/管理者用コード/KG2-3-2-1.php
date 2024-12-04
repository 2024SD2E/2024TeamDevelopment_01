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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $changepass1 = $_POST['changepass1'];
        $changepass2 = $_POST['changepass2'];
        $_SESSION['changename'] = $_POST['changename'];
        $_SESSION['changeemail'] = $_POST['changeemail'];
        $_SESSION['changepass'] = $changepass1;

        if ($changepass1 !== $changepass2) {
            $error_message = "パスワードが一致していません。";
        // } elseif ($changepass1 !== $password) {
        //     $error_message = "パスワードが間違っています。";
        } else {
            // ログイン成功時にリダイレクト
            header("Location: KG2-3-2-2.php");
        };
    };
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/KG2-3-1-1.css">
    <link rel="stylesheet" href="./css/kcommon.css">
    <title>管理者修正</title>
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
            <h2 class="header-title">管理者情報修正</h2>
        </div>
            <form action="" method="POST">
                <label>
                    <div class="text-lable">
                        <span class="textbox-label">名前</span>
                        <input type="text" class="textbox" name="changename" required data-maxlength="10" value="<?php echo htmlspecialchars($name); ?>"><br>
                        <span class="textbox-label">メールアドレス</span>
                        <input type="email" class="textbox" name="changeemail" required value="<?php echo htmlspecialchars($email); ?>"><br>
                    </div>
                </label>
                <hr>
                <h2>パスワード</h2>
                <label>
                    <div class="text-lable">
                        <span class="textbox-label">パスワード</span>
                        <input type="password" class="textbox" name="changepass1" required><br>
                        <span class="textbox-label">パスワード(確認)</span>
                        <input type="password" class="textbox" name="changepass2" required><br>
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
    </footer>
    <script src="./script/script.js"></script>
</body>

</html>