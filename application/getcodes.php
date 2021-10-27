<?php
include ("../config/connect.php");
if ($_POST) {
    $gl_group = $_POST['gl_group'];
    if ($gl_group != '') {
        $sql1 = "SELECT code FROM gl_codes WHERE name='$gl_group'";
        $result1 = mysqli_query($link, $sql1);

        while ($row = mysqli_fetch_array($result1)) {
            $glCode = substr($row['code'],0,3);
            $maxCode=mysqli_fetch_assoc(mysqli_query($link,"select max(code) from gl_codes where code like '$glCode%'"));
            $newCode=$maxCode['max(code)']+1;
            echo "<div class=\"form-group\">
<label for=\"name\" class=\"col-sm-2 control-label\">GL Code</label>
                                <div class=\"col-sm-10\">
                                    <input type=\"text\" name=\"glCode\" value='$newCode' class=\"form-control\">
                                </div>
                                </div>";
        }
    }
    else
    {
        echo  '';
    }
}
