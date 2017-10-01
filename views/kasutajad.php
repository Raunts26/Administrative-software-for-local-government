<?php
	$page_title = "Kasutajad";
	$page_file = "kasutajad.php";
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/head.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/header.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/sidebar.php"); ?>

<?php


require_once('../inc/class.user.php');
$user = new USER();


if(isset($_GET['delete'])) {
	$uid = strip_tags($_GET['delete']);
	$user->deleteUser($uid);
}

if(isset($_POST['btn-signup']))
{
	$uname = strip_tags($_POST['txt_uname']);
	$firstname = strip_tags($_POST['txt_firstname']);
	$lastname = strip_tags($_POST['txt_lastname']);
	$umail = strip_tags($_POST['txt_umail']);
	$upass = strip_tags($_POST['txt_upass']);
	$ugroup = strip_tags($_POST['txt_ugroup']);
	$rights = $_POST['txt_rights'];

	if($uname=="")	{
		$error[] = "Palun sisesta kasutajanimi !";
	}
	else if($umail=="")	{
		$error[] = "Palun lisa e-maili aadress !";
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Palun sisestage korrektne e-maili aadress !';
	}
	else if($upass=="")	{
		$error[] = "Palun lisage parool !";
	}
	else if($firstname=="")	{
		$error[] = "Palun lisage eesnimi !";
	}
	else if($lastname=="")	{
		$error[] = "Palun lisage perekonnanimi !";
	}
	else if(strlen($upass) < 6){
		$error[] = "Parool peab olema vähemalt 6 tähemärki";
	}
	else if($ugroup == "0"){
		$error[] = "Vali grupp!";
	}
	else
	{
		try
		{
			$stmt = $user->runQuery("SELECT user_name, user_email FROM users WHERE user_name=:uname AND deleted IS NULL OR user_email=:umail AND deleted IS NULL");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);

			if($row['user_name']==$uname) {
				$error[] = "Sama nimega kasutaja juba eksisteerib !";
			}
			else if($row['user_email']==$umail) {
				$error[] = "Selline e-mail juba eksisteerib !";
			}
			else
			{
				if($user->register($uname,$firstname,$lastname,$umail,$upass,$ugroup,$rights)){
					$user->mailSend($uname,$firstname,$lastname,$umail,$upass,$ugroup,$rights);
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}

/* MUUTMINE */
if(isset($_POST['edit_user']))
{
	$uid = strip_tags($_POST['txt_uid']);
	$uname = strip_tags($_POST['txt_uname']);
	$firstname = strip_tags($_POST['txt_firstname']);
	$lastname = strip_tags($_POST['txt_lastname']);
	$umail = strip_tags($_POST['txt_umail']);
	$upass = strip_tags($_POST['txt_upass']);
	$ugroup = strip_tags($_POST['txt_ugroup']);
	$rights = $_POST['txt_rights'];

	if($uname=="")	{
		$error[] = "Palun lisa kasutajanimi !";
	}
	else if($firstname=="")	{
		$error[] = "Palun lisage eesnimi !";
	}
	else if($lastname=="")	{
		$error[] = "Palun lisage perekonnanimi !";
	}
	else if($umail=="")	{
		$error[] = "Palun lisa e-maili aadress !";
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Palun sisesta korrektne e-maili aadress !';
	}
	else if($ugroup == "0"){
		$error[] = "Vali grupp!";
	}
	else
	{

		/*

		if(strlen($upass) < 6){
		 $error[] = "Password must be atleast 6 characters";
	 }

	 */
		$user->editUser($uid, $uname, $firstname, $lastname, $umail, $upass, $ugroup, $rights);
	}
}

$udata = $user->getUsers();
?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Kasutajad
            <small>Kasutajate haldamine</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="valjakud.php"><i class="fa fa-users"></i> Kasutajad</a></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="row">
						<div class="col-sm-12">

							<?php

							if(isset($error))
							{
								foreach($error as $error)
								{
									?>
									<div class="alert alert-danger">
										<i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
									</div>
									<?php
								}
							}
							else if(isset($_GET['joined']))
							{
								?>
								<div class="alert alert-info">
									Kasutaja olemas, <a href='http://haldus.rae.ee'>logi sisse</a> siit!
								</div>
								<?php
							}
							?>

						</div>


            <div class="col-sm-5">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Kasutajate loomine</h3>
                  <div class="box-tools pull-right">
                    <!-- Buttons, labels, and many other things can be placed here! -->
                    <!-- Here is a label for example -->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-12">

                      <form method="post" class="form-signin">
                        <div class="form-group">
                        <input type="text" class="form-control" name="txt_uname" placeholder="Kasutajanimi" value="<?php if(isset($error)){echo $uname;}?>" />
                        </div>
												<div class="form-group">
												<input type="text" class="form-control" name="txt_firstname" placeholder="Eesnimi" value="<?php if(isset($error)){echo $firstname;}?>" />
												</div>
												<div class="form-group">
												<input type="text" class="form-control" name="txt_lastname" placeholder="Perekonnanimi" value="<?php if(isset($error)){echo $lastname;}?>" />
												</div>
                        <div class="form-group">
                        <input type="text" class="form-control" name="txt_umail" placeholder="E-post" value="<?php if(isset($error)){echo $umail;}?>" />
                        </div>
                        <div class="form-group">
                        	<input type="password" class="form-control" name="txt_upass" placeholder="Parool" />
                        </div>
            						<div class="form-group">
            							<select class="form-control" name="txt_ugroup">
            								<option value="0" selected>Vali grupp</option>
            								<option value="1">1</option>
            								<option value="2">2</option>
														<option value="3">3</option>
            								<option value="4">4</option>
            							</select>
            						</div>

												<div class="form-group">
													<?=$Objects->fillSelectForUserAdding();?>
												</div>

                        <div class="clearfix"></div><hr />
                        <div class="form-group pull-right">
                        	<button type="submit" class="btn btn-primary" name="btn-signup">Lisa</button>
                        </div>
                      </form>

                    </div>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->


              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Kasutajagrupid</h3>
                  <div class="box-tools pull-right">
                    <!-- Buttons, labels, and many other things can be placed here! -->
                    <!-- Here is a label for example -->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">

                    <div class="col-sm-12">
                      <ul>
                        <li>4 - Super admin</li>

                        <li>
                          3 - Vallavalitsuse kasutaja – Näeb ja saab muuta:
                          <ul>
                            <li>Hoonepass</li>
                            <li>Hoolduskorraldus</li>
                            <li>Üüripinnad</li>
                            <li>Tööülesanded</li>
                            <li>Mänguväljakud</li>
                            <li>Personal</li>
                            <li>Aruanded</li>
                          </ul>
                        </li>

                        <li>
                          2 - Vallavalitsuse kasutaja grupp
                          <ul>
                            <li>Näeb:
                              <ul>
                                <li>Mobiilside</li>
                              </ul>
                            </li>
                            <li>Saab muuta:
                              <ul>
                                <li>Autod</li>
                                <li>Ruumid</li>
                              </ul>
                            </li>
                          </ul>
                        </li>

                        <li>
                          1 - Allasutuse kasutaja - Näeb ja saab muuta:
                          <ul>
                            <li>Enda asutuse profiil</li>
							<li>Saab registreerida tööülesandeid</li>
                          </ul>
                        </li>

                      </ul>




                    </div>


                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div>

            <div class="col-sm-7">

              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Kasutajate list</h3>
                  <div class="box-tools pull-right">
                    <!-- Buttons, labels, and many other things can be placed here! -->
                    <!-- Here is a label for example -->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">

                    <div class="col-sm-12">

                      <table class="table table-responsive">
        								<? for($i = 0; $i < count($udata); $i++): ?>
        								<tr>
        									<td><strong><?=$udata[$i]->user_name;?></strong></td>
        									<td><?=$udata[$i]->user_email;?></td>
        									<td>
        										<a style="color: #8e8e8e; cursor: pointer;" class="edit-user" data-id="<?=$udata[$i]->user_id;?>" data-name="<?=$udata[$i]->user_name;?>" data-firstname="<?=$udata[$i]->firstname;?>" data-lastname="<?=$udata[$i]->lastname;?>" data-email="<?=$udata[$i]->user_email;?>" data-group="<?=$udata[$i]->group_id;?>" data-toggle="modal" data-target="#usermodal">
        											<span class="glyphicon glyphicon-pencil"></span>
        										</a>
        										 <a style="color: #f00;" href="?delete=<?=$udata[$i]->user_id;?>" onclick="return confirm('Kas oled kindel, et soovid kustutada?')">
        											 <span class="glyphicon glyphicon-trash"></span>
        										 </a>
        									 </td>

        								</tr>
        								<? endfor; ?>
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

      <form method="post" class="form-signin">
				<div class="modal fade" id="usermodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel">Kasutaja muutmine</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-sm-12">

										<input class="form-control" type="hidden" id="user_id" name="txt_uid">

										<div class="form-group">
											<label for="name">Kasutajanimi</label>
											<input class="form-control" type="text" id="user_name" name="txt_uname">
										</div>

										<div class="form-group">
											<label for="name">Eesnimi</label>
											<input class="form-control" type="text" id="user_firstname" name="txt_firstname">
										</div>

										<div class="form-group">
											<label for="name">Perekonnanimi</label>
											<input class="form-control" type="text" id="user_lastname" name="txt_lastname">
										</div>

										<div class="form-group">
											<label for="add_org">E-post</label>
											<input class="form-control" id="user_email" name="txt_umail">
										</div>

										<div class="form-group">
											<label for="add_org">Uus parool</label> (võib tühjaks jätta)
											<input class="form-control" id="user_password" name="txt_upass">
										</div>

										<div class="form-group">
											<label for="phone">Grupp</label>
											<select class="form-control" type="text" id="user_group" name="txt_ugroup">
												<option value="0">Vali grupp</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
											</select>
										</div>

										<div class="form-group">
											<label for="select_rights_edit">Objekti õigused</label>
											<?=$Objects->fillSelectForUserEditing();?>
										</div>

									</div>
								</div>




							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
								<button type="submit" class="btn btn-success" name="edit_user">Salvesta</button>
							</div>
						</div>
					</div>
				</div>
			</form>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/views/_templates/footer.php"); ?>
