<?php	$page_title = "Profiil";	$page_file = "profiil.php";?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/head.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/header.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/sidebar.php"); ?>

<?php
  $data = $login->getMyData($_SESSION["user_session"]);
?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 <!-- Content Header (Page header) -->
 <section class="content-header">
   <h1>
     Profiil
     <small>Konto andmete muutmine</small>
   </h1>
 </section>
 <!-- Main content -->
 <section class="content">
 <div class="row">

   <?php if(isset($_SESSION['error_msg'])): ?>
     <div class="col-sm-12">
       <div class="alert alert-warning alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <h4><i class="icon fa fa-warning"></i> Tähelepanu!</h4>
         <?=$_SESSION['error_msg'];?>
         <?php
         if(count($error) > 0) {
           for($i = 0; $i < count($error); $i++) {
             if($i == count($error) - 1) {
               echo $error[$i];
             } else {
               echo $error[$i] . ", ";
             }
           }
         }
         ?>
       </div>
     </div>
   <?php $_SESSION['msg_seen'] = true; endif; ?>

   <?php if(isset($_SESSION['success_msg'])): ?>
     <div class="col-sm-12">
       <div class="alert alert-success alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <h4><i class="icon fa fa-check"></i> Salvestatud!</h4>
         <?=$_SESSION['success_msg'];?>
       </div>
     </div>
   <?php $_SESSION['msg_seen'] = true; endif; ?>


      <div class="col-sm-3">

        <div class="box box-danger">
            <div class="box-body box-profile">

              <h3 class="profile-username text-center"><?=$data['firstname'];?> <?=$data['lastname'];?></h3>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Kasutajanimi</b> <a class="pull-right"><?=$data['user_name'];?></a>
                </li>
                <li class="list-group-item">
                  <b>Email</b> <a class="pull-right"><?=$data['user_email'];?></a>
                </li>
              </ul>

            </div>
            <!-- /.box-body -->
          </div>

      </div>

      <div class="col-sm-9">

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Muuda</a></li>
            <!--<li><a href="#tab_2" data-toggle="tab">???</a></li>-->
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">

              <div class="row">
                <div class="col-sm-12">

                  <form method="post">

                    <div class="form-group">
                      <label>Eesnimi *</label>
                      <input class="form-control" name="firstname" value="<?=$data['firstname'];?>">
                    </div>

                    <div class="form-group">
                      <label>Perekonnanimi *</label>
                      <input class="form-control" name="lastname" value="<?=$data['lastname'];?>">
                    </div>

                    <div class="form-group">
                      <label>Email *</label>
                      <input class="form-control" type="email" name="email" value="<?=$data['user_email'];?>">
                    </div>

                    <div class="form-group">
                      <label>Uus parool</label>
                      <input class="form-control" type="password" name="password">
                    </div>

                    <button class="btn btn-success pull-right" type="submit" name="edit_profile">Salvesta</button>

                  </form>

                </div>
              </div>

            </div>
            <div class="tab-pane" id="tab_2">???</div>

          </div><!-- /.tab-content -->
        </div>

      </div>





  	   </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>

  <?php
    unset($_SESSION['success_msg']);
    unset($_SESSION['error_msg']);
  ?>

  <?php
  if(isset($_SESSION['upload_id'])) {
    unset($_SESSION['upload_id']);
  }

  ?>
