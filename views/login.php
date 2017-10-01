<?php
	$page_title = "Logi sisse";
	$page_file = "login.php";
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/functions.php");

if(isset($_POST['btn-login']))
{
  ### LOGIMISE VAHELE JÄTMINE ---- KUSTUTADA KUI AUTENTIMINE VALMIS ###
  /*$_SESSION['user_session'] = 1;
  header("Location: index.php");
  exit();*/
  ## LOGIMISE VAHELE JÄTT LÕPP ###

	$uname = strip_tags($_POST['txt_uname_email']);
	$umail = strip_tags($_POST['txt_uname_email']);
	$upass = strip_tags($_POST['txt_password']);

	if($login->doLogin($uname,$umail,$upass))
	{
		header('Location: ' . $_SERVER['BASE_PATH'] . '/index.php');
	}
	else
	{
		$errorlogin = "Vigased andmed!";
	}
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$page_title;?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?=$_SERVER['BASE_PATH'];?>/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=$_SERVER['BASE_PATH'];?>/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?=$_SERVER['BASE_PATH'];?>/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
     <!-- /.login-logo -->
      <div class="login-box-body">
        <?php
          if(isset($errorlogin)) {
            echo($errorlogin);
          }
        ?>
        <p class="login-box-msg"></p>		<div class="login-logo">        <img class="img-responsive center-block" src="<?=$_SERVER['BASE_PATH'];?>/images/logo.png">      </div>
        <form action="login.php" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name="txt_uname_email" placeholder="Kasutajanimi" required />
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
          <input type="password" class="form-control" name="txt_password" placeholder="Parool" />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Jäta meelde
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" name="btn-login" class="btn btn-primary btn-block btn-flat">Logi sisse</button>
			  </div>


            </div><!-- /.col -->
			 <div class="social-auth-links text-center">

			<!--
			<a href="/taat-login"><img src="/public/img/taat_hele.png" alt=""></a>			-->
		<!--<a id="cardAuth" href="javascript:void(0)"><img src="<?=$_SERVER['BASE_PATH'];?>/images/id-kaart-logo.png" alt=""></a>		</div>-->
          </div>
        </form>





      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="<?=$_SERVER['BASE_PATH'];?>/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?=$_SERVER['BASE_PATH'];?>/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="<?=$_SERVER['BASE_PATH'];?>/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
