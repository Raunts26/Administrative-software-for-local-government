<?php
	$page_title = "Avaleht";
	$page_file = "index.php";
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
            Töölaud
            <small>Ülevaade veebilehest</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=$_SERVER['BASE_PATH'];?>/index.php"><i class="fa fa-home"></i> Avaleht</a></li>
          </ol>
        </section>

        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
           <!-- <div class="col-lg-3 col-xs-6">
              <!-- small box -->
             <!--  <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?=$Stats->countObjects();?></h3>
                  <p>Objekti</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-home"></i>
                </div>
                <a href="objektid.php" class="small-box-footer">Vaata kõiki objekte <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->

						<!-- <div class="col-lg-3 col-xs-6">
										<!-- small box -->
										<!-- <div class="small-box bg-green">
											<div class="inner">
												<h3><?=$Stats->countProperties();?></h3>
												<p>Üüripinda</p>
											</div>
											<div class="icon">
												<i class="ion ion-cash"></i>
											</div>
											<a href="valjakud.php" class="small-box-footer">Vaata kõiki üüripindu <i class="fa fa-arrow-circle-right"></i></a>
										</div>
									</div><!-- ./col -->
          <!-- ./col -->
           <!--  <div class="col-lg-3 col-xs-6">
              <!-- small box -->
             <!--  <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?=$Stats->countUsers();?></h3>
                  <p>Registreeritud kasutajat</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="kasutajad.php" class="small-box-footer">Halda kasutajaid <i class="fa fa-arrow-circle-right"></i></a>
              </div>
          </div>
		  <div class="col-lg-3 col-xs-6">
              <!-- small box -->
             <!--  <div class="small-box bg-red">
                <div class="inner">
                  <h3><?=$Stats->countPlaygrounds();?></h3>
                  <p>Mänguväljakut</p>
                </div>
                <div class="icon">
                  <i class="ion ion-paper-airplane"></i>
                </div>
                <a href="valjakud.php" class="small-box-footer">Vaata kõiki mänguväljakuid <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
			<!--<div class="col-md-6">
              <!- small box ->
               <a class="btn btn-app">
                    <i class="fa fa-users"></i> Lisa kasutajad
                  </a>
              <!- small box ->
               <a class="btn btn-app">
                    <i class="glyphicon glyphicon-plus"></i> Vaata objekte
                  </a>
					<a class="btn btn-app">
                    <i class="fa fa-edit"></i>Lisa ülesanne
                  </a>
				  <a class="btn btn-app">
                    <i class="glyphicon glyphicon-search"></i> Otsi üürnike
                  </a>

               </div>-->
               </div>
<div class="row">
						<div class="col-md-4">
							<div class="box box-danger">
		            <div class="box-header">
		              <i class="ion ion-clipboard"></i>

		              <a href="<?=$_SERVER['BASE_PATH'];?>/views/ulesanded.php" style="color: #000;">
										<h3 class="box-title">Minu tööülesanded</h3>
									</a>

		              <div class="box-tools pull-right">
		                <ul id="pages" class="pagination pagination-sm inline">

		                </ul>
		              </div>
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">
		              <ul id="my_tasks" class="todo-list ui-sortable">


		              </ul>
		            </div>
		            <!-- /.box-body -->
		          </div>
						</div>

						<div class="col-md-4">
							<div class="box box-danger">
		            <div class="box-header">
		              <i class="ion ion-calendar"></i>

		              <a href="<?=$_SERVER['BASE_PATH'];?>/views/kalender.php" style="color: #000;">
										<h3 class="box-title">Lähenevad sündmused</h3>
									</a>
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">
		              <ul id="next_events" class="todo-list ui-sortable">


		              </ul>
		            </div>
		            <!-- /.box-body -->
		          </div>
						</div>


 </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

			<!-- Ülesane muutmise modal -->

			<div class="modal fade" id="editmodal" role="dialog" aria-labelledby="myModalLabel">
			    <div class="modal-dialog" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                    <span aria-hidden="true">&times;</span>
			                </button>
			                <h4 class="modal-title">Muutmine</h4>
			            </div>
			            <div class="modal-body">
			                <div class="row">
			                    <input type="hidden" id="task_id">
			                    <div class="col-sm-6">

			                        <div class="form-group">
			                            <label>Vali tüüp</label>
			                            <select class="form-control" id="object_type_edit" name="object_type">
			                                <option value="0" selected>- Vali -</option>
			                                <option value="1">Objekt</option>
			                                <?php if ($_SESSION['rights'] === NULL): ?>
			                                    <option value="2">Munitsipaalpind</option>
			                                    <option value="3">Äripind</option>
			                                    <option value="4">Mänguväljak</option>
			                                <?php endif; ?>
			                            </select>
			                        </div>

			                        <div class="form-group">
			                            <label>Vali objekt</label>
			                            <select id="object_id_edit" class="form-control" name="object_id">
			                                <option value="0" selected>- Vali tüüp -</option>
			                            </select>
			                        </div>
			                        <div class="form-group">
			                            <label>Ruumi nr või asukoht</label>
			                            <input class="form-control" id="location_edit" name="location">
			                        </div>

			                        <div class="form-group">
			                            <label>Probleemi tüüp</label>
			                            <ul class="checkbox-list">
			                                <li><input type="radio" class="minimal-red" name="problem_type" value="Hoone probleem">
			                                    Hoone probleem
			                                </li>
			                                <li><input type="radio" class="minimal-red" name="problem_type" value="Üürniku kaebus">
			                                    Üürniku kaebus
			                                </li>
			                                <li><input type="radio" class="minimal-red" name="problem_type" value="Inventari probleem">
			                                    Inventari probleem
			                                </li>
			                            </ul>
			                        </div>
			                    </div>
			                    <div class="col-sm-6">
			                        <div class="form-group">
			                            <label>Teema</label>
			                            <input id="short_description_edit" class="form-control" name="short_description">
			                        </div>
			                        <div class="form-group">
			                            <label>Kuupäev</label>
			                            <input id="problem_date_edit" class="form-control datepickertask" name="problem_date"
			                                   autocomplete="off">
			                        </div>
			                        <div class="form-group">
			                            <label>Prioriteet</label>
			                            <select class="form-control" id="priority_edit" name="priority">
			                                <option value="Puudub" selected>- Vali -</option>
			                                <option value="Kõrge">Kõrge</option>
			                                <option value="Keskmine">Keskmine</option>
			                                <option value="Madal">Madal</option>
			                            </select>
			                        </div>

			                        <div class="form-group">
			                            <label>Millega seotud</label>
			                            <table>
			                                <tr>
			                                    <td><input type="radio" class="minimal-red" name="source" value="Põhikonstruk.">
			                                        Põhikonstruk.</a></td>
			                                    <td><input type="radio" class="minimal-red" name="source" value="Seade"> Seade</a>
			                                    </td>
			                                </tr>
			                                <tr>
			                                    <td><input type="radio" class="minimal-red" name="source" value="Avarii"> Avarii</a>
			                                    </td>
			                                    <td><input type="radio" class="minimal-red" name="source" value="Heakord"> Heakord</a>
			                                    </td>
			                                </tr>
			                                <tr>
			                                    <td><input type="radio" class="minimal-red" name="source" value="Ruum"> Ruum</a>
			                                    </td>
			                                    <td><input type="radio" class="minimal-red" name="source" value="Muu"> Muu</a></td>
			                                </tr>
			                            </table>
			                        </div>

			                    </div>

			                    <div class="col-sm-12">
			                        <div class="form-group">
			                            <label>Kirjeldus</label>
			                            <textarea id="long_description_edit" class="form-control"
			                                      name="long_description"></textarea>
			                        </div>
			                    </div>

			                    <div class="col-sm-12">
			                        <div class="form-group">
			                            <label>Lahendus/kommentaar</label>
			                            <textarea id="solution_edit" class="form-control" name="solution"></textarea>
			                        </div>
			                    </div>

			                    <div class="col-sm-6">
			                        <div class="form-group">
			                            <label>Vastutaja</label>
			                            <input id="user_search_edit_old" class="form-control hidden" name="user-old2" autocomplete="off"></input>
			                            <table class="table table-hover hidden" style="position: absolute; width: 90%; background: #fff; z-index: 999;">
			                                <tbody id="livesearch_edit"></tbody>
			                            </table>
			                            <select name="user" id="user_search_edit" class="status_filter" style="width: 100%">
			                                <option value=""></option>
			                                <?php foreach ($Tasks->getAllUsers() as $user) { ?>
			                                    <option value="<?= $user->user_id; ?>"><?= $user->firstname; ?> <?= $user->lastname; ?></option>
			                                <?php } ?>
			                            </select>
			                        </div>
			                    </div>
			                    <div class="col-sm-6">
			                        <div class="form-group">
			                            <label>Tähtaeg</label>
			                            <input id="deadline_edit" class="form-control datepickertask" name="deadline"
			                                   autocomplete="off"></input>
			                        </div>
			                    </div>

			                    <div class="col-sm-6">
			                      <br><br>
			                      <input type="checkbox" class="minimal-red" name="problem_tocalendar_2"> Lisa kalendrisse
			                    </div>

			                    <div class="col-sm-6">
			                        <div class="form-group">
			                            <label>Staatus</label>
			                            <select id="status_edit" class="form-control" name="status">
			                                <option value="Registreeritud" selected>Registreeritud</option>
			                                <option value="Pooleli">Pooleli</option>
			                                <option value="Tehtud">Tehtud</option>
			                            </select>
			                        </div>
			                    </div>
			                </div>
			            </div>
			            <div class="modal-footer">
			                <span class="pull-left">Lisas: <span id="problem_adder"></span></span>
			                <button id="close_edit" type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
			                <button id="save_edit" type="button" class="btn btn-success" name="submit_task">Salvesta</button>
			            </div>
			        </div>
			    </div>
			</div>


			<!--<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">


				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title">Muutmine</h4>
				      </div>
				      <div class="modal-body">
								<div class="row">
									<input type="hidden" id="task_id">
									<div class="col-sm-6">

										<div class="form-group">
											<label>Vali tüüp</label>
											<select class="form-control" id="object_type_edit" name="object_type">
												<option value="0" selected>- Vali -</option>
												<option value="1">Objekt</option>
												<?php if($_SESSION['rights'] === NULL): ?>
													<option value="2">Munitsipaalpind</option>
													<option value="3">Äripind</option>
													<option value="4">Mänguväljak</option>
												<?php endif; ?>
											</select>
										</div>

										<div class="form-group">
											<label>Vali objekt</label>
											<select id="object_id_edit" class="form-control" name="object_id">
												<option value="0" selected>- Vali tüüp -</option>
											</select>
										</div>
										<div class="form-group">
											<label>Ruumi nr või asukoht</label>
											<input class="form-control" id="location_edit" name="location">
										</div>

										<div class="form-group">
											<label>Probleemi tüüp</label>
											<ul class="checkbox-list">
												<li><input type="radio" class="minimal-red" name="problem_type" value="Hoone probleem"> Hoone probleem</li>
												<li><input type="radio" class="minimal-red" name="problem_type" value="Üürniku kaebus"> Üürniku kaebus</li>
											</ul>
										</div>


									</div>

									<div class="col-sm-6">

										<div class="form-group">
											<label>Teema</label>
											<input id="short_description_edit" class="form-control" name="short_description">
										</div>

										<div class="form-group">
											<label>Kuupäev</label>
											<input id="problem_date_edit" class="form-control datepicker" name="problem_date">
										</div>
										<div class="form-group">
											<label>Prioriteet</label>
											<select class="form-control" id="priority_edit" name="priority">
												<option value="Puudub" selected>- Vali -</option>
												<option value="Kõrge">Kõrge</option>
												<option value="Keskmine">Keskmine</option>
												<option value="Madal">Madal</option>
											</select>
										</div>

										<div class="form-group">
											<label>Millega seotud</label>
											<table>
												<tr>
													<td><input type="radio" class="minimal-red" name="source" value="Põhikonstruk."> Põhikonstruk.</a></td>
													<td><input type="radio" class="minimal-red" name="source" value="Seade"> Seade</a></td>
												</tr>
												<tr>
													<td><input type="radio" class="minimal-red" name="source" value="Ruum"> Ruum</a></td>
													<td><input type="radio" class="minimal-red" name="source" value="Muu"> Muu</a></td>
												</tr>
											</table>
										</div>

									</div>

									<div class="col-sm-12">
										<div class="form-group">
											<label>Kirjeldus</label>
											<textarea id="long_description_edit" class="form-control" name="long_description"></textarea>
										</div>
									</div>

									<div class="col-sm-12">
										<div class="form-group">
											<label>Lahendus/kommentaar</label>
											<textarea id="solution_edit" class="form-control" name="solution"></textarea>
										</div>
									</div>

									<div class="col-sm-6">
										<div class="form-group">
											<label>Vastutaja</label>

											<select name="user" id="user_search_edit" class="status_filter" style="width: 100%">
													<option value=""></option>
													<?php foreach ($Tasks->getAllUsers() as $user) { ?>
															<option value="<?= $user->user_id; ?>"><?= $user->firstname; ?> <?= $user->lastname; ?></option>
													<?php } ?>
											</select>

										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label>Tähtaeg</label>
											<input id="deadline_edit" class="form-control datepicker" name="deadline"></input>
										</div>
									</div>

									<div class="col-sm-offset-6 col-sm-6">
										<div class="form-group">
											<label>Staatus</label>
											<select id="status_edit" class="form-control" name="status">
												<option value="Registreeritud" selected>Registreeritud</option>
												<option value="Pooleli">Pooleli</option>
												<option value="Tehtud">Tehtud</option>
											</select>
										</div>
									</div>


								</div>
				      </div>
				      <div class="modal-footer">
				        <button id="close_edit" type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
				        <button id="save_edit" type="button" class="btn btn-success" name="submit_task">Salvesta</button>
				      </div>


			    </div>
			  </div>
			</div>-->

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>
