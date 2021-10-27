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
        Payment Reversal
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> <a href="listpayment.php?id=<?php echo $_SESSION['tid']; ?>">Payment</a></li>
        <li class="active">Create</li>
      </ol>
    </section>
	
	
    <section class="content">
		<?php include("include/view_pmt_data.php"); ?>
	</section>	
</div>	

<?php include("include/footer.php"); ?>