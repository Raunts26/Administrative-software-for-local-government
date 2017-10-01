<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel (optional) -->


    <!-- search form (Optional) --
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
        </span>
      </div>
    </form>
    <!-- /.search form -->

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
      <!--<li class="header">Menüü</li>-->
      <!-- Optionally, you can add icons to the links -->

      <?php if($page_file === "index.php"):?>
        <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/index.php"><i class="fa fa-desktop"></i> <span>Töölaud</span></a></li>
      <?php else:?>
        <li><a href="<?=$_SERVER['BASE_PATH'];?>/index.php"><i class="fa fa-desktop"></i> <span>Töölaud</span></a></li>
      <?php endif; ?>







      <?php if($_SESSION['user_group'] !== "1" && $_SESSION['user_group'] !== "2"): ?>

        <?php if($page_file === "objektid.php"):?>
          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/objektid.php"><i class="fa fa-university"></i><span>Hoonepass</span></a></li>
        <?php else:?>
          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/objektid.php"><i class="fa fa-university"></i><span>Hoonepass</span></a></li>
        <?php endif; ?>

      <?php endif; ?>


        <?php if($page_file === "hooldus.php" || $page_file === "hooldusadmin.php" || $page_file === "hooldajad.php" ):?>


          <li class="treeview active">
            <a href="#"><i class="fa fa-folder-open"></i> <span>Hoolduskorraldus</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
            <?php if($page_file === "hooldusadmin.php"): ?>

              <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldusadmin.php"><i class="fa fa-file-text"></i><span>Täitmine</span></a></li>
              <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldus.php"><i class="fa fa-file-text-o"></i><span>Hooldustööd</span></a></li>
              <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldajad.php"><i class="fa fa-pencil-square-o"></i><span>Hoolduslepingud</span></a></li>

            <?php elseif($page_file === "hooldus.php"): ?>
              <!--<li><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldusadmin.php"><i class="fa fa-file-text"></i><span>Täitmine</span></a></li>-->
              <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldus.php"><i class="fa fa-file-text-o"></i><span>Hooldustööd</span></a></li>
              <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldajad.php"><i class="fa fa-pencil-square-o"></i><span>Hoolduslepingud</span></a></li>

    			  <?php elseif($page_file === "hooldajad.php"): ?>

              <?php if($_SESSION['user_group'] !== "1" && $_SESSION['user_group'] !== "2"): ?>

      			    <!--<li><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldusadmin.php"><i class="fa fa-file-text"></i><span>Täitmine</span></a></li>-->
                <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldus.php"><i class="fa fa-file-text-o"></i><span>Hooldustööd</span></a></li>

              <?php endif; ?>
                <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldajad.php"><i class="fa fa-pencil-square-o"></i><span>Hoolduslepingud</span></a></li>

            <?php endif; ?>

            </ul>
          </li>


        <?php else:?>


          <li class="treeview">
            <a href="#"><i class="fa fa-folder-open"></i> <span>Hoolduskorraldus</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">

              <?php if($_SESSION['user_group'] !== "1" && $_SESSION['user_group'] !== "2"): ?>

                <!--<li><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldusadmin.php"><i class="fa fa-file-text"></i><span>Täitmine</span></a></li>-->
                <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldus.php"><i class="fa fa-file-text-o"></i><span>Hooldustööd</span></a></li>

              <?php endif; ?>

              <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/hooldajad.php"><i class="fa fa-pencil-square-o"></i><span>Hoolduslepingud</span></a></li>

            </ul>
          </li>


        <?php endif; ?>

        <?php if($_SESSION['user_group'] !== "1" && $_SESSION['user_group'] !== "2"): ?>


        <?php if($page_file === "ulesanded.php"):?>
          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/ulesanded.php"><i class="fa fa-calendar-check-o"></i> <span>Haldusülesanded</span></a></li>
        <?php else:?>
          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/ulesanded.php"><i class="fa fa-calendar-check-o"></i> <span>Haldusülesanded</span></a></li>
        <?php endif; ?>


		<?php if($page_file === "it_support.php"):?>
          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/it_support.php"><i class="fa fa-desktop"></i> <span>IT-Kasutajatugi</span></a></li>
        <?php else:?>
          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/it_support.php"><i class="fa fa-desktop"></i> <span>IT-Kasutajatugi</span></a></li>
		<?php endif; ?>


		<?php if($page_file === "kalender.php"):?>          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/kalender.php"><i class="fa  fa-calendar-o"></i> <span>Kalender</span></a></li>        <?php else:?>          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/kalender.php"><i class="fa fa-calendar-o"></i> <span>Kalender</span></a></li>        <?php endif; ?>

        <?php if($page_file === "pinnad.php" || $page_file === "aripinnad.php"):?>
          <li class="treeview active">
            <a href="#"><i class="fa fa-building"></i> <span>Üüripinnad</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
            <?php if($page_file === "pinnad.php"): ?>
              <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/pinnad.php"><i class="fa fa-building-o"></i><span>Munitsipaalkorterid</span></a></li>
              <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/aripinnad.php"><i class="fa fa-hospital-o"></i><span>Äripinnad</span></a></li>
            <?php else: ?>
              <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/pinnad.php"><i class="fa fa-building-o"></i><span>Munitsipaalkorterid</span></a></li>
              <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/aripinnad.php"><i class="fa fa-hospital-o"></i><span>Äripinnad</span></a></li>
            <?php endif; ?>
            </ul>
          </li>
        <?php else:?>
          <li class="treeview">
            <a href="#"><i class="fa fa-building"></i> <span>Üüripinnad</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/pinnad.php"><i class="fa fa-building-o"></i><span>Munitsipaalkorterid</span></a></li>
              <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/aripinnad.php"><i class="fa fa-hospital-o"></i><span>Äripinnad</span></a></li>
            </ul>
          </li>
        <?php endif; ?>



        <?php if($page_file === "valjakud.php"):?>
          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/valjakud.php"><i class="fa fa-child"></i> <span>Mänguväljakud</span></a></li>
        <?php else:?>
          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/valjakud.php"><i class="fa fa-child"></i> <span>Mänguväljakud</span></a></li>
        <?php endif; ?>

      <?php endif; ?>


      <?php if($_SESSION['user_group'] !== "1"): ?>

        <?php if($page_file === "kasutajad.php" || $page_file === "mobile.php" || $page_file === "logiraamat.php"):?>
          <li class="treeview active">
            <a href="#"><i class="fa fa-group"></i> <span>Personal</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">

            <?php if($page_file === "kasutajad.php"): ?>
              <?php if($_SESSION['user_group'] === "4"): ?>
                <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/kasutajad.php"><i class="fa fa-user-plus"></i><span>Kasutajad</span></a></li>
              <?php endif; ?>

              <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/mobile.php"><i class="fa fa-phone"></i><span>Mobiilside</span></a></li>

              <?php if($_SESSION['user_group'] === "4"): ?>
                <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/logiraamat.php"><i class="fa fa-book"></i><span>Logiraamat</span></a></li>
              <?php endif; ?>


            <?php elseif($page_file === "mobile.php"): ?>

              <?php if($_SESSION['user_group'] === "4"): ?>
                <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/kasutajad.php"><i class="fa fa-user-plus"></i><span>Kasutajad</span></a></li>
              <?php endif; ?>

              <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/mobile.php"><i class="fa fa-phone"></i><span>Mobiilside</span></a></li>

              <?php if($_SESSION['user_group'] === "4"): ?>
                <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/logiraamat.php"><i class="fa fa-book"></i><span>Logiraamat</span></a></li>

            <?php endif; ?>

          <?php elseif($page_file === "logiraamat.php"): ?>

            <?php if($_SESSION['user_group'] === "4"): ?>
              <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/kasutajad.php"><i class="fa fa-user-plus"></i><span>Kasutajad</span></a></li>
            <?php endif; ?>

            <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/mobile.php"><i class="fa fa-phone"></i><span>Mobiilside</span></a></li>

            <?php if($_SESSION['user_group'] === "4"): ?>
              <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/logiraamat.php"><i class="fa fa-book"></i><span>Logiraamat</span></a></li>

          <?php endif; ?>



            <?php endif; ?>
            </ul>
          </li>
        <?php else:?>
          <li class="treeview">
            <a href="#"><i class="fa fa-group"></i> <span>Personal</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <?php if($_SESSION['user_group'] === "4"): ?>
                <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/kasutajad.php"><i class="fa fa-user-plus"></i><span>Kasutajad</span></a></li>
              <?php endif; ?>

              <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/mobile.php"><i class="fa fa-phone"></i><span>Mobiilside</span></a></li>
              <?php if($_SESSION['user_group'] === "4"): ?>
                <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/logiraamat.php"><i class="fa fa-book"></i><span>Logiraamat</span></a></li>
              <?php endif; ?>

            </ul>
          </li>
        <?php endif; ?>

      <?php endif; ?>


      <?php if($_SESSION['user_group'] !== "1" && $_SESSION['user_group'] !== "2"): ?>


	  <?php if($page_file === "majandamiskulud.php"):?>
          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/majandamiskulud.php"><i class="fa fa-pie-chart"></i> <span>Majandamiskulud</span></a></li>
        <?php else:?>
          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/majandamiskulud.php"><i class="fa fa-pie-chart"></i> <span>Majandamiskulud</span></a></li>
		<?php endif; ?>

        <?php if($page_file === "aruanded.php"):?>
          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/aruanded.php"><i class="fa fa-file-pdf-o"></i> <span>Aruanded</span></a></li>
        <?php else:?>
          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/aruanded.php"><i class="fa fa-file-pdf-o"></i> <span>Aruanded</span></a></li>
        <?php endif; ?>



      <?php endif; ?>

      <?php if($_SESSION['user_group'] === "1"): ?>

        <?php if($page_file === "ulesanded.php"):?>
          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/ulesanded.php"><i class="fa fa-calendar-check-o"></i> <span>Tööülesanded</span></a></li>
        <?php else:?>
          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/ulesanded.php"><i class="fa fa-calendar-check-o"></i> <span>Tööülesanded</span></a></li>
        <?php endif; ?>						<?php if($page_file === "kalender.php"):?>          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/kalender.php"><i class="fa  fa-calendar"></i> <span>Kalender</span></a></li>        <?php else:?>          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/kalender.php"><i class="fa  fa-calendar"></i> <span>Kalender</span></a></li>        <?php endif; ?>


		<?php if($page_file === "it_support.php"):?>
          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/it_support.php"><i class="fa fa-desktop"></i> <span>IT-Kasutajatugi</span></a></li>
        <?php else:?>
          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/it_support.php"><i class="fa fa-desktop"></i> <span>IT-Kasutajatugi</span></a></li>
		<?php endif; ?>


		<?php if($page_file === "majandamiskulud.php"):?>
          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/majandamiskulud.php"><i class="fa fa-pie-chart"></i> <span>Majandamiskulud</span></a></li>
        <?php else:?>
          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/majandamiskulud.php"><i class="fa fa-pie-chart"></i> <span>Majandamiskulud</span></a></li>
		<?php endif; ?>


        <?php if($page_file === "minuasutus.php"):?>
          <li class="active"><a href="<?=$_SERVER['BASE_PATH'];?>/views/minuasutus.php"><i class="fa fa-building-o"></i> <span>Minu asutus</span></a></li>
        <?php else:?>
          <li><a href="<?=$_SERVER['BASE_PATH'];?>/views/minuasutus.php"><i class="fa fa-building-o"></i> <span>Minu asutus</span></a></li>
        <?php endif; ?>

      <?php endif; ?>

      <li><a href="?logout"><i class="fa fa-sign-out"></i> <span>Logi välja</span></a></li>







    </ul><!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>
