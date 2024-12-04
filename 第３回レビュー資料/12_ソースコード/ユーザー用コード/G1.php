<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G1.css">
</head>

<body>
    <?php require_once 'header.php' ?>


    <div class="carousel">
        <!--  1枚目の画像  -->
        <img src="./upload/curuser1.png">
        <!--  2枚目の画像  -->
        <img src="./upload/curuser2.png">
        <!--  3枚目の画像  -->
        <img src="./upload/curosel3.png">
        <!--  4枚目の画像  -->
        <img src="./upload/curuser4.png">  
        <!--  1枚目と同じ画像  -->
        <img src="./upload/curuser1.png">
    </div>

    <div class="toptext">
        おすすめ商品
    </div>


    <section class="product-list">
        <?php
        require 'db.php';

        try {
            // クエリを準備
            $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id IN (?,?,?,?,?,?,?,?,?)");

            $stmt->execute([1,2,3,4,22,5,31,39,40]);

            // 結果を確認
            while ($row = $stmt->fetch()) {
                echo '<div class="product-item">';
                echo '<form action="G6-2.php" method="POST">';
                echo '<input type="hidden" name="type" value="' . $row['product_id'] . '">';
                echo '<button type="submit" class="no-style"><img src="' . $row['image_url'] . '"></button>';
                echo '</form>';
                echo '<p>' . $row['product_name'] . '</p>';
                echo '<span>￥' . number_format($row['price']) . '</span>';
                echo '</div>';
            }
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
        }
        ?>

    </section>

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
