<?php
   $getCompanyInfo  = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
   $companyInfo = mysqli_fetch_assoc($getCompanyInfo);
?>

<?php if(mysqli_num_rows( $getCompanyInfo ) > 0) { ?>
	<div class="row">
        <table width="100%">
            <tr>
                <td>
		<div class="col-sm-12">
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
                </td>
                <td style="text-align: right">
		<div class="col-sm-12" align="center">
		<img src='<?php echo $companyInfo['image']; ?>'  style="width: 60%; height: 60%;" alt="logo" class="img-responsive" />
		</div>
                </td>
                <td>
		<div class="col-sm-12">
			<table class="table-borderless table-responsive">
				<tr>
					<th>Email</th>
					<td> <?php echo $companyInfo['email'] ?> </td>
				</tr>
				<tr>
					<th><label for="">Web</label></th>
					<td><?php echo $companyInfo['website'] ?> </td>
				</tr>
				<tr>
					<th>Contacts </th>
					<td> <?php echo $companyInfo['mobile'] ?> </td>
				</tr>
			</table>
		</div>
                </td>
	</div>
    </tr>
    </table>
	<div class="row">
			<div class="col-sm-12 text-center">
				<label class="label h4" for="">Registration Number:  <span class="mr-5"> <?php echo $companyInfo['registration']?> </span> </label>
			</div>
		</div>
		<hr>
		<?php } ?>
		<br>