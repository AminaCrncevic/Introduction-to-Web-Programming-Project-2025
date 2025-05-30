
<?php
require_once 'BaseDao.php';

try {
    
    $dao = new BaseDao('orders');
    $results = $dao->getAll();

    echo "Connection successful! Fetched " . count($results) . " rows.\n";
    print_r($results);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
