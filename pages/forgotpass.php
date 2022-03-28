<?php
include '../includes/dbconfig.php';

if(isset($_POST['email'])) {
    $email = $_POST['email'];

    $auth = $firebase->createAuth();

    try {
        $auth->sendPasswordResetLink($email);
        echo '
            <script>alert("Password Reset link has been sent! Please kindly check your inbox/spam for the email");
            window.location = "sign-in.php"</script>
        ';
        exit();
    } catch (Kreait\Firebase\Auth\SendActionLink\FailedToSendActionLink $e) {
        echo '
            <script>alert("Email address not found! Please double check if the email is correct");</script>
        ';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Reset Password
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
</head>

<body class="bg-gray-200">
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('../assets/img/UPANG.jpg');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom bg-gradient-faded-light">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-login shadow-primary border-radius-lg py-3 pe-1">  
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Reset Password</h4>
                  <div class="row ">
                    <p class="text-white font-weight-bolder text-center mt-2 mb-0">Enter your Email.</p>
                  </div>
                </div>
              </div>
              <div class="card-body bg-gradient-faded-light">
                <form action="forgotpass.php" method="POST" class="text-start">
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                  </div>
                  <div class="text-center fs-6"> <a href="sign-in.html">Back to Login.</a> </div>
                  <div class="text-center">
                    <!-- <button onclick="window.location='dashboard.html'" type="button" class="btn bg-gradient-secondary w-100 my-4 mb-2">Log in</button> -->
                    <button type="submit" name="submit" class="btn login-btn text-white w-100 my-4 mb-2">Reset</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div>
          <img src="../assets\img\favicon.png" class="logo-login">
        </div>
      </div>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    const icon = document.querySelector("#password-icon");

    togglePassword.addEventListener("click", function () {
      // toggle the type attribute
      const type = password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", type);

      // toggle the icon
      icon.classList.toggle("fa-eye");
      icon.classList.toggle("fa-eye-slash");
      // prevent form submit
      password.focus();
      // const form = document.querySelector("form");
      // form.addEventListener('submit', function (e) {
      //   e.preventDefault();
      // });
    });
  </script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.0.0"></script>
</body>

</html>