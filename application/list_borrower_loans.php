<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php $id = $_GET['id']; ?>
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Borrowers Loans Information
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> <a href="listloans.php?id=<?php echo $_SESSION['tid']; ?>">Loans</a></li>
        <li class="active">Borrowers Loans</li>
      </ol>
    </section>
    <section class="content">
        <?php echo "<div class='alert alert-success'>Borrower's Loan Created Successfully!</div>"; ?>
		<?php include("include/viewborrowers_loans_old.php"); ?>
	</section>
</div>	

<?php include("modal/listloans_modal.php"); ?>

<?php include("include/footer.php"); ?>