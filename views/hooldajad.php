<?php
	$page_title = "Hooldajad";
	$page_file = "hooldajad.php";
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
            Hooldajad
            <small>Hooldus ja avariitööde korraldajad ning nende kontaktid</small><p>
						<!--
	          <small>Siin peab olema filter "asutus" ja "valdkond"</small><p>
						<small>Kontakt isik - 1 või mitu. Andmed Nimi, telefon, email,valdkond, märkused</small><p>
						-->
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

		  <div class="col-sm-3">

			  <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Filter</h3>
                  <div class="box-tools pull-right">
                    <!-- Buttons, labels, and many other things can be placed here! -->
                    <!-- Here is a label for example -->
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

												<label>Vali valdkond</label>
												<select id="status_filter" class="form-control status_filter" multiple="multiple">
														<option value="El.võrgutasu">El.võrgutasu</option>
														<option value="El.käiduleping">El.käiduleping</option>
														<option value="El.käiduleping">El.elektrienergia</option>
														<option value="Elekter">Elekter</option>
														<option value="Ventilatsioon">Ventilatsioon</option>
														<option value="Porivaibad">Porivaibad</option>
														<option value="Aknakatted">Aknakatted</option>
														<option value="Sideteenused">Sideteenused</option>
														<option value="Kütteseadmed">Kütteseadmed</option>
														<option value="Vesi ja kanal">Vesi ja kanal</option>
														<option value="Valvesignalisatsioon">Valvesignalisatsioon</option>
														<option value="Tulekahjusignalisatsioon">Tulekahjusignalisatsioon</option>
														<option value="Lifti hooldus">Lifti hooldus</option>
														<option value="Prügivedu">Prügivedu</option>
														<option value="Tuleohutus">Tuleohutus</option>
														<option value="Liugväravad ja tõkkepuud">Liugväravad ja tõkkepuud</option>
														<option value="Mänguväljakud">Mänguväljakud</option>
														<option value="Kahjuritõrje">Kahjuritõrje</option>
												</select>

                    </div>

                  </div>
                </div><!-- /.box-body -->
								<div class="box-footer">
									<button id="filter_services" class="btn btn-default pull-right">Filtreeri</button>
								</div>
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

                      <table id="service" class="table table-striped table-no-border">
              				  <thead id="table_head" style="display: none;">
              						<tr>
														<th>Asutus</th>
              							<th>Ettevõte</th>
              							<th>Valdkond</th>
              							<th>Kontaktid</th>
              							<th>Lepingu sisu</th>
														<th>Tasu</th>
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



          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->


		<!-- Kontaktide vaatamise modal -->
    <div class="modal fade" id="contacts_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
				    <h4 class="modal-title" id="myModalLabel">Kontaktide vaatamine</h4>
					</div>

          <div class="modal-body">
						<table class="table table-responsive table-hover">
							<thead>
								<th>Nimi</th>
								<th>Telefon</th>
								<th>Email</th>
								<th>Märkused</th>
							</thead>

							<tbody id="contacts_body">

							</tbody>


						</table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
          </div>
        </div>
      </div>
    </div>


   <!-- Lisamise modal -->
   <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
	   <div class="modal-dialog" role="document">
		   <div class="modal-content">
			   <form id="addform" method="post">

				   <div class="modal-header rmv-pdg-bottom">
						 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							 <span aria-hidden="true">&times;</span>
						 </button>

						 <!-- Custom Tabs -->
             <div class="nav-tabs-custom modal-nav">
               <ul class="nav nav-tabs">
                 <li class="active"><a href="#company" data-toggle="tab">Ettevõte</a></li>
                 <li><a href="#company_contacts" data-toggle="tab">Kontaktid</a></li>

               </ul>
             </div><!-- nav-tabs-custom -->


					 </div>

				   <div class="modal-body">
					   <div class="row">

							 <div class="tab-content">
								 <div class="tab-pane active" id="company">

									 <div class="col-sm-6">

										 <select class="form-control" id="object_type" name="object_type" style="display: none;">
												 <option value="0" selected>- Vali -</option>
												 <option value="1">Objekt</option>
												 <?php if ($_SESSION['rights'] === NULL): ?>
														 <option value="2">Munitsipaalpind</option>
														 <option value="3">Äripind</option>
														 <option value="4">Mänguväljak</option>
												 <?php endif; ?>
										 </select>

										 <div class="form-group">
											 <label>Vali asutus</label>
											 <select id="object_service" name="object_service" class="form-control status_filter" style="width: 100%;">
												 <?php foreach ($Objects->objectNames as $object): ?>
														 <option value="<?= $object->id; ?>"><?= $object->name; ?></option>
												 <?php endforeach; ?>
											 </select>
										 </div>

									   <div class="form-group">
										   <label>Ettevõte</label>
										   <input class="form-control" name="name">
									   </div>

									   <div class="form-group">
										   <label>Valdkond</label>
											 <select name="field" class="form-control status_filter" style="width: 100%;">
													 <option value="El.võrgutasu">El.võrgutasu</option>
														<option value="El.käiduleping">El.käiduleping</option>
														<option value="El.käiduleping">El.elektrienergia</option>
														<option value="Elekter">Elekter</option>
														<option value="Ventilatsioon">Ventilatsioon</option>
														<option value="Porivaibad">Porivaibad</option>
														<option value="Aknakatted">Aknakatted</option>
														<option value="Sideteenused">Sideteenused</option>
														<option value="Kütteseadmed">Kütteseadmed</option>
														<option value="Vesi ja kanal">Vesi ja kanal</option>
														<option value="Valvesignalisatsioon">Valvesignalisatsioon</option>
														<option value="Tulekahjusignalisatsioon">Tulekahjusignalisatsioon</option>
														<option value="Lifti hooldus">Lifti hooldus</option>
														<option value="Prügivedu">Prügivedu</option>
														<option value="Tuleohutus">Tuleohutus</option>
														<option value="Liugväravad ja tõkkepuud">Liugväravad ja tõkkepuud</option>
														<option value="Mänguväljakud">Mänguväljakud</option>
														<option value="Kahjuritõrje">Kahjuritõrje</option>
											 </select>

									   </div>

								   </div>

								   <div class="col-sm-6">

										 <div class="form-group">
										   <label>Lepingu sisu</label>
										   <input class="form-control" name="contract">
									   </div>

										 <div class="form-group">
											 <label>Lepingu tähtaeg</label>
											 <input class="form-control datepickertask" name="deadline">
										 </div>

										 <div class="form-group">
											 <div class="row">

												 <div class="col-sm-6">
													 <label>Tasu</label>
													 <input class="form-control" name="pay">
												 </div>

												 <div class="col-sm-6">
													 <label>Periood</label>
													 <select class="form-control" name="period">
														 <option value="kuu">kuu</option>
														 <option value="kvartal">kvartal</option>
														 <option value="1pa">1pa</option>
														 <option value="1a">1a</option>
													 </select>
												 </div>

											 </div>
										 </div>

								   </div>

								   <div class="col-sm-12">
									   <div class="form-group">
										   <label>Märkused</label>
										   <textarea class="form-control" name="comments"></textarea>
									   </div>
								   </div>



								 </div>

								 <div class="tab-pane" id="company_contacts">

									 <div class="col-sm-6">

										 <div class="form-group">
											 <label>Nimi</label>
											 <input id="c_name_add" type="text" class="form-control">
										 </div>

										 <!--<div class="form-group">
											 <label>Valdkond</label>
											 <input id="c_field_add" class="form-control">
										 </div>-->

									 </div>

									 <div class="col-sm-6">

										 <div class="form-group">
											 <label>Telefon</label>
											 <input id="c_phone_add" class="form-control">
										 </div>


									 </div>

									 <div class="col-sm-12">
										 <div class="form-group">
											 <label>Email</label>
											 <input id="c_email_add" class="form-control">
										 </div>
										 <div class="form-group">
											 <label>Märkused</label>
											 <textarea id="c_comments_add" class="form-control"></textarea>
										 </div>
										 <button type="button" class="btn btn-default pull-right" id="add_to_contact">Lisa</button>
									 </div>


									 <div class="col-sm-12">
										 <table id="contacts_add_table" class="table table-responsive">
											 <thead>
											 	<th>Nimi</th>
											 	<th>Telefon</th>
											 	<th>Email</th>
												<th>Märkused</th>
										 		<th>x</th>
											 </thead>

											 <tbody id="here_contacts">

											 </tbody>

										 </table>
									 </div>


								 </div>


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
<div class="modal fade" id="editmodal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

				<div class="modal-header rmv-pdg-bottom">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

					<!-- Custom Tabs -->
					<div class="nav-tabs-custom modal-nav">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#company_edit" data-toggle="tab">Ettevõte</a></li>
							<li><a href="#company_contacts_edit" data-toggle="tab">Kontaktid</a></li>
							<li><a href="#company_files_edit" data-toggle="tab">Failid</a></li>

						</ul>
					</div><!-- nav-tabs-custom -->

				</div>

				<div class="modal-body">

					<input class="form-control" id="service_id" type="hidden" name="name">


				<div class="row">

					<div class="tab-content">
						<div class="tab-pane active" id="company_edit">

							<div class="col-sm-6">

								<div class="form-group">
									<label>Vali asutus</label>
									<select id="object_service_edit" class="form-control status_filter" style="width: 100%;">
										<?php foreach ($Objects->objectNames as $object): ?>
												<option value="<?= $object->id; ?>"><?= $object->name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="form-group">
									<label>Ettevõte</label>
									<input id="name_edit" class="form-control" name="name">
								</div>

								<div class="form-group">
									<label>Valdkond</label>
									<select id="field_edit" class="form-control status_filter" style="width: 100%;">
											<option value="El.võrgutasu">El.võrgutasu</option>
														<option value="El.käiduleping">El.käiduleping</option>
														<option value="El.käiduleping">El.elektrienergia</option>
														<option value="Elekter">Elekter</option>
														<option value="Ventilatsioon">Ventilatsioon</option>
														<option value="Porivaibad">Porivaibad</option>
														<option value="Aknakatted">Aknakatted</option>
														<option value="Sideteenused">Sideteenused</option>
														<option value="Kütteseadmed">Kütteseadmed</option>
														<option value="Vesi ja kanal">Vesi ja kanal</option>
														<option value="Valvesignalisatsioon">Valvesignalisatsioon</option>
														<option value="Tulekahjusignalisatsioon">Tulekahjusignalisatsioon</option>
														<option value="Lifti hooldus">Lifti hooldus</option>
														<option value="Prügivedu">Prügivedu</option>
														<option value="Tuleohutus">Tuleohutus</option>
														<option value="Liugväravad ja tõkkepuud">Liugväravad ja tõkkepuud</option>
														<option value="Mänguväljakud">Mänguväljakud</option>
														<option value="Kahjuritõrje">Kahjuritõrje</option>
									</select>

								</div>

							</div>

							<div class="col-sm-6">

								<div class="form-group">
									<label>Lepingu sisu</label>
									<input id="contract_edit" class="form-control" name="contract">
								</div>

								<div class="form-group">
									<label>Lepingu tähtaeg</label>
									<input id="deadline_edit" class="form-control datepickertask" name="deadline">
								</div>


								<div class="form-group">
									<div class="row">

										<div class="col-sm-6">
											<label>Tasu</label>
											<input id="pay_edit" class="form-control">
										</div>

										<div class="col-sm-6">
											<label>Periood</label>
											<select id="period_edit" class="form-control">
												<option value="kuu">kuu</option>
												<option value="kvartal">kvartal</option>
												<option value="1pa">1pa</option>
												<option value="1a">1a</option>
											</select>
										</div>

									</div>
								</div>


							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label>Märkused</label>
									<textarea id="comments_edit" class="form-control" name="comments"></textarea>
								</div>
							</div>



						</div>

						<div class="tab-pane" id="company_contacts_edit">

							<div class="col-sm-6">

								<input id="contact_edit_id" type="hidden">

								<div class="form-group">
									<label>Nimi</label>
									<input class="form-control" id="c_name_edit">
								</div>


							</div>

							<div class="col-sm-6">

								<div class="form-group">
									<label>Telefon</label>
									<input class="form-control" id="c_phone_edit">
								</div>


							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label>Email</label>
									<input class="form-control" id="c_email_edit">
								</div>
								<div class="form-group">
									<label>Märkused</label>
									<textarea class="form-control" id="c_comments_edit"></textarea>
								</div>
								<div class="btn-group pull-right">
									<button type="button" class="btn btn-default" id="save_to_contact_edit" disabled>Salvesta</button>
									<button type="button" class="btn btn-default" id="add_to_contact_edit">Lisa</button>

								</div>
							</div>

							<div class="col-sm-12">
								<table id="contacts_add_table_edit" class="table table-hover">
									<thead>
									 <th>Nimi</th>
									 <th>Email</th>
									 <th>Telefon</th>
									 <th>Märkused</th>
									 <th>x</th>
									</thead>

									<tbody id="here_contacts_edit">

									</tbody>

								</table>
							</div>


						</div>


						<div class="tab-pane" id="company_files_edit">
							<div class="col-sm-12">
								<ul id="doc-list" class="doc-list"></ul>
							</div>

							<div class="col-sm-12">

								<form id="doc_form" name="form" action="../inc/uploadservice.php" method="POST" enctype="multipart/form-data">
			            <div class="input-group">
			              <input type="text" id="attach-newname" name="attach-newname" class="form-control" placeholder="Dokumendi nimi">
			              <div class="input-group-btn">
			                <button id="attach-btn" class="btn btn-default" type="button">
			                  <span class="glyphicon glyphicon-paperclip"></span>
			                </button>
			                <button id="add-attach-" type="submit" class="btn btn-default">
			                  <span class="glyphicon glyphicon-cloud-upload"></span>
			                </button>
			              </div>
			            </div>

			            <span id="attach-names"></span>
			            <input id="attach-new" type="file" name="my_files[]" multiple="">
			            <input id="attach-id" type="hidden" name="attach-id" value="">

			          </form>

							</div>
						</div>


					</div>


				</div>

				</div>

				<div class="modal-footer">
					<button id="close_edit" type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
					<button id="save_edit" type="button" class="btn btn-success" name="submit_service">Salvesta</button>
				</div>
		</div>
	</div>
</div>


<!-- Vaatamise modal -->
<div class="modal fade" id="viewmodal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="editform" method="post">

				<div class="modal-header rmv-pdg-bottom">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

					<!-- Custom Tabs -->
					<div class="nav-tabs-custom modal-nav">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#company_view" data-toggle="tab">Ettevõte</a></li>
							<li><a href="#company_contacts_view" data-toggle="tab">Kontaktid</a></li>
							<li><a href="#company_files_view" data-toggle="tab">Failid</a></li>

						</ul>
					</div><!-- nav-tabs-custom -->

				</div>

				<div class="modal-body">

				<div class="row">

					<div class="tab-content">
						<div class="tab-pane active" id="company_view">

							<div class="col-sm-6">

								<div class="form-group">
									<label>Vali asutus</label>
									<select id="object_service_view" class="form-control status_filter" style="width: 100%;" disabled>
										<?php foreach ($Objects->objectNames as $object): ?>
												<option value="<?= $object->id; ?>"><?= $object->name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="form-group">
									<label>Ettevõte</label>
									<input id="name_view" class="form-control" name="name" disabled>
								</div>

								<div class="form-group">
									<label>Valdkond</label>
									<select id="field_view" class="form-control status_filter" style="width: 100%;" disabled>
														<option value="El.võrgutasu">El.võrgutasu</option>
														<option value="El.käiduleping">El.käiduleping</option>
														<option value="El.käiduleping">El.elektrienergia</option>
														<option value="Elekter">Elekter</option>
														<option value="Ventilatsioon">Ventilatsioon</option>
														<option value="Porivaibad">Porivaibad</option>
														<option value="Aknakatted">Aknakatted</option>
														<option value="Sideteenused">Sideteenused</option>
														<option value="Kütteseadmed">Kütteseadmed</option>
														<option value="Vesi ja kanal">Vesi ja kanal</option>
														<option value="Valvesignalisatsioon">Valvesignalisatsioon</option>
														<option value="Tulekahjusignalisatsioon">Tulekahjusignalisatsioon</option>
														<option value="Lifti hooldus">Lifti hooldus</option>
														<option value="Prügivedu">Prügivedu</option>
														<option value="Tuleohutus">Tuleohutus</option>
														<option value="Liugväravad ja tõkkepuud">Liugväravad ja tõkkepuud</option>
														<option value="Mänguväljakud">Mänguväljakud</option>
														<option value="Kahjuritõrje">Kahjuritõrje</option>
									</select>

								</div>

							</div>

							<div class="col-sm-6">

								<div class="form-group">
									<label>Lepingu sisu</label>
									<input id="contract_view" class="form-control" name="contract" disabled>
								</div>

								<div class="form-group">
									<label>Lepingu tähtaeg</label>
									<input id="deadline_view" class="form-control datepickertask" name="deadline" disabled>
								</div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<label>Tasu</label>
											<input id="pay_view" class="form-control" disabled>
										</div>
										<div class="col-sm-6">
											<label>Periood</label>
											<input id="period_view" class="form-control" disabled>
										</div>
									</div>
								</div>

							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label>Märkused</label>
									<textarea id="comments_view" class="form-control" name="comments" disabled></textarea>
								</div>
							</div>



						</div>

						<div class="tab-pane" id="company_contacts_view">

							<div class="col-sm-12">
								<table id="contacts_add_table_view" class="table table-hover">
									<thead>
									 <th>Nimi</th>
									 <th>Email</th>
									 <th>Telefon</th>
									 <th>Märkused</th>
									 <th>x</th>
									</thead>

									<tbody id="here_contacts_view">

									</tbody>

								</table>
							</div>


						</div>

						<div class="tab-pane" id="company_files_view">
							<div class="col-sm-12">
								<ul id="doc-list-view" class="doc-list"></ul>
							</div>


						</div>


					</div>


				</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
				</div>

			</form>
		</div>
	</div>
</div>




<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>
<script>
$('#addform').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    return false;
  }
});
$('#editform').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    return false;
  }
});
</script>

<script>

/*document.querySelector("#object_select").value = objectid;*/
//Modal avama

$(window).on('load', function() {
		$('#editmodal').modal('show');
		var objectid = <?=$_SESSION['upload_id'];?>;
		document.querySelector("#service_id").value = objectid;
});


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
