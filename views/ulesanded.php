<?php
$page_title = "Haldusülesanded";
$page_file = "ulesanded.php";
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
            Haldusülesanded
            <small>Vaatamine/lisamine</small>
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
                                <label>Vastutaja kuvamine</label>
                                <ul class="checkbox-list">
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
                                <table id="problemtable" class="table table-striped table-no-border table-responsive">
                                    <thead id="table_head" style="display: none;">
                                    <tr>
                                        <th>Objekt</th>
                                        <th>Ruum/asukoht</th>
                                        <th>Teema</th>
                                        <th>Tähtaeg</th>
                                        <th>Vastutaja</th>
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
                                <label>Vali tüüp</label>
                                <select class="form-control" id="object_type" name="object_type">
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
                                <select id="object_id" class="form-control" name="object_id">
                                    <option value="0" selected>- Vali tüüp -</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Ruumi nr või asukoht</label>
                                <input class="form-control" id="location" name="location">
                            </div>

                            <div class="form-group">
                                <label>Probleemi tüüp</label>
                                <ul class="checkbox-list">
                                    <li><input type="radio" class="minimal-red" name="problem_type"
                                               value="Hoone probleem"> Hoone probleem
                                    </li>
                                    <li><input type="radio" class="minimal-red" name="problem_type"
                                               value="Üürniku kaebus"> Üürniku kaebus
                                    </li>
                                    <li><input type="radio" class="minimal-red" name="problem_type"
                                               value="Inventari probleem"> Inventari probleem
                                    </li>
                                </ul>
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
                            <div class="form-group">
                                <label>Millega seotud</label>
                                <table>
                                    <tr>
                                        <td><input type="radio" class="minimal-red" name="source" value="Põhikonstruk.">
                                            Põhikonstruk.</a></td>
                                        <td><input type="radio" class="minimal-red" name="source" value="Seade">
                                            Seade</a></td>
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
                                        <td><input type="radio" class="minimal-red" name="source" value="Muu"> Muu</a>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Kirjeldus </label>
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

                                <input id="user_search" class="form-control hidden" name="user_old1" autocomplete="off"></input>
                                <table class="table table-hover hidden"
                                       style="position: absolute; width: 90%; background: #fff; z-index: 999;border: 1px solid #d2d6de">
                                    <tbody id="livesearch"></tbody>
                                </table>
                                <label class="responsible_label">Vastutaja</label><br>
                                <div id="add_responsible_list" style="float: left; width: 100%;">

                                  <select name="users[]" class="responsible_reader" style="width: 100%">
                                    <option value="">- otsi -</option>
                                    <?php foreach ($Tasks->getAllUsers() as $user) { ?>
                                      <option value="<?= $user->user_id; ?>"><?= $user->firstname; ?> <?= $user->lastname; ?></option>
                                    <?php } ?>
                                  </select>

                                </div>

                                <div id="add_button_list">

                                  <button id="add_user_responsible" type="button" class="btn btn-default btn-block user_adding" data-toggle="tooltip" data-placement="top" title="Lisa vastutajaid juurde"><i class="fa fa-plus"></i></button>

                                </div>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tähtaeg</label>
                                <input class="form-control datepickertask" name="deadline" autocomplete="off"></input>
                            </div>
                            <div id="push_40down" class="form-group">
                                <label>Staatus</label>
                                <select class="form-control" name="status">
                                    <option value="Registreeritud" selected>Registreeritud</option>
                                    <option value="Pooleli">Pooleli</option>
                                    <option value="Tehtud">Tehtud</option>
                                </select>
                            </div>
                            <input type="checkbox" class="minimal-red" name="problem_tocalendar"> Lisa kalendrisse

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
                    <button type="submit" class="btn btn-success" name="submit_task">Salvesta</button>
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
                            <label class="responsible_label">Vastutaja</label>
                            <input id="user_search_edit_old" class="form-control hidden" name="user-old2" autocomplete="off"></input>
                            <table class="table table-hover hidden" style="position: absolute; width: 90%; background: #fff; z-index: 999;">
                                <tbody id="livesearch_edit"></tbody>
                            </table>

                            <div id="add_edit_responsible_list" style="float: left; width: 85%;">

                              <select name="users_edit[]" class="responsible_reader edit_responsible_reader" style="width: 100%" data-order="0">
                                <option value="">- otsi -</option>
                                <?php foreach ($Tasks->getAllUsers() as $user) { ?>
                                  <option value="<?= $user->user_id; ?>"><?= $user->firstname; ?> <?= $user->lastname; ?></option>
                                <?php } ?>
                              </select>


                            </div>

                            <div id="add_edit_button_list">

                              <button data-order="0" name="change_responsible_status" data-done="false" type="button" style="color: #d54e21;" class="btn btn-default user_adding" data-toggle="tooltip" data-placement="right" title="Määra täidetuks" disabled><i class="fa fa-times" disabled></i></button>


                            </div>
                            <button id="add_edit_user_responsible" type="button" class="btn btn-default btn-block user_adding" data-toggle="tooltip" data-placement="top" title="Lisa vastutajaid juurde"><i class="fa fa-plus"></i></button>

                            <button type="button" class="btn btn-default user_adding empty_button"><i class="fa fa-times"></i></button>

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Tähtaeg</label>
                            <input id="deadline_edit" class="form-control datepickertask" name="deadline"
                                   autocomplete="off"></input>
                        </div>

                        <div id="push_40down2" class="form-group">
                            <label>Staatus</label>
                            <select id="status_edit" class="form-control" name="status">
                                <option value="Registreeritud" selected>Registreeritud</option>
                                <option value="Pooleli">Pooleli</option>
                                <option value="Tehtud">Tehtud</option>
                            </select>
                        </div>

                        <input type="checkbox" class="minimal-red" name="problem_tocalendar_2"> Lisa kalendrisse

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
