<?php
// Database connection using PDO in PHP
try {
    // Create a new PDO instance
    $pdo = new PDO(
        'mysql:host=mysql304.phy.lolipop.lan;dbname=LAA1557115-aso2301376;charset=utf8',
        'LAA1557115',
        'Pass0914'
    );

    // Set PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Catch any errors and display the message
    echo "Connection failed: " . $e->getMessage();
    exit; // 結果が出ないように処理を終了
}
?>
