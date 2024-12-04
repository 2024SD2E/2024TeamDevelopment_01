<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: G7.php");
    exit;
}

// POSTデータをセッションに保存
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['form_data'] = $_POST; // セッションにフォームデータを保存
}
$name = $_SESSION['form_data']['name'];
$email = $_SESSION['form_data']['email'];
$phone = $_SESSION['form_data']['phone'];
$postal = $_SESSION['form_data']['postal'];
$address = $_SESSION['form_data']['address'];
$password = $_SESSION['form_data']['password'];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント新規作成 確認画面</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/G7-1-2.css">
</head>
<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
            <div class="header-text">
                <span>会員登録</span>
            </div>
    </header>

    <main class="form-container">
        <div class="back-link">
            <a href="G7-1-2.php">＜戻る</a>
        </div>
        <section class="form-section">
            <h2>アカウント情報確認画面</h2>
            <form method="POST" action="G7-1-4.php">
                <div class="form-group">
                    <h3>氏名(カナ)</h3>
                    <input type="text" value="<?= htmlspecialchars($name); ?>" readonly>
                </div>
                <div class="form-group">
                    <h3>メールアドレス</h3>
                    <input type="text" value="<?= htmlspecialchars($email); ?>" readonly>
                </div>
                <div class="form-group">
                    <h3>電話番号</h3>
                    <input type="text" value="<?= htmlspecialchars($phone); ?>" readonly>
                </div>
                <div class="form-group">
                    <h3>郵便番号</h3>
                    <input type="text" value="<?= htmlspecialchars($postal); ?>" readonly>
                </div>
                <div class="form-group">
                    <h3>住所</h3>
                    <input type="text" value="<?= htmlspecialchars($address); ?>" readonly>
                </div>
                <div class="password-change">
                    <h2>パスワード</h2>
                    <div class="form-group">
                        <h3>新しいパスワード</h3>
                        <p>セキュリティのため表示されません</p>
                    </div>
                </div>
                <div class="form-submit">
                    <button type="submit">登録</button>
                </div>
            </form>
        </section>
    </main>

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
