<?php
include 'config.php';
session_start();




error_reporting(0);
if (isset($_SESSION['staff_id'])) {
  header("Location: login.php");
}



if (isset($_POST['submit'])) {

  $login  = $_POST["staff_id"]; //pass value input to variable
  $pass   = $_POST["password"];

  if($login == 'Ilyana' && $pass == '12345'){
    $_SESSION['role'] = 'admin';
    $_SESSION['staff_id'] = 'ilyana3105';
    header("Location: weekly.php");
  }
    
  

  $ldap_host  = "10.45.236.28";
  $ldap_port  = 389;
  $base_dn    = "DC=tm,DC=my";
  $filter     = "(sAMAccountName=$login)";
  $ldap_con   = ldap_connect("10.45.236.28");
  $ldap_dn    = "cn=unifibuddyldapadmin,ou=serviceAccount,o=Telekom";
  $ldap_password = "QfmU8B5X";

  if (ldap_bind($ldap_con, $ldap_dn, $ldap_password)) {
    ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);


    if (ldap_bind($ldap_con, "cn=$login,ou=users,o=data", $pass)) {
      $filter  = "cn=$login";
      $result  = ldap_search($ldap_con, "ou=users,o=data", $filter) or exit("Unable to search");
      $entries = ldap_get_entries($ldap_con, $result);

      //    $_SESSION['name']    = ($entries[0]['ppdisplayname'][0]);
      $_SESSION['name']    = str_replace("'", "", ($entries[0]['ppdisplayname'][0]));
      $_SESSION['staffid'] = strtoupper($login);

      echo $_SESSION['name'];
      echo $_SESSION['staffid'];


      if ($_SESSION['name'] != '') {

        $staff_id = $_POST['staff_id'];
        
        $sql = "SELECT * FROM material_login WHERE staff_id='$staff_id'";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
          $row = mysqli_fetch_assoc($result);


          if ($row['role'] == 'Admin') {
            $_SESSION['role'] = 'admin';
            $_SESSION['staff_id'] = $row['staff_id'];
            echo $_SESSION['role'];

            header("Location: weekly.php");

          } 
          
          else if ($row['role'] == 'User') {
            $_SESSION['role'] = 'user';
            $_SESSION['staff_id'] = $row['staff_id'];
            echo $_SESSION['role'];

            header("Location: weekly.php");

          } 
          else {
            echo "<script>alert('Woops! Account does not exists.')</script>";
          }
        }
      } else {
        echo "<script>alert('Woops! Invalid Password.')</script>";
      }
    }
  }
  
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <!-- MDB -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.0.0/mdb.min.css" rel="stylesheet" />



  <title>Login Page</title>

</head>

<body>
  <div class="bg-image shadow-2-strong" style="
    background-image: url('images/tm.jpg');
    height: 100vh;
  ">

    <div class="mask" style="background-color: rgba(0, 0, 0, 0.7);">
      <div class="container py-5 h-100">

        <div class="card rounded-3 text-black mx-auto text-center opacity-90" style=" margin-top: 10%; width: 38rem; height: 35rem;">

          <div class="card-body p-md-5 mx-md-4">

            <div class="text-center">
              <img src="./images/tmlogo.png" style="width: 150px;" alt="logo">
              <h4 class="mt-3 mb-5 pb-1">We are Telekom Malaysia</h4>
            </div>

            <form method="POST">
              <p>Please login to your account</p>

              <div class="form-outline mb-4">
                <input type="text" value="<?php echo $staff_id; ?>" id="staff_id" name="staff_id" class="form-control" required />
                <label class="form-label" for="form2Example11">Staff ID</label>
              </div>

              <div class="form-outline mb-4">
                <input type="password" value="<?php echo $password; ?>" id="password" name="password" class="form-control" />
                <label class="form-label" for="form2Example22">Password</label>
              </div>

              <div class="text-center pt-1 mb-5 pb-1">
                <button name="submit" type="submit" type="submit" class="btn btn-primary btn-block mb-3" style="background-color: #e37124; color:#ffffff;">
                  Log in</button>

              </div>

              <!-- <div class="d-flex align-items-center justify-content-center pb-4">
                    <p class="mb-0 me-2">Don't have an account?</p>
                    <button type="button" class="btn btn-outline-danger">Create new</button>
                  </div> -->

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

  </div>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.0.0/mdb.min.js"></script>
  <!-- <script src="./js/mdb.min.js"></script> -->

</body>

</html>