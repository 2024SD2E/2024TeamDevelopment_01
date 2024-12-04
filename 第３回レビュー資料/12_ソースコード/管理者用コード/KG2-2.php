<?php
require_once 'db.php'; // データベース接続
session_start();

// 検索処理
$searchTerm = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_name LIKE ?");
        $stmt->execute(['%' . $searchTerm . '%']);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("検索エラー: " . $e->getMessage());
    }
} else {
    // データ取得
    // データ取得
    try {
        $stmt = $pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("データ取得エラー: " . $e->getMessage());
    }

    // 削除処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $delete_id = intval($_POST['delete_id']);
        try {
            $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
            $stmt->execute([$delete_id]);
            header("Location: index.php"); // リダイレクトして更新
            exit;
        } catch (PDOException $e) {
            die("削除エラー: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
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
            <h2 class="header-title">商品一覧</h2>
        </div>

        <!-- 検索ボックス -->
        <div class="search-container">
            <form method="GET" action="" class="search-form">
                <label for="search">商品名で検索:</label>
                <input type="text" id="search" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                <div class="add-button">
                    <button type="submit">検索</button>
            </form>
                <form method="" action="./KG2-2-2-1.php">
                    <button type="submit">追加</button>
                </form>
                </div>
        </div>


        <table border="1" cellpadding="8" cellspacing="0">

            <head>
                <tr>
                    <th>ID</th>
                    <th>種別</th>
                    <th>商品名</th>
                    <th>在庫数</th>
                    <th>価格</th>

                    <th></th>
                </tr>
            </head>

            <body>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['product_id']) ?></td>
                        <td><?= htmlspecialchars($product['category_id']) ?></td>
                        <td>
                            <a href="KG2-2-1-1.php?product_id=<?= htmlspecialchars($product['product_id']) ?>">
                                <?= htmlspecialchars($product['product_name']) ?>
                            </a>
                        </td>
                        <td>
                            <a href="KG2-2-4-1.php?product_id=<?= htmlspecialchars($product['product_id']) ?>">
                                <?= htmlspecialchars($product['stock'] ?? 'N/A') ?>
                            </a>
                        </td> <!-- 在庫数 -->
                        <td><?= htmlspecialchars($product['price']) ?>円</td>
                        <td>
                            <form method="POST" action="KG2-2-5.php" onsubmit="return confirm('本当に削除しますか？');">
                                <input type="hidden" name="delete_id" value="<?= htmlspecialchars($product['product_id']) ?>">
                                <button type="submit" class="deletebutton">削除</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </body>
        </table>
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