<?php
	// this is o for testing the file as standalone
	// Need to be removed


	require('../config/connect.php');
$id = $_GET['id'];
$getCompanyInfo  = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
$companyInfo = mysqli_fetch_assoc($getCompanyInfo);

$selectLoan = mysqli_query($link, "SELECT * FROM loan_info WHERE borrower='$id'") or die (mysqli_error($link));
$additionLoanInfo = mysqli_fetch_assoc($selectLoan);


  // getting the borrower details
  $borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE id ='$id'");
  $borrowerInfo = mysqli_fetch_assoc($borrower);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- font awesone cdn -->
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="custom/style.css">

</head>
<body>
	<?php if(mysqli_num_rows( $getCompanyInfo ) > 0) { ?>
<div class="container mt-4">
	<?php include "printingHeader.php" ?>
</div>
<!-- section of the page -->
<div class="container mt-8">
	<div class="col-xs-12">
            <div class="text-right">
                    <div class="noprint">
                        <a href="" class="btn btn-warning" id="back"><i class="fa fa-mail-reply-all">&nbsp;&nbsp;</i>Back</a>
                        <button class="btn btn-success print" id="print"><i class="fa fa-print">&nbsp;&nbsp;</i>Print</button>
                    </div>
            </div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<h3 class="h4">SALARY DEDUCTION AUTHORISATION FORM</h3>
		</div>
	</div>
	<div class="row mt-3">
		<table class="table-borderless" style="width: 45%;">
			<thead>
				<th><h4 class="h4">EMPLOYEE</h4></th>
			</thead>
			<tbody>
				<tr>
					<th>Surname: </th>
					<td>
						<?php if(isset($borrowerInfo['lname']))
							echo $borrowerInfo['lname'] 
						?>
					</td>
				</tr>
				<tr>
					<th>Forenames:</th>
					<td> 
						<?php 
						 if(isset($borrowerInfo['fname']))
						 		echo $borrowerInfo['fname'] 
						?> 
					</td>
				</tr>
			</tbody>
			<thead>
				<th><h4 class="h4 mt-3">EMPLOYER</h4></th>
			</thead>

			<tbody>
				<tr>
					<th>Name of the Employer </th>
					<td> 
						<?php if(isset($borrowerInfo['employer']))
								echo $borrowerInfo['employer'] 
						?>
					</td>
				</tr>
				<tr>
					<th>Department</th>
					<td>
						<?php if(isset($borrowerInfo['employment_status']))
							echo $borrowerInfo['employment_status'] 
						?> 
					</td>
				</tr>

				<tr>
					<th>Position / Type of work:</th>
					<td>
						<?php 
							if(isset($borrowerInfo['occupation']))
								echo $borrowerInfo['occupation'] 
							?> 
						</td>
				</tr>
			</tbody>
			
		</table>
	</div>
<?php } ?>
	<div class="row">
		<div class="col-md-12 mt-5">
			<p class="text-justify mt-5">
			I, the undersigned, request and authorize my Employer named above to deduct from Monthly salary 
			the amounts due and payable by me at any particular time, and pay the amounts so deducted to People???s 
			Saccos. I further understand and undertake that this is an irrevocable instruction and cannot be 
			cancelled by me until all amount due have been paid to Pulamaliboho financial Service.  Should my 
			Employer for any reason, not deduct any of the mounts in terms of this request, I shall consider the 
			amounts unpaid, and if due I undertake to pay Pulamaliboho Financial Service such sums.  I further
			understand and undertake that Pulamaboho Financial Service will receive all payments in terms of this 
			request without prejudice to its rights, and I shall regard the receipt of this request by Pulamaliboho 
			FS as receipt of the same by my said Employer
			</p>
		</div>
	</div>

	<div class="row mt-5">
		<div class="col-md-12">
			<table class="table-bordered table-responsive">
				<thead>
					<tr>
					<th scope="col">ID TYPE</th>
					<th scope="col">ID NUMBER</th>
					<th scope="col">EMPLOYEE NUMBER</th>
					<th scope="col">AMOUNT OF MONTHLY DEDUCTION</th>
					<th scope="col">FIRST INSTALMENT RECEIPT DATE</th>
					<th scope="col">FIRST INSTALMENT DEDUCTION MONTH</th>
					<th scope="col">NO OF INSTALMENTS</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td scope="row">
							<?php 
							if($borrowerInfo['id_number'])
								echo "National ID";
							else if($borrowerInfo['passport'])
								echo "Passport";
							?>
						</td>
						<td><?php echo $borrowerInfo['id_number']  ?></td>
						<td>002244</td>
						<td><?php echo number_format($additionLoanInfo['amount_topay'], 2, ".", ",")?></td>
						<td> <?php echo $additionLoanInfo['pay_date'] ?> </td>
						<td>
						</td>
						<td><?php echo $additionLoanInfo['loan_duration']; ?></td>
					</tr>
				
				</tbody>
			</table>
		</div>
	</div>

	<div class="row mt-3">
		<p class="p-4">
			*NB:  The first instalment deduction date may be a month earlier or later than the denoted due date 
			depending on whether the final loan application and supporting documents are given to the Lender before 
			or after the payroll cut-off dates.
		</p>
		<p class="mb-4">
			All loan payments shall be made to Pulamaliboho FS free of any deductions at an address
			or into such bank account, as Pulamaliboho may from time to time direct. I acknowledge and 
			agree that in the event of my loan(s) being scheduled or my taking of an additional loan, the
			terms of the Loan Agreement and this Salary Deduction Authorisation Form shall operate in favour
			of Pulamaliboho FS in respect of the rescheduled loan and additional loan, together with any 
			amendments, as if the Salary Deduction Authorisation Form had been signed and executed by me in 
			respect of the rescheduled or additional loan. 
		</p>
		<p class="mt-5" style="display:block">Signed at ____________________ on this ____________ day of ___________________ 2020 </p>
		<table class="table table-borderless mt-5">
			<tr>
				<th>_____________________________________</th>
				<th>_____________________________________</th>
			</tr>
			<tr>
				<td>Name/Signature of Borrower</td>
				<td>For and on behalf of the Lender</td>
				<td>  
					<div style="width: 250px; height: 200px; border: 1px solid #000; position: relative; bottom: 130px; right: 70px;">
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript">
	  //back
    $("#back").on("click", function(e){
        e.preventDefault();
        window.history.back();
    });

    // printing
    $(document).ready(()=>{
        $('.print').click(()=>{  
            window.print();  
        });
        console.log('the button clicked');
    });
 </script>
</body>
</html>