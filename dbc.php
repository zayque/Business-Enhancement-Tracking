<?Php
/////// Update your database login details here /////
$dbhost_name = "localhost"; // Your host name 
$database = "be_tracking";       // Your database name
$username = "root";            // Your login userid 
$password = "";            // Your password 
//////// End of database details of your server //////

//////// Do not Edit below /////////
$conn = mysqli_connect($dbhost_name, $username, $password, $database);

if (!$conn) {
    echo "Error: Unable to connect to MySQL.<br>";
    echo "<br>Debugging errno: " . mysqli_connect_errno();
    echo "<br>Debugging error: " . mysqli_connect_error();
    exit;
}
?> 