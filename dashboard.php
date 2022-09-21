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

        <title>Dashboard</title>
        <link rel="icon" type="image/ico" href="" />

        <link rel="stylesheet" href="css/jquery.dataTables.min.css">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.v5.css">
        <link rel="stylesheet" type="text/css" href="css/index.css">


        <!-- Page level plugin CSS-->
        <link href="css/dataTables.bootstrap4.css" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <!-- Custom styles for dialog box-->

        <?php
        if(isset($_SESSION['login_msg']) && $_SESSION['login_msg'] != ""){
            echo '<script> alert("' . $_SESSION['login_msg'] . '"); </script>';
        }
    ?>

    </head>


    <style>
/* body{
    background-color: #FEBF30;
} */

th {
    /* background-color: rgb(73, 72, 69); */
    color: rgb(0, 0, 0);
}

th:hover {
    background-color: rgb(182, 181, 181);
}

#dsh {
    background-color: #f08303;

}


#dsh:hover {
    background-color: #E5E3DE;
    color: black;
}

.btnedit {
    background-color: orange;
    border-radius: 6px;
    border-color: white;
    color: white;
    width: 100px;
}

.btndelete {
    background-color: #FF5733;
    border-radius: 6px;
    border-color: white;
    color: white;
    width: 100px;
}

#head {
    background-color: orange;
    color: white;
    border-radius: 8px;

}

#nav_1 {
    width: 50%;
}
    </style>


    <div class="card-body">
        Welcome, <a style="color:#f08303"><?php echo $fullname_session; ?>!</a>
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <a onclick="logOut()" class="nav-link active" href="logout.php">Logout</a>
            </li>
        </ul>
        <center>
            <nav class="nav nav-pills nav-justified" id="nav_1">
                <a class="nav-item nav-link active" id="dsh" href="#">Dashboard</a>
                &nbsp; &nbsp;
                <a class="nav-item nav-link active" id="dsh" href="formjob.php">Job</a>

            </nav>
        </center>
    </div>
    <!-- <hr> -->

    <!-- <div class="card-body">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
               <h4 style="color:#f08303;">Dashboard</h4>
            </li>
        </ol>
    </nav>
</div> -->

    <div class="navbar navbar-expand-lg navbar-light bg">
        <div class="container-fluid" id="head">

            <div class="collapse navbar-collapse" id="navbarExample01">
                <h3 class="mb-2 mt-2">Dashboard</h3>
            </div>
        </div>
    </div>

    <hr>

    <div class="card-header">
        <div class="card-body">
            <button type="button" id="deleteSelectedBtn" class="btn btn-danger text-white">Delete
            </button><br><br>
            <div class="table-responsive">

                <table id="user_table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th><input type=checkbox id="checkAll"></th>
                            <th>No</th>
                            <th>Registered by</th>
                            <th>Workstream</th>
                            <th>WS Code</th>
                            <th>Developer</th>

                            <th>SVP/Others</th>
                            <th>IRIS</th>
                            <th>Project/System</th>
                            <th>Brief Description</th>
                            <th>Status</th>

                            <th>BE Submission</th>
                            <th>Assessment Completed</th>
                            <th>Target System RFS</th>
                            <th>Revise Target System RFS</th>
                            <th>Actual System RFSI</th>

                            <th>Rollout</th>
                            <th>MOT</th>
                            <th>GIT Internal MD</th>
                            <th>GIT External MD</th>
                            <th>RM GIT Internal</th>

                            <th>RM GIT External +6%</th>
                            <th>GNT Internal MD</th>
                            <th>GNT External MD</th>
                            <th>RM GNT Internal</th>
                            <th>RM GNT External +6%</th>
                            <th>Remark</th>

                            <th>Last Update Timestamp</th>
                            <th>Last Update By Time</th>
                            <th>Last Update By User</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.5.1.js"></script>
    <script src="js/dataTables.bootstrap4.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <!-- <script src="js/jquery.dataTables.js"></script> -->





    <?php
    include 'dbc.php';
    

	$query = "SELECT * FROM user ORDER BY sysid Asc";
	$result = mysqli_query($conn,$query);

?>


    <script>
$(document).ready(function() {
    var datatable1 = $('#user_table').DataTable({
        data: data,

        columnDefs: [{
            checkboxes: {
                selectRow: true,
                selectAllPages: true
            },

        }]


    });

    $("#checkAll").click(function() {
        $("input[type=checkbox]").prop("checked", $(this).prop("checked"));
    });

    $("input[type=checkbox]").click(function() {
        if (!$(this).prop("checked")) {
            $("#checkAll").prop("checked", false);
        }
    });


    // init load page, hide delete button
    $("#deleteSelectedBtn").hide();



});
var data = [];
<?php 

$count=0;

while($row = mysqli_fetch_array($result))

{ $count++;

?>

data.push([
    "<input type=checkbox name=no onchange=handleChange(this); value=\"<?=$row["sysid"];?>\"/>",
    "<?php echo $count; ?>",
    "<?php echo $row["registered_by"];?>",
    "<?php echo $row["workstream"];?>",
    "<?php echo $row["ws_code"];?>",
    "<?php echo $row["developer"];?>",

    "<?php echo $row["svp_others"];?>",
    "<?php echo $row["iris"];?>",
    "<?php echo $row["project_system"];?>",
    "<?php echo $row["brief_description"];?>",
    "<?php echo $row["status"];?>",

    "<?php echo $row["be_submission"];?>",
    "<?php echo $row["assessment_completed"];?>",
    "<?php echo $row["target_systemrfs"];?>",
    "<?php echo $row["revise_target_systemrfs"];?>",
    "<?php echo $row["actual_systemrfs"];?>",

    "<?php echo $row["rollout"];?>",
    "<?php echo $row["mot"];?>",
    "<?php echo $row["git_internalmd"];?>",
    "<?php echo $row["git_externalmd"];?>",
    "<?php echo $row["rm_git_internal"];?>",

    "<?php echo $row["rm_git_external"];?>",
    "<?php echo $row["gnt_internalmd"];?>",
    "<?php echo $row["gnt_externalmd"];?>",
    "<?php echo $row["rm_gnt_internal"];?>",
    "<?php echo $row["rm_gnt_external"];?>",

    "<?php echo $row["remark"];?>",

    "<?php echo $row["last_update_timestamp"];?>",
    "<?php echo $row["last_update_by"];?>",
    "<?php echo $row["last_update_by_user"];?> ",
    "<a href=edituser.php?sysid=<?php echo $row['sysid'];?>><button class=btnedit>Edit</button></a>"

])

<?php } ?>

function logOut() {
    alert("Thank You");
}

var checkDeleteArray = [];

function handleChange(checkbox) {
    if (checkbox.checked == true) {
        // document.getElementById("submit").removeAttribute("disabled");
        checkDeleteArray.push(checkbox.value);
        console.log(checkDeleteArray);
    } else {
        // document.getElementById("submit").setAttribute("disabled", "disabled");
        var index = checkDeleteArray.indexOf(checkbox.value);
        if (index !== -1) {
            checkDeleteArray.splice(index, 1);
        }

        console.log(checkDeleteArray);
    }

    // each time function trigger, will check the array length
    if (checkDeleteArray.length > 0) {
        $("#deleteSelectedBtn").show();
    } else {
        $("#deleteSelectedBtn").hide();
    }


}


$('#deleteSelectedBtn').click(function() {

    if (confirm("Are you sure you want to delete the selected data ?")) {
        console.log(checkDeleteArray);

        $.post('api.php', {
            action: 'delete_selected',
            itemArray: JSON.stringify(checkDeleteArray)
        }, function(data) {
            var apiData = JSON.parse(data);

            if (apiData['message'] == "Successful") {
                location.reload();
            }
        });
    }


});
    </script>

    <!-- Page level plugin JavaScript-->




    </html>