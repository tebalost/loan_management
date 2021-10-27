<div class="row">
    <?php
    $id= $_GET['id'];
    $apiUrl="http://35.225.221.35:2022/scheduler-api/schedule/$id";

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

    //FIXME ... Decode the history array...
    ?>
    <section class="content-header">
        <h1>
            Schedule History <?php echo $_GET['type']; ?>
        </h1>
    </section>
      <section class="content">
        <div class="box box-info">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-money"></i>&nbsp;All Borrower Accounts</h3>
                </div>
                <div class="row" style="margin-right:0.2%;margin-left:0.2%;margin-top: 1%;">
                    <div class="col-sm-12 table-responsive">
                        <div id="view-loans-borrower-1194107_wrapper"
                             class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="pull-left"></div>
                            <div id="view-loans-borrower-1194107_processing"
                                 class="dataTables_processing panel panel-default" style="display: none;"><img src="#">
                                Processing..
                            </div>
                            <div class="pull-right"></div>

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
                                </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td><input id="optionsCheckbox" class="checkbox"
                                                   name="selector[]"
                                                   type="checkbox" value="<?php echo $id; ?>"></td>
                                        <td><?php echo $_SESSION['scheduleResponse']['scheduleDto']['consumerNote']['name']; ?></td>
                                        <td><?php echo $_SESSION['scheduleResponse']['scheduleDto']['startDate'] . " " . $_SESSION['scheduleResponse']['scheduleDto']['startTime']; ?></td>
                                        <td><?php echo $_SESSION['scheduleResponse']['scheduleDto']['nextRunDate']; ?></td>
                                        <td><?php echo $_SESSION['scheduleResponse']['scheduleDto']['scheduleTaskCode']; ?></td>
                                        <td><?php echo $_SESSION['scheduleResponse']['scheduleDto']['frequency']; ?></td>
                                        <td><?php echo $_SESSION['scheduleResponse']['scheduleDto']['frequencyDay'];; ?></td>
                                        <td><?php echo $_SESSION['scheduleResponse']['scheduleDto']['consumerEndPointUri']; ?></td>
                                     </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
