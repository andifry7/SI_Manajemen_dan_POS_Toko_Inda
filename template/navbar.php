<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <div class="overlay">
        <i class="i fas fa-2x fa-spinner fa-spin"></i>
    </div>
  </div>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-info navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <div class="form-group nav-item ml-3">
        <div class="custom-control custom-switch custom-switch-on-success nav-link">
            <input type="checkbox" class="custom-control-input" id="cekDark">
            <label for="cekDark" class="custom-control-label">Mode Gelap</label>
        </div>
      </div>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle"
            data-toggle="dropdown">
                <?= userLogin()['username'] ?><i class="fas fa-user-cog ml-2"></i>
            </a>
            <div class="dropdown-menu dropdown-menu dropdown-menu-right">
                <a href="<?= $main_url ?>auth/change-password.php" class="dropdown-item text-right">
                    Ganti Password <i class="fas fa-key"></i>
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= $main_url ?>auth/logout.php" class="dropdown-item text-right">
                    Logout <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </li>
    </ul>
  </nav>
  <!-- /.navbar -->