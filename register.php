<?php

    include './database/connection.php';
    $connect = connection();
    $message = '';

    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $avater = $_FILES['avater'];
        // print_r($avater);
        $image = $avater['name'];
        $tmp_name = $avater['tmp_name'];
        $size = $avater['size'];
        $format = explode('.', $image);
        $actualName = strtolower($format[0]);
        $actualFormat = strtolower($format[1]);
        $allowFormat = ['jpg', 'jpeg', 'png', 'gif'];

        if($password === $confirm_password){

            if(in_array($actualFormat, $allowFormat)){

                $location = 'Users/'.$actualName.'.'.$actualFormat;

                $sql = "INSERT INTO user_info(name, username, email, password, avater) VALUES('$name', '$username', '$email', '$password', '$location')";

                if($connect->query($sql)===true){
                    header('Location: login.php');
                    move_uploaded_file($tmp_name, $location);
                }else{
                    $message = "Connection Not Establish";
                }

            }
        }

        else{
            $message = 'Password is not matching';
        }
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register Page</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-2">Create an Account!</h1>
                                <p class="text-danger font-weight-bold">
                                    <?php
                                        if($message!=''){
                                            echo $message;
                                        }
                                    ?>
                                </p>
                            </div>
                            <form method="POST" action="register.php" enctype="multipart/form-data">
                                <div class="form-group mb-4">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                                </div>
                                <div class="form-group mb-4">      
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                                </div>
                                <div class="form-group mb-4">
                                    
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                                </div>
                                <div class="form-group mb-4">
                                    
                                    <input type="password" class="form-control"
                                    id="password" name="password" placeholder="Password"> 
                                </div>
                                <div class="form-group mb-4">
                                    
                                    <input type="password" class="form-control"
                                    id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                                </div>
                                <div class="form-group mb-4">                               
                                    <input type="file" name="avater" class="form-control"  id="avater" required>                                                
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-primary btn-user btn-block mb-4">
                                        Register
                                    </button>
                                </div>
                                
                            </form>
                            <hr>
                            
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>