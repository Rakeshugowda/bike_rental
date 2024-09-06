<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(isset($_POST['submit']))
{
  $fromdate=$_POST['fromdate'];
  $todate=$_POST['todate'];
  $message=$_POST['message'];
  $useremail=$_SESSION['login'];
  $status=0;
  $vhid=$_GET['vhid'];
  $sql="INSERT INTO tblbooking(userEmail,VehicleId,FromDate,ToDate,message,Status) VALUES(:useremail,:vhid,:fromdate,:todate,:message,:status)";
  $query = $dbh->prepare($sql);
  $query->bindParam(':useremail',$useremail,PDO::PARAM_STR);
  $query->bindParam(':vhid',$vhid,PDO::PARAM_STR);
  $query->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
  $query->bindParam(':todate',$todate,PDO::PARAM_STR);
  $query->bindParam(':message',$message,PDO::PARAM_STR);
  $query->bindParam(':status',$status,PDO::PARAM_STR);
  $query->execute();
  $lastInsertId = $dbh->lastInsertId();
  if($lastInsertId)
  {
    $_SESSION['totalamount'] = $_POST['totalamount']; // Save the total amount to session
    echo "<script>alert('Booking successful. Redirecting to payment page.');</script>";
    echo "<script type='text/javascript'> document.location = 'payment.php'; </script>"; // Redirect to payment page
  }
  else
  {
    echo "<script>alert('Something went wrong. Please try again');</script>";
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
<title>Bike Rental Port | Vehicle Details</title>
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
</head>
<body>

<!-- Start Switcher -->
<?php include('includes/colorswitcher.php');?>
<!-- /Switcher -->

<!--Header-->
<?php include('includes/header.php');?>
<!-- /Header -->

<!--Listing-Image-Slider-->
<?php
$vhid=intval($_GET['vhid']);
$sql = "SELECT tblvehicles.*,tblbrands.BrandName,tblbrands.id as bid from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblvehicles.id=:vhid";
$query = $dbh -> prepare($sql);
$query->bindParam(':vhid',$vhid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{
$_SESSION['brndid']=$result->bid;
?>

<section id="listing_img_slider">
  <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" alt="image" width="900" height="560"></div>
  <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage2);?>" class="img-responsive" alt="image" width="900" height="560"></div>
  <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage3);?>" class="img-responsive" alt="image" width="900" height="560"></div>
  <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage4);?>" class="img-responsive"  alt="image" width="900" height="560"></div>
  <?php if($result->Vimage5=="")
{

} else {
  ?>
  <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage5);?>" class="img-responsive" alt="image" width="900" height="560"></div>
  <?php } ?>
</section>
<!--/Listing-Image-Slider-->


<!--Listing-detail-->
<section class="listing-detail">
  <div class="container">
    <div class="listing_detail_head row">
      <div class="col-md-9">
        <h2><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></h2>
      </div>
      <div class="col-md-3">
        <div class="price_info">
          <p>₹<span id="pricePerDay"><?php echo htmlentities($result->PricePerDay);?></span> </p>Per Day
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-9">
        <div class="main_features">
          <ul>
            <li> <i class="fa fa-calendar" aria-hidden="true"></i>
              <h5><?php echo htmlentities($result->ModelYear);?></h5>
              <p>Reg.Year</p>
            </li>
            <li> <i class="fa fa-cogs" aria-hidden="true"></i>
              <h5><?php echo htmlentities($result->FuelType);?></h5>
              <p>Fuel Type</p>
            </li>
            <li> <i class="fa fa-user-plus" aria-hidden="true"></i>
              <h5><?php echo htmlentities($result->SeatingCapacity);?></h5>
              <p>Seats</p>
            </li>
          </ul>
        </div>
        <div class="listing_more_info">
          <div class="listing_detail_wrap">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs gray-bg" role="tablist">
              <li role="presentation" class="active"><a href="#vehicle-overview " aria-controls="vehicle-overview" role="tab" data-toggle="tab">Vehicle Overview </a></li>
              <li role="presentation"><a href="#accessories" aria-controls="accessories" role="tab" data-toggle="tab">Accessories</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <!-- vehicle-overview -->
              <div role="tabpanel" class="tab-pane active" id="vehicle-overview">
                <p><?php echo htmlentities($result->VehiclesOverview);?></p>
              </div>

              <!-- Accessories -->
              <div role="tabpanel" class="tab-pane" id="accessories">
                <!--Accessories-->
                <table>
                  <thead>
                    <tr>
                      <th colspan="2">Accessories</th>
                      <a href="select_accessories.php">ACCESSORIES</a>

                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>AntiLock Braking System (ABS)</td>
                      <?php if($result->AntiLockBrakingSystem==1)
                      {
                        ?>
                      <td><i class="fa fa-check" aria-hidden="true"></i></td>
                      <?php } else { ?>
                      <td><i class="fa fa-close" aria-hidden="true"></i></td>
                      <?php } ?>
                    </tr>
                    <tr>
                      <td>Power Windows</td>
                      <?php if($result->PowerWindows==1)
                      {
                        ?>
                      <td><i class="fa fa-check" aria-hidden="true"></i></td>
                      <?php } else { ?>
                      <td><i class="fa fa-close" aria-hidden="true"></i></td>
                      <?php } ?>
                    </tr>
                    <tr>
                      <td>CD Player</td>
                      <?php if($result->CDPlayer==1)
                      {
                        ?>
                      <td><i class="fa fa-check" aria-hidden="true"></i></td>
                      <?php } else { ?>
                      <td><i class="fa fa-close" aria-hidden="true"></i></td>
                      <?php } ?>
                    </tr>
                    <tr>
                      <td>Leather Seats</td>
                      <?php if($result->LeatherSeats==1)
                      {
                        ?>
                      <td><i class="fa fa-check" aria-hidden="true"></i></td>
                      <?php } else { ?>
                      <td><i class="fa fa-close" aria-hidden="true"></i></td>
                      <?php } ?>
                    </tr>
                    <tr>
                      <td>Central Locking</td>
                      <?php if($result->CentralLocking==1)
                      {
                        ?>
                      <td><i class="fa fa-check" aria-hidden="true"></i></td>
                      <?php } else { ?>
                      <td><i class="fa fa-close" aria-hidden="true"></i></td>
                      <?php } ?>
                    </tr>
                    <tr>
                      <td>Power Steering</td>
                      <?php if($result->PowerSteering==1)
                      {
                        ?>
                      <td><i class="fa fa-check" aria-hidden="true"></i></td>
                      <?php } else { ?>
                      <td><i class="fa fa-close" aria-hidden="true"></i></td>
                      <?php } ?>
                    </tr>
                    <tr>
                      <td>Driver Airbag</td>
                      <?php if($result->DriverAirbag==1)
                      {
                        ?>
                      <td><i class="fa fa-check" aria-hidden="true"></i></td>
                      <?php } else { ?>
                      <td><i class="fa fa-close" aria-hidden="true"></i></td>
                      <?php } ?>
                    </tr>
                    <tr>
                      <td>Passenger Airbag</td>
                      <?php if($result->PassengerAirbag==1)
                      {
                        ?>
                      <td><i class="fa fa-check" aria-hidden="true"></i></td>
                      <?php } else { ?>
                      <td><i class="fa fa-close" aria-hidden="true"></i></td>
                      <?php } ?>
                    </tr>
                    <tr>
                      <td>Crash Sensor</td>
                      <?php if($result->CrashSensor==1)
                      {
                        ?>
                      <td><i class="fa fa-check" aria-hidden="true"></i></td>
                      <?php } else { ?>
                      <td><i class="fa fa-close" aria-hidden="true"></i></td>
                      <?php } ?>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
      
      <!--Side-Bar-->
      <aside class="col-md-3">
        <div class="share_vehicle">
          <p>Share: <br>
          
                <a href="https://code-projects.org/"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
                <a href="https://code-projects.org/"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
                <a href="https://www.linkedin.com/in/rakesh-u-4818562b5?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app "><i class="fa fa-linkedin-square" aria-hidden="true"></i></a>
                <a href="https:http://localhost/bikerental%20PHP/bikerental/"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a>
                <a href="https://instagram.com/believe__in__04?utm_source=qr&igshid=MzNlNGNkZWQ4Mg%3D%3D"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                <a href="https://wa.me/7338152759" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
          </p>
              
        </div>
        <div class="sidebar_widget">
          <div class="widget_heading" style="height: auto; width: calc(100% + 20px); padding: 10px;">
            <h5><i class="fa fa-envelope" aria-hidden="true"></i> Book Now</h5>
          </div>
          <form method="post" style="height: auto; width: calc(100% + 20px); padding: 10px;">
            <div class="form-group">
              <label>From Date:</label>
              <input type="date" class="form-control" name="fromdate" placeholder="From Date" required>
            </div>
            <div class="form-group">
              <label>To Date:</label>
              <input type="date" class="form-control" name="todate" placeholder="To Date" required>
            </div>
            <div class="form-group">
              <label>Message:</label>
              <textarea rows="4" class="form-control" name="message" placeholder="Message" required></textarea>
            </div>
            <?php if($_SESSION['login'])
              {?>
                <div class="form-group">
                  <label>Total Amount: ₹<span id="totalAmount">0</span></label>
                  <input type="hidden" name="totalamount" id="hiddenTotalAmount" value="0">
                </div>
                <div class="form-group">
                  <input type="submit" class="btn" name="submit" value="Book Now">
                </div>
              <?php } else { ?>
                <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal">Login For Book</a>
              <?php } ?>
          </form>
        </div>
      </aside>
      <!--/Side-Bar--> 
    </div>
    
    <?php }} ?>
  </div>
</section>
<!--/Listing-detail--> 

<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer-->

<!--Back to top-->
<div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>
<!--/Back to top--> 

<!--Login-Form -->
<?php include('includes/login.php');?>
<!--/Login-Form --> 

<!--Register-Form -->
<?php include('includes/registration.php');?>

<!--/Register-Form --> 

<!--Forgot-password-Form -->
<?php include('includes/forgotpassword.php');?>
<!--/Forgot-password-Form --> 

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
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const fromDateInput = document.querySelector('input[name="fromdate"]');
    const toDateInput = document.querySelector('input[name="todate"]');
    const totalAmountSpan = document.getElementById('totalAmount');
    const hiddenTotalAmountInput = document.getElementById('hiddenTotalAmount');
    const pricePerDay = parseFloat(document.getElementById('pricePerDay').textContent);

    function calculateTotalAmount() {
      const fromDate = new Date(fromDateInput.value);
      const toDate = new Date(toDateInput.value);
      if (!isNaN(fromDate) && !isNaN(toDate) && toDate >= fromDate) {
        const timeDifference = toDate.getTime() - fromDate.getTime();
        const days = Math.ceil(timeDifference / (1000 * 3600 * 24)) + 1;
        const totalAmount = days * pricePerDay;
        totalAmountSpan.textContent = totalAmount.toFixed(2);
        hiddenTotalAmountInput.value = totalAmount.toFixed(2);
      } else {
        totalAmountSpan.textContent = '0';
        hiddenTotalAmountInput.value = '0';
      }
    }

    fromDateInput.addEventListener('change', calculateTotalAmount);
    toDateInput.addEventListener('change', calculateTotalAmount);
  });
</script>
</body>
</html>
