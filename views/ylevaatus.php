<?php
	$page_title = "Objekti ülevaatuse aeg";
	$page_file = "ylevaatus.php";
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
            Ülevaatus
            <small>Objekti ülevaatus aja määramine</small>
          </h1>

          <ol class="breadcrumb">
						<li>
							<button id="add" class="btn btn-box-tool" data-toggle="modal" data-target="#myModal"><span class="label label-success font-ok">Lisa</span></button>
						</li>
          </ol>

        </section>

        <!-- Main content -->
        <section class="content">

          <div class="row">

            <div class="col-sm-12">

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

                      <table id="inspection" class="table table-striped table-no-border">
              				  <thead id="table_head" style="display: none;">
              						<tr>
              							<th>Objekt</th>
              							<th>Pakutud aeg</th>
              							<th>Sobiv aeg</th>
              							<th>Märkus</th>
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



          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
   <!-- Lisamise modal -->
   <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document">
   <div class="modal-content">
   <form method="post">
   <div class="modal-header">				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="modal-title" id="myModalLabel">Lisamine</h4>				      </div>
   <div class="modal-body">
   <div class="row">
   <div class="col-sm-6">
   <div class="form-group">
   <label>Ettevõte</label>
   <input class="form-control" name="name">
   </div>
   <div class="form-group">
   <label>Reg.nr</label>
   <input class="form-control" name="regnr">
   </div>
   <div class="form-group">
   <label>Aadress</label>
   <input class="form-control" name="adress">
   </div>
   </div>
   <div class="col-sm-6">
   <div class="form-group">
   <label>Kontaktisik</label>
   <input class="form-control" name="contact">
   </div>
   <div class="form-group">
   <label>Telefon</label>
   <input class="form-control" name="phone">
   </div>
   <div class="form-group">
   <label>E-Post</label>
   <input class="form-control" name="mail">
   </div>
   </div>
   <div class="col-sm-12">
   <div class="form-group">
   <label>Selgitus</label>
   <textarea class="form-control" name="description"></textarea>
   </div>
   </div>
   <div class="col-sm-6">
   <label>Lepinguline</label>
   <table>
   <tr>
   <td><input type="radio" class="minimal-red" name="contract" value="Jah">Jah</a></td>
   <td><input type="radio" class="minimal-red" name="contract" value="Ei">Ei</a></td>
   </tr>
   </table>
   </div>
   </div>
   </div>
   <div class="modal-footer">
   <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
   <button type="submit" class="btn btn-success" name="submit_service">Salvesta</button>
   </div>
   </form>
   </div>
   </div>
   </div>

			<!-- Muutmise modal -->
			<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
						<form method="post">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title">Muutmine</h4>
				      </div>
				      <div class="modal-body">
							<div class="row">
								<input type="hidden" id="service_id">
									<div class="col-sm-6">
										<div class="form-group">
												<label>Ettevõte</label>
												<input id="name_edit" class="form-control" name="name">
														</div>
					<div class="form-group">
							<label>Reg.nr</label>
								<input id="regnr_edit" class="form-control" name="regnr">
										</div>
							<div class="form-group">
   <label>Aadress</label>
   <input id="adress_edit" class="form-control" name="adress">
   </div>
   </div>
   <div class="col-sm-6">
   <div class="form-group">
   <label>Kontaktisik</label>
   <input id="contact_edit" class="form-control" name="contact">
   </div>
   <div class="form-group">
   <label>Telefon</label>
   <input id="phone_edit" class="form-control" name="phone_edit">
   </div>
   <div class="form-group">
   <label>E-Post</label>
   <input id="mail_edit" class="form-control" name="mail">
   </div>
   </div>
   <div class="col-sm-12">
   <div class="form-group">
   <label>Selgitus</label>
   <textarea id="description_edit" class="form-control" name="description"></textarea>
   </div>
   </div>
   <div class="col-sm-6">
   <label>Lepinguline</label>
   <table>
   <tr>
   <td><input type="radio" class="minimal-red" name="contract_edit" value="Jah">Jah</a></td>
   <td><input type="radio" class="minimal-red" name="contract_edit" value="Ei">Ei</a></td>
   </tr>
   </table>
   </div>
   </div>
   </div>
   <div class="modal-footer">
    <button id="close_edit" type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
	<button id="save_edit" type="button" class="btn btn-success" name="submit_service">Salvesta</button>
   </div>
   </form>
   </div>
   </div>
   </div>


<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>
