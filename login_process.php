<?php
error_reporting(E_ERROR | E_PARSE);
//require_once 'db_conn.php';
session_start();

if (isset($_POST['submit'])) {

include 'dbc.php';

$login  = $_POST["username"];
$pass   = $_POST["password"];


$ldap_host  = "10.45.236.28";
$ldap_port  = 389;
$base_dn    = "DC=tm,DC=my";
$filter     = "(sAMAccountName=$login)";
$ldap_con   = ldap_connect("10.45.236.28");
$ldap_dn    = "cn=unifibuddyldapadmin,ou=serviceAccount,o=Telekom";
$ldap_password = "QfmU8B5X";

if(ldap_bind($ldap_con, $ldap_dn, $ldap_password)) 
{
  ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
  ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);


  if(ldap_bind($ldap_con, "cn=$login,ou=users,o=data", $pass)) 
  {
    $filter  = "cn=$login";
    $result  = ldap_search($ldap_con,"ou=users,o=data", $filter) or exit("Unable to search");
    $entries = ldap_get_entries($ldap_con, $result);

//    $_SESSION['name']    = ($entries[0]['ppdisplayname'][0]);
    $_SESSION['name']    = str_replace("'", "",($entries[0]['ppdisplayname'][0]));
    $_SESSION['username'] = strtoupper($login);


//echo $_SESSION['username'];

if (empty($_POST['username']) || empty($_POST['password'])) {
    $error = "Staff ID or Password invalid";
}


if(isset($_SESSION['name'])=="")
{

     // SQL query to fetch information of registerd users and finds user match.
     $query = "SELECT username, password, fullname from login where username=? AND password=? LIMIT 1";


     // To protect MySQL injection for Security purpose
     $stmt = $conn->prepare($query);
     $stmt->bind_param("ss", $username, $password);
     $stmt->execute();
     $stmt->bind_result($username, $password, $fullname);
     $stmt->store_result();

     
     if($stmt->num_rows > 0){
             $f_stmt = $stmt->fetch();
             $_SESSION['login_user'] = $username; // Initializing Session

             $_SESSION['login_msg'] = 'Welcome ' . $fullname . ' to BE Tracker Page!';
             echo '<script> windows.location="dashboard.php";></script>';
             // header("Location: index.php"); // Redirecting To Profile Page
     }else{
             $_SESSION['login_msg'] = "Staff ID or Password invalid!";
             //echo "<scriptalert('".$_SESSION['login_msg']."');></script>";
             echo '<script> windows.location="index.php";></script>';
             // header("Location: loginUser.php");
              }


die();

}

else
{
    exit();
//echo '<meta http-equiv="refresh" content="0; url=index2.php?msg=1" />';
}





}
}

}