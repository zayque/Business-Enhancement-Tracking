<?php 
              // Include database connection
           
              include('session.php');
              if(!isset($_SESSION['login_user'])){
                  echo "<script>alert('Staff ID/Password Invalid');</script>";
                  echo "<script>window.location='index.php?login=errormsg';</script>";
              }
          
      ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>BE Tracking</title>
    <link rel="icon" type="image/ico" href="" />

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">

    <!-- <script language=JavaScript>
    function reload(form) {
        var val = form.zon.options[form.zon.options.selectedIndex].value;
        self.location = 'boranglatihan.php?zon=' + val;
    }
    </script> -->

    <script type="text/javascript">
    window.history.forward();

    function noBack() {
        window.history.forward();
    }




    </script>
</head>

<style>
body {
    background: linear-gradient(to right, #fff 18%, #f08303 50%);

}

.form-input {
    width: 70%;
    margin-left: 10px;
    border-radius: 5px;

}

.panel-body-form {
    padding: 15px;
    padding-left: 10%;
    background-color: #F0ECEB;
    font-size: 15px;

}

.b {
    margin-left: 40%;
    width: 5%;

}

.no {
    padding-left: 80px;

}

.info {

    font-size: 20px;
    font-family: arial;
    padding-left: 25%;

}

.note {
    color: red;
    padding-bottom: 40px;
    font-size: 12px;

}

span {
    color: red;
    font-size: 11px;
}
</style>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
    <br>

    <!-- <div class="container">
        <div class="col-lg-12">
         
            <h3 style="float: center; margin-top: 5%; text-align: center;"><b>BE Tracking</b></h3>
        </div>
    </div> -->
    <div class="container" style="margin-top: 20px">

        <button><a href="dashboard.php" style="color:#f08303">Back</a></button>
        <br> <br>

        <div class="col-lg-13">
            <form role="form" action="index_db.php" method="post" enctype="multipart/form-data" onkeyup="calc()">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center"><b>Create New Job</b></h3>
                    </div>

                    <div class="panel-body-form">
                        <input type="hidden" name="registered_by" id="company_name" class="form-control"
                            value="<?php echo $fullname_session; ?>">
                        <br>
                        <div class="">

                            <div class="form-group">
                                <label for="Name"
                                    class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">Workstream</label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <input type="text" name="workstream" id="company_name" class="form-control"
                                        placeholder="Workstream">
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="">

                            <div class="form-group">
                                <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                    WS Code</label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <input type="text" name="ws_code" id="Applicant_name" class="form-control input"
                                        placeholder="WS Code">
                                </div>
                            </div>


                        </div>
                        <br><br><br>

                        <div class="">
                            <div class="form-group">
                                <br>
                                <table style="width:30%;">
                                    <label for="model" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                        Developer<a style="color:red;"> *</a></label>
                                    <tr>
                                        <td><input type="checkbox" name="check[]" value="GITD"> GITD</td>
                                        <td><input type="checkbox" name="check[]" value="GNT"> GNT</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" name="check[]" value="NRO-Megat"> NRO-Megat</td>
                                        <td><input type="checkbox" name="check[]" value="NRO-Ain"> NRO-Ain</td>
                                    </tr>
                                </table>
                            </div>
                            <br>
                        </div>
                        <div class="">
                            <div class="form-group">
                                <label for="model"
                                    class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">SVP/Others<a
                                        style="color:red;"> *</a></label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <select class="form-control" name="svp_others" required>
                                        <option value="">Choose...</option>
                                        <option value="SVP2-Waiters Clearence">SVP2-Waiters Clearence</option>
                                        <option value="SVP3 - Accelerate Service Installation">SVP3 - Accelerate Service
                                            Installation</option>
                                        <option value="SVP10 - NDDigital">SVP10 - NDDigital</option>
                                        <option value="NMO">NMO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="">

                            <div class="form-group">
                                <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                    IRIS</label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <input type="text" name="iris" id="Applicant_name" class="form-control input"
                                        placeholder="IRIS">
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="">

                            <div class="form-group">
                                <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                    Project/System<a style="color:red;"> *</a></label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <input type="text" name="project_system" id="Applicant_name"
                                        class="form-control input" placeholder="Project/System" required>
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="">

                            <div class="form-group">
                                <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                    Brief Description<a style="color:red;"> *</a></label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <textarea rows="4" cols="50" name="brief_description" id="brief_description"
                                        class="form-control input" placeholder="Brief Description" required></textarea>
                                    <!-- <input type="text" name="brief_description" id="brief_description"
                                        class="form-control input" placeholder="Brief Description" required> -->
                                </div>
                            </div>
                        </div>
                        <br><br><br><br><br>
                        <div class="">
                            <div class="form-group">
                                <label for="model" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">Status<a
                                        style="color:red;"> *</a></label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="">Choose...</option>
                                        <option value="Initiate">Initiate</option>
                                        <option value="Requirement">Requirement</option>
                                        <option value="Assessment">Assessment</option>
                                        <option value="Build">Build</option>
                                        <option value="Testing">Testing</option>
                                        <option value="Deployed">Deployed</option>
                                        <option value="Completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="">

                            <div class="form-group">
                                <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                    BE Submission</label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <input type="date" name="be_submission" id="Applicant_name"
                                        class="form-control input" placeholder="BE Submission">
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="">

                            <div class="form-group">
                                <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                    Assessment Completion</label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <input type="date" name="assessment_completed" id="Applicant_name"
                                        class="form-control input" placeholder="Assessment Completion">
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="">

                            <div class="form-group">
                                <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                    Target System RFS</label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <input type="date" name="target_systemrfs" id="Applicant_name"
                                        class="form-control input">
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="">
                            <div class="form-group">
                                <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                    Revise Target System RFS</label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <input type="date" name="revise_target_systemrfs" id="Applicant_name"
                                        class="form-control input">
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="">
                            <div class="form-group">
                                <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                    Actual System RFSI</label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <input type="date" name="actual_systemrfs" id="actual_systemrfs"
                                        class="form-control input">
                                    <span>*Fill when status Deployed</span>
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="">

                            <div class="form-group">
                                <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                    Target Rollout Date</label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <input type="date" name="rollout" id="rollout" class="form-control input">
                                    <span>*Fill when status Completed</span>
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="">

                            <div class="form-group">
                                <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                    MOT</label>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                    <input type="text" name="mot" id="Applicant_name" placeholder="MOT"
                                        class="form-control input">
                                </div>
                            </div>
                        </div>
                        <br><br><br><br>

                        <div>

                            <label for="Name" class="col-lg-2 col-md-4 col-sm-4 col-xs-4 control-label">
                                BE Cost</label><br><br>
                            <div class="">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-2 col-md-4 col-sm-4 col-xs-4 control-label">
                                        GIT Internal MD</label>
                                    <div class="col-lg-2 col-md-7 col-sm-7 col-xs-7">
                                        <input type="text" name="git_internalmd" id="git_internalmd"
                                            class="form-control input" placeholder="0">
                                        <span>*Fill when status Completed</span>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-2 col-md-4 col-sm-4 col-xs-4 control-label">
                                        RM GIT Internal</label>
                                    <div class="col-lg-2 col-md-7 col-sm-7 col-xs-7">
                                        <input type="text" name="rm_git_internal" id="rm_git_internal"
                                            class="form-control input" placeholder="0" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <br><br><br>
                            <div class="">

                                <div class="form-group">
                                    <label for="Name" class="col-lg-2 col-md-4 col-sm-4 col-xs-4 control-label">
                                        GIT External MD</label>
                                    <div class="col-lg-2 col-md-7 col-sm-7 col-xs-7">
                                        <input type="text" name="git_externalmd" id="git_externalmd"
                                            class="form-control input" value="0" onchange="myChangeFunction1(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-2 col-md-4 col-sm-4 col-xs-4 control-label">
                                        RM GIT External +6%</label>
                                    <div class="col-lg-2 col-md-7 col-sm-7 col-xs-7">
                                        <input type="text" name="rm_git_external" id="rm_git_external"
                                            class="form-control input" value="0">
                                    </div>
                                </div>
                            </div>
                            <br><br><br>
                            <div class="">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-2 col-md-4 col-sm-4 col-xs-4 control-label">
                                        GNT Internal MD</label>
                                    <div class="col-lg-2 col-md-7 col-sm-7 col-xs-7">
                                        <input type="text" name="gnt_internalmd" id="gnt_internalmd"
                                            class="form-control input" placeholder="0">
                                        <span>*Fill when status Completed</span>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-2 col-md-4 col-sm-4 col-xs-4 control-label">
                                        RM GNT Internal</label>
                                    <div class="col-lg-2 col-md-7 col-sm-7 col-xs-7">
                                        <input type="text" name="rm_gnt_internal" id="rm_gnt_internal"
                                            class="form-control input" placeholder="0" value="0" readonly>
                                    </div>
                                </div>
                            </div>

                            <br><br><br>
                            <div class="">

                                <div class="form-group">
                                    <label for="Name" class="col-lg-2 col-md-4 col-sm-4 col-xs-4 control-label">
                                        GNT External MD</label>
                                    <div class="col-lg-2 col-md-7 col-sm-7 col-xs-7">
                                        <input type="text" name="gnt_externalmd" id="gnt_externalmd"
                                            class="form-control input" value="0" onchange="myChangeFunction2(this)">
                                        <span>*Fill when status Completed</span>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-2 col-md-4 col-sm-4 col-xs-4 control-label">
                                        RM GNT External +6%</label>
                                    <div class="col-lg-2 col-md-7 col-sm-7 col-xs-7">
                                        <input type="text" name="rm_gnt_external" id="Applicant_name"
                                            class="form-control input" value="0">
                                    </div>
                                </div>
                            </div>

                            <br><br><br><br>
                            <div class="">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">
                                        Remark</label>
                                    <div class="col-lg-4 col-md-7 col-sm-7 col-xs-7">
                                        <textarea rows="4" cols="50" name="remark" id="remark"
                                            class="form-control input" placeholder="Remark"></textarea>
                                        <!-- <input type="text" name="remark" id="Applicant_name" placeholder="Remark"
                                            class="form-control input"> -->
                                    </div>
                                </div>
                            </div>

                        </div>

                        <br> <br> <br> <br><br><br>
                        <div class="panel-body">
                            <div class="row">
                                <div class="b">
                                    <button name="submit" type="submit" class="btn btn-primary"
                                        onClick="return confirm('Confirm ?');">Submit</button>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </form>

        </div>
    </div>
    </div>
    </div>
</body>

<div class="footer-copyright text-center py-3">© 2022 Copyright:
    <a href="">Telekom Malaysia</a>
</div>

<script type="text/javascript">
function checkBox() {
    var x = document.getElementById("myCheck").required;
}

var select = document.getElementById("status");
var textBoxElement1 = document.getElementById("actual_systemrfs");
var textBoxElement2 = document.getElementById("rollout");
var textBoxElement3 = document.getElementById("git_internalmd");
var textBoxElement4 = document.getElementById("gnt_internalmd");
var textBoxElement5 = document.getElementById("gnt_externalmd");

// var textBoxElement6 = document.getElementById("git_externalmd");

select.onchange = function() {

    var selectedString1 = select.options[select.selectedIndex].value;
    var selectedString2 = select.options[select.selectedIndex].value;


    if (selectedString1 == 'Deployed') {

        textBoxElement1.required = true;
        textBoxElement1.style.border = "1px solid red";

    } else {

        textBoxElement1.required = false;
        textBoxElement1.style.border = "";
    }


    if (selectedString2 == 'Completed') {

        textBoxElement2.required = true;
        textBoxElement2.style.border = "1px solid red";

        textBoxElement3.required = true;
        textBoxElement3.style.border = "1px solid red";

        textBoxElement4.required = true;
        textBoxElement4.style.border = "1px solid red";

        textBoxElement5.required = true;
        textBoxElement5.style.border = "1px solid red";


    } else {

        textBoxElement2.required = false;
        textBoxElement2.style.border = "";

        textBoxElement3.required = false;
        textBoxElement3.style.border = "";

        textBoxElement4.required = false;
        textBoxElement4.style.border = "";

        textBoxElement5.required = false;
        textBoxElement5.style.border = "";
    }
}


function calc() {
    let git_internalmd = parseFloat(document.getElementById("git_internalmd").value);
    let gnt_internalmd = parseFloat(document.getElementById("gnt_internalmd").value);


    let t = 800 * (git_internalmd);
    t = parseFloat(t).toFixed(2)
    document.getElementById("rm_git_internal").setAttribute('value', t);


    let e = 800 * (gnt_internalmd);
    e = parseFloat(e).toFixed(2)
    document.getElementById("rm_gnt_internal").setAttribute('value', e);

}


function myChangeFunction1(input1) {
    var input2 = document.getElementById('rm_git_external');
    // input2.value = input1.value;
    
    if(input2 == input1){
        return true;    
    }
    alert("You must fill value in RM GIT External +6%");
        return false;
    }

    function myChangeFunction2(input3) {
    var input4 = document.getElementById('rm_gnt_external');
    // input2.value = input1.value;
    
    if(input4 == input3){
        return true;    
    }
    alert("You must fill value in RM GNT External +6%");
        return false;
    }
  

</script>


<br>

</html>