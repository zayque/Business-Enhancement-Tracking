<?php

	include('dbc.php');
    include('session.php');

	$sysid= $_GET['sysid'];
	$sql = "SELECT * FROM user WHERE sysid ='$sysid'";
	$cari = mysqli_query($conn,$sql) or die(mysqli_error($conn,$sql));
    $row = mysqli_fetch_array($cari);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Edit</title>
    <link rel="icon" type="image/ico" href="" />

    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.v5.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">

</head>

<br>

<div class="card">
    <div class="card-body">

        <form action="updateform.php" method="post">

            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control-plaintext" id="" value="<?php echo $row['sysid'];?>"
                        name="sysid">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Registered By</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['registered_by'];?>"
                        name="registered_by">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Workstream</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="" value="<?php echo $row['workstream'];?>"
                        name="workstream">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">WS Code</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="" value="<?php echo $row['ws_code'];?>"
                        name="ws_code">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Developer</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['developer'];?>"
                        name="developer">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">SVP/Others</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['svp_others'];?>"
                        name="svp_others">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Iris</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="" value="<?php echo $row['iris'];?>"
                        name="iris">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Project/System</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="" value="<?php echo $row['project_system'];?>"
                        name="project_system">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Brief Description</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="" value="<?php echo $row['brief_description'];?>"
                        name="brief_description">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['status'];?>"
                        name="status">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">BE Submission</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['be_submission'];?>"
                        name="be_submission">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Assessment Completion</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['assessment_completed'];?>"
                        name="assessment_completed">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Target System RFS</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['target_systemrfs'];?>"
                        name="target_systemrfs">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Revise Target System RFS</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['revise_target_systemrfs'];?>"
                        name="revise_target_systemrfs">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Actual System RFSI</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['actual_systemrfs'];?>"
                        name="actual_systemrfs">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Target Rollout Date</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['rollout'];?>"
                        name="rollout">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Target Rollout Date</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['rollout'];?>"
                        name="rollout">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">MOT</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="" value="<?php echo $row['mot'];?>"
                        name="mot">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">GIT Internal MD</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['git_internalmd'];?>"
                        name="git_internalmd">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">GIT External MD</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['git_externalmd'];?>"
                        name="git_externalmd">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">RM GIT Internal</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['rm_git_internal'];?>"
                        name="rm_git_internal">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">RM GIT External</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['rm_git_external'];?>"
                        name="rm_git_external">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">GNT Internal MD</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['gnt_internalmd'];?>"
                        name="gnt_internalmd">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">GNT Internal MD</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['gnt_internalmd'];?>"
                        name="gnt_internalmd">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">GNT External MD</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['gnt_externalmd'];?>"
                        name="gnt_externalmd">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">RM GNT Internal</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['rm_gnt_internal'];?>"
                        name="rm_gnt_internal">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">RM GNT External</label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control" id="" value="<?php echo $row['rm_gnt_external'];?>"
                        name="rm_gnt_external">
                </div>
            </div>
            <br>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Remark</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="" value="<?php echo $row['remark'];?>"
                        name="remark">
                </div>
            </div>
            <br>
            <input type="hidden" value="<?php echo $fullname_session?>" name="last_update_by_user">
            <a><input type="submit" class="btn btn-success" value="Submit"></a>
            <a href="dashboard.php" class="btn btn-primary">Back</a>
        </form>

    </div>
</div>

<html>

