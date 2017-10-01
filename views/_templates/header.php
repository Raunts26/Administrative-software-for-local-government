<!-- Main Header -->
<header class="main-header">

  <!-- Logo -->
  <a href="<?=$_SERVER['BASE_PATH'];?>/index.php" class="logo" style="background-color: #d2d6de;">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <!--<span class="logo-mini"><b>Rae</b></span>-->
    <span class="logo-mini"><img src="<?=$_SERVER['BASE_PATH'];?>/images/raevapp.png" style="height: 40px;"></span>
    <!-- logo for regular state and mobile devices -->
    <!--<span class="logo-lg"><b>Rae</b> haldustarkvara</span>-->
    <span class="logo-lg"><img src="<?=$_SERVER['BASE_PATH'];?>/images/logo.png" style="height: 65px; margin-top: -10px;"></span>

  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
          <!-- Menu Toggle Button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!-- The user image in the navbar-->
            <!--<img src="<?=$_SERVER['BASE_PATH'];?>/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span><?=$_SESSION['user_name'];?>&nbsp;<i class="fa fa-angle-down"></i></span>
          </a>



          <!--


          <ul class="dropdown-menu sidebar-menu" style="background-color: #222d32;">
          <li><a style="color: #b8c7ce;" href="<?=$_SERVER['BASE_PATH'];?>/views/profiil.php"><i class="fa fa-user"></i> <span>Profiil</span></a></li>
          <li><a style="color: #b8c7ce;" href="?logout"><i class="fa fa-sign-out"></i> <span>Logi välja</span></a></li>
        </ul>

          -->

          <ul class="dropdown-menu">
            <!-- The user image in the menu -->
            <li class="user-header">
              <!--<img src="<?=$_SERVER['BASE_PATH'];?>/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
              <p>
                <?=$_SESSION['user_name'];?>
                <small></small>
              </p>
            </li>

            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <a href="<?=$_SERVER['BASE_PATH'];?>/views/profiil.php" class="btn btn-default btn-flat">Profiil</a>
              </div>
              <div class="pull-right">
                <a href="?logout" class="btn btn-default btn-flat">Logi välja</a>
              </div>
            </li>
          </ul>

        </li>

      </ul>
    </div>
  </nav>
</header>
