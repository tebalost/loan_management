<body style="font-family: 'Tahoma'">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.7/css/rowReorder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<?php
include("../../config/connect.php");
if (isset($_POST['delete'])) {
    $idm = $_GET['id'];
    $id = $_POST['selector'];
    $N = count($id);
    if ($id == '') {
        echo '<div class="alert alert-danger" >
                                         <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                          No rows Selected!!!&nbsp; &nbsp;&nbsp;
                                            </div>';
    } else {
        for ($i = 0; $i < $N; $i++) {
            $result = mysqli_query($link, "update borrowers set status='Deactivated' WHERE id ='$id[$i]'");

        }
        echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Borrowers Updated Successfully!!!&nbsp; &nbsp;&nbsp;
                                           </div>';
    }
}
?>
<table id="example" class="display nowrap" style="width:100%">
    <thead>
    <tr>
        <th><input type="checkbox" id="select_all"/></th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Mobile Number</th>
        <td align="center"><b>Reg. Status</b></td>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
                                if(!isset($_GET['act'])) {
                                    $select = mysqli_query($link, "SELECT * FROM borrowers  where status = 'Active'") or die (mysqli_error($link));
                                }else{
                                    $select = mysqli_query($link, "SELECT * FROM borrowers where status = 'Partial'") or die (mysqli_error($link));
                                }

                                if (mysqli_num_rows($select) == 0) {
                                    echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
    } else {
    while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
    $lname = $row['lname'];
    $fname = $row['fname'];
    $email = $row['email'];
    $phone = $row['phone'];
    $status = $row['status'];
    //$image = $row['image'];
    $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Borrower Details'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $pupdate = $get_check['pupdate'];
    $pread = $get_check['pread'];
    ?>
    <tr>
        <td><input id="optionsCheckbox" class="checkbox" name="selector[]"
                   type="checkbox" value="<?php echo $id; ?>"></td>
        <td><?php echo $fname; ?></td>
        <td><?php echo $lname; ?></td>
        <td><?php echo $email; ?></td>
        <td align="right"><?php echo $phone; ?></td>
        <?php
                                            if ($status == "Pending" || $status == "Partial") {
                                                ?>
        <td align="left" >
            <?php echo ($pupdate == '1') ? '<a href="updateborrowers.php?id=' . $id . '&&mid=' . base64_encode("403") . '&&document="><span class="label label-danger">Complete registration</span></a>' : ''; ?><br>


        </td>
        <?php
                                                }
                                            else if ($status == "Deactivated") {
                                                ?>
        <td align="left" >
            <?php echo ($pupdate == '1') ? '<a href="#?id=' . $id . '&&mid=' . base64_encode("403") . '" ><span class="label label-danger">In-Active</span></a>' : ''; ?>
        </td>
        <?php
                                            }
                                            else {
                                                    ?>
        <td align="left"><span
                class="label label-success"><?php echo $status; ?></span>

        </td>
        <?php } ?>
        <td align="center">


            <?php echo ($pupdate == '1') ? '<a href="updateborrowers.php?id=' . $id . '&&mid=' . base64_encode("403") . '&&document="><i class="fa fa-pencil"></i></a>' : ''; ?>&nbsp;
            <a href="viewborrowers.php?id=<?php echo $id . "&&mid=" . base64_encode("403");?>"><i class="fa fa-eye"></i></a>&nbsp;
            <?php
                                                $se = mysqli_query($link, "SELECT * FROM battachment WHERE get_id = '$id'") or die (mysqli_error($link));
                                                if(mysqli_num_rows($se)>0) {
            ?>
            <?php echo ($pread == '1') ? '<a href="updateborrowers.php?id=' . $id . '&&mid=' . base64_encode("403") . '&&document=download"><i class="fa fa-download"></i>&nbsp;</a>' : ''; ?>
            <?php } ?>

        </td>
    </tr>
    <?php }
                                } ?>
    </tbody>
    </table>





<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.7/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    var table = $('#example').DataTable( {
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true
    } );
} );
</script>