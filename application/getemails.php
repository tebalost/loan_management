<style>
    #holder {
        width: 100%;
    }

    #holder > div {
        clear: both;
        padding: 2%;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        float: left;
        width: 96%;
    }

    label {
        display: inline;
    }

    .regular-checkbox {
        display: none;
    }

    .regular-checkbox + label {
        background-color: #fafafa;
        border: 1px solid #cacece;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
        padding: 9px;
        border-radius: 3px;
        display: inline-block;
        position: relative;
    }

    .regular-checkbox + label:active, .regular-checkbox:checked + label:active {
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1);
    }

    .regular-checkbox:checked + label {
        background-color: #e9ecee;
        border: 1px solid #adb8c0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05), inset 15px 10px -12px rgba(255,255,255,0.1);
        color: #99a1a7;
    }

    .regular-checkbox:checked + label:after {
        content: '\2714';
        font-size: 14px;
        position: absolute;
        top: 0px;
        left: 3px;
        color: #99a1a7;
    }


    .big-checkbox + label {
        padding: 18px;
    }

    .big-checkbox:checked + label:after {
        font-size: 28px;
        left: 6px;
    }

    .tag {
        font-family: Arial, sans-serif;
        width: 200px;
        position: relative;
        top: 5px;
        font-weight: bold;
        text-transform: uppercase;
        display: block;
        float: left;
    }

    .radio-1 {
        width: 193px;
    }

    .button-holder {
        float: left;
    }

    /* RADIO */

    .regular-radio {
        display: none;
    }

    .regular-radio + label {
        -webkit-appearance: none;
        background-color: #fafafa;
        border: 1px solid #cacece;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
        padding: 9px;
        border-radius: 50px;
        display: inline-block;
        position: relative;
    }

    .regular-radio:checked + label:after {
        content: ' ';
        width: 12px;
        height: 12px;
        border-radius: 50px;
        position: absolute;
        top: 3px;
        background: #99a1a7;
        box-shadow: inset 0px 0px 10px rgba(0,0,0,0.3);
        text-shadow: 0px;
        left: 3px;
        font-size: 32px;
    }

    .regular-radio:checked + label {
        background-color: #e9ecee;
        color: #99a1a7;
        border: 1px solid #adb8c0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05), inset 15px 10px -12px rgba(255,255,255,0.1), inset 0px 0px 10px rgba(0,0,0,0.1);
    }

    .regular-radio + label:active, .regular-radio:checked + label:active {
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1);
    }

    .big-radio + label {
        padding: 16px;
    }

    .big-radio:checked + label:after {
        width:50px;
        height: 24px;
        left: 4px;
        top: 4px;
    }

    /* ------- IGNORE */

    #header {
        width: 100%;
        margin: 0px auto;
    }

    #header #center {
        text-align: center;
    }

    #header h1 span {
        color: #000;
        display: block;
        font-size: 50px;
    }

    #header p {
        font-family: 'Georgia', serif;
    }
    #header h1 {
        color: #892dbf;
        font: bold 40px 'Bree Serif', serif;
    }

    #travel {
        padding: 10px;
        background: rgba(0,0,0,0.6);
        border-bottom: 2px solid rgba(0,0,0,0.2);
        font-variant: normal;
        text-decoration: none;
        margin-bottom: 20px;
    }

    #travel a {
        font-family: 'Georgia', serif;
        text-decoration: none;
        border-bottom: 1px solid #f9f9f9;
        color: #f9f9f9;
    }
</style>
<?php
include ("../config/connect.php");

if ($_POST) {
    $to = $_POST['to'];
    $_SESSION['email']=$to;
    if ($to !== '') {
        if($to=="Individual"){
            echo "<div class=\"form-group\">
                <label class=\"col-sm-4 control-label\">Email Address:</label>
                <div class=\"col-sm-8\">
                <div class=\"input-group\">
                  <div class=\"input-group-addon\">
                    <i class=\"fa  fa-envelope\"></i>
                  </div>
                  <input type=\"text\" name='emailAddress' 
                   class=\"form-control\" >
                </div>
                </div>
              </div>";
        }
        else if($to=="Group"){
            echo "                    <div class=\"form-group error\">
                        <label class=\"control-label col-sm-4  required\" for=\"ReportMailingJob_emailRecipients\">Email
                            Recipients </label>
                        <div class=\"col-sm-8\">
                            <textarea class=\"form-control textareaResizing\"
                                      placeholder=\"first.email@domain.com,second.email@domain.com\"
                                      name=\"consumerNote[emailRecipients]\"
                                      id=\"ReportMailingJob_emailRecipients\"></textarea></div>
                    </div>";
        }
        else if($to=="Non-Regular"){
            //Get Count of email addresses
            $email=mysqli_fetch_assoc(mysqli_query($link,"select count(*) from borrowers where member = 0 and email!='' and email like '%@%'"));
            $emails=number_format($email['count(*)'],"0",".",",");
            echo "
                <div class=\"form-group\">
                <label class=\"col-sm-4 control-label\">Members Emails:</label>
                <div class=\"col-sm-8\">
                    <div class=\"input-group\">
                        <div id=\"holder\">
                            <div>
                                <input type=\"checkbox\" id=\"checkbox-1-1\" class=\"regular-checkbox\" name='emailList' /><label for=\"checkbox-1-1\">Emails: <br>$emails</label>
                            </div>
                        </div>
                    </div>
                </div>
                ";
        }
        else if($to==="Regular"){
            //Get Count of mobile and telephone numbers
            $email=mysqli_fetch_assoc(mysqli_query($link,"select count(*) from borrowers where member = 1 and email!='' and email like '%@%'"));
            $emails=number_format($email['count(*)'],"0",".",",");
            echo "
                <div class=\"form-group\">
                <label class=\"col-sm-4 control-label\">Members Emails:</label>
                <div class=\"col-sm-8\">
                    <div class=\"input-group\">
                        <div id=\"holder\">
                            <div>
                                <input type=\"checkbox\" id=\"checkbox-1-1\" class=\"regular-checkbox\" name='mobiles' /><label for=\"checkbox-1-1\">Emails: <br>$emails</label>
                            </div>
                        </div>
                    </div>
                </div>
                ";
        }
      }
    else
    {
        echo  '';
    }
}
?>
<script>
    // This is an old version, for a more recent version look at
    // https://jsfiddle.net/DRSDavidSoft/zb4ft1qq/2/
    function maxLengthCheck(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }
</script>
