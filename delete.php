<?php
	include('dbc.php');
	$sysid = $_GET['sysid'];
	$query = "DELETE FROM user where sysid = '$sysid'";
	$result = mysqli_query($conn,$query) or die(mysqli_error($conn,$query));

	echo $sysid;

	if($result==TRUE) echo '<script>alert("Successfully!")</script>';
	echo "<script>window.location='dashboard.php';</script>";
	if($result==FALSE) echo "Fail";
?>

<a href="dashboard.php">Back</a>