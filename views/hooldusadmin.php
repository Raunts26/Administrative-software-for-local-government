<?php	$page_title = "Hoolduspäeviku täitmine";
	$page_file = "hooldusadmin.php";
?><?php require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/head.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/header.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Hoolduspäeviku täitmine
            <small>Objektide hoolduspäevikute täitmine</small>
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="row">

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
                      <div class="form-group">
                        <label>Vali vorm</label>
                        <?=$Objects->formSelect();?>
                      </div>
                    </div>

                  </div>
                </div><!-- /.box-body -->
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
                      Vali eelnevalt objekt ja vormi tüüp!
                    </div>

                  </div>
                </div><!-- /.box-body -->
								<div class="box-footer">
									<button id="add_new_row" class="btn btn-primary" style="display: none;">+</button>
									<button id="save_maintance" class="btn btn-success pull-right">Salvesta</button>
								</div>
              </div><!-- /.box -->

            </div>


          </div>



          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->



<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>