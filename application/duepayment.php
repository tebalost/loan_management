<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Due/Overdue Payments
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> <a href="newpayments.php?id=<?php echo $_SESSION['tid']; ?>">Payment</a></li>
        <li class="active">List</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/duepayment_data.php"); ?>

        <?php
        if(!isset($_GET['act'])) {
            include("modal/payments_modal.php");
        }
        if($_GET['act']=="reversedPayments") {
            include("modal/reverse_payments_modal.php");
        }
        if($_GET['act']=="overPayments") {
            include("modal/over_payments_modal.php");
        }
        include ("modal/deny_reversal_modal.php");
        ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>