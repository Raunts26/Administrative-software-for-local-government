<?php	$page_title = "Mobiilside";
	$page_file = "mobile.php";
?><?php require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php"); ?><?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/head.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/header.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Mobiilside
            <small>Kontaktnumbrite vaatamine</small>
          </h1>
					<?php if($_SESSION['user_group'] === "3" || $_SESSION['user_group'] === "4"): ?>
	          <ol class="breadcrumb">
							<li>
								<button id="add" class="btn btn-box-tool" data-toggle="modal" data-target="#myModal"><span class="label label-success font-ok">Lisa</span></button>
							</li>
	          </ol>
					<?php endif;?>

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
                      <label for="org">Vali asutus</label>
                      <select id="org" class="form-control">
                        <option>- Vali -</option>
                        <option value="Rae Vallavalitsus">Rae Vallavalitsus</option>
                        <option value="Rae Kultuurikeskus">Rae Kultuurikeskus</option>
                        <option value="Jüri Gümnaasium">Jüri Gümnaasium</option>
                        <option value="Peetri kool">Peetri kool</option>
                        <option value="Järveküla kool">Järveküla kool</option>
                        <option value="Vaida kool">Vaida kool</option>
                        <option value="Lagedi kool">Lagedi kool</option>
                        <option value="Õie Lasteaed">Õie Lasteaed</option>
                        <option value="Vaida Lasteaed Pillerpall">Vaida Lasteaed Pillerpall</option>
                        <option value="Taaramäe Lasteaed">Taaramäe Lasteaed</option>
                        <option value="Tõrukese Lasteaed">Tõrukese Lasteaed</option>
                        <option value="Võsukese Lasteaed">Võsukese Lasteaed</option>
                        <option value="Assaku Lasteaed">Assaku Lasteaed</option>
                        <option value="Aruheina Lasteaed">Aruheina Lasteaed</option>
                        <option value="Raamatukogud">Raamatukogud</option>
                        <option value="Rae hooldekodu">Rae Hooldekodu</option>
                        <option value="Rae huvialakool">Rae Huvialakool</option>
                        <option value="Spordikeskus">Spordikeskus</option>
                        <option value="Noortekeskused">Noortekeskused</option>
                      </select>
                      <br>

                      <select id="dep" class="form-control" style="display: none;">
                        <option>- Vali -</option>
                        <option value="Ehitusamet">Ehitusamet</option>
                        <option value="Keskkonnaamet">Keskkonnaamet</option>
                        <option value="Haridusamet">Haridusamet</option>
                        <option value="Sotsiaalamet">Sotsiaalamet</option>
                        <option value="Majandusamet">Majandusamet</option>
                        <option value="Vallavalitsus">Vallavalitsus</option>
                      </select>

                    </div>

                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>

            <div class="col-sm-9">

              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Andmed</h3>
                  <div class="box-tools pull-right">
                    <!-- Buttons, labels, and many other things can be placed here! -->
                    <!-- Here is a label for example -->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-12">

											<span id="tutorial_msg">Vali eelnevalt asutus!</span>

                      <table id="mobiletable" class="table table-striped table-no-border">
              				  <thead id="table_head" style="display: none;">
              						<tr>
              							<th>Nimi</th>
              							<th>Amet</th>
              							<th>Telefon</th>
              							<th>Pakett</th>
														<th>Märkus</th>
              							<th>Halda</th>
              						</tr>
              					</thead>
              					<tbody id="mobile-data">
              					</tbody>
              				</table>
                    </div>

                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div>


          </div>



          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">			  <div class="modal-dialog" role="document">			    <div class="modal-content">			      <div class="modal-header">			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">Lisamine</h4>
			      </div>
			      <div class="modal-body">
							<div class="form-group">								<label for="name">Nimi</label>			        	<input class="form-control" type="text" id="name">							</div>
							<div class="form-group">								<label for="add_org">Organisatsioon</label>								<select class="form-control" id="add_org"></select>							</div>
							<div class="form-group" id="dep_group">								<label for="add_dep">Osakond</label>								<select class="form-control" id="add_dep"></select>							</div>
							<div class="form-group">								<label for="phone">Telefon</label>				        <input class="form-control" type="text" id="phone">							</div>
							<div class="form-group">								<label for="info">Pakett</label>				        <input class="form-control" type="text" id="info">							</div>
							<div class="form-group">								<label for="comment">Märkus</label>				        <input class="form-control" type="text" id="comment">							</div>
							<div class="form-group">								<label for="mail">E-Mail</label>				        <input class="form-control" type="email" id="mail">							</div>
							<div class="form-group">								<label for="job">Amet</label>								<input class="form-control" type="text" id="job">							</div>
							<input type="checkbox" id="is_hidden" class="minimal-red"> Peidetud			      </div>
			      <div class="modal-footer">			        <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>			        <button type="button" class="btn btn-success" id="save_data">Salvesta</button>			      </div>
			    </div>			  </div>			</div>

			<!-- Modal -->
			<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel2">Muutmine</h4>
						</div>
						<div class="modal-body">

							<input class="form-control" type="hidden" id="personal_id">

							<div class="form-group">
								<label for="name_edit">Nimi</label>
								<input class="form-control" type="text" id="name_edit">
							</div>

							<div class="form-group">
								<label for="phone_edit">Telefon</label>
								<input class="form-control" type="text" id="phone_edit">
							</div>

							<div class="form-group">
								<label for="info_edit">Pakett</label>
								<input class="form-control" type="text" id="info_edit">
							</div>

							<div class="form-group">
								<label for="comment_edit">Märkus</label>
								<input class="form-control" type="text" id="comment_edit">
							</div>

							<div class="form-group">
								<label for="job_edit">Amet</label>
								<input class="form-control" type="text" id="job_edit">
							</div>

							<input type="checkbox" id="is_hidden_edit" class="minimal-red"> Peidetud


						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
							<button type="button" class="btn btn-success" id="save_edit">Salvesta</button>
						</div>
					</div>
				</div>
			</div>


<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>