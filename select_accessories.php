<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Fetch accessories data
$sql = "SELECT id, name, price FROM accessories";
$result = $dbh->query($sql);

$accessoriesData = [];
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $accessoriesData[] = $row;
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
<title>Bike Rental Portal | Select Accessories</title>
<!--Bootstrap -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
<!--Custom Style -->
<link rel="stylesheet" href="assets/css/styles.css" type="text/css">
<!--OWL Carousel slider-->
<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
<!--slick-slider -->
<link href="assets/css/slick.css" rel="stylesheet">
<!--bootstrap-slider -->
<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
<!--FontAwesome Font Style -->
<link href="assets/css/font-awesome.min.css" rel="stylesheet">

<!-- SWITCHER -->
<link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="assets/images/favicon-icon/24x24.png">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
.container {
    text-align: center; /* Center-align the contents of the container */
}

table {
    width: 80%; /* Reduce the table width */
    max-width: 600px; /* Set a maximum width for the table */
    margin: 0 auto; /* Center table horizontally */
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #ddd; /* Lighter border color */
}

th, td {
    padding: 8px; /* Reduced padding for a smaller table */
    text-align: left;
}

th {
    background-color: #f4f4f4;
}

#totalAmount {
    font-size: 1em; /* Adjust font size as needed */
    font-weight: bold;
}

button {
    margin-top: 20px; /* Space above the button */
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>

<!-- Start Switcher -->
<?php include('includes/colorswitcher.php');?>
<!-- /Switcher -->

<!--Header-->
<?php include('includes/header.php');?>
<!-- /Header -->

<section class="container">
    <h2>Select Accessories</h2>
    <form id="accessoriesForm">
        <table>
            <thead>
                <tr>
                    <th>Accessory</th>
                    <th>Price</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($accessoriesData) > 0) {
                    foreach ($accessoriesData as $row) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                        echo '<td>₹' . number_format($row['price'], 2) . '</td>';
                        echo '<td><input type="checkbox" name="accessories[]" value="' . $row['id'] . '" data-price="' . $row['price'] . '"></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">No accessories available.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <p>Total Accessories Amount: ₹<span id="totalAmount">0.00</span></p>
        <input type="hidden" id="hiddenTotalAmount" name="hiddenTotalAmount" value="0.00">
        <button type="button" id="saveButton" class="btn btn-primary">Save Accessories Amount</button>
    </form>
</section>

<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    var checkboxes = document.querySelectorAll('input[name="accessories[]"]');
    var totalAmountSpan = document.getElementById('totalAmount');
    var hiddenTotalAmountInput = document.getElementById('hiddenTotalAmount');
    var saveButton = document.getElementById('saveButton');
    
    function updateTotal() {
        var total = 0;
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                total += parseFloat(checkbox.getAttribute('data-price'));
            }
        });
        totalAmountSpan.textContent = total.toFixed(2);
        hiddenTotalAmountInput.value = total.toFixed(2);

        // Save the total amount in the session
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "save_accessories_total.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("total=" + total.toFixed(2));
    }

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', updateTotal);
    });

    // Initialize total amount on page load
    updateTotal();

    saveButton.addEventListener('click', function() {
        // Optionally show a message or handle additional logic
        alert('Accessories amount saved!');
    });
});
</script>

</body>
</html>
