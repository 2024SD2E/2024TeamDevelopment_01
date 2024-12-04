<?php
require_once 'db.php'; // データベース接続
session_start();

// 検索処理
$searchTerm = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE name LIKE ?");
        $stmt->execute(['%' . $searchTerm . '%']);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("検索エラー: " . $e->getMessage());
    }
} else {
    // データ取得
    try {
        $stmt = $pdo->query("SELECT * FROM user");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("データ取得エラー: " . $e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員一覧</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/KG2-2.css">
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
            <a href="KG2.php" class="header-back">≺戻る</a>
            <h2 class="header-title">会員一覧</h2>
        </div>

    <!-- 検索ボックス -->
    <div class="search-container">
        <form method="GET" action="" class="search-form">    
                <label for="search">氏名で検索:</label>
                <input type="text" id="search" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                <button type="submit">検索</button>
        </form>
    </div>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>会員ID</th>
                <th>氏名</th>
                <th>メールアドレス</th>
                <th>住所</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['address']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

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