<div class="box">

    <div class="box-body">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-user"></i> New Employee</h3>
            </div>
            <div class="box-body">


                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_emp.php">
                    <div class="box-body">

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Your Image *</label>
                            <div class="col-sm-10">
                                <input type='file' name="image" onChange="readURL(this);">
                                <img id="blah" class="img-circle" src="../avtar/user.png" alt="Image Here" height="120"
                                     width="120"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Full Name *</label>
                            <div class="col-sm-10">
                                <input name="name" type="text" class="form-control" placeholder="First Name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Role *</label>
                            <div class="col-sm-10">
                                <select name="role" class="form-control" required>
                                    <option value="">Select Role</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Credit Controller">Credit Controller</option>
                                    <option value="Teller">Teller</option>
                                    <option value="Front Desk Officer">Front Desk Officer</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Branch *</label>
                            <div class="col-sm-10">
                                <select name="branch" class="form-control" required>
                                    <option value="">Select a branch&hellip;</option>
                                    <?php
                                        $getBranches = mysqli_query($link,"select * from branches where status='Active'");
                                        while($branch=mysqli_fetch_assoc($getBranches)){
                                    ?>
                                    <option value="<?php echo $branch['code'] ?>"><?php echo $branch['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Date of Birth *</label>
                            <div class="col-sm-10">
                                <input name="dateofbirth" type="date" class="form-control"
                                       placeholder="Date of Birth"
                                       max="<?php echo date("Y-m-d", strtotime('-18 years')); ?>"
                                       required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Gender *</label>
                            <div class="col-sm-10">
                                <select name="gender" class="form-control" rows="4" cols="80" required>
                                    <option value="">Select Gender</option>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Email *</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" type="email" class="form-control" placeholder="Email"
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Mobile Number</label>
                            <div class="col-sm-10">
                                <input name="phone" type="number"  oninput="maxLengthCheck(this)" maxlength="8" class="form-control" placeholder="Mobile Number"
                                       required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label" >ID Number</label>
                            <div class="col-sm-10">
                                <input name="idNumber" type="number" oninput="maxLengthCheck(this)" maxlength="12" class="form-control" placeholder="ID Number">
                            </div>
                        </div>
                        <script>
                            // This is an old version, for a more recent version look at
                            // https://jsfiddle.net/DRSDavidSoft/zb4ft1qq/2/
                            function maxLengthCheck(object) {
                                if (object.value.length > object.maxLength)
                                    object.value = object.value.slice(0, object.maxLength)
                            }
                        </script>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label" >Passport</label>
                            <div class="col-sm-10">
                                <input name="passport" type="text" maxlength="8" class="form-control"
                                       placeholder="Passport Number">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Physical Address *</label>
                            <div class="col-sm-10">
                                <textarea name="addr1" class="form-control" rows="2" cols="80" required></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Postal Address*</label>
                            <div class="col-sm-10">
                                <textarea name="addr2" class="form-control" rows="2" cols="80" required></textarea>
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
                            <label for="" class="col-sm-2 control-label">Country*</label>
                            <div class="col-sm-10">
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
                            <label for="" class="col-sm-2 control-label">District *</label>
                            <div class="col-sm-10">
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
                            <label for="" class="col-sm-2 control-label">Comment</label>
                            <div class="col-sm-10">
                                <textarea name="comment" class="form-control" rows="2" cols="80"></textarea>
                            </div>
                        </div>

                        <hr>
                        <div class="panel-body bg-gray text-bold">&nbsp;EMPLOYEE LOGIN INFORMATION</div>
                        <hr>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Username*</label>
                            <div class="col-sm-10">
                                <input name="username" type="text" class="form-control" placeholder="Username" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input name="password" type="password" id="userPassword" class="form-control" placeholder="Password"
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Confirm Password</label>
                            <div class="col-sm-10">
                                <input name="cpassword" type="password" id="userRepeatPassword" class="form-control"
                                       placeholder="Confirm Password" required>
                            </div>
                        </div>

                    </div>

                    <div align="center">
                        <div class="box-footer">
                            <button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i>
                            </button>
                            <button name="emp" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save">&nbsp;Save</i>
                            </button>

                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<script>
    var pass1 = document.getElementById('userPassword');
    var pass2 = document.getElementById('userRepeatPassword');
    function validatePassword(){
        if (pass2.value == pass1.value) {
            pass2.setCustomValidity('');
        } else {
            pass2.setCustomValidity('Both passwords do not match');
        }
    }
    pass1.addEventListener('change', validatePassword);
    pass2.addEventListener('keyup', validatePassword);
</script>