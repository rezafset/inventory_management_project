<?php 
    session_start();
    $_SESSION['user']='';
    $_SESSION['userid']='';
    $_SESSION['avater']='';
    include('./database/connection.php');
    $connect = connection();
    $message = '';

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM user_info WHERE username = '$username' AND password = '$password'";
        $result = $connect->query($sql);

        if(mysqli_num_rows($result) == 1){
            $user = mysqli_fetch_assoc($result);

            $id = $user['id'];
            $sql = "UPDATE user_info SET last_login= current_timestamp() WHERE id='$id'";
            $connect->query($sql);
            $_SESSION['userid'] = $user['id'];
            $_SESSION['user'] = $user['name'];
            $_SESSION['avater'] = $user['avater'];

            header('Location: dashboard.php');
        }else{
            $message = "Credentials Mismatch";
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
                                <h1 class="h4 text-gray-900 mb-2">Login Here</h1>
                                <p class="text-danger font-weight-bold">
                                    <?php
                                        if($message!=''){
                                            echo $message;
                                        } 
                                    ?>
                                </p>
                            </div>
                            <form method="POST" class="">

                                <div class="form-group mb-3">
                                    <label for="username" class="col-form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                                </div>

                                <div class="form-group mb-3">  
                                    <label for="password" class="col-form-label">Password</label>
                                    <input type="password" class="form-control"
                                    id="password" name="password" placeholder="Password"> 
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" id="customCheck">
                                        <label class="custom-control-label" for="customCheck">Remember
                                            Me</label>
                                    </div>
                                </div>
                               
                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-primary btn-user btn-block mb-4">
                                        Login
                                    </button>
                                </div>
                                
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="register.php">Create an Account!</a>
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