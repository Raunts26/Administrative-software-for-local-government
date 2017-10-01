<?php	$page_title = "Hoolduspäevik";	$page_file = "hooldus.php";?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/head.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/header.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/sidebar.php"); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Hooldustööd
      <small>Objektide hooldustööd</small>
    </h1>

    <ol class="breadcrumb">
      <li>
        <button id="form_add_btn" class="btn btn-box-tool" data-toggle="modal" data-target="#maintance_add"><span class="label label-success font-ok">Täida</span></button>
        <button id="form_edit_btn" class="btn btn-box-tool" data-toggle="modal" data-target="#maintance_edit" disabled><span class="label label-success font-ok">Muuda</span></button>
      </li>
    </ol>

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
          <div class="box-header with-border">
            <h3 class="box-title">Valik</h3>
            <div class="box-tools pull-right">
              <!-- Buttons, labels, and many other things can be placed here! -->
              <!-- Here is a label for example -->
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box-tools -->
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Vali objekt</label>
                  <?=$Objects->fillSelect();?>
                </div>
                <div id="only_maintance_data" class="form-group">
                  <label>Vali vorm</label>
                  <?=$Objects->fantomFormSelect();?>
                  <?=$Objects->formSelect();?>
                </div>
                <div id="maintance_categories" class="form-group" style="display: none;">
                  <label>Vali kategooria</label>
                  <?=$Objects->fantomTechnicalSelect();?>
                </div>

              </div>

            </div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title">Arhiiv</h3>
            <div class="box-tools pull-right">
              <!-- Buttons, labels, and many other things can be placed here! -->
              <!-- Here is a label for example -->
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box-tools -->
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12">
                <table class="table table-striped table-hover">
                  <tbody id="maintance_archive">

                  </tbody>
                </table>
              </div>

            </div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title">Dokumendid</h3>
            <div class="box-tools pull-right">
              <!-- Buttons, labels, and many other things can be placed here! -->
              <!-- Here is a label for example -->
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box-tools -->
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12">
                <ul id="doc-list" class="doc-list"></ul>
              </div>

            </div>
          </div><!-- /.box-body -->
          <div class="box-footer">
            <!--
            # Dokumentide üleslaadimine
            # Kui ühele lehele tuleb mitu üleslaadimist siis tuleb ID'si muuta, ID ei tohi korduda!
            # Samuti tuleb js'i juurde kirjutada ka teisele IDle vastavad tegurid
            # Kõige lihtsam on ID lõppu lisada number, seda ka js poole pealt
            -->
            <form id="doc_form" name="form" action="../inc/uploadmaintance.php" method="POST" enctype="multipart/form-data" >

              <div class="input-group">
                <input type="text" id="attach-newname" name="attach-newname" class="form-control" placeholder="Dokumendi nimi">
                <div class="input-group-btn">
                  <button id="attach-btn" class="btn btn-default" type="button">
                    <span class="glyphicon glyphicon-paperclip"></span>
                  </button>
                  <button id="add-attach-" type="submit" class="btn btn-default">
                    <span class="glyphicon glyphicon-cloud-upload"></span>
                  </button>
                </div>
              </div>

              <span id="attach-names"></span>
              <input id="attach-new" type="file" name="my_files[]" multiple>
              <input id="attach-id" type="hidden" name="attach-id">


            </form>
            <!--<iframe id='my_iframe' name='my_iframe' src="" style="display: none;"></iframe> <!-- Sellel ära muuda midagi -->
            <!-- Siin lõppeb üleslaadimine -->
          </div>
        </div><!-- /.box -->

      </div>

      <div class="col-sm-9">

        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title">Vorm</h3>
            <div class="box-tools pull-right">
              <!-- Buttons, labels, and many other things can be placed here! -->
              <!-- Here is a label for example -->
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

            </div><!-- /.box-tools -->
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="row">

              <div id="form_inner" class="col-sm-12">
                Vali arhiivist vorm või täida uus!
              </div>


            </div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

      </div>


    </div>

    <!-- Your Page Content Here -->

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->



<!-- Täitmise modal -->
<div id="maintance_add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="maintance_edit">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Hoolduse lisamine <span id="maintance_add_name"></span></h4>
      </div>
      <div class="modal-body">
        <div class="row">

          <input type="hidden" id="maintance_add_id">

          <div id="form_inner_add" class="col-sm-12">
            Vali eelnevalt objekt ja vormi tüüp!
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button id="add_new_row_add" class="btn btn-primary pull-left" style="display: none;">+</button>
        <button id="close_maintance_add" type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
        <button id="save_maintance_add" type="button" class="btn btn-success">Salvesta</button>
      </div>
    </div>
  </div>
</div>

<!-- Muutmise modal -->
<div id="maintance_edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="maintance_edit">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Hoolduse <span id="edit_name"></span> muutmine</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <input type="hidden" id="maintance_edit_id">
          <div id="edit_inner" class="col-sm-12">

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="delete_maintance" type="button" class="btn btn-danger pull-left"><i class="fa fa-trash"></i></button>
        <button id="add_new_row" class="btn btn-primary pull-left" style="display: none;">+</button>

        <button id="close_maintance_edit" type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
        <button id="save_maintance_edit" type="button" class="btn btn-success">Salvesta</button>
      </div>
    </div>
  </div>
</div>

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
