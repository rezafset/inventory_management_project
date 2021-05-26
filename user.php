<?php  
    session_start();
    include ('./database/connection.php');
    $connect = connection();
    $userid = $_SESSION['userid'];
    $user = $_SESSION['user'];
    $profile_pic = $_SESSION['avater'];
    $message = '';

    $sql = "SELECT * FROM user_info";
    $user_infomation = $connect->query($sql);

    $sql = "SELECT COUNT(*) AS total_product FROM product_info";
    $total_product = mysqli_fetch_assoc($connect->query($sql));
    $sql = "SELECT SUM(bought) AS total_bought FROM product_info";
    $total_bought = mysqli_fetch_assoc($connect->query($sql));
    $sql = "SELECT SUM(sold) AS total_sold FROM product_info";
    $total_sold = mysqli_fetch_assoc($connect->query($sql));
    $total_stock = $total_bought['total_bought'] - $total_sold['total_sold'];
    // Update user
    $user_sql = "SELECT * FROM user_info WHERE id ='$userid'";
    $currentUser = mysqli_fetch_assoc($connect->query($user_sql));

    if (isset($_POST['submit'])) {
        if ($currentUser['password'] == $_POST['password']) {
            $user_update_sql = "UPDATE user_info SET ";
            // Name Portion
            if (isset($_POST['name'])) {
                $name = $_POST['name'];
                if ($name != $currentUser['name']) {
                    $user_update_sql .="name = '$name',";
                }
            }
            // Email Portion
            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                if ($email != $currentUser['email']) {
                    $user_update_sql .="email = '$email',";
                }
            }
            // Avater portion
            if (isset($_FILES['avater'])) {
                $image = $_FILES['avater'];
                $image_name = $image['name'];
                $tmp_name = $image['tmp_name'];
                $size = $image['size'];
                if($size<2000000){
                    $format = explode('.', $image_name);
                    $actualName = strtolower($format[0]);
                    $actualFormat = strtolower($format[1]);
                    $allowFormat = ['jpg', 'jpeg', 'png', 'gif'];
                    $location = 'Users/'.$actualName.'.'.$actualFormat;

                    if ($actualFormat=='jpg' || $actualFormat=='jpeg') {
                        $img = imagecreatefromjpeg($tmp_name);
                        $imgResize = imagescale($img, 1000,1000);
                        imagejpeg($imgResize, $location, -1);
                    }elseif ($actualFormat=='png') {
                        $img = imagecreatefrompng($tmp_name);
                        $imgResize = imagescale($img, 1000,1000);
                        imagepng($imgResize, $location, -1);
                    }elseif ($actualFormat=='gif') {
                        $img = imagecreatefromgif($tmp_name);
                        $imgResize = imagescale($img, 1000,1000);
                        imagegif($imgResize, $location, -1);
                    }

                    $user_update_sql .="avater = '$location',";
                }
                else{
                        $message = "<div class='alert alert-light text-light font-weight-bold bg-danger show' role='alert'>
                            Image size must be less than 3MB!!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>";

                }
            }
            // New Password && Confirm Password Portion

            if (isset($_POST['new_password']) && $_POST['new_password']!='' && isset($_POST['confirm_password']) && $_POST['confirm_password']!='') {
                if ($_POST['new_password'] == $_POST['confirm_password']) {
                    $new_password = $_POST['new_password'];
                    if ($new_password != $currentUser['password']) {
                        $user_update_sql .="password = '$new_password',";
                    }
                }
            }
            $user_update_sql = substr($user_update_sql, 0, -1);
            $user_update_sql .="WHERE id ='$userid'";
            $connect->query($user_update_sql);
            $message = "<div class='alert alert-light text-light font-weight-bold bg-success show' role='alert'>
                User Information Updated Successfully!!
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>";

        }else{
            $message = "<div class='alert alert-light text-light font-weight-bold bg-danger show' role='alert'>
                Credentials mismatch!!
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>";
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

    <title>Product Page</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <img class="img-profile rounded-circle" height="55px;" width="55px;" src="<?php echo $profile_pic ?>">
                <div class="sidebar-brand-text mx-3">IMS</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="product.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Product</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="user.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>User</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="customer.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Customer</span>
                </a>
            </li>
            

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <h1 class="h3 mb-0 text-gray-800">User Page</h1>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?php echo $user ?> </span>
                                <img class="img-profile rounded-circle"
                                src="<?php echo $profile_pic ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="userProfile.php?id=<?php echo $userid; ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <!-- <div class="dropdown-divider"></div> -->
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Total Product -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-light text-uppercase mb-1">
                                                <span>Total Product</span>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-light"><?php echo $total_product['total_product']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fab fa-product-hunt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buy Product -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-light text-uppercase mb-1">
                                                Buy Product
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-light"><?php echo $total_bought['total_bought']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fab fa-product-hunt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sold product -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-light text-uppercase mb-1">Sold Product
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-light"><?php echo $total_sold['total_sold']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fab fa-product-hunt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Available Stock-->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-light text-uppercase mb-1">
                                            Available Stock</div>
                                            <div class="h5 mb-0 font-weight-bold text-light"><?php echo $total_stock; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fab fa-product-hunt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Add New Product -->
                    <div class="text-center mb-2">
                        <button type="button" class="btn btn-primary text-center" data-toggle="modal" data-target="#product"><i class="far fa-user"></i> Update User</button>
                    </div>
                    <div class="modal fade" id="product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mb-2" id="exampleModalLabel">
                                    Update <?php echo $currentUser['name'] ?>'s Information</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form method="POST" action="user.php" enctype="multipart/form-data">
                                        <!-- Name -->
                                        <div class="form-group mb-0">
                                            <label for="name" class="col-form-label">Name</label>
                                            <input type="text" name="name" value="<?php echo $currentUser['name'] ?>" class="form-control" id="name" required>
                                        </div>
                                        <!-- Email -->
                                        <div class="form-group mb-0">
                                            <label for="email" class="col-form-label">Email</label>
                                            <input type="text" name="email" value="<?php echo $currentUser['email'] ?>" class="form-control" id="email" required>
                                        </div>
                                        <!-- User Avater -->
                                        <div class="form-group mb-0">    
                                            <label for="avater" class="col-form-label">User Avater</label>
                                        
                                            <input name="avater"  class="form-control" type="file" id="avater"> 
                                        </div>
                                        
                                        <!-- Password -->
                                        <div class="form-group mb-0">
                                            <label for="password" class="col-form-label">Password</label>
                                            <input type="text" name="password" class="form-control" id="password" required>
                                        </div>

                                        <!-- New Password -->
                                        <div class="form-group mb-0">
                                            <label for="new_password" class="col-form-label">New Password</label>
                                            <input type="text" name="new_password" class="form-control" id="new_password">
                                        </div>
                                        <!-- New Password -->
                                        <div class="form-group">
                                            <label for="confirm_password" class="col-form-label">Confirm Password</label>
                                            <input type="text" name="confirm_password" class="form-control" id="confirm_password">
                                        </div>

                                        <div class="form-group mb-0 text-center">
                                            <button type="submit" value="submit" name="submit" class="btn btn-success w-50">Update</button>
                                        </div>

                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
            

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800 ">All User List</h1>  
                    <?php
                        if($message!=''){
                            echo $message;
                        }
                    ?>       
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Is Active</th>
                                            <th>Role</th>
                                            <th>Last Login Time</th>
                                            <th>Image</th>
                                            <?php 
                                                if($currentUser['is_admin']==='admin'){
                                                    echo "<th>Action</th>";
                                                }
                                            
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        
                                            if (mysqli_num_rows($user_infomation)>0) {
                                                while ($row = mysqli_fetch_assoc($user_infomation)) {
                                                    echo '<tr>';
                                                        echo '<td>'.$row['name'].'</td>';
                                                        echo '<td>'.$row['email'].'</td>';
                                                        echo '<td>'.$row['is_active'].'</td>';
                                                        echo '<td>'.$row['is_admin'].'</td>';
                                                        echo '<td>'.date("F j, Y, g:i a", strtotime($row['last_login'])).'</td>';
                                                        echo "<td><img src='$row[avater]' class='img-fluid' style='height: 70px;' alt=''></td>";

                                                        if($currentUser['is_admin']==='admin'){
                                                            echo '<td>'; 
                                                            echo " <a href='updateUser.php?id=".$row['id']."' class='btn btn-info btn-sm'><i class='fas fa-edit'></i></a>"; 

                                                            echo " <a href='deleteUser.php?id=".$row['id']."' class='btn btn-danger btn-sm'><i class='far fa-trash-alt'></i></a>";
                                                            echo '</td>';
                                                        }

                                                    echo '</tr>';
                                                }
                                            }
                                        
                                        ?>                                     
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white shadow">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
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

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>