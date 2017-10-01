<?php
	$page_title = "Ametiautode broneerimine";
	$page_file = "autod.php";
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
            Autod
            <small>Ametiautode broneerimine</small>
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
                  <label for="select-1">Vali nädal</label>
                  <select id="week" class="form-control">
                    <option value="0" id="this-week">Praegune nädal</option>
                    <option value="7" id="next-week">Järgmine nädal</option>
                    <option value="14" id="two-week">Ülejärgmine nädal</option>
                  </select>

                  <label for="select-1">Vali auto</label>

                        <select id='type' class="form-control">
                          <option> ----- </option>
                          <option value='1'>203BMT(Haridus)</option>
                          <option value='2'>204BMT(Sotsiaalamet)</option>
                          <option value='3'>205BMT(Ehitus)</option>
                          <option value='4'>206BMT(Keskkond)</option>
                          <option value='5'>395BGV(Sotsiaalamet)</option>
                          <option value='6'>396BGV(Sotsiaalamet)</option>
                          <option value='7'>207BMT(Planeerimine)</option>
                        </select>

                  <label for="select-1">Registreerija</label>
                  <input id='name' class='inputs form-control' type="text">

                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div>

            <div class="col-sm-9">

							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Broneeringud</h3>
									<div class="box-tools pull-right">
										<!-- Buttons, labels, and many other things can be placed here! -->
										<!-- Here is a label for example -->
										<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /.box-tools -->
								</div><!-- /.box-header -->
								<div class="box-body">
                  <div class="row">
										<div class="col-sm-12" id="answer"></div>

                    <div class="col-sm-12">
                      <div id="content">

                      </div>
                    </div>

                  </div>
								</div><!-- /.box-body -->
								<div class="box-footer">
								</div>
							</div><!-- /.box -->


            </div>

          </div>



          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- Modal -->
      <div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Ametiautode broneerimine</h4>
            </div>
            <div class="modal-body">
              <span id="blockerName"><strong>Broneerija:</strong></span> <span id="blockerNameValue"></span>
              <br>
              <span id="blockerDate"><strong>Kuupäev:</strong></span> <span id="blockerDateValue"></span>
              <br>
              <span id="blockerHour"><strong>Aeg:</strong></span> <span id="blockerHourValue"></span>


              <br>
              <span id="blockerTablet" style="display: none;"><strong>Hõivatav kogus:</strong></span> <span id="blockerTabletValue"></span>
              <br>
              <span id="blockerAmount" style="display: none;"><strong>Tahvelarvuteid saadaval:</strong></span> <span id="blockerAmountValue"></span>
              <br class="tabletamount"><br class="tabletamount">

              <div id="currentblockers">

              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
              <button type="button" id="savetime" data-dismiss="modal" class="btn btn-primary">Salvesta</button>
              <button type="button" id="deletetime" data-dismiss="modal" class="btn btn-danger pull-left">Kustuta</button>
            </div>
          </div>
        </div>
      </div>






<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>
<script src="js/timetable.js"></script>