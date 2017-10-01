<?php	$page_title = "Kalender";	$page_file = "kalender.php";?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/head.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/header.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/sidebar.php"); ?>

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 <!-- Content Header (Page header) -->
 <section class="content-header">
   <h1>
     Kalender
     <small></small>
   </h1>

	 <ol class="breadcrumb">
		 <li>
			 <button id="add" class="btn btn-box-tool" data-toggle="modal" data-target="#addevent"><span class="label label-success font-ok">Lisa</span></button>
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

	 <div class="col-md-3">
		 <div class="box box-danger">
			 <div class="box-header with-border">
				 <h3 class="box-title">Filter</h3>
				 <div class="box-tools pull-right">
					 <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				 </div><!-- /.box-tools -->

			 </div>
			 <div class="box-body">

				 <div class="form-group">
					 <label>Vali objekt</label>
					 <select id="filter_object" class="form-control status_filter" multiple>
             <?php if($_SESSION['rights'] === NULL): ?>
               <option value="0" selected>Kõik</option>
               <?=$Objects->fillSelectOptions();?>
             <?php else: ?>
               <?=$Objects->fillMySelectOptions();?>
             <?php endif; ?>
					 </select>
				 </div>

				 <div class="form-group">
					 <label>Tüübi kuvamine</label>
					 <ul class="checkbox-list">

						 <li><input type="checkbox" class="minimal-red" name="filter_calendar" value="Hooldustöö"> Hooldustöö</li>
						 <li><input type="checkbox" class="minimal-red" name="filter_calendar" value="Remonttöö"> Remonttöö</li>
						 <li><input type="checkbox" class="minimal-red" name="filter_calendar" value="Garantiitöö"> Garantiitöö</li>
						 <li><input type="checkbox" class="minimal-red" name="filter_calendar" value="Objekti ülevaatus"> Objekti ülevaatus</li>
						 <li><input type="checkbox" class="minimal-red" name="filter_calendar" value="Muu"> Muu</li>
						 <li><input id="make_checked" type="checkbox" class="minimal-red checked" name="filter_calendar" value="0" checked> Kõik</li>

					 </ul>

				 </div>

			 </div>
			 <!-- /.box-body -->
			 <div class="box-footer">
				 <button id="filter_btn" class="btn btn-default pull-right">Filtreeri</button>
			 </div>
		 </div>

	 </div>

	 <div class="col-md-9">
		 <div class="box box-danger">
			 <div class="box-body no-padding">
				 <!-- THE CALENDAR -->
				 <div id="calendar" class="fc fc-ltr fc-unthemed">
				 </div>
			 </div>
			 <!-- /.box-body -->
		 </div>
		 <!-- /. box -->
	 </div>



		</div>
 	</section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Modal -->
<form method="post">

  <div class="modal fade" id="addevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">

        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

  				<h4 class="modal-title" id="myModalLabel">Lisamine</h4>

  			</div>
  		<div class="modal-body">
  			<div class="row">

  				<div class="col-sm-12">
  					<div class="col-sm-6">

  						<div class="form-group">
  							<label>Vali objekt</label>
  							<select class="form-control status_filter" style="width: 100%;" name="object_id[]" multiple>

                  <?php if($_SESSION['rights'] === NULL): ?>
                    <option value="koolidlasteaiad">Kõik koolid ja lasteaiad</option>
                    <option value="koolid">Kõik koolid</option>
                    <option value="lasteaiad">Kõik lasteaiad</option>
                    <?=$Objects->fillSelectOptions();?>
                  <?php else: ?>
                    <?=$Objects->fillMySelectOptions();?>
                  <?php endif; ?>

  							</select>
  						</div>

              <div class="form-group">
                <label>Algus</label>
                <input id="reserv_start" class="form-control" name="start">
              </div>

  					</div>

  					<div class="col-sm-6">

  						<div class="form-group">
  							<label>Vali tüüp</label>
  							<select class="form-control status_filter" style="width: 100%;" name="type">
                  <option value="0" selected>- Vali -</option>
                  <option value="Hooldustöö">Hooldustöö</option>
                  <option value="Garantiitöö">Garantiitöö</option>
                  <option value="Objekti ülevaatus">Objekti ülevaatus</option>
                  <option value="Remonttöö">Remonttöö</option>
                  <option value="Muu">Muu</option>
  							</select>
  						</div>

              <div class="form-group">
                <label>Lõpp</label>
                <input id="reserv_end" class="form-control" name="end">
              </div>

  					</div>

            <div class="col-sm-12">

              <div class="form-group">
  							<label>Kirjeldus</label>
  							<textarea class="form-control" cols="2" name="text"></textarea>
  						</div>



            </div>


  				</div>



  			</div>
  		</div>
  		<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
        <button type="submit" class="btn btn-success" name="submit_calendar">Salvesta</button>
      </div>

  		</div>
    </div>
  </div>

</form>

<!-- Muutmise ja vaatamise modal -->
<div class="modal fade" id="editevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title">Muutmine</h4>

      </div>
    <div class="modal-body">
      <div class="row">

        <div class="col-sm-12">
          <div class="col-sm-6">
            <input id="id_edit" style="display: none;">

            <div class="form-group">
              <label>Objekt</label>
              <select id="object_edit" class="form-control status_filter" style="width: 100%;" name="object_id_edit[]" multiple >

                <?php if($_SESSION['rights'] === NULL): ?>
                  <option value="koolidlasteaiad">Kõik koolid ja lasteaiad</option>
                  <option value="koolid">Kõik koolid</option>
                  <option value="lasteaiad">Kõik lasteaiad</option>
                  <?=$Objects->fillSelectOptions();?>
                <?php else: ?>
                  <?=$Objects->fillMySelectOptions();?>
                <?php endif; ?>

              </select>
            </div>

            <div class="form-group">
              <label>Algus</label>
              <input id="reserv_start_edit" class="form-control" name="start">
            </div>

          </div>

          <div class="col-sm-6">

            <div class="form-group">
              <label>Vali tüüp</label>
              <select id="type_edit" class="form-control status_filter" style="width: 100%;" name="type">
                <option value="0" selected>- Vali -</option>
                <option value="Hooldustöö">Hooldustöö</option>
                <option value="Garantiitöö">Garantiitöö</option>
                <option value="Objekti ülevaatus">Objekti ülevaatus</option>
                <option value="Remonttöö">Remonttöö</option>
                <option value="Muu">Muu</option>
              </select>
            </div>

            <div class="form-group">
              <label>Lõpp</label>
              <input id="reserv_end_edit" class="form-control" name="end">
            </div>

          </div>

          <div class="col-sm-12">

            <div class="form-group">
              <label>Kirjeldus</label>
              <textarea id="text_edit" class="form-control" cols="2" name="text"></textarea>
            </div>



          </div>


        </div>



      </div>
    </div>
    <div class="modal-footer">
      <button id="delete_event" type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-trash"></i></button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
      <button id="update_event" type="button" class="btn btn-success">Salvesta</button>
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
