<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (!isset($_SESSION['login'])) {
    header('location:index.php');
    exit;
}

// Retrieve the total bike rental amount and accessories total from the session
$totalBikeRentalAmount = isset($_SESSION['totalamount']) ? floatval($_SESSION['totalamount']) : 0.00;
$totalAccessoriesAmount = isset($_SESSION['accessoriesamount']) ? floatval($_SESSION['accessoriesamount']) : 0.00;

// Calculate the total amount
$totalAmount = $totalBikeRentalAmount + $totalAccessoriesAmount;
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
<title>Bike Rental Portal | Payment</title>
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
        .payment-section {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .payment-section .form-container {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: -50px;
        }

        .payment-section .form-group {
            margin-bottom: 15px;
        }

        .payment-section label {
            font-weight: bold;
        }

        .payment-section input, .payment-section select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .payment-section .form-group .btn {
            padding: 10px 20px;
        }

        .footer-section {
            padding: 20px;
            background: #333;
            color: #fff;
        }

        .footer-section a {
            color: #fff;
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
<!--Payment Section-->
<section class="payment-section">
    <div class="form-container">
        <h2>Payment Details</h2>
        <p>Bike Rental Amount: ₹<?php echo number_format($totalBikeRentalAmount, 2); ?></p>
        <p>Accessories Amount: ₹<?php echo number_format($totalAccessoriesAmount, 2); ?></p>
        <p>Total Amount: ₹<?php echo number_format($totalAmount, 2); ?></p>

        <form method="post" action="process_payment.php" onsubmit="return validateForm()">
            <!-- Hidden total amount field -->
            <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars(number_format($totalAmount, 2)); ?>">
            
            <div class="form-group">
                <label for="paymentmethod">Payment Method:</label>
                <select class="form-control" id="paymentmethod" name="paymentmethod" required onchange="togglePaymentMethod()">
                    <option value="">Select Payment Method</option>
                    <option value="Paytm">Paytm</option>
                    <option value="Card">Card</option>
                </select>
            </div>
            <div id="paytm_section" style="display: none;">
                <div class="form-group">
                    <label>
                        <input type="radio" name="paytm_option" value="paytm_qr" id="paytm_qr_option" onclick="toggleQRCode()"> Scan Paytm QR Code
                    </label>
                    <label>
                        <input type="radio" name="paytm_option" value="paytm_upi" id="paytm_upi_option" onclick="toggleUPI()"> UPI Payment
                    </label>
                </div>
                <div class="form-group" id="paytm_qr_code" style="display: none;">
                    <img src="assets/images/qr.jpeg" alt="Paytm QR Code" class="img-responsive">
                </div>
                <div id="upi_section" style="display: none;">
                    <div class="form-group">
                        <label for="upi_id">UPI ID</label>
                        <input type="text" class="form-control" id="upi_id" name="upi_id" placeholder="example@upi">
                    </div>
                </div>
            </div>
            <div id="card_payment_section" class="card-payment-section" style="display: none;">
                <div class="form-group">
                    <label for="cardnumber">Card Number</label>
                    <input type="text" class="form-control" id="cardnumber" name="cardnumber" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                <div class="form-group">
                    <label for="cardname">Name on Card</label>
                    <input type="text" class="form-control" id="cardname" name="cardname" placeholder="John Doe">
                </div>
                <div class="form-group row">
                    <label for="expiry">EXP</label>
                    <input type="text" class="form-control" id="expiry" name="expiry" placeholder="MM/YYYY" maxlength="7">
                    <label for="cvv">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" maxlength="4">
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="pay" id="paynow" value="Pay Now ₹<?php echo number_format($totalAmount, 2); ?>">
            </div>
        </form>
    </div>
</section>

<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer-->

<!--/Forgot-password-Form -->
<?php include('includes/forgotpassword.php');?>

<script>
function togglePaymentMethod() {
    var paymentMethod = document.getElementById("paymentmethod").value;
    var paytmSection = document.getElementById("paytm_section");
    var cardPaymentSection = document.getElementById("card_payment_section");
    if (paymentMethod === "Paytm") {
        paytmSection.style.display = "block";
        cardPaymentSection.style.display = "none";
    } else if (paymentMethod === "Card") {
        paytmSection.style.display = "none";
        cardPaymentSection.style.display = "block";
    } else {
        paytmSection.style.display = "none";
        cardPaymentSection.style.display = "none";
    }
}

function toggleQRCode() {
    var paytmQRCode = document.getElementById("paytm_qr_code");
    var upiSection = document.getElementById("upi_section");
    if (document.getElementById("paytm_qr_option").checked) {
        paytmQRCode.style.display = "block";
        upiSection.style.display = "none";
    } else {
        paytmQRCode.style.display = "none";
        upiSection.style.display = "none";
    }
}

function toggleUPI() {
    var upiSection = document.getElementById("upi_section");
    var paytmQRCode = document.getElementById("paytm_qr_code");
    if (document.getElementById("paytm_upi_option").checked) {
        upiSection.style.display = "block";
        paytmQRCode.style.display = "none";
    } else {
        upiSection.style.display = "none";
        paytmQRCode.style.display = "none";
    }
}

function validateForm() {
    var paymentMethod = document.getElementById("paymentmethod").value;
    var valid = true;

    if (paymentMethod === "Paytm") {
        var paytmOption = document.querySelector('input[name="paytm_option"]:checked');
        if (!paytmOption) {
            alert("Please select a Paytm payment option.");
            valid = false;
        }
        if (paytmOption && paytmOption.value === "paytm_upi") {
            var upiId = document.getElementById("upi_id").value;
            if (!upiId) {
                alert("Please enter UPI ID.");
                valid = false;
            }
        }
    } else if (paymentMethod === "Card") {
        var cardNumber = document.getElementById("cardnumber").value;
        var cardName = document.getElementById("cardname").value;
        var expiry = document.getElementById("expiry").value;
        var cvv = document.getElementById("cvv").value;

        if (!cardNumber || !cardName || !expiry || !cvv) {
            alert("Please fill in all card details.");
            valid = false;
        }
    }

    return valid;
}
</script>
<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/interface.js"></script>
<!--Switcher-->
<script src="assets/switcher/js/switcher.js"></script>
<!--bootstrap-slider-JS-->
<script src="assets/js/bootstrap-slider.min.js"></script>
<!--Slider-JS-->
<script src="assets/js/slick.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
</body>
</html>
