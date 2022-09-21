<?php
	
if (isset($_POST['submit'])) {
	
	include_once 'dbc.php';

	$registered_by = mysqli_real_escape_string($conn, $_POST['registered_by']);
	$workstream = mysqli_real_escape_string($conn, $_POST['workstream']);
	$ws_code = mysqli_real_escape_string($conn, $_POST['ws_code']);
	// $chk= mysqli_real_escape_string($conn, $_POST['chk']);
	$svp_others = mysqli_real_escape_string($conn, $_POST['svp_others']);


	$iris = mysqli_real_escape_string($conn, $_POST['iris']);
	$project_system = mysqli_real_escape_string($conn, $_POST['project_system']);
	$brief_description = mysqli_real_escape_string($conn, $_POST['brief_description']);
	$status = mysqli_real_escape_string($conn, $_POST['status']);
	$be_submission = mysqli_real_escape_string($conn, $_POST['be_submission']);


	$assessment_completed = mysqli_real_escape_string($conn, $_POST['assessment_completed']);
	$target_systemrfs = mysqli_real_escape_string($conn, $_POST['target_systemrfs']);
	$revise_target_systemrfs = mysqli_real_escape_string($conn, $_POST['revise_target_systemrfs']);
	$actual_systemrfs = mysqli_real_escape_string($conn, $_POST['actual_systemrfs']);
	$rollout = mysqli_real_escape_string($conn, $_POST['rollout']);

    $mot = mysqli_real_escape_string($conn, $_POST['mot']);
	$git_internalmd = mysqli_real_escape_string($conn, $_POST['git_internalmd']);
	$rm_git_internal = mysqli_real_escape_string($conn, $_POST['rm_git_internal']);
	$git_externalmd = mysqli_real_escape_string($conn, $_POST['git_externalmd']);
	$rm_git_external = mysqli_real_escape_string($conn, $_POST['rm_git_external']);

    $gnt_internalmd = mysqli_real_escape_string($conn, $_POST['gnt_internalmd']);
	$rm_gnt_internal = mysqli_real_escape_string($conn, $_POST['rm_gnt_internal']);
	$gnt_externalmd = mysqli_real_escape_string($conn, $_POST['gnt_externalmd']);
	$rm_gnt_external = mysqli_real_escape_string($conn, $_POST['rm_gnt_external']);
	$remark = mysqli_real_escape_string($conn, $_POST['remark']);



    $checkbox1=$_POST['check'];  
	$chk="";  

    foreach ($checkbox1 as $chk1){  
        $chk .= $chk1.",";  

        }

        			$sql="INSERT INTO user
							(registered_by,workstream,ws_code,developer,svp_others,iris,project_system,brief_description,status,be_submission,assessment_completed,target_systemrfs,revise_target_systemrfs,actual_systemrfs,rollout,mot,git_internalmd,rm_git_internal,git_externalmd,rm_git_external,gnt_internalmd,rm_gnt_internal,gnt_externalmd,rm_gnt_external,remark) 
							VALUES 
							('$registered_by','$workstream','$ws_code','$chk','$svp_others','$iris','$project_system','$brief_description','$status','$be_submission','$assessment_completed','$target_systemrfs','$revise_target_systemrfs','$actual_systemrfs','$rollout','$mot','$git_internalmd','$rm_git_internal','$git_externalmd','$rm_git_external','$gnt_internalmd','$rm_gnt_internal','$gnt_externalmd','$rm_gnt_external','$remark')"; 



            if (mysqli_query($conn, $sql)) {
                            echo '<script>alert("Done!")</script>';
							echo "<script>window.location='dashboard.php';</script>";
            } 
            else {
                echo "Error: " . $sql . "" . mysqli_error($conn);
            }

            mysqli_close($conn);


        
        

				// if ($resultCheck > 1) {
				// 	header("Location: formjob.php?formjob=usertaken");
				// 	exit();
				// } 
				// else{

				// 		$sql=mysqli_query($conn,"INSERT INTO user
				// 			(registered_by,workstream,ws_code,developer,svp_others,iris,project_system,brief_description,status,be_submission,assessment_completed,target_systemrfs,revise_target_systemrfs,actual_systemrfs,rollout,mot,git_internalmd,rm_git_internal,git_externalmd,rm_git_external,gnt_internalmd,rm_gnt_internal,gnt_externalmd,rm_gnt_external) 
				// 			VALUES 
				// 			('$registered_by','$workstream','$ws_code,'$chk','$svp_others','$iris','$project_system','$brief_description','$status','$be_submission','$assessment_completed','$target_systemrfs','$revise_target_systemrfs','$actual_systemrfs','$rollout','$mot','$git_internalmd','$rm_git_internal','$git_externalmd','$rm_git_external','$gnt_internalmd','$rm_gnt_internal','$gnt_externalmd','$rm_gnt_external')"); 

                         
                          
                //             echo '<script>alert("Done!")</script>';
				// 			echo "<script>window.location='dashboard.php';</script>";


						
				// 			exit;
				// }
			
			
}


?>