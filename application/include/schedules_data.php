<?php
$Code = mysqli_fetch_assoc(mysqli_query($link, "select * from systemset"));
$companyCode = $Code['srn'];
$apiUrl="http://35.225.221.35:2022/scheduler-api/list/$companyCode";

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
    return json_decode(curl_exec($ch),true);
}
$scheduleResponse=getAPICall($apiUrl);
$_SESSION['scheduleResponse'] = $scheduleResponse;
?>
<div class="row">
    <section class="content">
        <div class="box box-success">
            <div class="box-body">
                <div class="table-responsive">
                    <div class="box-body">
                        <?php
                        if(isset($_GET['message'])){
                            $response=base64_decode($_GET['message']);
                            //print_r($scheduleData);
                            if(strpos($response,"Success")!==false) {
                                echo "<div class=\"alert alert-success\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                    $response&nbsp; &nbsp;&nbsp;
                                              </div>";
                            }else{
                                echo "<div class=\"alert alert-danger\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                    $response&nbsp; &nbsp;&nbsp;
                                              </div>";
                            }
                        }
                        ?>

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all"/></th>
                                    <th>Report Name</th>
                                    <th>Start Date</th>
                                    <th>Next Run Date</th>
                                    <th>Report Code</th>
                                    <th>Frequency</th>
                                    <th>Frequency Day</th>
                                    <th>Report URI</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                foreach ($_SESSION['scheduleResponse']['scheduleDtos'] as $key => $value) {
                                    $id=$value['scheduleDtoId'];
                                    $taskCode=$value['scheduleTaskCode'];
                                    //Get the scheduleName
                                    $reportName=mysqli_fetch_assoc(mysqli_query($link,"select * from report_types where type='$taskCode'"));
                                    $nameOfReport=$reportName['name'];
                                    switch ($value['frequency']){
                                        case "M":
                                            $frequency="Monthly";
                                            break;
                                        case "W":
                                            $frequency="Weekly";
                                            break;
                                        case "D":
                                            $frequency="Daily";
                                            break;
                                        Case "Y":
                                            $frequency="Yearly";
                                            break;
                                        Default:
                                            $frequency=$value['frequency'];
                                            break;
                                    }

                                    ?>
                                    <tr>
                                        <td><input id="optionsCheckbox" class="checkbox"
                                                   name="selector[]"
                                                   type="checkbox" value="<?php echo $id; ?>"></td>
                                        <td><?php echo $nameOfReport; ?></td>
                                        <td><?php echo $value['startDate'] . " " . $value['startTime']; ?></td>
                                        <td><?php echo $value['nextRunDate']; ?></td>
                                        <td><?php echo $value['scheduleTaskCode']; ?></td>
                                        <td><?php echo $frequency; ?></td>
                                        <td><?php echo $value['frequencyDay'];; ?></td>
                                        <td><?php echo $value['consumerEndPointUri']; ?></td>
                                        <td>
                                            <a href="add_schedule.php?id=<?php echo $id . "&&mid=" . base64_encode("416"). "&&type=" . $taskCode. "&company=" . $companyCode. "&action=view";?>"><i class="fa fa-eye"></i></a>
                                            &nbsp;
                                            <!--Make this a modal -->
                                            <a href="add_schedule.php?id=<?php echo $id . "&&mid=" . base64_encode("416"). "&&type=" . $taskCode. "&company=" . $companyCode. "&action=edit" ;?>"><i class="fa fa-pencil"></i></a>

                                        </td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                    </div>


                </div>
            </div>
        </div>
</div>