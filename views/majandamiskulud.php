<?php
	$page_title = "Majandamiskulud";
	$page_file = "majandamiskulud.php";
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
            Majandamiskulud
            <small>Majandamiskulude jälgimine</small>
          </h1>

          <ol class="breadcrumb">
						<li>
							<button id="add" class="btn btn-box-tool" data-toggle="modal" data-target="#myModal"><span class="label label-success font-ok">Lisa kulu</span></button>
							<button id="add" class="btn btn-box-tool" data-toggle="modal" data-target="#myModal"><span class="label label-success font-ok">Lisa artikkel</span></button>
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
											<div class="form-group">
												<label>Vali objekt</label>
												<select id="object_filter" class="form-control status_filter" multiple="multiple">
													<?php foreach ($Objects->objectNames as $object): ?>
															<option value="<?= $object->id; ?>"><?= $object->name; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group">
												<label>Vali artikkel</label>
												<select class="form-control status_filter" multiple="multiple">
													<?php foreach ($Management->main_articles as $object): ?>
															<option value="<?= $object->id; ?>"><?= $object->name; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group">
												<label>Vali periood</label>
												<select class="form-control">
													<option>- Vali -</option>
													<option>Kuu</option>
													<option>Kvartal</option>
													<option>Poolaasta</option>
													<option>Aasta</option>
												</select>
											</div>

										</div>

									</div>
								</div><!-- /.box-body -->
							</div><!-- /.box -->

						</div>

						<div class="col-sm-9">

							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Vorm</h3>
									<div class="box-tools pull-right">
										<!-- Buttons, labels, and many other things can be placed here! -->
										<!-- Here is a label for example -->
										<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

									</div><!-- /.box-tools -->
								</div><!-- /.box-header -->
								<div class="box-body">
									<div class="row">

										<div id="form_inner" class="col-sm-12">
											Vali arhiivist vorm või täida uus!
										</div>


									</div>
								</div><!-- /.box-body -->
							</div><!-- /.box -->

						</div>


					</div>

				</section><!-- /.content -->

  </div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>
