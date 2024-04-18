<?php

class LaundryStockManager {
    private $stockFile = 'stock.txt';

    public function __construct() {
        if (!file_exists($this->stockFile)) {
            $this->updateStock(array());
        }
    }

    private function readStock() {
        $stockData = file_get_contents($this->stockFile);
        return json_decode($stockData, true);
    }

    private function updateStock($stock) {
        $stockData = json_encode($stock);
        file_put_contents($this->stockFile, $stockData);
    }

    public function addSupplies($item, $quantity) {
        $stock = $this->readStock();
        if (isset($stock[$item])) {
            $stock[$item] += $quantity;
        } else {
            $stock[$item] = $quantity;
        }
        $this->updateStock($stock);
    }

    public function removeSupplies($item, $quantity) {
        $stock = $this->readStock();
        if (isset($stock[$item])) {
            if ($stock[$item] >= $quantity) {
                $stock[$item] -= $quantity;
                $this->updateStock($stock);
                return true;
            } else {
                return false; // Insufficient stock
            }
        } else {
            return false; // Item not found
        }
    }

    public function getStock() {
        return $this->readStock();
    }
}

// Initialize LaundryStockManager
$stockManager = new LaundryStockManager();

// Process form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) {
        $item = $_POST["item"];
        $quantity = $_POST["quantity"];
        $stockManager->addSupplies($item, $quantity);
    } elseif (isset($_POST["remove"])) {
        $item = $_POST["item"];
        $quantity = $_POST["quantity"];
        $result = $stockManager->removeSupplies($item, $quantity);
        if (!$result) {
            $errorMessage = "Failed to remove $quantity $item from stock. Either the item doesn't exist or the quantity requested exceeds the stock.";
        }
    }
}

// Get current stock
$stock = $stockManager->getStock();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../Presentation/css\track.css"/>
    <title>138 Dorm Laundry Supplies Stock</title>
</head>
<body>
    <h1>138 Dorm Laundry Supplies Stock</h1>
  
    <h2>Add Supplies</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="item">Item:</label>
        <input type="text" id="item" name="item" required><br><br>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>
        <input type="submit" name="add" value="Add Supplies">
    </form>
  
    <h2>Remove Supplies</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="item">Item:</label>
        <input type="text" id="item" name="item" required><br><br>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>
        <input type="submit" name="remove" value="Remove Supplies">
    </form>

    <?php if (isset($errorMessage)): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <h2>Current Stock</h2>
    <table border="1">
        <tr>
            <th>Item</th>
            <th>Quantity</th>
        </tr>
        <?php foreach ($stock as $item => $quantity): ?>
            <tr>
                <td><?php echo $item; ?></td>
                <td><?php echo $quantity; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
