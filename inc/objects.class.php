<?php
class Objects
{


    private $connection;

    private $myProperties;

    public $objectNames;


    function __construct($mysqli)
    {

        $this->connection = $mysqli;

        $this->getObjects();

    }


    function insertData($name, $address, $code, $year, $usedfor, $contact, $email, $number)
    {

        $name = utf8_decode($name);

        $address = utf8_decode($address);

        $usedfor = utf8_decode($usedfor);

        $contact = utf8_decode($contact);

        $email = utf8_decode($email);


        $stmt = $this->connection->prepare("INSERT INTO objects (name, address, code, year, usedfor, contact, email, number, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $stmt->bind_param("sssissss", $name, $address, $code, $year, $usedfor, $contact, $email, $number);

        $stmt->execute();

        $stmt->close();


        echo $this->connection->insert_id;

    }


    function getPlans($id)
    {

        $directory = '../plans/objects/' . $id . "/";

        $images = glob($directory . "*.jpg");

        echo json_encode($images);

        /*foreach($images as $image)

        {

          echo $image;

        }*/

    }


    function deletePlan($id, $name)
    {

      //echo($_SERVER['DOCUMENT_ROOT'] . "/plans/objects/" . $id . "/" . $name . ".jpg");

        unlink($_SERVER['DOCUMENT_ROOT'] . "/plans/objects/" . $id . "/" . $name . ".jpg");

        unlink($_SERVER['DOCUMENT_ROOT'] . "/plans/objects/" . $id . "/" . $name . ".pdf");

    }


    function uploadPlan($id, $name, $img, $pdf)
    {


        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/plans/objects/' . $id)) {

            mkdir($_SERVER['DOCUMENT_ROOT'] . '/plans/objects/' . $id, 0755, true);

        }


        $this->uploadImg($id, $name, $img);

        $this->uploadPDF($id, $name, $pdf);

        //header("Location: objektid.php");


        $_SESSION['upload_id'] = $id;

        $_SESSION['msg_seen'] = false;



    }


    function uploadImg($id, $name, $img)
    {

        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/plans/objects/" . $id . "/";


        $target_file = $target_dir . basename($img["name"]);

        $uploadOk = 1;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $target_file = $target_dir . $name . ".jpg";

        //echo($this->connection->insert_id);


        if (file_exists($target_file)) {

            $_SESSION['error_msg'] = "Fail eksisteerib juba.";

            $uploadOk = 0;

        }


        if ($img["size"] > 10000000) {

            $_SESSION['error_msg'] = "Faili suurus ei tohi olla üle 10mb.";

            $uploadOk = 0;

        }


        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"

            && $imageFileType != "gif"
        ) {

            $_SESSION['error_msg'] = "Ainult jpg, png, jpeg ja gif failid on lubatud.";

            $uploadOk = 0;

        }


        if ($uploadOk == 0) {

            $_SESSION['error_msg'] = "Faili ei laetud üles!";

        } else {

            if (move_uploaded_file($img["tmp_name"], $target_file)) {

                $_SESSION['success_msg'] = "Fail " . basename($img["name"]) . " laeti edukalt üles.";

            } else {

                $_SESSION['error_msg'] = "Tekkis tundmatu viga, anna haldajale teada.";

            }

        }

    }


    function uploadPDF($id, $name, $pdf)
    {

        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/plans/objects/" . $id . "/";


        $target_file = $target_dir . basename($pdf["name"]);

        $uploadOk = 1;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $target_file = $target_dir . $name . ".pdf";

        //echo($this->connection->insert_id);


        if (file_exists($target_file)) {

            $_SESSION['error_msg'] = "Fail eksisteerib juba.";

            $uploadOk = 0;

        }


        if ($pdf["size"] > 10000000) {

            $_SESSION['error_msg'] = "Faili suurus ei tohi olla üle 10mb.";

            $uploadOk = 0;

        }


        if ($imageFileType != "pdf") {

            $_SESSION['error_msg'] = "Ainult pdf failid on lubatud.";

            $uploadOk = 0;

        }


        if ($uploadOk == 0) {

            $_SESSION['error_msg'] = "Faili ei laetud üles!";

        } else {

            if (move_uploaded_file($pdf["tmp_name"], $target_file)) {

                $_SESSION['success_msg'] = "Fail " . basename($pdf["name"]) . " laeti edukalt üles.";

            } else {

                $_SESSION['error_msg'] = "Tekkis tundmatu viga, anna haldajale teada.";

            }

        }

    }


    function getMaintanceArchive($form, $object)
    {

        $stmt = $this->connection->prepare("SELECT id, title FROM maintance_fills WHERE form_id = ? AND object_id = ? AND deleted IS NULL  ORDER BY created DESC LIMIT 10");

        $stmt->bind_param("ii", $form, $object);

        $stmt->bind_result($id, $title);

        $stmt->execute();


        $array = array();


        while ($stmt->fetch()) {

            $a = new Stdclass();

            $a->id = $id;

            $a->title = $title;


            array_push($array, $a);

        }


        echo(json_encode($array));


        $stmt->close();


    }


    function saveMaintance($form, $object, $title, $answer)
    {

        $answer = utf8_decode($answer);

        //echo(json_encode($answer));

        $stmt = $this->connection->prepare("INSERT INTO maintance_fills (form_id, object_id, title, answer, created) VALUES (?, ?, ?, ?, NOW())");

        $stmt->bind_param("iiss", $form, $object, $title, $answer);

        $stmt->execute();

        $stmt->close();


        echo $this->connection->insert_id;

    }


    function getMaintance($maintance_id)
    {

        $stmt = $this->connection->prepare("SELECT * FROM maintance_fills WHERE id = ? AND deleted IS NULL");

        $stmt->bind_param("i", $maintance_id);

        $stmt->bind_result($id, $form_id, $object_id, $title, $answer, $created, $deleted);

        $stmt->execute();


        $array = array();


        while ($stmt->fetch()) {

            $answer = utf8_decode($answer);


            $a = new Stdclass();

            $a->id = $id;

            $a->form_id = $form_id;

            $a->object_id = $object_id;

            $a->title = $title;

            $a->answer = $answer;

            $a->created = $created;

            $a->deleted = $deleted;


            array_push($array, $a);

        }


        echo(json_encode($array));


        $stmt->close();


    }


    function addMeta($object, $type, $key, $answer)
    {

        $key = utf8_decode($key);

        $answer = utf8_decode($answer);


        $duplicate = false;


        $stmt = $this->connection->prepare("SELECT id FROM objects_meta WHERE object_id = ? AND meta_key = ?");

        $stmt->bind_param("is", $object, $key);

        $stmt->bind_result($exists);

        $stmt->execute();

        if ($stmt->fetch()) {

            $duplicate = true;

        }

        $stmt->close();


        if ($duplicate === true) {

            $stmt = $this->connection->prepare("UPDATE objects_meta SET meta_answer = ? WHERE object_id = ? AND meta_key = ?");

            $stmt->bind_param("sis", $answer, $object, $key);

            $stmt->execute();

            $stmt->close();

        } else {

            $stmt = $this->connection->prepare("INSERT INTO objects_meta (object_id, type, meta_key, meta_answer) VALUES (?,?,?,?)");

            $stmt->bind_param("iiss", $object, $type, $key, $answer);

            $stmt->execute();

            $stmt->close();

        }


    }


    function updateData($id, $name, $code, $year, $usedfor, $address, $contact, $email, $number)
    {

        $name = utf8_decode($name);

        $address = utf8_decode($address);

        $usedfor = utf8_decode($usedfor);

        $contact = utf8_decode($contact);

        $email = utf8_decode($email);


        $stmt = $this->connection->prepare("UPDATE objects SET name = ?, address = ?, code = ?, year = ?, usedfor = ?, contact = ?, email = ?, number = ? WHERE id = ?");

        $stmt->bind_param("sssissssi", $name, $address, $code, $year, $usedfor, $contact, $email, $number, $id);

        $stmt->execute();

        $stmt->close();

    }


    function deleteObject($id)
    {

        $stmt = $this->connection->prepare("UPDATE objects SET deleted = NOW() WHERE id = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->close();

    }


    function removeDoc($id)
    {

        $stmt = $this->connection->prepare("UPDATE documents SET deleted = NOW() WHERE id = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->close();

    }


    function getObjects() {
        $stmt = $this->connection->prepare("SELECT id, name FROM objects WHERE deleted IS NULL ORDER BY name");
        $stmt->bind_result($id, $name);
        $stmt->execute();

        $array = array();

        while ($stmt->fetch()) {
            $ob = new stdClass();
            $name = utf8_encode($name);
            $ob->id = $id;
            $ob->name = $name;
            array_push($array, $ob);
        }

        $this->objectNames = $array;

        $stmt->close();
    }

    function getSchools() {
      $stmt = $this->connection->prepare("SELECT id FROM objects WHERE type = 2");
      $stmt->bind_result($id);
      $stmt->execute();

      $array = array();

      while ($stmt->fetch()) {
        array_push($array, $id);
      }

      return($array);

      $stmt->close();
    }

    function getKinders() {
      $stmt = $this->connection->prepare("SELECT id FROM objects WHERE type = 1");
      $stmt->bind_result($id);
      $stmt->execute();

      $array = array();

      while ($stmt->fetch()) {
        array_push($array, $id);
      }

      return($array);

      $stmt->close();
    }

    function getSchoolsAndKinders() {
      $stmt = $this->connection->prepare("SELECT id FROM objects WHERE type = 1 OR type = 2");
      $stmt->bind_result($id);
      $stmt->execute();

      $array = array();

      while ($stmt->fetch()) {
        array_push($array, $id);
      }

      return($array);

      $stmt->close();
    }


    function getData($fromid)
    {

        $stmt = $this->connection->prepare("SELECT * FROM objects WHERE id = ? AND deleted IS NULL");

        $stmt->bind_param("i", $fromid);

        $stmt->bind_result($id, $type, $name, $address, $code, $year, $usedfor, $contact, $email, $number, $created, $deleted);

        $stmt->execute();


        $array = array();


        while ($stmt->fetch()) {

            $ob = new stdClass();

            $name = utf8_encode($name);

            $address = utf8_encode($address);

            $usedfor = utf8_encode($usedfor);

            $contact = utf8_decode($contact);

            $email = utf8_decode($email);


            $ob->id = $id;

            $ob->type = $type;

            $ob->name = $name;

            $ob->address = $address;

            $ob->code = $code;

            $ob->year = $year;

            $ob->usedfor = $usedfor;

            $ob->contact = $contact;

            $ob->email = $email;

            $ob->number = $number;

            $ob->created = $created;

            $ob->deleted = $deleted;


            array_push($array, $ob);

        }


        echo json_encode($array);


        $stmt->close();

    }


    function getMeta($fromid)
    {

        $stmt = $this->connection->prepare("SELECT * FROM objects_meta WHERE object_id = ?");

        $stmt->bind_param("i", $fromid);

        $stmt->bind_result($id, $object_id, $type, $meta_key, $meta_answer);

        $stmt->execute();


        $array = array();


        while ($stmt->fetch()) {

            $ob = new stdClass();

            $meta_key = utf8_encode($meta_key);

            $meta_answer = utf8_encode($meta_answer);


            $ob->id = $id;

            $ob->object_id = $object_id;

            $ob->type = $type;

            $ob->meta_key = $meta_key;

            $ob->meta_answer = $meta_answer;


            array_push($array, $ob);

        }


        echo json_encode($array);


        $stmt->close();

    }


    function getDocs($belong)
    {

        $stmt = $this->connection->prepare("SELECT id, name, link, extension FROM documents WHERE category = 'objects' AND belong_id = ? AND deleted IS NULL ORDER BY extension");

        $stmt->bind_param("i", $belong);

        $stmt->bind_result($id, $name, $link, $ext);

        $stmt->execute();


        $array = array();


        while ($stmt->fetch()) {

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


    function fillSelect()
    {


        $html = "<select id='object_select' class='form-control'>";

        $html .= "<option value='0' selected> - Vali - </option>";


        foreach ($this->objectNames as $data) {
          if($data->id !== 9999) {
            $html .= "<option value='" . $data->id . "'>" . $data->name . "</option>";

          }

        }


        $html .= "</select>";


        echo($html);


    }

    function fillSelectOptions() {


      foreach ($this->objectNames as $data) {
        if($data->id !== 9999) {
          $html .= "<option value='" . $data->id . "'>" . $data->name . "</option>";

        }

      }


      echo($html);

    }


    function fillSelectForUserAdding()
    {


        $html = "<select id='select_rights' class='form-control' multiple='multiple' name='txt_rights[]'>";


        foreach ($this->objectNames as $data) {
          if($data->id !== 9999) {
            $html .= "<option value='" . $data->id . "'>" . $data->name . "</option>";

          }


        }


        $html .= "</select>";


        echo($html);


    }


    function fillSelectForUserEditing()
    {


        $html = "<select id='select_rights_editing' class='form-control' multiple='multiple' name='txt_rights[]' style='width: 100%'>";


        foreach ($this->objectNames as $data) {

          if($data->id !== 9999) {
            $html .= "<option value='" . $data->id . "'>" . $data->name . "</option>";

          }


        }


        $html .= "</select>";


        echo($html);


    }


    function getSelectedValues($id)
    {

        $stmt = $this->connection->prepare("SELECT rights FROM users WHERE user_id = ?");

        $stmt->bind_param("i", $id);

        $stmt->bind_result($rights);

        $stmt->execute();


        if ($stmt->fetch()) {

            $ob = new stdClass();

            $ob->rights = $rights;

        }


        echo json_encode($ob);

        $stmt->close();


    }


    function getMyProperties()
    {
        $stmt = $this->connection->prepare("SELECT user_id, rights FROM users WHERE user_name = ?");
        $stmt->bind_param("s", $_SESSION['user_name']);
        $stmt->bind_result($id, $rights);
        $stmt->execute();

        if ($stmt->fetch()) {
            $ob = new stdClass();
            $ob->id = $id;
            $ob->rights = $rights;
        }

        $this->myProperties = $ob;

        $stmt->close();

    }

    function fillMySelectOptions() {
      $this->getMyProperties();

      $rights = explode(",", $this->myProperties->rights);

      for ($i = 0; $i < count($rights); $i++) {
          foreach ($this->objectNames as $data) {
            if($data->id !== 9999) {

              if ((int)$rights[$i] === (int)$data->id) {
                if($i === 0) {
                  $html .= "<option value='" . $data->id . "' selected>" . $data->name . "</option>";
                } else {
                  $html .= "<option value='" . $data->id . "'>" . $data->name . "</option>";
                }
              }

            }


          }
      }

      echo($html);

    }


    function fillMySelect()
    {
      $this->getMyProperties();

        $html = "<select id='object_select' class='form-control'>";

        $html .= "<option value='0' selected> - Vali - </option>";


        $rights = explode(",", $this->myProperties->rights);


        for ($i = 0; $i < count($rights); $i++) {

            foreach ($this->objectNames as $data) {

              if($data->id !== 9999) {

                if ((int)$rights[$i] === (int)$data->id) {

                  $html .= "<option value='" . $data->id . "'>" . $data->name . "</option>";

                }

              }



            }

        }


        $html .= "</select>";


        echo($html);


    }


    function getMaintanceSubs($maintance)
    {

        $stmt = $this->connection->prepare("SELECT * FROM maintance_sub WHERE maintance_id = ?");

        $stmt->bind_param("i", $maintance);

        $stmt->bind_result($id, $maintance_id, $article, $name, $frequency, $action, $result);

        $stmt->execute();


        $array = array();


        while ($stmt->fetch()) {

            $ob = new stdClass();

            $name = utf8_encode($name);

            $frequency = utf8_encode($frequency);

            $action = utf8_encode($action);

            $result = utf8_encode($result);


            $ob->id = $id;

            $ob->maintance_id = $maintance_id;

            $ob->article = $article;

            $ob->name = $name;

            $ob->frequency = $frequency;

            $ob->action = $action;

            $ob->result = $result;


            array_push($array, $ob);

        }


        echo json_encode($array);


        $stmt->close();


    }


    function getSubCalendar($sub)
    {

        $stmt = $this->connection->prepare("SELECT * FROM maintance_calendar WHERE sub_id = ?");

        $stmt->bind_param("i", $sub);

        $stmt->bind_result($id, $sub_id, $act, $company, $done, $notes, $created, $made, $deleted);

        $stmt->execute();


        $array = array();


        while ($stmt->fetch()) {

            $ob = new stdClass();

            $act = utf8_encode($act);

            $company = utf8_encode($company);

            $done = utf8_encode($done);

            $notes = utf8_encode($notes);


            $ob->id = $id;

            $ob->sub_id = $sub_id;

            $ob->act = $act;

            $ob->company = $company;

            $ob->done = $done;

            $ob->notes = $notes;

            $ob->created = $created;

            $ob->made = $made;

            $ob->deleted = $deleted;


            array_push($array, $ob);

        }


        echo json_encode($array);


        $stmt->close();


    }


    function maintanceSelect()
    {

        $stmt = $this->connection->prepare("SELECT * FROM maintance");

        $stmt->bind_result($id, $name);

        $stmt->execute();


        $html = "<select id='maintance_select' class='form-control'>";

        $html .= "<option value='0' selected> - Vali - </option>";


        while ($stmt->fetch()) {


            $name = utf8_encode($name);

            $html .= "<option value='" . $id . "'>" . $name . "</option>";


        }


        $html .= "</select>";

        echo($html);


        $stmt->close();


    }


    function formSelect() {
        $stmt = $this->connection->prepare("SELECT * FROM maintance_names");
        $stmt->bind_result($id, $name);
        $stmt->execute();

        $html = "<select id='maintance_select' class='form-control' style='display: none;'>";
        $html .= "<option value='0' selected> - Vali - </option>";

        while ($stmt->fetch()) {
            $name = utf8_encode($name);
            $html .= "<option value='" . $id . "'>" . $name . "</option>";
        }

        $html .= "</select>";

        echo($html);

        $stmt->close();
    }

    function fantomFormSelect() {
        $stmt = $this->connection->prepare("SELECT * FROM maintance_names");
        $stmt->bind_result($id, $name);
        $stmt->execute();

        $html = "<select id='maintance_select_fantom' class='form-control'>";
        $html .= "<option value='0' selected> - Vali - </option>";
        $count = 0;

        while ($stmt->fetch()) {
            $name = utf8_encode($name);
            if(substr($name, 0, 20) === "Tehniline ülevaatus") {
              if($count === 0) {
                $html .= "<option value='tehniline'>Tehniline ülevaatus</option>";
                $count++;
              }
            } else {
              $html .= "<option value='" . $id . "'>" . $name . "</option>";

            }
        }

        $html .= "</select>";

        echo($html);

        $stmt->close();
    }

    function fantomTechnicalSelect() {
        $stmt = $this->connection->prepare("SELECT * FROM maintance_names");
        $stmt->bind_result($id, $name);
        $stmt->execute();

        $html = "<select id='category_select' class='form-control'>";
        $html .= "<option value='0' selected> - Vali - </option>";

        while ($stmt->fetch()) {
            $name = utf8_encode($name);
            if(substr($name, 0, 20) === "Tehniline ülevaatus") {
              preg_match("/\(([^\)]*)\)/", $name, $aMatches);
              $sResult = $aMatches[1];
              $html .= "<option value='" . $id . "'>" . $sResult . "</option>";
            }
        }

        $html .= "</select>";

        echo($html);

        $stmt->close();
    }


    function formFill($name_id)
    {

        $stmt = $this->connection->prepare("SELECT * FROM maintance_forms WHERE name_id = ?");

        $stmt->bind_param("i", $name_id);

        $stmt->bind_result($id, $name, $rows, $heads);

        $stmt->execute();


        $array = array();


        while ($stmt->fetch()) {

            $rows = utf8_encode($rows);

            $heads = utf8_encode($heads);

            $ob = new stdClass();

            $ob->id = $id;

            $ob->name = $name;

            $ob->rows = $rows;

            $ob->heads = $heads;

            array_push($array, $ob);

        }


        echo json_encode($array);


        $stmt->close();


    }


    function insertFormFill($form, $object, $answer)
    {

        $stmt = $this->connection->prepare("INSERT INTO maintance_fills (form_id, object_id, answer, created) VALUES (?, ?, ?, NOW())");

        $stmt->bind_param("iis", $form, $object, $answer);

        $stmt->execute();

        $stmt->close();

    }


    function updateMaintance($id, $answer)
    {

        $answer = utf8_decode($answer);


        $stmt = $this->connection->prepare("UPDATE maintance_fills SET answer = ? WHERE id = ?");

        $stmt->bind_param("si", $answer, $id);

        $stmt->execute();

        $stmt->close();

    }


    function deleteMaintance($id)
    {

        $answer = utf8_decode($answer);


        $stmt = $this->connection->prepare("UPDATE maintance_fills SET deleted = NOW() WHERE id = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->close();

    }

    function getMaintanceDocs($maintance) {
      $stmt = $this->connection->prepare("SELECT id, name, link, extension FROM maintance_documents WHERE fill_id = ? AND deleted IS NULL ORDER BY extension");
      $stmt->bind_param("i", $maintance);
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

    function deleteMaintanceDocs($id) {
      $stmt = $this->connection->prepare("UPDATE maintance_documents SET deleted = NOW() WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $stmt->close();
    }


}


?>
