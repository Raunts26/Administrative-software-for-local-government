<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/tasks.class.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/PHPMailerAutoload.php');

  $database = "d16410_haldus";
  $mysqli = new mysqli($servername, $server_username, $server_password, $database);

  $Tasks = new Tasks($mysqli);

  $Tasks->sendMailPriorityHigh();
  $Tasks->sendMailPriorityMedium();
  $Tasks->sendMailPriorityLow();
?>
