<div class="row">
    <section class="content">
          <div class="box box-info">
            <div class="box-body">
                <div class="table-responsive">
                    <div class="box-body">
                        <form method="post">
                            <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>">
                                <button type="button" class="btn btn-flat btn-warning"><i
                                            class="fa fa-mail-reply-all"></i>&nbsp;Back
                                </button>
                            </a>
                            <hr>
                            <style>
                                th {
                                    padding-top: 12px;
                                    padding-bottom: 12px;
                                    text-align: left;
                                    background-color: #D1F9FF;
                                }
                            </style>
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Filename</th>
                                    <th>Records</th>
                                    <th align="right">Cover Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $tid = $_SESSION['tid'];
                                $select = mysqli_query($link, "SELECT * FROM insurance_loan_life_cover") or die (mysqli_error($link));

                                if (mysqli_num_rows($select) == 0) {
                                    echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
                                } else {
                                    while ($row = mysqli_fetch_array($select)) {

                                            ?>
                                            <tr>

                                                <td><?php echo $row['date_sent']; ?></td>
                                                <td><a href="reporting/files-to-insurance/<?php echo $row['name']; ?>"><?php echo $row['name']; ?></a></td>
                                                <td><?php echo $row['number_of_loans']; ?></td>
                                                <td align="right"><strong><?php echo number_format($row['amount'], 2, ".", ","); ?></strong></td>

                                            </tr>
                                        <?php
                                    }
                                } ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>
