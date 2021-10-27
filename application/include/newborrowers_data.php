<?php
if (isset($_POST['save'])) {
    $fname = mysqli_real_escape_string($link, $_POST['fname']);
    $lname = mysqli_real_escape_string($link, $_POST['lname']);
    $dateofbirth = mysqli_real_escape_string($link, $_POST['date_of_birth']);
    $idNumber = mysqli_real_escape_string($link, $_POST['idNumber']);
    $passport = mysqli_real_escape_string($link, $_POST['passport']);
    $gender = mysqli_real_escape_string($link, $_POST['gender']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    $postal = mysqli_real_escape_string($link, $_POST['postalCode']);
    $physical1 = mysqli_real_escape_string($link, $_POST['physical1']);
    $physical2 = mysqli_real_escape_string($link, $_POST['physical2']);
    $addrs1 = $physical1."\r\n".$physical2;
    $postal1 = mysqli_real_escape_string($link, $_POST['postal1']);
    $postal2 = mysqli_real_escape_string($link, $_POST['postal2']);
    $addrs2 = $postal1."\r\n".$postal2;
    $ownershipType = mysqli_real_escape_string($link, $_POST['ownershipType']);
    $district = mysqli_real_escape_string($link, $_POST['district']);
    $country = mysqli_real_escape_string($link, $_POST['country']);
    $comment = mysqli_real_escape_string($link, $_POST['comment']);
    $account = mysqli_real_escape_string($link, $_POST['account']);
    $membership = mysqli_real_escape_string($link, $_POST['membership']);
    $marital= mysqli_real_escape_string($link, $_POST['marital']);
    $marriageType = mysqli_real_escape_string($link, $_POST['marriageType']);
    $telephone = mysqli_real_escape_string($link, $_POST['telephone']);
    $borrower_title = mysqli_real_escape_string($link, $_POST['borrower_title']);
    $borrower_working_status = mysqli_real_escape_string($link, $_POST['borrower_working_status']);
    $employer = mysqli_real_escape_string($link, $_POST['employer']);
    $borrower_credit_score = "";
    $status = "Pending";

    if ($_FILES["image"]["name"] != "") {
        $target_dir = "../img/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $check = getimagesize($_FILES["image"]["tmp_name"]);

        $id = "Loan" . "=" . rand(10000000, 340000000);

        $sourcepath = $_FILES["image"]["tmp_name"];
        $targetpath = "../img/" . $_FILES["image"]["name"];
        move_uploaded_file($sourcepath, $targetpath);

        $location = "img/" . $_FILES['image']['name'];
    }else{
        $location="";
    }
        $username = $_SESSION['username'];

        $insert = mysqli_query($link, "INSERT INTO borrowers
                                        VALUES(0,'$fname','$lname','$email','$phone','$addrs1','$addrs2','$district','$country','$comment',
                                        '$account','$location',NOW(),'$status','$dateofbirth','$gender','$idNumber','$passport','$borrower_credit_score',
                                        '$borrower_working_status','$employer','$borrower_title','$username','',0,'','0','0','',NOW(), '$postal','$ownershipType','$membership','$marital','$marriageType')")
        or die (mysqli_error($link));
        if (!$insert) {
            echo "<div class='alert alert-warning'>Unable to Insert Borrower Records.....Please try again later</div>";
        } else {
            echo '<meta http-equiv="refresh" content="2;url=listborrowers.php?tid='.$_SESSION['tid'].'">';
            echo '<br>';
            echo'<div class=\'alert alert-success\'>Borrower Successfully Saved!</div>';
        }

}
?>
<div class="box">

    <div class="box-body">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-user"></i> New Borrower</h3>
            </div>

            <div class="box-body">

                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="col-lg-6">

                            <?php
                            $account = '508' . rand(1000000, 10000000);
                            ?>
                            <input name="account" type="hidden" class="form-control" value="<?php echo $account; ?>"
                                   placeholder="Account Number" readonly>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label">Borrower
                                    Photo</label>
                                <div class="col-sm-9">
                                    <input type='file' name="image" onChange="readURL(this);"/>
                                    <img id="blah" src="../avtar/user2.png" alt="Image Here" height="100" width="100"/>
                                </div>
                            </div>
                            <div class="form-group"><label form="" class="col-sm-3 control-label">Title *</label>
                                <div class="col-sm-9"><select class="form-control" name="borrower_title" id="inputBorrowerTitle">
                                        <option value="" selected></option>
                                        <option value="MR">Mister</option>
                                        <option value="MRS">Mrs.</option>
                                        <option value="MISS">Miss</option>
                                        <option value="SIR">Sir</option>
                                        <option value="ADV">Advocate</option>
                                        <option value="DR">Doctor</option>
                                        <option value="PROF">Professor</option>
                                        <option value="PAST">Pastoor</option>
                                        <option value="REV">Reverend</option>
                                        <option value="LORD">Lord</option>
                                        <option value="CAPT">Captain</option>
                                        <option value="LADY">Lady</option>
                                        <option value="COL">Colonel</option>
                                        <option value="DS">Dominee</option>
                                        <option value="JUDGE">Judge </option>
                                        <option value="KAPT">Kaptein </option>
                                        <option value="KOL">Kolonel </option>
                                        <option value="LT">Lieutenant</option>
                                        <option value="MAJ">Major</option>
                                        <option value="ME">MEJ/MEV </option>
                                        <option value="MEJ">Mejufrou</option>
                                        <option value="MEV">Mejufrou </option>
                                        <option value="SERS">Sersant</option>
                                        <option value="SGT">Sergeant</option>
                                    </select></div>
                            </div>
                            <div class="form-group">
                                <label for="membership" class="col-sm-3 control-label">Regular</label>
                                <div class="col-sm-9">
                                    <input type="checkbox" id="membership" name="membership" value="1" checked required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label" >First Name *</label>
                                <div class="col-sm-9">
                                    <input name="fname" type="text" class="form-control" placeholder="First Name"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label" >Last Name *</label>
                                <div class="col-sm-9">
                                    <input name="lname" type="text" class="form-control" placeholder="Last Name"
                                           required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-3 control-label" >Marital Status</label>
                                <div class="col-sm-9"><select class="form-control" name="borrower_marital_status"
                                                              id="inputBorrowerEORS"
                                                              onchange="showMaritalField(this.options[this.selectedIndex].value)"
                                                              required>
                                        <option value="" selected disabled></option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Separated">Separated</option>
                                        <option value="Registered Partnership">Registered Partnership</option>
                                    </select></div>
                            </div>

                            <div id="div_marital_status"></div>

                            <div class="form-group"><label class="col-sm-3 control-label" >Working? *</label>
                                <div class="col-sm-9"><select class="form-control" name="borrower_working_status"
                                                              id="inputBorrowerEORS"
                                                              onchange="showfield(this.options[this.selectedIndex].value)"
                                                              required>
                                        <option value="" selected></option>
                                        <option value="Employee">Employee</option>
                                        <option value="Government Employee">Government Employee</option>
                                        <option value="Private Sector Employee">Private Sector Employee</option>
                                        <option value="Owner">Owner</option>
                                        <option value="Student">Student</option>
                                        <option value="Overseas Worker">Overseas Worker</option>
                                        <option value="Pensioner">Pensioner</option>
                                        <option value="Unemployed">Unemployed</option>
                                    </select></div>
                            </div>

                            <div id="div_employment_status"></div>

                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label">Date of Birth *</label>
                                <div class="col-sm-9">
                                    <input name="date_of_birth" type="date" class="form-control"
                                           placeholder="Date of Birth"
                                           max="<?php echo date("Y-m-d", strtotime('-18 years')); ?>"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label">ID Number *</label>
                                <div class="col-sm-9">
                                    <input name="idNumber"
                                           oninput="maxLengthCheck(this)"
                                           placeholder="ID Number"
                                           type="number"
                                           class="form-control"
                                           maxlength="12"
                                           minlength="12"
                                           min="0"
                                    />

                                    <script>
                                        // This is an old version, for a more recent version look at
                                        // https://jsfiddle.net/DRSDavidSoft/zb4ft1qq/2/
                                        function maxLengthCheck(object) {
                                            if (object.value.length > object.maxLength)
                                                object.value = object.value.slice(0, object.maxLength)
                                        }
                                    </script>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label" >Passport *</label>
                                <div class="col-sm-9">
                                    <input name="passport" type="text" minlength="8" maxlength="8" class="form-control"
                                           placeholder="Passport Number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label" >Gender *</label>
                                <div class="col-sm-9">
                                    <select name="gender" class="form-control" required>
                                        <option value="">Select Gender</option>
                                        <option value="Female">Female</option>
                                        <option value="Male">Male</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email" type="text" class="form-control"
                                           placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label" >Phone Number *</label>
                                <div class="col-sm-9">
                                    <input name="phone" type="number" class="form-control" placeholder="Phone Number"
                                           required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label" >Home Number *</label>
                                <div class="col-sm-9">
                                    <input name="telephone" type="number" class="form-control" placeholder="Telephone/Home Number"
                                           required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label">Physical Line 1 *</label>
                                <div class="col-sm-9">
                                    <input name="physical1" type="text" maxlength="25" class="form-control" placeholder="Physical Address, Line 1" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label">Physical Line 2</label>
                                <div class="col-sm-9">
                                    <input name="physical2" type="text" maxlength="25" class="form-control" placeholder="Physical Address, Line 2" required>
                                </div>
                            </div>
                                <div class="form-group">
                                   <label for="" class="col-sm-3 control-label" >OwnerShip Type </label>
                                      <div class="col-sm-9">
                                                                <select name="ownershipType" class="form-control" >
                                                                    <option value="">Select</option>
                                                                    <option value="O">Owner</option>
                                                                    <option value="T">Tenant</option>
                                                                </select>
                                                            </div>
                                                        </div>

                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label">Postal Line 1 *</label>
                                <div class="col-sm-9">
                                    <input name="postal1" type="text" maxlength="25" class="form-control" placeholder="Postal Address, Line 1" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label">Postal Line 2</label>
                                <div class="col-sm-9">
                                    <input name="postal2" type="text" maxlength="25" class="form-control" placeholder="Postal Address, Line 2" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label">Postal Code *</label>
                                <div class="col-sm-9">
                                    <input name="postalCode" type="text" maxlength="4" class="form-control" placeholder="Postal Code of the Postal address" required>
                                </div>
                            </div>
                            <?php
                            // PHP code to extract IP

                            function getVisIpAddr() {

                                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                                    return $_SERVER['HTTP_CLIENT_IP'];
                                }
                                else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                                    return $_SERVER['HTTP_X_FORWARDED_FOR'];
                                }
                                else {
                                    return $_SERVER['REMOTE_ADDR'];
                                }
                            }

                            // Store the IP address
                            $vis_ip = getVisIPAddr();
                            // Use JSON encoded string and converts
                            // it into a PHP variable
                            $ipdat = @json_decode(file_get_contents(
                                "http://www.geoplugin.net/json.gp?ip=" . $vis_ip));

                            /*echo 'Country Name: ' . $ipdat->geoplugin_countryName . "\n";
                            echo 'Country Code: ' . $ipdat->geoplugin_countryCode . "\n";
                            echo 'City Name: ' . $ipdat->geoplugin_city . "\n";
                            echo 'Continent Name: ' . $ipdat->geoplugin_continentName . "\n";
                            echo 'Latitude: ' . $ipdat->geoplugin_latitude . "\n";
                            echo 'Longitude: ' . $ipdat->geoplugin_longitude . "\n";
                            echo 'Currency Symbol: ' . $ipdat->geoplugin_currencySymbol . "\n";
                            echo 'Currency Code: ' . $ipdat->geoplugin_currencyCode . "\n";
                            echo 'Timezone: ' . $ipdat->geoplugin_timezone;*/
                            ?>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label" >Country *</label>
                                <div class="col-sm-9">
                                    <select name="country" class="form-control" required>
                                        <?php if ($ipdat->geoplugin_countryName==""){ ?>
                                            <option value="">Select a country&hellip;</option>
                                        <?php } else{ ?>
                                            <option value="<?php echo $ipdat->geoplugin_countryName; ?>" selected="selected"><?php echo $ipdat->geoplugin_countryName; ?></option>
                                        <?php } ?>
                                        <option value="AX">&#197;land Islands</option>
                                        <option value="AF">Afghanistan</option>
                                        <option value="AL">Albania</option>
                                        <option value="DZ">Algeria</option>
                                        <option value="AD">Andorra</option>
                                        <option value="AO">Angola</option>
                                        <option value="AI">Anguilla</option>
                                        <option value="AQ">Antarctica</option>
                                        <option value="AG">Antigua and Barbuda</option>
                                        <option value="AR">Argentina</option>
                                        <option value="AM">Armenia</option>
                                        <option value="AW">Aruba</option>
                                        <option value="AU">Australia</option>
                                        <option value="AT">Austria</option>
                                        <option value="AZ">Azerbaijan</option>
                                        <option value="BS">Bahamas</option>
                                        <option value="BH">Bahrain</option>
                                        <option value="BD">Bangladesh</option>
                                        <option value="BB">Barbados</option>
                                        <option value="BY">Belarus</option>
                                        <option value="PW">Belau</option>
                                        <option value="BE">Belgium</option>
                                        <option value="BZ">Belize</option>
                                        <option value="BJ">Benin</option>
                                        <option value="BM">Bermuda</option>
                                        <option value="BT">Bhutan</option>
                                        <option value="BO">Bolivia</option>
                                        <option value="BQ">Bonaire, Saint Eustatius and Saba</option>
                                        <option value="BA">Bosnia and Herzegovina</option>
                                        <option value="BW">Botswana</option>
                                        <option value="BV">Bouvet Island</option>
                                        <option value="BR">Brazil</option>
                                        <option value="IO">British Indian Ocean Territory</option>
                                        <option value="VG">British Virgin Islands</option>
                                        <option value="BN">Brunei</option>
                                        <option value="BG">Bulgaria</option>
                                        <option value="BF">Burkina Faso</option>
                                        <option value="BI">Burundi</option>
                                        <option value="KH">Cambodia</option>
                                        <option value="CM">Cameroon</option>
                                        <option value="CA">Canada</option>
                                        <option value="CV">Cape Verde</option>
                                        <option value="KY">Cayman Islands</option>
                                        <option value="CF">Central African Republic</option>
                                        <option value="TD">Chad</option>
                                        <option value="CL">Chile</option>
                                        <option value="CN">China</option>
                                        <option value="CX">Christmas Island</option>
                                        <option value="CC">Cocos (Keeling) Islands</option>
                                        <option value="CO">Colombia</option>
                                        <option value="KM">Comoros</option>
                                        <option value="CG">Congo (Brazzaville)</option>
                                        <option value="CD">Congo (Kinshasa)</option>
                                        <option value="CK">Cook Islands</option>
                                        <option value="CR">Costa Rica</option>
                                        <option value="HR">Croatia</option>
                                        <option value="CU">Cuba</option>
                                        <option value="CW">Cura&Ccedil;ao</option>
                                        <option value="CY">Cyprus</option>
                                        <option value="CZ">Czech Republic</option>
                                        <option value="DK">Denmark</option>
                                        <option value="DJ">Djibouti</option>
                                        <option value="DM">Dominica</option>
                                        <option value="DO">Dominican Republic</option>
                                        <option value="EC">Ecuador</option>
                                        <option value="EG">Egypt</option>
                                        <option value="SV">El Salvador</option>
                                        <option value="GQ">Equatorial Guinea</option>
                                        <option value="ER">Eritrea</option>
                                        <option value="EE">Estonia</option>
                                        <option value="ET">Ethiopia</option>
                                        <option value="FK">Falkland Islands</option>
                                        <option value="FO">Faroe Islands</option>
                                        <option value="FJ">Fiji</option>
                                        <option value="FI">Finland</option>
                                        <option value="FR">France</option>
                                        <option value="GF">French Guiana</option>
                                        <option value="PF">French Polynesia</option>
                                        <option value="TF">French Southern Territories</option>
                                        <option value="GA">Gabon</option>
                                        <option value="GM">Gambia</option>
                                        <option value="GE">Georgia</option>
                                        <option value="DE">Germany</option>
                                        <option value="GH">Ghana</option>
                                        <option value="GI">Gibraltar</option>
                                        <option value="GR">Greece</option>
                                        <option value="GL">Greenland</option>
                                        <option value="GD">Grenada</option>
                                        <option value="GP">Guadeloupe</option>
                                        <option value="GT">Guatemala</option>
                                        <option value="GG">Guernsey</option>
                                        <option value="GN">Guinea</option>
                                        <option value="GW">Guinea-Bissau</option>
                                        <option value="GY">Guyana</option>
                                        <option value="HT">Haiti</option>
                                        <option value="HM">Heard Island and McDonald Islands</option>
                                        <option value="HN">Honduras</option>
                                        <option value="HK">Hong Kong</option>
                                        <option value="HU">Hungary</option>
                                        <option value="IS">Iceland</option>
                                        <option value="IN">India</option>
                                        <option value="ID">Indonesia</option>
                                        <option value="IR">Iran</option>
                                        <option value="IQ">Iraq</option>
                                        <option value="IM">Isle of Man</option>
                                        <option value="IL">Israel</option>
                                        <option value="IT">Italy</option>
                                        <option value="CI">Ivory Coast</option>
                                        <option value="JM">Jamaica</option>
                                        <option value="JP">Japan</option>
                                        <option value="JE">Jersey</option>
                                        <option value="JO">Jordan</option>
                                        <option value="KZ">Kazakhstan</option>
                                        <option value="KE">Kenya</option>
                                        <option value="KI">Kiribati</option>
                                        <option value="KW">Kuwait</option>
                                        <option value="KG">Kyrgyzstan</option>
                                        <option value="LA">Laos</option>
                                        <option value="LV">Latvia</option>
                                        <option value="LB">Lebanon</option>
                                        <option value="LS" selected='selected'>Lesotho</option>
                                        <option value="LR">Liberia</option>
                                        <option value="LY">Libya</option>
                                        <option value="LI">Liechtenstein</option>
                                        <option value="LT">Lithuania</option>
                                        <option value="LU">Luxembourg</option>
                                        <option value="MO">Macao S.A.R., China</option>
                                        <option value="MK">Macedonia</option>
                                        <option value="MG">Madagascar</option>
                                        <option value="MW">Malawi</option>
                                        <option value="MY">Malaysia</option>
                                        <option value="MV">Maldives</option>
                                        <option value="ML">Mali</option>
                                        <option value="MT">Malta</option>
                                        <option value="MH">Marshall Islands</option>
                                        <option value="MQ">Martinique</option>
                                        <option value="MR">Mauritania</option>
                                        <option value="MU">Mauritius</option>
                                        <option value="YT">Mayotte</option>
                                        <option value="MX">Mexico</option>
                                        <option value="FM">Micronesia</option>
                                        <option value="MD">Moldova</option>
                                        <option value="MC">Monaco</option>
                                        <option value="MN">Mongolia</option>
                                        <option value="ME">Montenegro</option>
                                        <option value="MS">Montserrat</option>
                                        <option value="MA">Morocco</option>
                                        <option value="MZ">Mozambique</option>
                                        <option value="MM">Myanmar</option>
                                        <option value="NA">Namibia</option>
                                        <option value="NR">Nauru</option>
                                        <option value="NP">Nepal</option>
                                        <option value="NL">Netherlands</option>
                                        <option value="AN">Netherlands Antilles</option>
                                        <option value="NC">New Caledonia</option>
                                        <option value="NZ">New Zealand</option>
                                        <option value="NI">Nicaragua</option>
                                        <option value="NE">Niger</option>
                                        <option value="Nigeria">Nigeria</option>
                                        <option value="NU">Niue</option>
                                        <option value="NF">Norfolk Island</option>
                                        <option value="KP">North Korea</option>
                                        <option value="NO">Norway</option>
                                        <option value="OM">Oman</option>
                                        <option value="PK">Pakistan</option>
                                        <option value="PS">Palestinian Territory</option>
                                        <option value="PA">Panama</option>
                                        <option value="PG">Papua New Guinea</option>
                                        <option value="PY">Paraguay</option>
                                        <option value="PE">Peru</option>
                                        <option value="PH">Philippines</option>
                                        <option value="PN">Pitcairn</option>
                                        <option value="PL">Poland</option>
                                        <option value="PT">Portugal</option>
                                        <option value="QA">Qatar</option>
                                        <option value="IE">Republic of Ireland</option>
                                        <option value="RE">Reunion</option>
                                        <option value="RO">Romania</option>
                                        <option value="RU">Russia</option>
                                        <option value="RW">Rwanda</option>
                                        <option value="ST">S&atilde;o Tom&eacute; and Pr&iacute;ncipe</option>
                                        <option value="BL">Saint Barth&eacute;lemy</option>
                                        <option value="SH">Saint Helena</option>
                                        <option value="KN">Saint Kitts and Nevis</option>
                                        <option value="LC">Saint Lucia</option>
                                        <option value="SX">Saint Martin (Dutch part)</option>
                                        <option value="MF">Saint Martin (French part)</option>
                                        <option value="PM">Saint Pierre and Miquelon</option>
                                        <option value="VC">Saint Vincent and the Grenadines</option>
                                        <option value="SM">San Marino</option>
                                        <option value="SA">Saudi Arabia</option>
                                        <option value="SN">Senegal</option>
                                        <option value="RS">Serbia</option>
                                        <option value="SC">Seychelles</option>
                                        <option value="SL">Sierra Leone</option>
                                        <option value="SG">Singapore</option>
                                        <option value="SK">Slovakia</option>
                                        <option value="SI">Slovenia</option>
                                        <option value="SB">Solomon Islands</option>
                                        <option value="SO">Somalia</option>
                                        <option value="ZA">South Africa</option>
                                        <option value="GS">South Georgia/Sandwich Islands</option>
                                        <option value="KR">South Korea</option>
                                        <option value="SS">South Sudan</option>
                                        <option value="ES">Spain</option>
                                        <option value="LK">Sri Lanka</option>
                                        <option value="SD">Sudan</option>
                                        <option value="SR">Suriname</option>
                                        <option value="SJ">Svalbard and Jan Mayen</option>
                                        <option value="SZ">Swaziland</option>
                                        <option value="SE">Sweden</option>
                                        <option value="CH">Switzerland</option>
                                        <option value="SY">Syria</option>
                                        <option value="TW">Taiwan</option>
                                        <option value="TJ">Tajikistan</option>
                                        <option value="TZ">Tanzania</option>
                                        <option value="TH">Thailand</option>
                                        <option value="TL">Timor-Leste</option>
                                        <option value="TG">Togo</option>
                                        <option value="TK">Tokelau</option>
                                        <option value="TO">Tonga</option>
                                        <option value="TT">Trinidad and Tobago</option>
                                        <option value="TN">Tunisia</option>
                                        <option value="TR">Turkey</option>
                                        <option value="TM">Turkmenistan</option>
                                        <option value="TC">Turks and Caicos Islands</option>
                                        <option value="TV">Tuvalu</option>
                                        <option value="UG">Uganda</option>
                                        <option value="UA">Ukraine</option>
                                        <option value="AE">United Arab Emirates</option>
                                        <option value="GB">United Kingdom (UK)</option>
                                        <option value="US">United States (US)</option>
                                        <option value="UY">Uruguay</option>
                                        <option value="UZ">Uzbekistan</option>
                                        <option value="VU">Vanuatu</option>
                                        <option value="VA">Vatican</option>
                                        <option value="VE">Venezuela</option>
                                        <option value="VN">Vietnam</option>
                                        <option value="WF">Wallis and Futuna</option>
                                        <option value="EH">Western Sahara</option>
                                        <option value="WS">Western Samoa</option>
                                        <option value="YE">Yemen</option>
                                        <option value="ZM">Zambia</option>
                                        <option value="ZW">Zimbabwe</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label">District *</label>
                                <div class="col-sm-9">
                                    <select name="district" class="form-control" required>
                                        <option value="">Select a district&hellip;</option>
                                        <option value="Butha Buthe">Butha Buthe</option>
                                        <option value="Leribe">Leribe</option>
                                        <option value="Berea">Berea</option>
                                        <option value="Maseru">Maseru</option>
                                        <option value="Mafeteng">Mafeteng</option>
                                        <option value="Mohales Hoek">Mohales Hoek</option>
                                        <option value="Quthing">Quthing</option>
                                        <option value="Qachas Nek">Qachas Nek</option>
                                        <option value="Thaba Tseka">Thaba Tseka</option>
                                        <option value="Mokhotlong">Mokhotlong</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label" >Comment</label>
                                <div class="col-sm-9"><textarea name="comment" class="form-control" rows="2"
                                                                cols="80"></textarea></div>
                            </div>

                        </div>



            </div>
                    <div align="center">
                        <div class="box-footer">
                            <button type="reset" class="btn btn-primary btn-flat"><i
                                        class="fa fa-times">&nbsp;Reset</i>
                            </button>
                            <button name="save" type="submit" class="btn btn-success btn-flat"><i
                                        class="fa fa-save">&nbsp;Save</i>
                            </button>

                        </div>
                    </div>

                </form>
            </div>

    </div>
</div>
</div>
<script type="text/javascript">
    function showfield(name) {
        if (name !== 'Pensioner' && name !== 'Unemployed')
            document.getElementById('div_employment_status').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-3 control-label"></label>\n' +
                '                            <div class="col-sm-9"><input type="text" placeholder="Employer Name / Institution" class="form-control" name="employer" id="employer" required></div></div>';
        else document.getElementById('div_employment_status').innerHTML = '';
    }
</script>
<script>
    function showMaritalField(name) {
        if (name == 'Married')
            document.getElementById('div_marital_status').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-3 control-label"></label>\n' +
                '                            <div class="col-sm-9"><select  class="form-control" name="marriageType" required><option value="" disabled>Select</option><option>Community of property</option><option>Antenuptial Contract (ANC) </option></select></div></div>';
        else document.getElementById('div_marital_status').innerHTML = '';
    }
</script>