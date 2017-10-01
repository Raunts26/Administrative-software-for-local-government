<?php
require_once("functions.php");

$total = count($_FILES['my_files']['name']);

if (!file_exists('../docs/' . $_POST['attach-what'] . "/" . $_POST['attach-id'])) {
    mkdir('../docs/' . $_POST['attach-what'] . "/" . $_POST['attach-id'], 0755, true);
}

if (!file_exists('../docs/' . $_POST['attach-what'] . "/" . $_POST['attach-id'] . "/" . $_POST['attach-newname'])) {
    mkdir('../docs/' . $_POST['attach-what'] . "/" . $_POST['attach-id'] . "/" . $_POST['attach-newname'], 0755, true);
}

$target_dir = "../docs/" . $_POST['attach-what'] . "/" . $_POST['attach-id'] . "/" . $_POST['attach-newname'] . "/";

for($i = 0; $i < $total; $i++) {

  $target_file = $target_dir . basename($_FILES["my_files"]["name"][$i]);
  $uploadOk = 1;
  $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  $target_file = $target_dir . $_POST['attach-newname'] . "." . $fileType;

  /*if (file_exists($target_file)) {
    $_SESSION['error_msg'] =  "Fail juba eksisteerib.";
    $uploadOk = 0;
  }*/

  if($fileType != "bdoc" && $fileType != "doc" && $fileType != "pdf"
  && $fileType != "docx" ) {
      $_SESSION['error_msg'] =  "Ainult bdoc, doc, pdf, docx failid on lubatud.";
      $uploadOk = 0;
  }

  if ($_FILES["my_files"]["size"][$i] > 10000000) {
    $_SESSION['error_msg'] =  "Fail liiga suur.";
    $uploadOk = 0;
  }

  if ($uploadOk == 0) {
    $_SESSION['error_msg'] = "Faili ei laetud üles!";
  } else {
    if (move_uploaded_file($_FILES["my_files"]["tmp_name"][$i], $target_file)) {
      $_SESSION['success_msg'] = "Fail ". basename( $_FILES["my_files"]["name"][$i]). " on üles laetud.<br>";
      $Upload->addDocument($_POST['attach-type'], $_POST['attach-what'], $_POST['attach-id'], $_POST['attach-newname'], $target_file, $fileType);
    } else {
      $_SESSION['error_msg'] = "Tekkis tundmatu viga, anna teada haldajale!";
    }
  }

}
$_SESSION['msg_seen'] = false;
$_SESSION['upload_id'] = $_POST['attach-id'];

header("Location: " . $_SERVER['HTTP_REFERER']);




?>
