<?php
	// this is o for testing the file as standalone
	// Need to be removed
require('../config/connect.php');
$getCompanyInfo  = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
$companyInfo = mysqli_fetch_assoc($getCompanyInfo);

//getting id from url
$id = $_GET['id'];
$borrowers = mysqli_query($link, "SELECT * FROM borrowers WHERE id='$id'") or die (mysqli_error($link));
$borrowersInfo = mysqli_fetch_assoc($borrowers);

// banking details
$loanid = $_GET['loanId'];
$contract = $_GET['contract'];
$bankinfo = mysqli_query($link, "SELECT transaction FROM loan_disbursements WHERE loan='$loanid'")  or die(mysqli_error($link));
$bankdetails = mysqli_fetch_array($bankinfo);
$bankDetails = json_decode($bankdetails['transaction'], true);

$next = mysqli_query($link, "SELECT * FROM `next_of_kin_details` WHERE borrower='$id'") OR die(mysqli_error($link));
$nextofkin = mysqli_fetch_assoc($next);

$sqlresult = mysqli_query($link, "SELECT * FROM borrowers_salaries WHERE borrower='$id'") OR die(mysqli_error($link));
$borrowmoney = mysqli_fetch_assoc($sqlresult);
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- custom css that filters the button for print -->
<link rel="stylesheet" href="custom/style.css">

<!-- font awesome -->
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php if(mysqli_num_rows( $getCompanyInfo ) > 0) {?>
	<div class="container mt-4">
		<div class="row">
			<div class="col-sm-4">
				<h5 class="h5 bold">
				<?php 
					$address = $companyInfo['address'];
					for($i = 0; $i < strlen($address); $i++) {
						echo $address[$i];
						if($address[$i] === ','){
							echo "<br/>";
						}
						
					} ?>
				</h5>
			</div>

			<div class="col-sm-4 ">
				<img src='<?php echo $companyInfo['image']; ?>' style="max-width: 283px; max-height: 93px" alt="logo" class="img-responsive" />
			</div>

			<div class="col-sm-4">
				<table class="table-borderless table-responsive">
					<tr>
						<th>Email </th>
						<td> <?php echo $companyInfo['email'] ?> </td>
					</tr>
					<tr>
						<th><label for="">Website</label></th>
						<td><?php echo $companyInfo['website'] ?> </td>
					</tr>
					<tr>
						<th><label for=""> Contacts</label></th>
						<td> <?php echo $companyInfo['mobile'] ?> </td>
					</tr>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 text-center">
				<label class="label h4" for="">Registration Number:  <span class="mr-5"> <?php echo $companyInfo['registration']?> </span> </label>
			</div>
		</div>
		<hr>
		<?php } ?>
		
	</div>
	<div class="container mt-5">
		 <div class="col-xs-12">
            <div class="text-right">
                    <div class="noprint">
                        <button class="btn btn-warning" id="back"><i class="fa fa-mail-reply-all">&nbsp;&nbsp;</i>Back</button>
                        <button class="btn btn-success print" id="print"><i class="fa fa-print">&nbsp;&nbsp;</i>Print</button>
                    </div>
            </div>
        </div>
		<div class="row">
			<div class="col-md-12 text-center"> <h3 class="h3">Loan Application Form</h3> </div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="border">
					<h4 class="h4">Section A – Applicant Details</h4>
				</div>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col-sm-12">
				<p class="text-justify">
					This Loan Application and Agreement dated <u class="font-weight-bold ml-3 mr-3"> <?php echo date("F j, Y");  ?></u>Entered into between Pulamaliboho 
					Financial Services (hereafter referred to as the Lender or 
					Pulamaliboho Financial Services) and <?php echo  $borrowersInfo['title']  ?> <u class="font-weight-bold ml-3 mr-3"> <?php echo $borrowersInfo['fname']." ".$borrowersInfo['lname'] ?> </u> (as Borrower).
				</p>
			</div>
		</div>
	
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered">
					<tr>
						<th>Contract Number</th>
						<td> 
							<?php if (isset($contract)) {
									echo $contract;
							} ?> 
						</td>
						<th>Marital Status</th>
						<td> 
						 <?php if (isset($borrowersInfo['marital'])) {
									echo $borrowersInfo['marital'];
							} ?> 
						</td>
					</tr>

					<tr>
						<th>Title</th>
						<td>
							<?php if (isset($borrowersInfo['title'])) {
									echo $borrowersInfo['title'];
							} ?> 
						</td>
						<th>Marriage in community Property?</th>
						<td> 
							 <?php if (isset($borrowersInfo['marriageType'])) {
									echo $borrowersInfo['marriageType'];
							} ?> 
						</td>
					</tr>

					<tr>
						<th>Surname:</th>
						<td>
							<?php if (isset($borrowersInfo['lname'])) {
									echo $borrowersInfo['lname'];
							} ?> 
						</td>
						<th> Nationality </th>
						<td> 
							 <?php if (isset($borrowersInfo['country'])) {
									echo $borrowersInfo['country'];
							} ?> 
						</td>
					</tr>

					<tr>
						<th>First Name</th>
						<td> 
								<?php if (isset($borrowersInfo['fname'])) {
									echo $borrowersInfo['fname'];
							} ?> 
						</td>
						<th> ID Type</th>
						<td> 
							<?php if (isset($borrowersInfo['id_number'])) {
									echo "National ID";
							} 
							else if(isset($borrowersInfo['passport'])){
                       echo "Passport";
							}?> 
						</td>
					</tr>


					<tr>
						<th>Maiden Name:</th>
						<td> </td>
						<th>
							<?php if (isset($borrowersInfo['id_number'])) {
									echo "National ID";
							} 
							else if(isset($borrowersInfo['passport'])){
                       echo "Passport";
							}?> 
						</th>
						<td>
							<?php if (isset($borrowersInfo['id_number'])) {
									echo $borrowersInfo['id_number'];
							} else if (isset($borrowersInfo['passport'])) {
									echo $borrowersInfo['passport'];
							}
							
							?> 
						</td>
					</tr>
				</table>
				
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-sm-12">
				<div class="border">
					<h4 class="h4">Section B – Applicant Contact Details</h4>
				</div>
			</div>
		</div>


		<div class="row mt-5">
			<div class="col-md-12">
				<table class="table table-bordered">
					<tr>
						<th>Telephone No:</th>
						<td>(H) <span aligh="right">
						<?php if ($borrowersInfo['telephone']) {
										echo $borrowersInfo['telephone'];
								} 
						?>
						</span></td>
						<td>(W)</td>
						<td>(C)
							<span align="right">
								<?php if ($borrowersInfo['phone']) {
										echo $borrowersInfo['phone'];
								} 
								?>
							</span>
						</td>
					</tr>

					<tr>
						<th>Postal Address</th>
						<td>
							<?php if ($borrowersInfo['postal']) {
									echo $borrowersInfo['postal'];
							}
							?>
						</td>
						<td></td>
						<td></td>
					</tr>

					<tr>
						<th>Physical Address:</th>
						<td>
							<?php if ($borrowersInfo['addrs1']) {
									echo $borrowersInfo['addrs1'];
							}
							?>
						</td>
						<td>
							<?php if ($borrowersInfo['addrs2']) {
									echo $borrowersInfo['addrs2'];
							}
							?>
						</td>
						<td> </td>
					</tr>

					<tr>
						<th>Home Village:</th>
						<td> </td>
						<th> Home </th>
						<td> </td>
					</tr>


					<tr>
						<td> </td>
						<td> </td>
						<th> Email Add: </th>
						<td>
							<?php if ($borrowersInfo['email']) {
									echo $borrowersInfo['email'];
							}
							?>
						 </td>
					</tr>
				</table>
				
			</div>

			
		</div>

		<div class="row mt-5">
			<div class="col-sm-12">
				<div class="border">
					<h4 class="h4">Section C – Applicant Employment Details</h4>
				</div>
			</div>
		</div>


		<div class="row mt-5">
			<div class="col-md-12">
				<table class="table table-bordered">
					<tbody>
					<?php     
							$employer = mysqli_query($link, "SELECT * FROM employer_details WHERE id='$id'") or die(mysqli_error($link));
							$employerInfo = mysqli_fetch_assoc($employer);
							
					?>
					<tr>
						<th>Employee No:</th>
						<td>
							<?php if(isset($employerInfo['employee_no'])){
								echo $employerInfo['employee_no'];
							}
							?>
						</td>
						<th>Employee Name</th>
						<td>
							<?php if(isset($employerInfo['employer_name'])){
								echo $employerInfo['employer_name'];
							} ?>
						</td>
					</tr>

					<tr>
						<th> Department</th>
						<td> 
							<?php if(isset($employerInfo['department'])){
								echo $employerInfo['department'];
							} ?>
						</td>
						<th>Employee Code</th>
						<td>
							<?php if(isset($employerInfo['employer_code'])){
								echo $employerInfo['employer_code'];
							} ?>
						</td>
					</tr>

					<tr>
						<th>Designation</th>
						<td>
							<?php if(isset($employerInfo['designation'])){
								echo $employerInfo['designation'];
							} ?>
						
						</td>
						<th> Engagement Date</th>
						<td>
							<?php if(isset($employerInfo['engagement_date'])){
								echo $employerInfo['engagement_date'];
							} ?>
						</td>
					</tr>

					<tr>
						<th> Employment Status</th>
						<td>
							<?php if(isset($employerInfo['employment_status'])){
								echo $employerInfo['employment_status'];
							} ?>
						</td>
						<th> Date of Retirement/Contract End Date </th>
						<td>
							<?php if(isset($employerInfo['retirement'])){
								echo $employerInfo['retirement'];
							} ?>
						</td>
					</tr>


					<tr>
						<th>Employer Contact:</th>
						<td>
							<?php if(isset($employerInfo['employer_contact'])){
								echo $employerInfo['employer_contact'];
							} ?>
						</td>
						<th> Telephone No: </th>
						<td>
							<?php if(isset($employerInfo['telephone'])){
								echo $employerInfo['telephone'];
							} ?>
						</td>
					</tr>
						<th>Employer Designation</th>
						<td>
							<?php if(isset($employerInfo['employer_designation'])){
								echo $employerInfo['employer_designation'];
							} ?>
						</td>
						<td> </td>
						<td> </td>
					</tr>
				</tbody>
				</table>
				
			</div>
		</div>

		<div class="row mt-5">
				<div class="col-md-12 ">
					<p class="text-justify">
					Do you intend on taking study leave/maternity leave or any other extended leave which may result in defaulting the monthly repayments during the term of the loan? (Yes/No). If Yes, please state the period of the intended leave.
					</p>
				</div>
			</div>

		</div>
	</div>

	

	<div class="container">
	<div class="row mt-5">
			<div class="col-sm-12">
				<div class="border">
					<h4 class="h4">Section D – Application Banking Details</h4>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<th>Bank Name</th>
							<td>
								<?php  
									if($bankDetails['bankName']){
										echo $bankDetails['bankName'];
									}
								?>
							</td>
							<th>Account Name</th>
							<td>
								<?php
									if(isset($bankDetails['accountName'])){
											echo $bankDetails['accountName'];
										}
								?>
							</td>
						</tr>

						<tr>
							<th>Branch Name</th>
							<td>
								<?php
									if(isset($bankDetails['branchName'])){
											echo $bankDetails['branchName'];
										}
								?>
							</td>
							<th> Account Number </th>
							<td>
							<?php
									if(isset($bankDetails['accountNumber'])){
											echo $bankDetails['accountNumber'];
										}
								?>
							
							</td>
						</tr>

						<tr>
							<th>Branch Code</th>
							<td>
								<?php
									if(isset($bankDetails['branchCode'])){
											echo $bankDetails['branchCode'];
										}
								?>
							
							</td>
							<th></th>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		
		<div class="row mt-5">
			<div class="col-sm-12">
				<div class="border">
					<h4 class="h4">Section E – Applicant Next of Kin</h4>
				</div>
			</div>
		</div>


		<div class="row mt-3">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Name</th>
						<th>Relationship</th>
						<th>Physcal Address</th>
						<th>Email</th>
						<th>Mobile Number</th>
						<th>Employer</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<?php
                                if (isset($nextofkin['names'])) {
                                    echo $nextofkin['names'];
                                }
							?>
						</td>
						<td>
							<?php
                                if (isset($nextofkin['names'])) {
                                    echo $nextofkin['names'];
                                }
							?>
						</td>
						<td>
							<?php
                                if (isset($nextofkin['address'])) {
                                    echo $nextofkin['address'];
                                }
							?>
						</td>
						<td>
							<?php
                                if (isset($nextofkin['email'])) {
                                    echo $nextofkin['email'];
                                }
							?>
						</td>
						<td>
							<?php
								if (isset($nextofkin['contact'])) {
									echo $nextofkin['contact'];
								}
							?>
						</td>
						<td>
							<?php
								if (isset($nextofkin['employer'])) {
									echo $nextofkin['employer'];
								}
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="row mt-5">
			<div class="col-sm-12">
				<div class="border">
					<h4 class="h4"> Section F - Applicant Loan Details</h4>
				</div>
			</div>
		</div>

		<div class="row ">
			<div class="col-md-12">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td> What do you intend to use the Loan for?  </td>
							<td> 
								<?php
									$loaninfo = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM loan_info WHERE borrower='$id' and id='$loanid'")) or die(mysqli_error($link));
									$strJsonFileContents = file_get_contents('include/packages.json');
									$arrayOfTypes = json_decode($strJsonFileContents, true);
									$loan_remarks = $loaninfo['reason'];
									foreach ($arrayOfTypes['loanReasonCode'] as $key => $value) {
											if ($loan_remarks == $key) {
												$loan_remarks = $value;
											}
									}

									if(isset($loan_remarks))
										echo $loan_remarks;
								?>	
							</td>
						</tr>

						<tr>
							<td> Loan amount applied for? </td>
							<td style="text-align: right">
								<?php if(isset($loaninfo['amount'])){ 
									echo '<b>'.number_format($loaninfo['amount'],2,'.',', ').'</b>';
								}?> 
							</td>
						</tr>

						<tr>
							<td> How much can you afford to pay per month? </td>
							<td style="text-align: right">
								<?php if(isset($borrowmoney['max_available'])){
									echo '<b>'.number_format($borrowmoney['max_available'],2,'.',', ').'</b>';
								}?> 
							</td>
						</tr>
						<tr>
							<td> Period of the loan repayment </td>
							<td style="text-align: right">
							<?php
								if (isset($loaninfo['loan_duration'])) {
									echo '<b>'.$loaninfo['loan_duration'].'</b>';
								}
								?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-sm-12">
				<div class="border">
					<h4 class="h4">Section G– Affordability Criteria</h4>
				</div>
			</div>
		</div>
		
		<div class="row ">
			<div class="col-md-12">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td> Income Details (For official Use Only)  </td>
							<td> Amount (LSL)  </td>
						</tr>

						<tr>
							<td> <b>Basic Pay</b> </td>
							<td style="text-align: right">
								<?php
									if (isset($borrowmoney['basic_pay'])) {
											echo number_format($borrowmoney['basic_pay'],2,'.',', ');
									} 
								?>
							</td>
						</tr>

						<tr>
							<td> Additional Fixed Allowance </td>
							<td style="text-align: right">
								<?php
									if (isset($borrowmoney['additional_fixed_allowance'])) {
											echo number_format($borrowmoney['additional_fixed_allowance'],2,'.',', ');
									} 
								?>
							</td>
						</tr>
						<tr>
							<td><b>Gross Pay</b></td>
							<td style="text-align: right">
								<?php
									if (isset($borrowmoney['gross_pay'])) {
											echo number_format($borrowmoney['gross_pay'],2,'.',', ');
									} ?>
							</td>
						</tr>

						<tr>
							<td> Statutory and Non-Statutory Deductions </td>
							<td style="text-align: right">
								<?php
								if (isset($borrowmoney['statutory_deductions'])) {
                                    echo number_format($borrowmoney['statutory_deductions'],2,'.',', ');
								} ?>
							</td>
						</tr>

						<tr>
							<td> Loan Instalments to be consolidated </td>
							<td style="text-align: right">
								<?php
									if (isset($borrowmoney['loan_instalments'])) {
											echo number_format($borrowmoney['loan_instalments'],2,'.',', ');
									} 
								?>
							</td>
						</tr>

						<tr>
							<td><b> Net Pay </b></td>
							<td style="text-align: right">
								<?php
									if (isset($borrowmoney['net_pay'])) {
											echo number_format($borrowmoney['net_pay'],2,'.',', ');
									} ?>
							</td>
						</tr>

						<tr>
							<td> Other Bank loan instalments </td>
							<td style="text-align: right">
						<?php
							if (isset($borrowmoney['other_bank_loans'])) {
									echo number_format($borrowmoney['other_bank_loans'],2,'.',', ');
							} ?>
							</td>
						</tr>
                        <?php
                        if (isset($borrowmoney['compuscan'])) {?>
                            <tr>
                                <td> Other Instalments from Compuscan</td>
                                <td style="text-align: right">
                                    <?php
                                    echo number_format($borrowmoney['compuscan'],2,'.',', ');
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
						<tr>
							<td> Monthly Living Expenses </td>
							<td style="text-align: right">
								<?php
									if (isset($borrowmoney['monthly_living_expenses'])) {
											echo number_format($borrowmoney['monthly_living_expenses'],2,'.',', ');
									} 
								?>
							</td>
						</tr>

						<tr>
							<td> Maximum available for Pulamaliboho Loan repayment </td>
							<td style="text-align: right">
								<?php
								if (isset($borrowmoney['max_available'])) {
										echo number_format($borrowmoney['max_available'],2,'.',', ');
								} ?>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-sm-12">
				<div class="border">
					<h4 class="h4">Section H – Debt Write-off</h4>
				</div>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-md-12">
				<p class="text-justify"> I acknowledge that the loan I am applying for, includes debt write-off, at no additional cost to me, that, in the event of my death, the Lender will settle the outstanding balance on the loan. </p>
			</div>
		</div>


		<div class="row mt-5">
			<div class="col-sm-12">
				<div class="border">
					<h4 class="h4">Section I – Marital Status Declaration (Spouses Consent where applicable)</h4>
				</div>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-md-12">
				<p class="text-justify"> I, the undersigned and married to declare that I have obtained my spouse’s consent to approach Pulamaliboho Financial Services for a loan facility and to agree to the terms and conditions of Pulamaliboho Financial Service Loan Agreement and confirm that our joint estate shall be committed and liable for the fulfillment of the terms and conditions of the loan that may be advanced to me by the Lender. </p>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-sm-12">
				<div class="border">
					<h4 class="h4">Section J – Applicant Declaration</h4>
				</div>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-md-12">
				<p class="text-justify"> I, the undersigned declare that the information stated on the Loan Application Form is true and acknowledge that the Lender regards the information as correct in order to consider the application.  I further confirm that the terms and conditions of the loan have been explained to me and that I have read, understood and agree to be bound by the terms and conditions of the loan granted to me. </p>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-md-6">
				<p>_________________________________________</p>
			</div>

			<div class="col-md-6">
				<p>_________________________________________</p>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<p> <b> Borrower’s Name and Signature </b></p>
			</div>

			<div class="col-md-6">
				<p><b> Date  </b></p>
			</div>
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
</body>
