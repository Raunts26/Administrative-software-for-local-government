<?php
$page_title = "IT-Kasutajatugi";
$page_file = "it_support.php";
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
            IT-Kasutajatugi
            <small>IT-probleemide vaatamine/lisamine</small>
        </h1>

        <ol class="breadcrumb">
            <li>
                <button id="add" class="btn btn-box-tool" data-toggle="modal" data-target="#myModal">
                    <span class="label label-success font-ok">Lisa</span>
                </button>
            </li>
        </ol>

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-lg-3">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filter</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">

                              <?php if($_SESSION['user_group'] === "3" || $_SESSION['user_group'] === "4"): ?>
                                <label>Vali asutus</label>
                                <select id="object_filter" class="form-control status_filter" multiple="multiple">
                                  <?php foreach ($Objects->objectNames as $object): ?>
                                      <option value="<?= $object->id; ?>"><?= $object->name; ?></option>
                                  <?php endforeach; ?>
                                </select>
                                <br>
                              <?php endif; ?>

                                <label>Vali staatus</label>
                                <select id="status_filter" class="form-control status_filter" multiple="multiple">
                                    <option value="Registreeritud">Registreeritud</option>
                                    <option value="Pooleli">Pooleli</option>
                                    <option value="Tehtud">Tehtud</option>
                                </select>
                                <br>
                                <!--<label>Vastutaja kuvamine</label>-->
                                <ul class="checkbox-list" style="display: none;">
                                    <li><input type="radio" class="minimal-red" name="filter_for" value="Minule määratud">
                                        Minule määratud
                                    </li>
                                    <li><input type="radio" class="minimal-red" name="filter_for" value="Kõik" checked>
                                        Kõik
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button id="filter_tasks" class="btn btn-default pull-right">Filtreeri</button>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <div class="col-lg-9">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tööülesanded</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="itsupporttable" class="table table-striped table-no-border table-responsive">
                                    <thead id="table_head" style="display: none;">
                                    <tr>
                                        <th>Objekt</th>
                                        <th>Teema</th>
                                        <th>Tähtaeg</th>
                                        <th>Prioriteet</th>
                                        <th>Staatus</th>
                                        <th>Halda</th>
                                    </tr>
                                    </thead>
                                    <tbody id="problem-data">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Lisamise modal -->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Lisamine</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                               <select class="form-control" id="object_type" name="object_type" style="display: none;">
                                   <option value="0" selected>- Vali -</option>
                                   <option value="1">Objekt</option>
                                   <?php if ($_SESSION['rights'] === NULL): ?>
                                       <option value="2">Munitsipaalpind</option>
                                       <option value="3">Äripind</option>
                                       <option value="4">Mänguväljak</option>
                                   <?php endif; ?>
                               </select>

                                <label>Vali asutus</label>
                                <select id="object_id" name="object_id" class="form-control status_filter" multiple="multiple" style="width: 100%;">
                                  <?php foreach ($Objects->objectNames as $object): ?>
                                      <option value="<?= $object->id; ?>"><?= $object->name; ?></option>
                                  <?php endforeach; ?>
                                </select>

                            </div>


                            <div class="form-group">
                                <label>Ruumi nr või asukoht</label>
                                <input class="form-control" id="location" name="location">
                            </div>

                            <div class="form-group">
                                <label>Kaughalduse ID(ei pea täitma)</label>
                                <input class="form-control" id="tv_id" name="tv_id">
                            </div>




                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Teema</label>
                                <input class="form-control" name="short_description">
                            </div>
                            <div class="form-group">
                                <label>Kuupäev</label>
                                <input class="form-control datepickertask" name="problem_date" autocomplete="off">
                            </div>

                            <div class="form-group">

                                <label>Prioriteet</label>
                                <select class="form-control" id="priority" name="priority">
                                    <option value="Puudub" selected>- Vali -</option>
                                    <option value="Kõrge">Kõrge</option>
                                    <option value="Keskmine">Keskmine</option>
                                    <option value="Madal">Madal</option>
                                </select>
                            </div>



                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Kirjeldus (võimalikult täpselt) </label>
                                <textarea class="form-control" name="long_description"></textarea>
                            </div>
                        </div>
						<div class="col-sm-12">
                            <div class="form-group">
                                <label>Lahendus/kommentaar (täidab vastutaja)</label>
                                <textarea class="form-control" name="solution"></textarea>
                            </div>
                        </div>

                        <div class="col-sm-6">

                              <div class="form-group">
                                  <label>Tähtaeg</label>
                                  <input class="form-control datepickertask" name="deadline" autocomplete="off"></input>
                              </div>

                              <input type="hidden" name="user_responsible" value="12">

                              <!--<label>Vastutaja</label><br>
                              <select name="user" id="user_responsible" style="width: 100%">
                                  <option value="">- otsi -</option>
                                  <?php #foreach ($Tasks->getAllUsers() as $user) { ?>
                                      <option value="<?= $user->user_id; ?>"><?= $user->firstname; ?> <?= $user->lastname; ?></option>
                                  <?php #} ?>
                              </select>
                          <input type="checkbox" class="minimal-red" name="problem_tocalendar"> Lisa kalendrisse-->

                      </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Staatus</label>
                                <select class="form-control" name="status">
                                    <option value="Registreeritud" selected>Registreeritud</option>
                                    <option value="Pooleli">Pooleli</option>
                                    <option value="Tehtud">Tehtud</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
                    <button type="submit" class="btn btn-success" name="submit_itsupport">Salvesta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Muutmise modal -->
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

                        <select class="form-control" id="object_type_edit" name="object_type" style="display: none;">
                            <option value="0" selected>- Vali -</option>
                            <option value="1">Objekt</option>
                            <?php if ($_SESSION['rights'] === NULL): ?>
                                <option value="2">Munitsipaalpind</option>
                                <option value="3">Äripind</option>
                                <option value="4">Mänguväljak</option>
                            <?php endif; ?>
                        </select>

                        <div class="form-group">
                            <label>Vali objekt</label>
                            <select id="object_id_edit" class="form-control status_filter" name="object_id" style="width: 100%;">
                                <option value="0" selected>- Vali tüüp -</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ruumi nr või asukoht</label>
                            <input class="form-control" id="location_edit" name="location">
                        </div>

                        <div class="form-group">
                            <label>Kaughalduse ID</label>
                            <input class="form-control" id="tv_id_edit" name="tv_id">
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




                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Kirjeldus(võimalikult täpselt)</label>
                            <textarea id="long_description_edit" class="form-control"
                                      name="long_description"></textarea>
                        </div>
                    </div>
						<div class="col-sm-12">
                            <div class="form-group">
                                <label>Lahendus/kommentaar (täidab vastutaja)</label>
                                <textarea id="solution_edit" class="form-control" name="solution"></textarea>
                            </div>
                        </div>

                    <div class="col-sm-6">
                        <input type="hidden" id="user_search_edit">
                        <div class="form-group">
                            <label>Tähtaeg</label>
                            <input id="deadline_edit" class="form-control datepickertask" name="deadline"
                                   autocomplete="off"></input>
                        </div>
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

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>
