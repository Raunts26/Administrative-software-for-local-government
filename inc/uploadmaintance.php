<?php
require_once("functions.php");
$total = count($_FILES['my_files']['name']);

if (!file_exists('../docs/maintance/' . $_POST['attach-id'])) {
    mkdir('../docs/maintance/' . $_POST['attach-id'], 0755, true);
}

if(strlen($_POST['attach-newname']) > 0) {
  if (!file_exists('../docs/maintance/' . $_POST['attach-id'] . "/" . $_POST['attach-newname'])) {
      mkdir('../docs/maintance/' . $_POST['attach-id'] . "/" . $_POST['attach-newname'], 0755, true);
  }
}


for($i = 0; $i < $total; $i++) {

  if(strlen($_POST['attach-newname']) === 0) {
    if (!file_exists('../docs/maintance/' . $_POST['attach-id'] . "/" . pathinfo($_FILES['my_files']['name'][$i], PATHINFO_FILENAME))) {
        mkdir('../docs/maintance/' . $_POST['attach-id'] . "/" . pathinfo($_FILES['my_files']['name'][$i], PATHINFO_FILENAME), 0755, true);
    }
    $target_dir = "../docs/maintance/" . $_POST['attach-id'] . "/" . pathinfo($_FILES['my_files']['name'][$i], PATHINFO_FILENAME) . "/";
  } else {
    $target_dir = "../docs/maintance/" . $_POST['attach-id'] . "/" . $_POST['attach-newname'] . "/";
  }


  $target_file = $target_dir . basename($_FILES["my_files"]["name"][$i]);
  $uploadOk = 1;
  $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  if(strlen($_POST['attach-newname']) > 0) {
    $target_file = $target_dir . $_POST['attach-newname'] . "." . $fileType;
  } else {
    $target_file = $target_dir . pathinfo($_FILES['my_files']['name'][$i], PATHINFO_FILENAME) . "." . $fileType;
  }

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

      if(strlen($_POST['attach-newname']) > 0) {
        $Upload->addMaintanceDocument($_POST['attach-id'], $_POST['attach-newname'], $target_file, $fileType);
      } else {
        $Upload->addMaintanceDocument($_POST['attach-id'], pathinfo($_FILES['my_files']['name'][$i], PATHINFO_FILENAME), $target_file, $fileType);
      }

    } else {
      $_SESSION['error_msg'] = "Tekkis tundmatu viga, anna teada haldajale!";
    }
  }

}
$_SESSION['msg_seen'] = false;
$_SESSION['upload_id'] = $_POST['attach-id'];

header("Location: " . $_SERVER['HTTP_REFERER']);




?>
