<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add and Remove Items with Checkboxes</title>
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .item {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add and Remove Items</h2>
        <form method="post">
            <div class="item">
                <input type="checkbox" id="item1" name="items[]" value="Item 1">
                <label for="item1">Item 1</label>
            </div>
            <div class="item">
                <input type="checkbox" id="item2" name="items[]" value="Item 2">
                <label for="item2">Item 2</label>
            </div>
            <div class="item">
                <input type="checkbox" id="item3" name="items[]" value="Item 3">
                <label for="item3">Item 3</label>
            </div>
            <div class="item">
                <input type="checkbox" id="item4" name="items[]" value="Item 4">
                <label for="item4">Item 4</label>
            </div>
            <br>
            <button type="submit" name="add">Add Selected Items</button>
            <button type="submit" name="remove">Remove Selected Items</button>
        </form>

        <?php
        // Initialize array to store selected items
        $selectedItems = [];

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Handle adding selected items
            if (isset($_POST['add'])) {
                if (!empty($_POST['items'])) {
                    foreach ($_POST['items'] as $item) {
                        $selectedItems[] = $item;
                    }
                }
            }

            // Handle removing selected items
            if (isset($_POST['remove'])) {
                if (!empty($_POST['items'])) {
                    foreach ($_POST['items'] as $item) {
                        // Remove item from selected items array if it exists
                        if (($key = array_search($item, $selectedItems)) !== false) {
                            unset($selectedItems[$key]);
                        }
                    }
                }
            }
        }

        // Display selected items
        if (!empty($selectedItems)) {
            echo "<h3>Selected Items:</h3>";
            echo "<ul>";
            foreach ($selectedItems as $item) {
                echo "<li>$item</li>";
            }
            echo "</ul>";
        }
        ?>
    </div>
</body>
</html>
