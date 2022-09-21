<?php
        session_start(); // Starting Session
        $error = ''; // Variable To Store Error Message

        if (isset($_POST['submit'])) {

        if (empty($_POST['staff_id']) || empty($_POST['password'])) {
        $error = "Staff ID or Password invalid";

        }

        else{

        // Define $username and $password
        $staff_id = $_POST['staff_id'];
        $password = $_POST['password'];
        $fullname;

        // mysqli_connect() function opens a new connection to the MySQL server.
        $conn = mysqli_connect("localhost", "root", "", "be_tracking");

        // SQL query to fetch information of registerd users and finds user match.
        $query = "SELECT staff_id, password, fullname from login where staff_id=? AND password=? LIMIT 1";


        // To protect MySQL injection for Security purpose
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $staff_id, $password);
        $stmt->execute();
        $stmt->bind_result($staff_id, $password, $fullname);
        $stmt->store_result();

        
        if($stmt->num_rows > 0){
                $f_stmt = $stmt->fetch();
                $_SESSION['login_user'] = $staff_id; // Initializing Session

                $_SESSION['login_msg'] = 'Welcome ' . $fullname . ' to BE Tracker Page!';
                echo '<script> windows.location="dashboard.php";></script>';
                // header("Location: index.php"); // Redirecting To Profile Page
        }else{
                $_SESSION['login_msg'] = "Staff ID or Password invalid!";
                //echo "<scriptalert('".$_SESSION['login_msg']."');></script>";
                echo '<script> windows.location="index.php";></script>';
                // header("Location: loginUser.php");
                 }
                }
        }
?>