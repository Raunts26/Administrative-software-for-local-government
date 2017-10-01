<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
$database = "d16410_haldus";

$mysqli = new mysqli($servername, $server_username, $server_password, $database);

// ID, fname, org, dep, phone, info, comment, mail, job, created

function insertData($name, $org, $dep, $phone, $info, $comment, $mail, $job, $hidden, $mysqli) {
  $stmt = $mysqli->prepare("INSERT INTO personal (fname, org, dep, phone, info, comment, mail, job, hidden, created) VALUES (?,?,?,?,?,?,?,?,?,NOW())");
  $stmt->bind_param("ssssssssi", $name, $org, $dep, $phone, $info, $comment, $mail, $job, $hidden);
  $stmt->execute();
  $stmt->close();
}

function deleteData($id, $mysqli) {
  $stmt = $mysqli->prepare("DELETE FROM personal WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
}

function editData($id, $name, $job, $phone, $info, $comment, $hidden, $mysqli) {
  $stmt = $mysqli->prepare("UPDATE personal SET fname = ?, phone = ?, info = ?, comment = ?, job = ?, hidden = ? WHERE ID = ?");
  $stmt->bind_param("sssssii", $name, $phone, $info, $comment, $job, $hidden, $id);
  $stmt->execute();
  $stmt->close();
}

function getAll($mysqli) {
  $stmt = $mysqli->prepare("SELECT id, fname, org, dep, job, phone, mail FROM personal");
  $stmt->bind_result($id, $name, $org, $dep, $job, $phone, $mail);
  $stmt->execute();
  $array = array();

  while($stmt->fetch()) {
    $data = new stdClass();
    $data->id = $id;
    $data->name = $name;
    $data->org = $org;
    $data->dep = $dep;
    $data->job = $job;
    $data->phone = $phone;
    $data->mail = $mail;
    array_push($array, $data);
  }
  var_dump($array);
  $stmt->close();
}

function getMobileData($org, $mysqli) {
  $stmt = $mysqli->prepare("SELECT id, fname, job, phone, mail, info, comment, hidden FROM personal WHERE org = ?");
  $stmt->bind_param("s", $org);
  $stmt->bind_result($id, $name, $job, $phone, $mail, $info, $comment, $hidden);
  $stmt->execute();
  $array = array();

  while($stmt->fetch()) {
    $data = new stdClass();
    $data->id = $id;
    $data->name = $name;
    $data->job = $job;
    $data->phone = $phone;
    $data->mail = $mail;
    $data->info = $info;
    $data->comment = $comment;
    $data->hidden = $hidden;
    array_push($array, $data);
  }
  echo json_encode($array);
  $stmt->close();
}

function getMobileDataDep($dep, $mysqli) {
  $stmt = $mysqli->prepare("SELECT id, fname, job, phone, mail, info, comment, hidden FROM personal WHERE dep = ? AND org = 'Rae Vallavalitsus'");
  $stmt->bind_param("s", $dep);
  $stmt->bind_result($id, $name, $job, $phone, $mail, $info, $comment, $hidden);
  $stmt->execute();
  $array = array();

  while($stmt->fetch()) {
    $data = new stdClass();
    $data->id = $id;
    $data->name = $name;
    $data->job = $job;
    $data->phone = $phone;
    $data->mail = $mail;
    $data->info = $info;
    $data->comment = $comment;
    $data->hidden = $hidden;
    array_push($array, $data);
  }
  echo json_encode($array);
  //var_dump($array);
  $stmt->close();
}

if(isset($_GET['insertdata'])) {
  //$name, $org, $dep, $phone, $info, $comment, $mail, $mysqli
  insertData($_GET['name'], $_GET['org'], $_GET['dep'], $_GET['phone'], $_GET['info'], $_GET['comment'], $_GET['mail'], $_GET['job'], $_GET['hidden'], $mysqli);
}

if(isset($_GET['edit'])) {
  //$name, $org, $dep, $phone, $info, $comment, $mail, $mysqli
  editData($_GET['edit'], $_GET['name'], $_GET['job'], $_GET['phone'], $_GET['info'], $_GET['comment'], $_GET['hidden'], $mysqli);
}

if(isset($_GET['delete'])) {
  deleteData($_GET['delete'], $mysqli);
}

if(isset($_GET['getdata'])) {
  getMobileData($_GET['getdata'], $mysqli);
}

if(isset($_GET['getall'])) {
  getAll($mysqli);
}

if(isset($_GET['getdepdata'])) {
  getMobileDataDep($_GET['getdepdata'], $mysqli);
}


?>
