<?php
	$page_title = "Mänguväljakud";
	$page_file = "valjakud.php";
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/head.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/header.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Mänguväljakud
            <small>Mänguväljakute ülevaade</small>
          </h1>

          <ol class="breadcrumb">
            <li>
            	<button class="btn btn-box-tool" data-toggle="modal" data-target="#add_playground"><span class="label label-success font-ok">Lisa</span></button>
            </li>
						<li>
							<button class="btn btn-box-tool" data-toggle="modal" data-target="#add_area"><span class="label label-success font-ok">Lisa piirkond</span></button>
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

            <div class="col-sm-6">

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
                  <label for="select-1">Vali Piirkond</label>
                  <?php $Playgrounds->fillAreaSelect(); ?>
        		        <label for="select-1">Vali mänguväljak</label>
                  <?php $Playgrounds->fillPlaySelect(); ?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Põhiandmed</h3>
                  <div class="box-tools pull-right">
                    <!-- Buttons, labels, and many other things can be placed here! -->
                    <!-- Here is a label for example -->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">

                  <div class="col-sm-6">
                    <p><strong>Aadress:</strong> <span id="address_value"></span></p>
                    <p><strong>Kontaktisik:</strong> <span id="contact_value"></span></p>
                    <p><strong>Telefon:</strong> <span id="phone_value"></span></p>
                  </div>

                </div><!-- /.box-body -->
                <div class="box-footer">
                  <button id="edit_playground" class="btn btn-box-tool pull-right" data-toggle="modal" data-target="#pg_modal" disabled><i class="fa fa-pencil"></i></button>

                </div><!-- box-footer -->
              </div><!-- /.box -->

              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Atraktsioonid</h3>
                  <div class="box-tools pull-right">
                    <!-- Buttons, labels, and many other things can be placed here! -->
                    <!-- Here is a label for example -->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <ul id="attractions_value" style="padding: 0">
                  </ul>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div>

            <div class="col-sm-6">

              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Pilt</h3>
                  <div class="box-tools pull-right">
                    <!-- Buttons, labels, and many other things can be placed here! -->
                    <!-- Here is a label for example -->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <a id="playground-link" data-toggle="modal" data-target="#picture_modal">
                    <img id="playground-img" src="<?=$_SERVER['BASE_PATH'];?>/images/no-img.jpg" class="img-responsive" onerror="this.src='<?=$_SERVER['BASE_PATH'];?>/images/no-img.jpg'">
                  </a>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Hooldusraportid</h3>
                  <div class="box-tools pull-right">
                    <!-- Buttons, labels, and many other things can be placed here! -->
                    <!-- Here is a label for example -->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <ul id="doc-list" class="doc-list">
                    <!-- js saadab siia andmed -->
                  </ul>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <!--
                  # Dokumentide üleslaadimine
                  # Kui ühele lehele tuleb mitu üleslaadimist siis tuleb ID'si muuta, ID ei tohi korduda!
                  # Samuti tuleb js'i juurde kirjutada ka teisele IDle vastavad tegurid
                  # Kõige lihtsam on ID lõppu lisada number, seda ka js poole pealt
                  -->
                  <form id="doc_form" name="form" action="../inc/upload.php#ready" method="POST" enctype="multipart/form-data" >

                    <div class="input-group">
                      <input type="text" id="attach-newname" name="attach-newname" class="form-control" placeholder="Dokumendi nimi" required disabled>
                      <div class="input-group-btn">
                        <button id="attach-btn" class="btn btn-default" type="button" disabled>
                          <span class="glyphicon glyphicon-paperclip"></span>
                        </button>
                        <button id="add-attach" type="submit" class="btn btn-default" disabled>
                          <span class="glyphicon glyphicon-cloud-upload"></span>
                        </button>
                      </div>
                    </div>

                    <span id="attach-names"></span>
                    <input id="attach-new" type="file" name="my_files[]" multiple>
                    <input id="attach-id" type="hidden" name="attach-id">
                    <input id="attach-what" type="hidden" name="attach-what">
                    <!-- Values määrata dokumendi tüüp (1 - Hooldus, 2 - Tehniline, 3 - Muu)a -->
                    <input id="attach-type" type="hidden" name="attach-type" value="1">


                  </form>
                  <iframe id='my_iframe' name='my_iframe' src="" style="display: none;"></iframe> <!-- Sellel ära muuda midagi -->
                  <!-- Siin lõppeb üleslaadimine -->
                </div><!-- box-footer -->
              </div><!-- /.box -->
            </div>

          </div>



          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->


      <div class="modal fade" id="pg_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Mänguväljakute muutmine</h4>
            </div>
            <div class="modal-body">

              <input class="form-control" type="hidden" id="pg_id">

              <div class="form-group">
                <label for="name">Aadress</label>
                <input class="form-control" type="text" id="pg_address">
              </div>

              <div class="form-group">
                <label for="add_org">Kontaktisik</label>
                <input class="form-control" id="pg_contact">
                  </div>

              <div class="form-group">
                <label for="phone">Telefon</label>
                <input class="form-control" type="text" id="pg_phone">
              </div>

              <div class="form-group">
                <label for="info">Atraktsioonid</label>
                <input class="form-control" type="text" id="pg_attr">
              </div>



            </div>
            <div class="modal-footer">
              <button id="delete_pg" type="button" class="btn btn-danger pull-left"><span class="glyphicon glyphicon-trash"></span></button>
              <button id="close_edit_pg" type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
              <button id="save_new_pg" type="button" class="btn btn-success" id="save_data">Salvesta</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pildi vaatamise modal -->
      <div class="modal fade" id="picture_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <img id="playground-modal-img" src="<?=$_SERVER['BASE_PATH'];?>/images/no-img.jpg" class="img-responsive" onerror="this.src='<?=$_SERVER['BASE_PATH'];?>/images/no-img.jpg'">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
            </div>
          </div>
        </div>
      </div>

			<!-- Mänguväljaku lisamise modal -->
			<div class="modal fade" id="add_playground" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">Lisa mänguväljak</h4>
			      </div>
			      <form method="post" enctype="multipart/form-data">
			      <div class="modal-body">
			        <div class="row">

			            <div class="col-sm-12">
			                  <input type="file" name="fileToUpload" id="fileToUpload">
			            </div>


			            <div class="col-sm-6">
			              <div class="form-group">
			                <label for="select">Vali piirkond</label>
			                <?php $Playgrounds->fillAreaSelectAdmin(); ?>
			              </div>

			              <div class="form-group">
			                <label for="name">Nimi</label>
			                <input class="form-control" type="text" id="name" name="name">
			              </div>

			              <div class="form-group">
			                <label for="address">Aadress</label>
			                <input class="form-control" type="text" id="address" name="address">
			              </div>
			            </div>

			            <div class="col-sm-6">
			              <div class="form-group">
			                <label for="contact">Kontaktisik</label>
			                <input class="form-control" id="contact" name="contact">
			                  </div>

			              <div class="form-group">
			                <label for="number">Telefon</label>
			                <input class="form-control" type="text" id="number" name="number">
			              </div>

			              <div class="form-group">
			                <label for="attractions">Atraktsioonid</label>
			                <input class="form-control" type="text" id="attractions" name="attractions">
			              </div>
			            </div>

			        </div>

			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
			        <button type="submit" name="submit_playground" class="btn btn-primary">Lisa</button>
			      </div>
			    </form>
			    </div>
			  </div>
			</div>

			<!-- Lisa piirkond -->
			<div class="modal fade" id="add_area" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Lisa piirkond</h4>
						</div>
						<form method="post" enctype="multipart/form-data">
						<div class="modal-body">
							<div class="row">
								<div class="col-sm-12">

									<div class="form-group">
										<label for="name">Nimi</label>
										<input class="form-control" type="text" id="name" name="name">
									</div>

								</div>

							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
							<button type="submit" name="submit_area" class="btn btn-primary">Lisa</button>
						</div>
					</form>
					</div>
				</div>
			</div>


<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>


<script>
var objectid = <?=$_SESSION['upload_id'];?>;

document.querySelector("#play_select").value = objectid;


</script>

<?php
  unset($_SESSION['success_msg']);
  unset($_SESSION['error_msg']);
?>

<?php
if(isset($_SESSION['upload_id'])) {
  unset($_SESSION['upload_id']);
}

?>
