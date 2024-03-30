<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Ztrack</title>
  <link rel="shortcut icon" type="image/png" href="<?php echo base_url; ?>Assets/admin/images/logos/log.png" />
  <link rel="stylesheet" href="<?php echo base_url; ?>Assets/admin/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="<?php echo base_url; ?>Assets/admin/images/logos/log.png" width="180" alt="">
                </a>
                <p class="text-center">Bienvenidos al Sistema</p>
                <form autocomplete="off" id="frmLogin" onsubmit="frmLogin(event);">
                  <div class=" mb-3">
                    <label for="usuario" class="form-label">Usuario</label>

                    <input type="text" class="form-control" id="usuario" name="usuario" aria-describedby="emailHelp" autofocus required>
                  </div>
                  <div class="mb-4">
                    <label for="clave" class="form-label">Contraseña</label>

                    <input type="password" class="form-control" id="clave" name="clave" required>
                  </div>
                  <div class="form-group btn-container">
                    <button class="btn btn-success w-100 py-8 fs-4 mb-4 rounded-2" type="submit"><i class="fa fa-sign-in fa-lg fa-fw"></i>Login</button>
                </div>


                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url; ?>Assets/admin/js/jquery.min.js"></script>
  <script src="<?php echo base_url; ?>Assets/admin/js/bootstrap.bundle.min.js"></script>
  <script>
        const base_url = '<?php echo base_url; ?>';
       console.log(base_url);
    </script>
  <script src="<?php echo base_url ; ?>Assets/js/LoginPage.js "></script>
</body>
</html>