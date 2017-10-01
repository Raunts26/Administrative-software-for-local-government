<?php	$page_title = "Äripinnad";
	$page_file = "aripinnad.php";
?><?php require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/head.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/header.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Äripinnad
            <small>Kinnisvarade ülevaade</small>
          </h1>

					<ol class="breadcrumb">
            <li>
            	<button class="btn btn-box-tool" data-toggle="modal" data-target="#add_business"><span class="label label-success font-ok">Lisa äripind</span></button>
            </li>
          </ol>

        </section>

        <!-- Main content -->
        <section class="content">

          <div class="row">

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

                  <!--<label for="select-1">Filter</label><br>

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
                  </ul>-->


									<div class="form-group">
										<label for="select-1">Vali objekt</label>
                  	<?php $Properties->fillBusinessesSelect(); ?>
									</div>

									<div class="form-group">
										<label for="select-1">Vali rentnik</label>
										<select id="business_tenants" class="form-control">
											<option value="0">- Vali äripind -</option>
										</select>
									</div>


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
                  <p><strong>Seisukord:</strong> <span id="condition_value"></span></p>
									<p><strong>Lisainfo:</strong> <span id="info_value"></span></p>

                </div><!-- /.box-body -->
                <div class="box-footer">
                  <button id="edit_business" class="btn btn-box-tool pull-right" data-toggle="modal" data-target="#myModal" disabled><i class="fa fa-pencil"></i></button>

                </div><!-- box-footer -->
              </div><!-- /.box -->

            </div>

            <div class="col-sm-6">

							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Rentniku info</h3>
									<div class="box-tools pull-right">
										<!-- Buttons, labels, and many other things can be placed here! -->
										<!-- Here is a label for example -->
										<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /.box-tools -->
								</div><!-- /.box-header -->
								<div class="box-body">
									<div class="row">

										<div class="col-sm-6">

											<p><strong>Rentnik:</strong> <span id="name_rent"></span></p>
											<p><strong>Registrikood:</strong> <span id="id_rent"></span></p>
											<p><strong>Kontaktisik:</strong> <span id="contact_rent"></span></p>
											<p><strong>Telefon:</strong> <span id="number_rent"></span></p>
											<p><strong>E-Mail:</strong> <span id="mail_rent"></span></p>

										</div>
										<div class="col-sm-6">
											<p><strong>DHS lepingu number:</strong> <span id="dhs_rent"></span></p>
											<p><strong>Lepingu tähtaeg:</strong> <span id="deadline_rent"></span></p>
											<p><strong>Üüri hind:</strong> <span id="price_rent"></span></p>
											<p><strong>Kasutusotstarve:</strong> <span id="usage_rent"></span></p>
											<p><strong>Märkus:</strong> <span id="info_rent"></span></p>
										</div>

									</div>
								</div><!-- /.box-body -->
								<div class="box-footer">
									<button id="add_rental" class="btn btn-box-tool pull-left" data-toggle="modal" data-target="#rentalmodal" disabled><i class="fa fa-plus"></i></button>
									<button id="clear_rental" class="btn btn-box-tool pull-left" style="display: none;"><i class="fa fa-times"></i></button>
									<button id="edit_rental" class="btn btn-box-tool pull-right" data-toggle="modal" data-target="#rentalmodal" disabled><i class="fa fa-pencil"></i></button>
								</div>
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

			<!-- Äripinna muutmine -->

      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Äripinna muutmine</h4>
            </div>
            <div class="modal-body">

              <input class="form-control" type="hidden" id="id_edit">

              <div class="form-group">
                <label for="name">Nimi</label>
                <input class="form-control" type="text" id="name_edit">
              </div>

							<div class="form-group">
								<label for="name">Aadress</label>
								<input class="form-control" type="text" id="address_edit">
							</div>

							<div class="form-group">
								<label for="condition">Seisukord</label>
								<input class="form-control" type="text" id="condition_edit">
							</div>

							<div class="form-group">
								<label for="info">Lisainfo</label>
								<input class="form-control" type="text" id="info_edit">
							</div>


            </div>
            <div class="modal-footer">
							<button type="button" id="delete_business" class="btn btn-danger pull-left"><i class="fa fa-trash"></i></button>
              <button type="button" id="close_business" class="btn btn-default" data-dismiss="modal">Sulge</button>
              <button type="button" class="btn btn-success" id="save_business">Salvesta</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="rentalmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="rental_heading">Rentniku muutmine</h4>
            </div>
            <div class="modal-body">

							<input class="form-control" type="hidden" id="id_tenant"> <!-- Property id -->
              <input class="form-control" type="hidden" id="tenant_real_id"> <!-- Tenant id -->

              <div class="form-group">
                <label for="name">Nimi</label>
                <input class="form-control" type="text" id="tenant_name">
              </div>

              <div class="form-group">
                <label for="add_org">Registrikood</label>
                <input class="form-control" id="tenant_reg">
              </div>

							<div class="form-group">
								<label for="contact">Kontaktisik</label>
								<input class="form-control" id="tenant_contact">
							</div>

              <div class="form-group">
                <label for="phone">Telefon</label>
                <input class="form-control" type="text" id="tenant_phone">
              </div>

              <div class="form-group">
                <label for="info">E-mail</label>
                <input class="form-control" type="text" id="tenant_email">
              </div>

							<div class="form-group">
								<label for="comment">DHS lepingu number</label>
								<input class="form-control" type="text" id="tenant_dhs">
							</div>

              <div class="form-group">
                <label for="comment">Lepingu tähtaeg</label>
                <input class="form-control" id="tenant_deadline" type="date">
              </div>

							<div class="form-group">
								<label for="comment">Üüri hind</label>
								<input class="form-control" id="tenant_price">
							</div>

							<div class="form-group">
								<label for="comment">Kasutusotstarve</label>
								<input class="form-control" id="tenant_usedfor">
							</div>

							<div class="form-group">
								<label for="comment">Märkus</label>
								<input class="form-control" id="tenant_info">
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

			<!-- Lisamise modal -->
			<div class="modal fade" id="add_business" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">Lisa äripind</h4>
			      </div>
			      <form method="post" enctype="multipart/form-data">

			        <div class="modal-body">

			          <div class="row">

									<div class="col-sm-12">
										<div class="form-group">
											<label for="address">Nimi</label>
											<input class="form-control" type="text" name="name">
										</div>
									</div>


									<div class="col-sm-6">
										<div class="form-group">
											<label for="address">Aadress</label>
											<input class="form-control" type="text" name="address">
										</div>
									</div>

									<div class="col-sm-6">
										<div class="form-group">
											<label for="condition">Seisukord</label>
											<input class="form-control" type="text" name="condition">
										</div>
									</div>

									<div class="col-sm-12">
										<div class="form-group">
											<label for="info">Lisainfo</label>
											<input class="form-control" type="text" name="info">
										</div>
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
			        <button id="go-property" type="button" class="btn btn-default pull-left" style="display: none">Kinnisvara</button>
			        <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
			        <button type="submit" name="submit_business" class="btn btn-primary">Lisa</button>
			      </div>
			    </form>
			    </div>
			  </div>
			</div>


			<?php
				unset($_SESSION['success_msg']);
				unset($_SESSION['error_msg']);
			?>


<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>