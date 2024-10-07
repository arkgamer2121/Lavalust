<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Register</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../public/vendors/feather/feather.css">
  <link rel="stylesheet" href="../public/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../public/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../public/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../public/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <h4 class="text-center">New here?</h4>
              <h6 class="font-weight-light text-center">Signing up is easy. It only takes a few steps</h6>            
              <?php $LAVA =& lava_instance(); ?>
              <?php if (isset($error_message)) { ?>
                 <div class="alert alert-danger text-center" id="message-container"><?php echo $error_message; ?></div>
                <?php } ?>
              <?php echo $LAVA->form_validation->errors(); ?>         
             <form action="<?= site_url('validate_reg'); ?> " method="post">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" name="name" placeholder="Username" value="" size="50">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control form-control-lg" name="email" placeholder="Email" value="" size="50">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" value="" size="50" >
                </div>
                <div class="form-group">
                    <input type="password" class="form-control form-control-lg" name="confpassword" placeholder="Confirm Password" value="" size="50">
                </div>
                <div class="mt-3 d-flex justify-content-center">
                <div><input type="submit" value="Sign Up" class="btn btn-primary" /></div>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Already have an account? <a href="login" class="text-primary">Login</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    setTimeout(function() {
        document.getElementById('message-container').style.display = 'none';
    }, 2000); 
</script>
  <script src="../../vendors/js/vendor.bundle.base.js"></script>
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/settings.js"></script>
  <script src="../../js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>
