    <?php

            include('login.php'); // Includes Login Script
            if(isset($_SESSION['login_user'])){
            header("location: dashboard.php"); // Redirecting To Profile Page
            }

    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>BE Tracker</title>


        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/Login_page.css">


        <script type="text/javascript">
        window.history.forward();

        function noBack() {
            window.history.forward();
        }
        </script>

        <?php
        if(isset($_SESSION['login_msg']) && $_SESSION['login_msg'] != ""){
            echo '<script> alert("' . $_SESSION['login_msg'] . '"); </script>';
           //$_SESSION['login_msg'] = "";
        }
    ?>

    </head>

    <style>
body {
    background: linear-gradient(to right, #fff 18%, #f08303 50%);
}

.wrap-login::before,
.wrap-login::after {
    display: block;
    content: "";
    clear: both;
}

.responsive {
    width: 100%;
    height: auto;
}

#btnlgn:hover {
    color: white;
    background-color: orange;
}
    </style>

    <body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">

        <div class="container">
            <div style="width:50%; height:700px; position:relative; margin:0px auto; margin-top:20%;">
                 <div class="text-center">
                 <img src="img/logo.png">
                    <!-- <h2 style="font-family: Arial, Helvetica, sans-serif; color:white">BE Tracking</h2> -->
                </div>
                <br>
                <div>
                    <!-- <div style="width:50%; position:absolute; left:50%; top:50%; transform: translate(-50%, 50%)"> -->
                    <div class="wrap-login col-lg-12">
                        <h3 class="text-center">Business Enhancement Tracking</h3>
                        <br>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="email">Staff ID</label>
                                <input name="staff_id" type="text" class="form-control" id="email"
                                    placeholder="Username"
                                    value="<?php if(isset($_POST['submit'])){echo $_POST['staff_id']; } ?>">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input name="password" type="password" class="form-control" id="myInput"
                                    placeholder="Password"
                                    value="<?php if(isset($_POST['submit'])){echo $_POST['password']; } ?>">
                                &nbsp<input type="checkbox" onclick="myFunction()">&nbsp <a style="color:black">Show
                                    Password</a>
                            </div>
                            <input id="btnlgn" type="submit" class="btn btn-block" value="Login" name="submit"></input>
                        </form>
                        <!-- <center style="margin-top:25px;"><a href="index.php">Back to main</a></center> -->
                    </div>
                </div>

            </div>
        </div>

        <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
        </script> -->
        <script src="js/jquery-3.2.1.slim.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/popper.min.js"></script>
        <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous">
        </script> -->

        <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })

        function myFunction() {
            var x = document.getElementById("myInput");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
        </script>

    </body>


    <div class="footer-copyright text-center py-3">© 2022 Copyright:
        <a href="">Telekom Malaysia</a>
    </div>
    <br>

    </html>