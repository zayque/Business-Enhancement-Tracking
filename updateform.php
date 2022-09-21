<?php
	    
	require 'dbc.php';

		//user

		$sysid = $_POST['sysid'];
		$registered_by = $_POST['registered_by'];
		$workstream = $_POST['workstream'];
		$ws_code = $_POST['ws_code'];
		$developer = $_POST['developer'];

		$svp_others = $_POST['svp_others'];
		$iris = $_POST['iris'];
		$project_system = $_POST['project_system'];
		$brief_description = $_POST['brief_description'];
		$status = $_POST['status'];

		$be_submission = $_POST['be_submission'];
		$assessment_completed = $_POST['assessment_completed'];
		$target_systemrfs = $_POST['target_systemrfs'];
		$revise_target_systemrfs = $_POST['revise_target_systemrfs'];
		$actual_systemrfs = $_POST['actual_systemrfs'];

		$rollout = $_POST['rollout'];
		$mot = $_POST['mot'];
		$git_internalmd = $_POST['git_internalmd'];
		$git_externalmd = $_POST['git_externalmd'];
		$rm_git_internal = $_POST['rm_git_internal'];

		$rm_git_external = $_POST['rm_git_external'];
		$gnt_internalmd = $_POST['gnt_internalmd'];
		$gnt_externalmd = $_POST['gnt_externalmd'];
		$rm_gnt_internal = $_POST['rm_gnt_internal'];
		$rm_gnt_external = $_POST['rm_gnt_external'];

		$remark = $_POST['remark'];
		$last_update_by_user = $_POST['last_update_by_user'];



	$query = "UPDATE user SET
				sysid= '$sysid',
				registered_by = '$registered_by',
				workstream = '$workstream',
				ws_code = '$ws_code',
				developer = '$developer',

				svp_others= '$svp_others',
				iris = '$iris',
				project_system = '$project_system',
				brief_description = '$brief_description',
				status = '$status',

				be_submission= '$be_submission',
				assessment_completed = '$assessment_completed',
				target_systemrfs = '$target_systemrfs',
				revise_target_systemrfs = '$revise_target_systemrfs',
				actual_systemrfs = '$actual_systemrfs',

				rollout= '$rollout',
				mot = '$mot',
				git_internalmd = '$git_internalmd',
				git_externalmd = '$git_externalmd',
				rm_git_internal = '$rm_git_internal',

				rm_git_external= '$rm_git_external',
				gnt_internalmd = '$gnt_internalmd',
				gnt_externalmd = '$gnt_externalmd',
				rm_gnt_internal = '$rm_gnt_internal',
				rm_gnt_external = '$rm_gnt_external',

				remark = '$remark',
				last_update_by_user = '$last_update_by_user'

				WHERE sysid = '$sysid'";

	mysqli_query($conn,$query) or die(mysqli_error($conn));

	// echo "Successfully!";
	// echo "<a href='dashboard.php'>Back to Dashboard</a>";

	echo '<script>alert("Successfully!")</script>';
	echo "<script>window.location='dashboard.php';</script>";
?>