<?php
	// this is o for testing the file as standalone
	// Need to be removed
	require_once('../config/connect.php');
	$getCompanyInfo  = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
	$companyInfo = mysqli_fetch_assoc($getCompanyInfo);

//getting id from url
$id = $_GET['id'];
$selectLoan = mysqli_query($link, "SELECT * FROM borrowers WHERE id='$id'") or die (mysqli_error($link));
$additionLoanInfo = mysqli_fetch_assoc($selectLoan);

// banking details
	$loanid = $_GET['loanId'];
  $bankdetails = mysqli_fetch_array(mysqli_query($link, "SELECT transaction FROM loan_disbursements WHERE loan='$loanid'"));
    if ($bankdetails > 0) {
        $bankDetails = json_decode($bankdetails['transaction'], true);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

	<!-- font awesome -->
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

	<!-- custom style for printing -->
	<link rel="stylesheet" href="custom/style.css">
	<link href="img/<?php echo $row['image']; ?>" rel="icon" type="dist/img">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
	<?php if(mysqli_num_rows( $getCompanyInfo ) > 0) { ?>
	<div class="container mt-4">
		<?php include "printingHeader.php"; ?>
	</div>

	<?php } ?>

	<div class="container">
		 <div class="col-xs-12">
            <div class="text-right">
                    <div class="noprint">
                        <button class="btn btn-warning" id="back"><i class="fa fa-mail-reply-all">&nbsp;&nbsp;</i>Back</button>
                        <button class="btn btn-success print" id="print"><i class="fa fa-print">&nbsp;&nbsp;</i>Print</button>
                    </div>
            </div>
        </div>
		<div class="row">
			<div class="col-sm-6">
				<p class="h4 "> Section A.  Loan Agreement </p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<p class="p text-justify mt-5"> I, the Borrower, shall, subject to giving to the Lender 30 days’ notice or an early settlement penalty fee of an amount not exceeding three months’ interest on the outstanding loan balance in lieu of notice, be entitled at any time to make payment to the Lender of the full amount of Capital, interest and/or any other sums or fees which may then be owned by the Borrower to the Lender. I further understand that the required settlement notice period and settlement fee is subject to change from time to time and that the prevailing notice period and fee may be requested from the Lender any time prior to settlement.  I further understand that I may request and obtain a statement of my account at any time from the Lender’s Main branch and that electronic statements will be emailed in lieu of printed statements if a valid email address is provided to the Lender. Prior to disbursement, I can, in writing to the Lender, cancel the loan application and agreement without prejudice; similarly, the Lender reserves the right to reject my loan application or rescind the loan agreement based on its internal credit policy and not disburse the loan amounts contained herein. </p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
			<p class="h4" > Section B – Third Party Settlement Authorisation </p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<p class="p text-justify mt-5">Upon approval of the loan amount, as per the Schedule, and if I request the Lender to settle any of my existing debts with another third party, I hereby irrevocably and unconditionally authorize the Lender or its relevant nominee to contact the following thirty party and obtain an independent settlement quotation and/or confirmation of my total outstanding balance and liability and then pay:	</p>
				<p class="p text-justify">I further confirm that I agree, without any exception or limitation, with the accuracy of the balances and liability on the third party settlement quotes which shall be settled by the Lender and incorporated into the total loan amount which I shall be liable for in accordance with the terms and conditions or my loan agreement with the Lender.
					The remaining balance of the loan amount will be paid electronically into my bank account as per the Master Loan Agreement. I confirm that this authorization is purely to assist me and I hereby absolve the Lender from any liability pertaining hereto. I further understand that the money will be released by the Lender to my bank account and/or any creditors as per Section B above until such time as my personal loan is approved.  I am aware, understand and agree that all the terms and conditions contained in the Master Loan Agreement between myself and the Lender and agree that all conditions on the loan agreement apply:</p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
			<p class="h4" > Section C – Salary Deduction and Debit Order Instruction </p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<p class="p text-justify mt-5">
				I <?php echo  $additionLoanInfo['title']  ?> <u class="font-weight-bold ml-3 mr-3"> <?php echo $additionLoanInfo['fname']." ".$additionLoanInfo['lname'] ?> </u> (ID Type: 
				<?php 
					if(isset($additionLoanInfo['id_number']))
						echo "ID Number";
					else if(isset($additionLoanInfo['passport']))
						echo "Passport";
				?>
				&
				<?php 
					if(isset($additionLoanInfo['id_number']))
						echo "National ID";
					else if(isset($additionLoanInfo['passport']))
						echo "Passport Number";
				?>
				<u class="font-weight-bold ml-3 mr-3"> 
				<?php 
					if(isset($additionLoanInfo['id_number']))
						echo $additionLoanInfo['id_number'];
					else if(isset($additionLoanInfo['passport']))
						echo $additionLoanInfo['passport'];
				?>
				</u>
				) the undersigned do acknowledge that I am truly and lawfully 
				indebted to the Lender (Pulamaliboho Financial Services) in respect of a loan or loans granted 
				to me by the Lender.  I hereby authorize my employer to deduct from my salary, or any other 
				source of remuneration by way of a deduction code or otherwise, on salary date each month, 
				or any other date in respect of other remuneration, such sums as the Lender may from time to 
				time notify my employer, which notification shall be either in writing or in such electronic or
				magnetic media as the Lender and my employer agree from time to time, in repayment of any and 
				all loans granted by the Lender to me together with interest and fees as accrued thereon.  I 
				acknowledge and agree that no deduction may be withheld or postponed for whatsoever reason.
				</p>
			</div>
		</div>
		

		<div class="row">
			<div class="col-md-6">
				<table class="table">
					<tr>
						<th>Account Name:</th>
						<td><?php 
							if(isset($bankDetails['accountName']))
								echo $bankDetails['accountName'] ?>
						</td>
					</tr>

					<tr>
						<th>Bank Name:</th>
						<td>
							<?php if(isset($bankDetails['bankName']))
								echo $bankDetails['bankName'] ?>
						</td>
					</tr>

					<tr>
						<th>Bank Account Number:</th>
						<td><?php 
							if(isset($bankDetails['accountNumber'])) 
								echo $bankDetails['accountNumber'] ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-6">
				<table class="table">
					<tr>
						<th>Account Type:</th>
						<td>
							<?php 
								if(isset($bankDetails['accountType']))
									echo $bankDetails['accountType'] ?>
						</td>
					</tr>

					<tr>
						<th>Branch Name:</th>
						<td>
							<?php 
								if(isset($bankDetails['branchName']))
									echo $bankDetails['branchName'] 
							?>
						</td>
					</tr>

					<tr>
						<th>Branch Code:</th>
						<td>
							<?php if(isset($bankDetails['branchCode']))
									echo $bankDetails['branchCode'] ?>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<p class="p text-justify mt-5">
					I hereby authorize the Lender, its holding company, or any of their designated agent 
					to draw against my account with the above mentioned bank (or any other bank or branch 
					to which I may transfer my account) the amount necessary for payment of the monthly/
					quarterly/annual commitment due in respect of loan repayments/deductions or any future 
					indebtedness I may incur with the Lender. All such withdrawals from my bank account by 
					the Lender shall be treated as though they had been authorized/signed by me personally. 
					Should my loan account fall in arrears or any periodical instalment be returned by my bank 
					on the basis of insufficient funds in my account, then, I hereby authorized the Lender to 
					increase my monthly instalment to recover the arrears within the contract period or to collect 
					this amount in partial amounts which amounts may be deducted from my account at any time.  
					I agree that the Lender shall be entitled to change the date that deductions are made, to coincide 
					with my salary payment date and I need not be notified of such.  I understand that the withdrawals 
					hereby authorized will be processed by computer through any electronic means and I also understand 
					that details of each withdrawal will be printed on my bank statement or on an accompanying voucher. 
					I agree to pay any bank charges relating to this debit order instruction.
				</p>
			</div>
		</div>

		
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<p class="text-justify mt-5">
					I agree to pay any and all bank charges that relate to this debt order including, without derogating from the generally hereof, all lodgement, failure and other costs that the Lender may incur. I understand that I shall not be entitled to any refund of amounts which may have been withdrawn by the Lender while the authority was in force if such amounts were legally owing to the Lender.  Issuance of this instruction by the Lender shall be regarded as receipt thereof by my bank (whichever it is or will be).  I hereby authorize the Lender to use the debit order instruction as a stop order when so required.  I hereby confirm that I also understand that the Lender may apply set-off in this matter and any other current and future matter where I am indebted to the Lender, including debts ceded to the Lender.  This set-off shall include but is not limited to actions where certain monies in excess of the original amount of the debt have been collected by the Lender but I am not in a position to claim a refund for such monies if I am still indebted to the Lender.  Furthermore, I agree to let the Lender know when I change/move my Bank account.
				</p>
				<p>Signed:</p>
			</div>
		</div>

		<table class="table table-borderless mt-5">
				<tr>
					<th>_______________________________________</th>
					<th>_______________________________________</th>
				</tr>
				<tr>
					<td>Borrower’s Name and Signature</td>
					<td>Date </td>
				</tr>
				
				<tr>
				<th class="mt-2"><p>For the Lender:</p></th>
				</tr>

				<tr>
					<th>____________________________________</th>
					<th>____________________________________</th>
					
				</tr>
				<tr>
					<td>Authorised Officer’s Name and Signature</td>
					<td>Date</td>
				</tr>

			</table>
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