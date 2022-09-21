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
    <link href="style.css" type="text/css" rel="stylesheet" />
    <!-- Custom styles for dialog box-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">

</head>


<style>
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
    background-color: silver;
}

.btnedit {
    background-color: orange;
    border-radius: 6px;
    border-color: white;
    color: black;
}

.btndelete {
    background-color: #FF5733;
    border-radius: 6px;
    border-color: white;
    color: black;
}
</style>


<div class="card-body">
    Welcome, <a style="color:#f08303">Akmal!</a>
    <ul class="nav justify-content-end">
        <li class="nav-item">
            <a class="nav-link active" href="#">Logout</a>
        </li>
    </ul>

    <nav class="nav nav-pills nav-justified">
        <a class="nav-item nav-link active" id="dsh" href="#">Dashboard</a>
        <a class="nav-item nav-link active" id="dsh" href="#">Job</a>

    </nav>
</div>
<hr>
<div class="card-body">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <h4 style="color:#f08303">Dashboard</h4>
            </li>
        </ol>
    </nav>
</div>


<div class="card-header">
    <div class="card-body">
        <div class="table-responsive">

            <table id="user_table" class="display" style="width:100%">
                <thead>
                    <tr>
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

                        <th>Last Update Timestamp</th>
                        <th>Last Update By</th>
                        <th>Action</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<script src="js/jquery-3.5.1.js"></script>
<script src="js/jquery.dataTables.min.js"></script>

<?php
    include 'dbc.php';
    

	$query = "SELECT * FROM login ORDER BY sysid Asc";
	$result = mysqli_query($conn,$query);

?>


<script>
$(document).ready(function() {
    $('#user_table').DataTable({
        data: data
    });
});
var data = [];
<?php while($row = mysqli_fetch_array($result)){?>

data.push([
    "1",
    "Megat",
    "1a",
    "4.9",
    "GNT",

    "SVP10",
    "C34251",
    "SPHERE",
    "Handover Module in SPHERE",
    "Assessment",

    "2/22/2022",
    "3/28/2022",
    "6/30/2022",
    "1/28/2022",
    "5/28/2022",

    "2/30/2022",
    "MOT11",
    "62",
    "0",
    "49600",

    "0",
    "210",
    "749",
    "168000",
    "990146",

    "4/20/2022",
    "5/20/2022",
    "<a href=edittest.php?sysid=<?php echo $row['sysid'];?>><button class=btnedit>Edit</button></a> <a href=deletetest.php?sysid=<?php echo $row['sysid'];?> onClick=return confirm('Anda pasti ?');><button class=btndelete>Delete</button></a>"

])

<?php } ?>
</script>

<!-- Page level plugin JavaScript-->
<script src="js/jquery.dataTables.js"></script>
<script src="js/dataTables.bootstrap4.js"></script>

</html>