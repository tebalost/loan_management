<?php

foreach ($_SESSION['scheduleResponse']['scheduleDtos'] as $key => $value) {
    $id=$value['scheduleDtoId'];
    $taskCode=$value['scheduleTaskCode'];
       /*<td><?php echo $value['consumerNote']['name']; */?><!--</td>
<td><?php /*echo $value['startDate'] . " " . $value['startTime']; */?></td>
<td><?php /*echo $value['nextRunDate']; */?></td>
<td><?php /*echo $value['scheduleTaskCode']; */?></td>
<td><?php /*echo $value['frequency']; */?></td>
<td><?php /*echo $value['frequencyDay'];; */?></td>
<td><?php /*echo $value['consumerEndPointUri']; */?></td>-->
    ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap4.min.css">

    <div class="modal fade" id="c<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div id="printarea">
                <div class="modal-content"  style="width: 1100px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <strong><h4 class="modal-title" align="center">Edit <?php echo $value['scheduleTaskCode']; ?>  Schedule</h4></strong>
                    </div>
                    <div class="modal-body">
                        <form class="ReportMailingJob form-horizontal" action="" method="post">
                            <div class="form-group error">
                                <label class="col-sm-2 control-label" for="ReportMailingJob_name">Name
                                    <span class="required" style="color: red">*</span></label>
                                <div class="controls col-sm-6">
                                    <input
                                            class="form-control"
                                            name="consumerNote[name]"
                                            id="ReportMailingJob_name"
                                            type="text"
                                            value="<?php echo $value['scheduleTaskCode']; ?>"
                                            required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2 " for="ReportMailingJob_description">Description</label>
                                <div class="col-sm-6">
                            <textarea class=" form-control" name="consumerNote[description]"
                                      id="ReportMailingJob_description"></textarea>
                                    <span class="help-inline error" id="ReportMailingJob_description_em_"
                                          style="display: none"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2  required" for="ReportMailingJob_startDate">Start Date
                                    <span class="required" style="color: red">*</span></label>
                                <div class="col-sm-6">
                                    <input type="datetime-local" id="datetimeLocalSelect" class="form-control"
                                           name="schedule[startDate]"
                                           min="<?php echo date('Y-m-dTH:i'); ?>" value="<?php echo $value['startDate'] . "T" . $value['startTime']; ?>"
                                           required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2  required" for="ReportMailingJob_endDate">End Date
                                    <span class="required" style="color: red">*</span></label>
                                <div class="col-sm-6">
                                    <input type="datetime-local" id="datetimeLocalSelect" class="form-control"
                                           name="schedule[endDate]"
                                           min="<?php echo date('Y-m-d H:i'); ?>" value="<?php echo str_replace("@","T",$value['endDateTime']); ?>"
                                           required>
                                </div>
                            </div>
                            <div id="divDisplay"></div>
                            <div id="RecurrenceRule_Frequency_Interval_Wrapper" style="">
                                <div class="form-group">
                                    <label class="control-label col-sm-2 ">Frequency/Interval <span class="required"
                                                                                                    style="color: red">*</span></label>
                                    <div class="col-sm-2">
                                        <select class="form-control" name="schedule[frequency]"
                                                onchange="showfield(this.options[this.selectedIndex].value)"
                                                id="RecurrenceRule_frequency"
                                                tabindex="-1" style="" required>
                                            <option value="">- Select Option -</option>
                                            <option value="D">Daily</option>
                                            <option value="W">Weekly</option>
                                            <option value="M">Monthly</option>
                                            <option value="Y">Yearly</option>
                                        </select>


                                    </div>
                                    <div id="share_data_date"></div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Type</label>
                                <div class="col-sm-6">
                                    <select
                                            name="consumerNote[messageType]"
                                            class="form-control"
                                            onchange="showAudience(this.options[this.selectedIndex].value)">
                                        <option>
                                        <option selected disabled>Select</option>
                                        <option value="EMAIL">Email</option>
                                        <option value="SMS">SMS</option>
                                        <option value="SYSTEM">SYSTEM</option>
                                    </select>
                                </div>
                            </div>

                            <div id="div_contacts"></div>
                            <div id="get_numbers"></div>
                            <div id="get_emails"></div>

                            <div class="form-group">
                                <label class="control-label col-sm-2  required" for="ReportMailingJob_isActive">Active
                                    <span class="required" style="color: red">*</span></label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="schedule[status]"
                                            id="ReportMailingJob_isActive" tabindex="-1" required aria-hidden="true">
                                        <option value="">- Select Option -</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2  required" for="StretchyReport_id">Report Category
                                    <span class="required" style="color: red">*</span></label>
                                <div class="col-sm-6">
                                    <select class="select2 form-control" required name="schedule[report]" style="width: 100%">
                                        <option value="">- Select Option -</option>
                                        <?php while ($row = mysqli_fetch_assoc($reports)) { ?>
                                            <option value="<?php echo $row['type'] . ">" . $row['url'] . ">" . $row['uri']; ?>"><?php echo $row['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div id="ReportMailingJob_Report_Paramters_Wrapper"></div>
                            <div class="form-group" align="center">
                                <div class="controls">
                                    <button class="btn btn-danger" type="reset"><i class="icon-arrow-left"></i>&nbsp;Reset</button>
                                    <button class="btn btn-primary" name="saveSchedule" type="submit"><i
                                                class="icon-white icon-ok"></i>&nbsp;Save
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


<?php } ?>