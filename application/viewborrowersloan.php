<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<?php
$id = $_GET['id'];
$loanId = $_GET['loanId'];
?>
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> <a href="listloans.php?id=<?php echo $_SESSION['tid']; ?>">Loans</a></li>
        <li class="active">Profile</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/viewborrowers_loans.php"); ?>
        <?php include("modal/payments_modal.php"); ?>
	</section>
</div>
    <?php
    include("modal/listloans_modal_profile.php");
    include("modal/over_payments_modal.php");
    ?>
<?php include("include/footer.php"); ?>
