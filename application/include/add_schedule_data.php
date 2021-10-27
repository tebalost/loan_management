<div class="box">
    <?php
    //Get all Reports
    $reports = mysqli_query($link, "select * from report_types where status=1 and uri!=''");

    $api = $baseAPI . "scheduler-api/register";

    $Code = mysqli_fetch_assoc(mysqli_query($link, "select * from systemset"));
    $companyCode = $Code['srn'];
    $tradingName = $Code['name'];
    $schedule = [];

    function postToAPI($dataString, $api)
    {
        $ch = curl_init($api);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($dataString))
        );
        return json_decode(curl_exec($ch), true);
    }

    if (isset($_POST['saveSchedule'])) {
        //$schedule=$_POST['schedule'];
        $consumerNote = $_POST['consumerNote'];

        $schedule['companyCode'] = $companyCode;
        $schedule['frequency'] = $_POST['schedule']['frequency'];

        if ($_POST['schedule']['frequency'] === "D") {
            $day = date('d');
        } else {
            $day = $_POST['schedule']['frequencyDay'];
        }
      /*  if(isset($_POST['schedule']['frequencyDay'])){
            $day = $_POST['schedule']['frequencyDay'];
        }else{
            $day=$_SESSION['frequencyDay'];
        }*/
        if(isset($_POST['scheduleDtoId'])){
            $schedule['scheduleDtoId'] = $_POST['scheduleDtoId'];
        }

        $schedule['frequencyDay'] = $_POST['schedule']['frequencyDay'];
        $schedule['scheduleTaskCode'] = explode(">", $_POST['schedule']['report'])[0];
        $schedule['consumerEndPointUrl'] = explode(">", $_POST['schedule']['report'])[1];
        $schedule['consumerEndPointUri'] = explode(">", $_POST['schedule']['report'])[2];
        $schedule['startDate'] = substr(explode("T", $_POST['schedule']['startDate'])[0], 0, 7) . "-" . $day;
        $start_time=explode("T", $_POST['schedule']['startDate'])[1] . ":00";
        $start_time_data=date('H:i', strtotime($start_time) - 60 * 60 * 2).":00";
        $schedule['startTime'] = $start_time_data;
        $schedule['endDateTime'] = str_replace("T", "@", $_POST['schedule']['endDate']) . ":00";

        if ($_POST['schedule']['status'] == "1") {
            $schedule['status'] = true;
        } else {
            $schedule['status'] = false;
        }
        $schedule['lastRanDate'] = "";
        $schedule['lastRanTime'] = "";
        $schedule['nextRunDate'] = substr(explode("T", $_POST['schedule']['startDate'])[0], 0, 7) . "-" . $day;
        $schedule['consumerNote'] = $_POST['consumerNote'];//to, mobiles, telephones, emailList
        $schedule['consumerNote']['scheduleType'] = explode(">", $_POST['schedule']['report'])[0];




        //$schedule['consumerNote']=array_push($schedule['consumerNote'], $_POST['to'],$_POST['mobiles'],$_POST['telephones'],$_POST['emailList']);
        //$schedule['consumerNote']=array_push($schedule['consumerNote'], $_POST['to']);
        if (isset($_POST['to'])) {
            $schedule['consumerNote']['to'] = $_POST['to'];
        }

        if ($_POST['consumerNote']['messageType'] == "SMS") {
            if ($_POST['mobiles']) {
                $mobiles = true;
            } else {
                $mobiles = false;
            }
            if ($_POST['telephones']) {
                $telephones = true;
            } else {
                $telephones = false;
            }
            if(isset($_POST['phoneNumber'])){
                $schedule['consumerNote']['phoneNumber'] = $_POST['phoneNumber'];
            }
            $schedule['consumerNote']['mobiles'] = $mobiles;
            $schedule['consumerNote']['telephones'] = $telephones;
        } else if ($_POST['consumerNote']['messageType'] == "EMAIL") {
            $emailRecipients=$_POST['consumerNote']['emailRecipients'];
            $schedule['consumerNote']['emailSubject'] = $_POST['subject'];
            $allEmails=explode(',', $emailRecipients);
            $schedule['consumerNote']['emailList'] = $allEmails;

            if($schedule['consumerNote']['scheduleType']!=="INVOICE"){
                $schedule['consumerNote']['sender'] = $tradingName;
            }
        }

        $scheduleData = json_encode(array("scheduleDto" => $schedule));

        $createSchedule = postToAPI($scheduleData, $api);
        $scheduleResponse = $createSchedule;

        $_SESSION['scheduleResponse'] = $scheduleResponse;
        $response = $_SESSION['scheduleResponse']['statusMessage'];
    }
    $startDate = date('Y-m-d') . "T" . date('H:i');
    $endDate =  date('Y-m-d') . "T" . date('H:i');
    $scheduleTaskCode = $nameOfReport = $frequencyInterval = $status = $type = "";
    if (isset($_GET['action'])) {

        $id = $_GET['id'];
        $apiUrl = $baseAPI . "scheduler-api/schedule/$id";

        function getAPICall($apiUrl)
        {
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $_SESSION['authentication']);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json')
            );
            return json_decode(curl_exec($ch), true);
        }

        $scheduleResponse = getAPICall($apiUrl);

        $startDate = $scheduleResponse['scheduleDto']['startDate'] . "T" . $scheduleResponse['scheduleDto']['startTime'];
        $endDate = str_replace("@","T",$scheduleResponse['scheduleDto']['endDateTime']);
        $scheduleTaskCode = $scheduleResponse['scheduleDto']['scheduleTaskCode'];
        $frequencyInterval = $scheduleResponse['scheduleDto']['frequency'];
        $status = $scheduleResponse['scheduleDto']['status'];
        $nextRunDate=$scheduleResponse['scheduleDto']['nextRunDate'];
        $_SESSION['frequencyDay']=$scheduleResponse['scheduleDto']['frequencyDay'];
        $reportName=mysqli_fetch_assoc(mysqli_query($link,"select * from report_types where type='$scheduleTaskCode'"));
        $nameOfReport=$reportName['name'];

    }

    ?>

    <div class="box-body">

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-clock-o"></i>

                    New Schedule Details</h3>
            </div>
            <div class="box-body">
                <div class="col-sm-7">
                    <form class="ReportMailingJob form-horizontal" action="" method="post">
                    <?php if($_GET['action']=="edit"){  ?>
                    <input type="hidden" name="scheduleDtoId" value="<?php echo $id; ?>">
                    <?php } ?>
                    <div class="form-group">
                        <label class="control-label col-sm-4  required" for="StretchyReport_id">Report Category
                            <span class="required" style="color: red">*</span></label>
                        <div class="col-sm-8">
                            <select class="select2 form-control"
                                <?php if($_GET['action']=="view"){ echo "disabled";} ?>
                                    required
                                    name="schedule[report]"
                                    style="width: 100%">
                                <option value="">- Select Option -</option>
                                <?php while ($row = mysqli_fetch_assoc($reports)) { ?>
                                    <option value="<?php echo $row['type'] . ">" . $row['url'] . ">" . $row['uri']; ?>" <?php if($scheduleTaskCode==$row['type']){ echo "selected";} ?>><?php echo $row['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group error">
                        <label class="col-sm-4 control-label" for="ReportMailingJob_name">Name
                            <span class="required" style="color: red">*</span></label>
                        <div class="controls col-sm-8">
                            <input class="form-control"
                                   name="consumerNote[name]"
                                   id="ReportMailingJob_name"
                                   type="text"
                                   value="<?php echo $nameOfReport; ?>"
                                <?php if($_GET['action']=="view"){ echo "readonly";} ?>
                                   required>
                        </div>
                    </div>

                    <div class="form-group" <?php if($_GET['action']=="view"){ echo "style=\"display: none\"";} ?>>
                        <label class="control-label col-sm-4 " for="ReportMailingJob_description">Description</label>
                        <div class="col-sm-8">
                            <textarea class=" form-control" name="consumerNote[description]"
                                      id="ReportMailingJob_description"></textarea>
                            <span class="help-inline error" id="ReportMailingJob_description_em_"
                                  style="display: none"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4  required" for="ReportMailingJob_startDate">Start Date
                            <span class="required" style="color: red">*</span></label>
                        <div class="col-sm-8">
                            <input type="datetime-local" id="datetimeLocalSelectStart" class="form-control"
                                   name="schedule[startDate]"
                                   min="<?php echo $startDate; ?>" value="<?php echo $startDate; ?>"
                                   <?php if($_GET['action']=="view"){ echo "readonly";} ?>
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4  required" for="ReportMailingJob_endDate">End Date
                            <span class="required" style="color: red">*</span></label>
                        <div class="col-sm-8">
                            <input type="datetime-local" id="datetimeLocalSelectEnd" class="form-control"
                                   name="schedule[endDate]"
                                   min="<?php echo $endDate; ?>" value="<?php echo $endDate; ?>"
                                <?php if($_GET['action']=="view"){ echo "readonly";} ?>
                                   required>
                        </div>
                    </div>
                    <div id="divDisplay"></div>
                    <div id="RecurrenceRule_Frequency_Interval_Wrapper" style="">
                        <div class="form-group">
                            <label class="control-label col-sm-4 ">Frequency/Interval <span class="required"
                                                                                            style="color: red">*</span></label>
                            <div class="col-sm-4">
                                <select <?php if($_GET['action']=="view"){ echo "disabled";} ?> class="form-control" name="schedule[frequency]"
                                        onchange="showfield(this.options[this.selectedIndex].value)"
                                        id="RecurrenceRule_frequency"
                                        tabindex="-1" style="" required>
                                    <option value="">- Select Option -</option>
                                    <option <?php if($frequencyInterval=="D"){ echo "selected";} ?> value="D">Daily</option>
                                    <option <?php if($frequencyInterval=="W"){ echo "selected";} ?> value="W">Weekly</option>
                                    <option <?php if($frequencyInterval=="M"){ echo "selected";} ?> value="M">Monthly</option>
                                </select>


                            </div>

                            <div id="share_data_date"></div>

                        </div>

                    </div>
                    <div class="form-group" <?php if($_GET['action']=="view"){ echo "style=\"display: none\"";} ?>>
                        <label for="" class="col-sm-4 control-label">Type</label>
                        <div class="col-sm-8">
                            <select
                                    name="consumerNote[messageType]"
                                    class="form-control"
                                <?php if($_GET['action']=="view"){ echo "disabled";} ?>
                                    onchange="showAudience(this.options[this.selectedIndex].value)">
                                <option>
                                <option selected disabled>Select</option>
                                <option <?php if($type=="EMAIL"){ echo "selected";} ?> value="EMAIL">Email</option>
                                <option <?php if($type=="SMS"){ echo "selected";} ?> value="SMS">SMS</option>
                                <option <?php if($type=="SYSTEM"){ echo "selected";} ?> value="SYSTEM">System</option>
                            </select>
                        </div>
                    </div>

                    <div id="div_contacts"></div>
                    <div id="get_numbers"></div>
                    <div id="get_emails"></div>

                    <div class="form-group">
                        <label class="control-label col-sm-4  required" for="ReportMailingJob_isActive">Active
                            <span class="required" style="color: red">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="schedule[status]"
                                <?php if($_GET['action']=="view"){ echo "disabled";} ?>
                                    id="ReportMailingJob_isActive" tabindex="-1" required aria-hidden="true">
                                <option value="">- Select Option -</option>
                                <option <?php if($status=="false"){ echo "selected";} ?> value="0">No</option>
                                <option <?php if($status=="true"){ echo "selected";} ?> value="1">Yes</option>
                            </select>
                        </div>
                    </div>


                    <div id="ReportMailingJob_Report_Paramters_Wrapper"></div>

                    <div class="form-group col-sm-12" <?php if($_GET['action']=="view"){ echo "style=\"display: none\"";} ?> align="center">
                        <div class="controls">
                            <button class="btn btn-danger" type="reset"><i class="icon-arrow-left"></i>&nbsp;Reset
                            </button>
                            <button class="btn btn-primary" name="saveSchedule" type="submit"><i
                                        class="icon-white icon-ok"></i>&nbsp;Save
                            </button>
                        </div>
                    </div>
                </form>
                </div>

                <?php if(isset($_GET['action'])){ ?>
                <div class="col-sm-5">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse"
                                       href="#collapse">Schedule History</a>
                                </h4>
                            </div>
                            <div id="collapse"
                                 class="panel-collapse collapse">
                                <table class="table table-bordered table-condensed table-hover dataTable no-footer"
                                       role="grid">
                                    <th>Last Ran Date</th>
                                    <th>Call Status</th>
                                    <th>Updated By</th>
                                    <th>Last Modified on</th>
                                    <?php foreach ($scheduleResponse['scheduleHistoryDtos'] as $key => $value){ ?>
                                        <tr>
                                            <td><?php echo $value['lastRanDate']; ?></td>
                                            <td><?php echo $value['callStatus']; ?></td>
                                            <td><?php echo $value['updateBy']; ?></td>
                                            <td><?php echo str_replace(["T",".000+00:00"]," ",$value['updateTimestamp']); ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                                <div class="panel-footer" align="right">
                                    <p style="color: #1b7e5a"> <strong>Next Run Time is: <?php echo $nextRunDate; ?></strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

    </div>

</div>

<?php
if (isset($_POST['saveSchedule'])) {
    $menu = base64_encode('416');
    $encodedResponse = base64_encode($response);
    $URL = "schedule_list.php?mid=$menu&&message=$encodedResponse";
    echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
    echo '<META HTTP-EQUIV="refresh" content="5;URL=' . $URL . '">';
}
?>

<script type="text/javascript">
    function showfield(name) {
        if (name == 'W') {
            document.getElementById('share_data_date').innerHTML = '<div class="form-group">\n' +
                '                           <div class="col-sm-1"> ON</div> <div class="col-sm-3">' +
                '<select name="schedule[frequencyDay]" class="form-control" required>' +
                '<option value="">Select day of week</option>' +
                '<option <?php if($scheduleResponse['scheduleDto']['frequencyDay']==1){echo "selected";} ?>  value="1">Sunday</option>' +
                '<option <?php if($scheduleResponse['scheduleDto']['frequencyDay']==2){echo "selected";} ?>   value="2">Monday</option>' +
                '<option <?php if($scheduleResponse['scheduleDto']['frequencyDay']==3){echo "selected";} ?>   value="3">Tuesday</option>' +
                '<option <?php if($scheduleResponse['scheduleDto']['frequencyDay']==4){echo "selected";} ?>   value="4">Wednesday</option>' +
                '<option <?php if($scheduleResponse['scheduleDto']['frequencyDay']==5){echo "selected";} ?>   value="5">Thursday</option>' +
                '<option <?php if($scheduleResponse['scheduleDto']['frequencyDay']==6){echo "selected";} ?>   value="6">Friday</option>' +
                '<option <?php if($scheduleResponse['scheduleDto']['frequencyDay']==7){echo "selected";} ?>   value="7">Saturday</option>' +
                '</select>' +
                '</div>' +
                '</div>';
        } else if (name == 'M') {
            document.getElementById('share_data_date').innerHTML = '<div class="form-group">\n' +
                '<div class="col-sm-1"> ON</div> <div class="col-sm-3">' +
                '<select name="schedule[frequencyDay]" class="form-control" required>' +
                '<option selected="selected" value="">Select day of Month</option>' +
                '<?php foreach (range(1, 31, 1) as $number) { ?>' +
                '<option  value="<?php echo $number ?>" <?php if($scheduleResponse['scheduleDto']['frequencyDay']==$number){echo "selected";} ?>><?php echo $number ?></option>' +
                '<?php } ?>' +
                '<option>Last Day of Month</option>'+
                '</select>' +
                '</div>' +
                '</div>';
        } else document.getElementById('share_data_date').innerHTML = '';
    }
</script>
<script>
    var divDisplay = document.getElementById("divDisplay");
    var inputDatetimeLocal = document.getElementById("datetimeLocalSelect");

    function getMinimum() {
        divDisplay.textContent = 'Minimum Legal Input: ' + inputDatetimeLocal.min;
    }
</script>

<script>
    function showAudience(name) {
        if (name == 'SMS')
            document.getElementById('div_contacts').innerHTML = '<div class="form-group">\n' +
                '                           <label for="" class="col-sm-4 control-label">Select SMS Audience</label>\n' +
                '                           <div class="col-sm-8">\n' +
                '                               <select\n' +
                '                                       name="to"\n' +
                '                                       id="to"\n' +
                '                                       class="form-control"\n' +
                '                                       onchange="get_numbers();"\n' +
                '                                       style="width:100%">\n' +
                '                                   <option value="" selected="selected"></option>\n' +
                '                                   <option value="Regular">Regular Members</option>\n' +
                '                                   <option value="Non-Regular">Non-Regular Members</option>\n' +
                '                                   <option value="Group">Group</option>\n' +
                '                               </select>\n' +
                '                           </div>\n' +
                '                       </div>' +
                '                    <div class="form-group error">\n' +
                '                        <label class="control-label col-sm-4  required" for="ReportMailingJob_smsContent">SMS\n' +
                '                            Content </label>\n' +
                '                        <div class="col-sm-8">\n' +
                '                            <textarea class="form-control textareaResizing" name="consumerNote[smsContent]"\n' +
                '                                      id="ReportMailingJob_smsContent"></textarea>\n' +
                '                        </div>\n' +
                '                    </div>';
        else if (name == 'EMAIL')
            document.getElementById('div_contacts').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-4 control-label">Subject</label>\n' +
                '                            <div class="col-sm-8">\n' +
                '                                <input type="text" name="subject" id="editor1" class="form-control" required></input>\n' +
                '                            </div>\n' +
                '                        </div>' +
                '' +
                '<div class="form-group">\n' +
                '                           <label for="" class="col-sm-4 control-label">Select Email Audience</label>\n' +
                '                           <div class="col-sm-8">\n' +
                '                               <select\n' +
                '                                       name="to"\n' +
                '                                       id="email"\n' +
                '                                       class="form-control"\n' +
                '                                       onchange="get_emails();"\n' +
                '                                       style="width:100%">\n' +
                '                                   <option value="" selected="selected"></option>\n' +
                '                                   <option value="Regular">Regular Members</option>\n' +
                '                                   <option value="Non-Regular">Non-Regular Members</option>\n' +
                '                                   <option value="Group">Group</option>\n' +
                '                               </select>\n' +
                '                           </div>\n' +
                '                       </div>' +
                '                    <div class="form-group error">\n' +
                '                        <label class="control-label col-sm-4  required" for="ReportMailingJob_emailMessage">Email\n' +
                '                            Message </label>\n' +
                '                        <div class="col-sm-8">\n' +
                '                            <textarea class="form-control textareaResizing" name="consumerNote[emailBody]"\n' +
                '                                      id="ReportMailingJob_emailMessage"></textarea>\n' +
                '                        </div>\n' +
                '                    </div>' +
                '<div class="form-group">\n' +
                '                        <label class="control-label col-sm-4  required" for="EmailAttachmentFileFormat_id">Email\n' +
                '                            Attachment File Format </label>\n' +
                '                        <div class="col-sm-8">\n' +
                '                            <select class="form-control" name="consumerNote[fileFormat]"\n' +
                '                                    id="EmailAttachmentFileFormat_id" required tabindex="-1" aria-hidden="true">\n' +
                '                                <option value="">- Select Option -</option>\n' +
                '                                <option value="xlsx">XLS (Microsoft Excel)</option>\n' +
                '                                <option value="PDF">PDF</option>\n' +
                '                                <option value="CSV">CSV</option>\n' +
                '                            </select>\n' +
                '                        </div>\n' +
                '                    </div>';
        else document.getElementById('div_contacts').innerHTML = '';
    }
</script>
<script type="text/javascript">
    function get_numbers() { // Call to ajax function
        var to = $('#to').val();
        var dataString = "to=" + to;
        console.log(to);
        $.ajax({
            type: "POST",
            url: "getnumbers.php", // Name of the php files
            data: dataString,
            success: function (html) {
                $("#get_numbers").html(html);
            }
        });
    }

    function get_emails() { // Call to ajax function
        var to = $('#email').val();
        var dataString = "to=" + to;
        console.log(to);
        $.ajax({
            type: "POST",
            url: "getemails.php", // Name of the php files
            data: dataString,
            success: function (html) {
                $("#get_emails").html(html);
            }
        });
    }


    $("input[type='radio']").change(function () {

        if ($(this).val() == "sms") {
            $("#smsField").show();
        } else {
            $("#smsField").hide();
        }
        if ($(this).val() == "email") {
            $("#emailField").show();
        } else {
            $("#emailField").hide();
        }

    });
</script>