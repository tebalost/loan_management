<h5 class="text-red text-bold">New Borrower Information:</h5>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">First Name *</label>
                                    <div class="col-sm-6">
                                        <input name="basicInfo[firstname]"
                                               type="text" class="form-control"
                                               placeholder="First Name"
                                               value="basicInfo[firstname]"
                                               required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Last Name *</label>
                                    <div class="col-sm-6">
                                        <input name="basicInfo[lastname]"
                                               type="text" class="form-control"
                                               placeholder="Last Name"
                                               value=""
                                               required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Employee Code *</label>
                                    <div class="col-sm-6">
                                        <input name="basicInfo[employeeCode]" type="text"
                                               class="form-control"
                                               placeholder="Employee Code"
                                               value="<?php echo $_GET['newSearch']; ?>"
                                               required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Required Loan *</label>
                                    <div class="col-sm-6">
                                        <input name="basicInfo[principalAmount]"
                                               type="number"
                                               class="form-control"
                                               placeholder="Required Loan Amount"
                                               min="<?php echo $minimumLoan; ?>"
                                               id="requiredLoan"
                                               max="<?php echo $maximumLoan; ?>"
                                               step="0.01"
                                               value="<?php //echo $_SESSION['basicInfo']['disposableIncome']; ?>"
                                               required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Disposable Income *</label>
                                    <div class="col-sm-6">
                                        <input name="basicInfo[disposableIncome]"
                                               type="number"
                                               class="form-control"
                                               placeholder="Disposable Income"
                                               min="0"
                                               step="0.01"
                                               id=""
                                               value=""
                                               required>
                                    </div>
                                </div>

                                <div align="center">
                                    <div class="box-footer">
                                        <button type="reset" class="btn btn-primary btn-flat"><i
                                                    class="fa fa-times">&nbsp;Reset</i>
                                        </button>
                                        <button name="continue" type="submit" class="btn btn-success"><i
                                                    class="fa fa-save">&nbsp;Continue</i>
                                        </button>

                                    </div>
                                </div>

                            </form>