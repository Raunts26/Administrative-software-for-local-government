<?php
	$page_title = "Üüripinnad";
	$page_file = "pinnad.php";
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
            Üüripinnad
            <small>Kinnisvarade ülevaade</small>
          </h1>

					<ol class="breadcrumb">
            <li>
            	<button class="btn btn-box-tool" data-toggle="modal" data-target="#add_property"><span class="label label-success font-ok">Lisa üüripind</span></button>
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

                  <label for="select-1">Filter</label><br>

                  <ul id="property-filter" style="list-style: none; padding-left: 0px;">
                    <li data-id="ending-filter" class="filter-type">
                      <input type="checkbox" class="minimal-red" id="ending-fil"> Lõppevad (leping lõppeb käesoleval aastal)</a>
                    </li>
                    <li data-id="sale-filter" class="filter-type">
                      <input type="checkbox" class="minimal-red" id="sale-fil"> Müügis</a>
                    </li>
                    <li data-id="free-filter" class="filter-type">
                      <input type="checkbox" class="minimal-red" id="free-fil"> Vabad</a>
                    </li>
                  </ul>

                    <!--<input type="checkbox" class="minimal-red"> Lõppevad<br>
                    <input type="checkbox" class="minimal-red"> Müügis<br>
                    <input type="checkbox" class="minimal-red"> Vabad<br>-->


                  <label for="select-1">Vali objekt</label>
                  <?php $Properties->fillPropertiesSelect(); ?>

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

                  <p><strong>Aadress:</strong> <span id="address_value"></span></p>
                  <p><strong>Tube:</strong> <span id="rooms_value"></span></p>
                  <p><strong>Pindala:</strong> <span id="space_value"></span></p>
                  <p><strong>Üüri hind(m2):</strong> <span id="m2_value"></span></p>
                  <p><strong>Koefitsent:</strong> <span id="koef_value"></span></p>
                  <p><strong>Üürihind:</strong> <span id="price_value"></span></p>
									<p><strong>Seisukord:</strong> <span id="condition_value"></span></p>
									<p><strong>Lisainfo:</strong> <span id="info_value"></span></p>

                </div><!-- /.box-body -->
                <div class="box-footer">
                  <button id="edit_property" class="btn btn-box-tool pull-right" data-toggle="modal" data-target="#myModal" disabled><i class="fa fa-pencil"></i></button>

                </div><!-- box-footer -->
              </div><!-- /.box -->

            </div>

            <div class="col-sm-6">

							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Üürniku info</h3>
									<div class="box-tools pull-right">
										<!-- Buttons, labels, and many other things can be placed here! -->
										<!-- Here is a label for example -->
										<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /.box-tools -->
								</div><!-- /.box-header -->
								<div class="box-body">
									<div class="row">

										<div class="col-sm-6">

											<p><strong>Nimi:</strong> <span id="name_rent"></span></p>
											<p><strong>Isikukood:</strong> <span id="id_rent"></span></p>
											<p><strong>Telefon:</strong> <span id="number_rent"></span></p>
											<p><strong>E-Mail:</strong> <span id="mail_rent"></span></p>
											<p><strong>Sissekirjutus:</strong> <span id="real_rent"></span></p>

										</div>
										<div class="col-sm-6">
											<p><strong>Lepingu number:</strong> <span id="contract_rent"></span></p>
											<p><strong>Viide DHSile:</strong> <span id="dhs_rent"></span></p>
											<p><strong>Lepingu tähtaeg:</strong> <span id="deadline_rent"></span></p>
										</div>

									</div>
								</div><!-- /.box-body -->
								<div class="box-footer">
									<button id="clear_rental" class="btn btn-box-tool pull-left" style="display: none;"><i class="fa fa-times"></i></button>
									<button id="edit_rental" class="btn btn-box-tool pull-right" data-toggle="modal" data-target="#rentalmodal" disabled><i class="fa fa-pencil"></i></button>
								</div>
							</div><!-- /.box -->

              <!--<div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Pilt</h3>
                  <div class="box-tools pull-right">
                    <!-- Buttons, labels, and many other things can be placed here! ->
                    <!-- Here is a label for example ->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools ->
                </div><!-- /.box-header ->
                <div class="box-body">
                  <a id="property-link" data-toggle="modal" data-target="#picture_modal">
                    <img id="property-img" src="images/no-img.jpg" class="img-responsive" onerror="this.src='images/no-img.jpg'">
                  </a>
                </div><!-- /.box-body ->
              </div><!-- /.box -->

							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Üürnike arhiiv</h3>
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
			                  <tbody id="tenant_archive">

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
                  <form id="doc_form" name="form" action="../inc/upload.php" method="POST" enctype="multipart/form-data" >

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
                    <input id="attach-type" type="hidden" name="attach-type" value="3">


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

      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Kinnisvara muutmine</h4>
            </div>
            <div class="modal-body">

              <input class="form-control" type="hidden" id="id_edit">

              <div class="form-group">
                <label for="name">Aadress</label>
                <input class="form-control" type="text" id="address_edit">
              </div>

              <div class="form-group">
                <label for="add_org">Tube</label>
                <input class="form-control" id="rooms_edit">
                  </div>

              <div class="form-group">
                <label for="phone">Pindala</label>
                <input class="form-control" type="text" id="space_edit">
              </div>

              <div class="form-group">
                <label for="info">Üürihind(m2)</label>
                <input class="form-control" type="text" id="m2_edit">
              </div>

              <div class="form-group">
                <label for="comment">Koefitsent</label>
                <input class="form-control" type="text" id="koef_edit">
              </div>

							<div class="form-group">
								<label for="condition">Seisukord</label>
								<input class="form-control" type="text" id="condition_edit">
							</div>

              <div class="form-group">
                <label for="mail">Lisainfo</label>
                <textarea class="form-control" type="text" id="info_edit"></textarea>
              </div>

              <div class="checkbox">
                <label>
                  <input id="forsale" type="checkbox" name="forsale"> Müügis
                </label>
              </div>

            </div>
            <div class="modal-footer">
							<button type="button" id="delete_property" class="btn btn-danger pull-left"><i class="fa fa-trash"></i></button>
              <button type="button" id="close_property" class="btn btn-default" data-dismiss="modal">Sulge</button>
              <button type="button" class="btn btn-success" id="save_property">Salvesta</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="rentalmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Üürniku muutmine</h4>
            </div>
            <div class="modal-body">

							<input class="form-control" type="hidden" id="id_tenant"> <!-- Property id -->
              <input class="form-control" type="hidden" id="tenant_real_id"> <!-- Tenant id -->

              <div class="form-group">
                <label for="name">Nimi</label>
                <input class="form-control" type="text" id="tenant_name">
              </div>

              <div class="form-group">
                <label for="add_org">Isikukood</label>
                <input class="form-control" id="tenant_nid">
                  </div>

              <div class="form-group">
                <label for="phone">Telefon</label>
                <input class="form-control" type="text" id="tenant_number">
              </div>

              <div class="form-group">
                <label for="info">E-mail</label>
                <input class="form-control" type="text" id="tenant_email">
              </div>

              <div class="form-group">
                <label for="comment">Sissekirjutus</label>
                <input class="form-control" type="text" id="tenant_real">
              </div>

              <div class="form-group">
                <label for="comment">Lepingu number</label>
                <input class="form-control" type="text" id="tenant_contract">
              </div>

							<div class="form-group">
								<label for="comment">Viide DHSile</label>
								<input class="form-control" type="text" id="tenant_dhs">
							</div>

              <div class="form-group">
                <label for="comment">Lepingu tähtaeg</label>
                <input class="form-control" id="tenant_deadline" type="date">
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" id="delete_tenant" class="btn btn-danger pull-left"><span class="glyphicon glyphicon-trash"></span></button>
              <button type="button" id="close_tenant" class="btn btn-default" data-dismiss="modal">Sulge</button>
              <button type="button" class="btn btn-success" id="save_tenant">Salvesta</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pildi vaatamise modal -->
      <!--<div class="modal fade" id="picture_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <img id="property-modal-img" src="images/no-img.jpg" class="img-responsive" onerror="this.src='images/no-img.jpg'">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
            </div>
          </div>
        </div>
      </div>-->

			<!-- Lisamise modal -->
			<div class="modal fade" id="add_property" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">Lisa kinnisvara</h4>
			      </div>
			      <form method="post" enctype="multipart/form-data">

			        <div id="property-adding" class="modal-body">

			          <div class="row">

			              <!--<div class="col-sm-12">
			                <input type="file" name="fileToUpload" id="fileToUpload">
			              </div>-->


			              <div class="col-sm-6">
			                <div class="form-group">
			                  <label for="select">Vali piirkond</label>
			                  <?php $Playgrounds->fillAreaSelect(); ?>
			                </div>

			                <div class="form-group">
			                  <label for="address">Aadress</label>
			                  <input class="form-control" type="text" id="address" name="address">
			                </div>

			                <div class="form-group">
			                  <label for="rooms">Tube</label>
			                  <input class="form-control" type="text" id="rooms" name="rooms">
			                </div>

			              </div>
			              <div class="col-sm-6">
			                <div class="form-group">
			                  <label for="space">Pindala</label>
			                  <input class="form-control" type="text" id="space" name="space">
			                </div>

			                <div class="form-group">
			                  <label for="price">Üürihind</label>
			                  <input class="form-control" id="price" name="price">
			                </div>

			                <div class="form-group">
			                  <label for="koef">Koefitsent</label>
			                  <input class="form-control" type="text" id="koef" name="koef">
			                </div>

			              </div>
			              <div class="col-sm-12">
											<div class="form-group">
												<label for="condition">Seisukord</label>
												<input class="form-control" type="text" id="condition" name="condition">
											</div>
			                <div class="form-group">
			                  <label for="additional">Lisainfo</label>
			                  <textarea class="form-control" type="text" id="additional" name="additional"></textarea>
			                </div>
			              </div>

			              <div class="col-sm-12">
			              	<input type="checkbox" class="minimal-red" name="forsale"> Müügis
			              </div>

			          </div>

			        </div>

			        <div id="tenant-adding" class="modal-body" style="display: none;">
			          <div class="form-group">
			            <label for="name">Nimi</label>
			            <input class="form-control" type="text" id="tenant_name" name="tenant_name">
			          </div>

			          <div class="form-group">
			            <label for="add_org">Isikukood</label>
			            <input class="form-control" id="tenant_nid" name="tenant_nid">
			              </div>

			          <div class="form-group">
			            <label for="phone">Telefon</label>
			            <input class="form-control" type="text" id="tenant_number" name="tenant_number">
			          </div>

			          <div class="form-group">
			            <label for="info">E-mail</label>
			            <input class="form-control" type="text" id="tenant_email" name="tenant_email">
			          </div>

			          <div class="form-group">
			            <label for="comment">Sissekirjutus</label>
			            <input class="form-control" type="text" id="tenant_real" name="tenant_real">
			          </div>

			          <div class="form-group">
			            <label for="comment">Lepingu number</label>
			            <input class="form-control" type="text" id="tenant_contract" name="tenant_contract">
			          </div>

								<div class="form-group">
									<label for="comment">Viide DHSile</label>
									<input class="form-control" type="text" id="tenant_dhs" name="tenant_dhs">
								</div>

			          <div class="form-group">
			            <label for="comment">Lepingu tähtaeg</label>
			            <input class="form-control" type="date" id="tenant_deadline" name="tenant_deadline">
			          </div>

			        </div>


			      <div class="modal-footer">
			        <button id="go-tenant" type="button" class="btn btn-default pull-left">Üürnik</button>
			        <button id="go-property" type="button" class="btn btn-default pull-left" style="display: none">Kinnisvara</button>
			        <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
			        <button type="submit" name="submit_property" class="btn btn-primary">Lisa</button>
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

document.querySelector("#properties_select").value = objectid;


</script>

<?php
unset($_SESSION['success_msg']);
unset($_SESSION['error_msg']);
unset($_SESSION['upload_id']);
?>
