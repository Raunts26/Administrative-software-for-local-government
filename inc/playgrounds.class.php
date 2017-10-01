<?php
class Playgrounds {

    private $connection;
    public $playData;
    public $allAreas;

    function __construct($mysqli){
        $this->connection = $mysqli;
        $this->getPlayData();
        $this->getAreas();

    }

    function insertArea($name) {
      $stmt = $this->connection->prepare("INSERT INTO area (name) VALUES (?)");
      $stmt->bind_param("s", $name);
      $stmt->execute();
      $stmt->close();
    }

    function insertData($area, $name, $address, $contact, $number, $attr) {
      $stmt = $this->connection->prepare("INSERT INTO playgrounds (area_id, name, address, contact, phone, attractions) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("isssss", $area, $name, $address, $contact, $number, $attr);
      $stmt->execute();
      $stmt->close();
    }

    function updateData($id, $address, $contact, $number, $attr) {
      $stmt = $this->connection->prepare("UPDATE playgrounds SET address = ?, contact = ?, phone = ?, attractions = ? WHERE id = ?");
      $stmt->bind_param("ssssi", $address, $contact, $number, $attr, $id);
      $stmt->execute();
      $stmt->close();
    }

    function deletePlayground($id) {
      $stmt = $this->connection->prepare("UPDATE playgrounds SET deleted = NOW() WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $stmt->close();
    }

    function removeDoc($id) {
      $stmt = $this->connection->prepare("UPDATE documents SET deleted = NOW() WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $stmt->close();
    }

    function uploadPicture() {

      $target_dir = "images/playgrounds/";
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $target_file = $target_dir . $this->connection->insert_id . "-playground" . ".jpg";
      //echo($this->connection->insert_id);

      if (file_exists($target_file)) {
          $_SESSION['error_msg'] = "Fail eksisteerib juba.";
          $uploadOk = 0;
      }

      if ($_FILES["fileToUpload"]["size"] > 10000000) {
          $_SESSION['error_msg'] = "Faili suurus ei tohi olla üle 10mb.";
          $uploadOk = 0;
      }

      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" ) {
          $_SESSION['error_msg'] = "Ainult jpg, png, jpeg ja gif failid on lubatud.";
          $uploadOk = 0;
      }

      if ($uploadOk == 0) {
          $_SESSION['error_msg'] = "Faili ei laetud üles!";
      } else {
          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
              $_SESSION['success_msg'] = "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles.";
          } else {
              $_SESSION['error_msg'] = "Tekkis tundmatu viga, anna haldajale teada.";
          }
      }
      $_SESSION['msg_seen'] = false;
      header("Location: valjakud.php");

    }

    function getAreas() {
      $stmt = $this->connection->prepare("SELECT * FROM area");
      $stmt->bind_result($id, $name);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $ob = new stdClass();
        $ob->id = $id;
        $ob->name = $name;

        array_push($array, $ob);
      }

      $this->allAreas = $array;

      $stmt->close();
    }

    function getPlayData() {
      $stmt = $this->connection->prepare("SELECT * FROM playgrounds WHERE deleted IS NULL");
      $stmt->bind_result($id, $area, $name, $address, $contact, $phone, $attractions, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $ob = new stdClass();
        $ob->id = $id;
        $ob->area = $area;
        $ob->name = $name;
        $ob->address = $address;
        $ob->contact = $contact;
        $ob->phone = $phone;
        $ob->attractions = $attractions;

        array_push($array, $ob);
      }

      $this->playData = $array;

      $stmt->close();
    }

    function getPlayDocs($belong) {
      $stmt = $this->connection->prepare("SELECT id, name, link, extension FROM documents WHERE category = 'playgrounds' AND belong_id = ? AND deleted IS NULL ORDER BY extension");
      $stmt->bind_param("i", $belong);
      $stmt->bind_result($id, $name, $link, $ext);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $ob = new stdClass();
        $ob->id = $id;
        $ob->name = $name;
        $ob->link = $link;
        $ob->ext = $ext;

        array_push($array, $ob);
      }

      echo json_encode($array);
      $stmt->close();
    }

    function fillPlaySelect() {

      $html = "<select id='play_select' class='form-control'>";
      $html .= "<option selected> - Vali - </option>";

      foreach ($this->playData as $data) {
        $html .= "<option value='" . $data->id . "'>" . $data->name . ", " . $data->address . "</option>";
      }

      $html .= "</select>";

      echo($html);

    }

    function fillAreaSelect() {
      $html = "<select id='area_select' name='area_select' class='form-control'>";
      $html .= "<option selected> - Vali - </option>";

      foreach ($this->allAreas as $data) {
        $html .= "<option value='" . $data->id . "'>" . $data->name . "</option>";
      }

      $html .= "</select>";

      echo($html);
    }

    function fillAreaSelectAdmin() {
      $html = "<select id='area_selectadmin' name='area_select' class='form-control'>";
      $html .= "<option selected> - Vali - </option>";

      foreach ($this->allAreas as $data) {
        $html .= "<option value='" . $data->id . "'>" . $data->name . "</option>";
      }

      $html .= "</select>";

      echo($html);
    }

    function getCorrectPlay($current) {
      $html = "<option selected>- Vali -</option>";

      if($current > 0) {
        foreach ($this->playData as $data) {
          if($data->area == $current) {
            $html .= "<option value='" . $data->id . "'>" . $data->name . ", " . $data->address . "</option>";
          }
        }
      } else {
        foreach ($this->playData as $data) {
          $html .= "<option value='" . $data->id . "'>" . $data->name . ", " . $data->address . "</option>";
        }
      }

      echo $html;
    }


}

?>
<?php
  // Piirkonna lisamine
  if(isset($_POST['submit_area'])) {

      if($_POST['name'] !== "") {
        $name = $_POST['name'];
      } else {
        array_push($error, "nimi");
      }

      if(count($error) == 0) {
        $Playgrounds->insertArea($name);
      } else {
        echo "Järgnevad väljad ei tohi olla tühjad: ";
        for($i = 0; $i < count($error); $i++) {
          if($i == count($error) - 1) {
            echo $error[$i];
          } else {
            echo $error[$i] . ", ";
          }
        }
      }

  }


?>
