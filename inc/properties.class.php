<?php
class Properties {

    private $connection;
    public $propertiesData;
    public $businessesData;
    //public $businessTenantData;
    public $filteredData;
    public $lastInsert;

    function __construct($mysqli){
        $this->connection = $mysqli;
        $this->getPropertiesData();
        $this->getBusinessesData();
    }

    ############################
    ### TAVALISED ÜÜRIPINNAD ###
    ############################

    function insertData($area, $address, $rooms, $space, $m2, $koef, $condition, $info, $price) {
      $rooms = (string)$rooms;
      $stmt = $this->connection->prepare("INSERT INTO properties (area_id, address, rooms, space, m2, koef, conditions, info, price, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
      $stmt->bind_param("issssssss", $area, $address, $rooms, $space, $m2, $koef, $condition, $info, $price);
      $stmt->execute();
      $stmt->close();

      $this->lastInsert = $this->connection->insert_id;
    }

    function updateData($id, $address, $rooms, $space, $m2, $koef, $condition, $info, $price) {
      if($price === "") {
        $price = NULL;
      }
      $stmt = $this->connection->prepare("UPDATE properties SET address = ?, rooms = ?, space = ?, m2 = ?, koef = ?, conditions = ?, info = ?, price = ? WHERE id = ?");
      $stmt->bind_param("ssssssssi", $address, $rooms, $space, $m2, $koef, $condition, $info, $price, $id);
      $stmt->execute();
      $stmt->close();
    }

    function redirect() {
      header("Location: pinnad.php");
    }

    function deleteProperty($id) {
      $stmt = $this->connection->prepare("UPDATE properties SET deleted = NOW() WHERE id = ?");
      $stmt->bind_param("s", $id);
      $stmt->execute();
      $stmt->close();
    }

    function deleteTenant($prop_id) {
      $stmt = $this->connection->prepare("UPDATE tenants SET deleted = NOW() WHERE id = ?");
      $stmt->bind_param("i", $prop_id);
      $stmt->execute();
      $stmt->close();
    }

    function getTenantArchive($prop_id) {
      $stmt = $this->connection->prepare("SELECT * FROM tenants WHERE prop_id = ? AND deleted IS NOT NULL ORDER BY deleted DESC LIMIT 5");
      $stmt->bind_param("i", $prop_id);
      $stmt->bind_result($id, $prop_id, $name, $id_number, $number, $email, $realhome, $contract, $dhs, $deadline, $created, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $ob = new stdClass();
        $ob->id = $id;
        $ob->prop_id = $prop_id;
        $ob->name = $name;
        $ob->idnumber = $id_number;
        $ob->number = $number;
        $ob->email = $email;
        $ob->realhome = $realhome;
        $ob->contract = $contract;
        $ob->dhs = $dhs;
        $ob->deadline = $deadline;
        $ob->created = $created;
        $ob->deleted = $deleted;

        array_push($array, $ob);
      }

      echo(json_encode($array));

      $stmt->close();
    }

    function doTenant($prop_id, $name, $idn, $number, $email, $real, $contract, $dhs, $deadline) {
      $exists = false;

      $stmt = $this->connection->prepare("SELECT id FROM tenants WHERE prop_id = ? AND deleted IS NULL");
      $stmt->bind_param("i", $prop_id);
      $stmt->bind_result($new_id);
      $stmt->execute();
      if($stmt->fetch()) {
        $exists = true;
      }
      $stmt->close();

      if($exists === true) {
        $stmt = $this->connection->prepare("UPDATE tenants SET name = ?, id_number = ?, number = ?, email = ?, realhome = ?, contract = ?, dhs = ?, deadline = ?, created = NOW() WHERE prop_id = ? AND deleted IS NULL");
        $stmt->bind_param("ssisssssi", $name, $idn, $number, $email, $real, $contract, $dhs, $deadline, $prop_id);
        $stmt->execute();
        $stmt->close();
      } else {
        $stmt = $this->connection->prepare("INSERT INTO tenants (prop_id, name, id_number, number, email, realhome, contract, dhs, deadline, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ississsss", $prop_id, $name, $idn, $number, $email, $real, $contract, $dhs, $deadline);
        $stmt->execute();
        $stmt->close();
      }
    }

    function uploadPicture() {

      $target_dir = "images/properties/";
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $target_file = $target_dir . $this->connection->insert_id . "-property" . ".jpg";
      //echo($this->connection->insert_id);

      if (file_exists($target_file)) {
          echo "Fail eksisteerib juba.";
          $uploadOk = 0;
      }

      if ($_FILES["fileToUpload"]["size"] > 10000000) {
          echo "Faili suurus ei tohi olla üle 10mb.";
          $uploadOk = 0;
      }

      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" ) {
          echo "Ainult jpg, png, jpeg ja gif failid on lubatud.";
          $uploadOk = 0;
      }

      if ($uploadOk == 0) {
          echo "Faili ei laetud üles!";
      } else {
          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
              echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti edukalt üles.";
          } else {
              echo "Tekkis tundmatu viga, anna haldajale teada.";
          }
      }

    }

    function getPropertiesData() {
      $stmt = $this->connection->prepare("SELECT * FROM properties WHERE deleted IS NULL");
      $stmt->bind_result($id, $area, $address, $rooms, $space, $m2, $koef, $condition, $price, $info, $created, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $ob = new stdClass();
        $ob->id = $id;
        $ob->area = $area;
        $ob->address = $address;
        $ob->rooms = $rooms;
        $ob->space = $space;
        $ob->m2 = $m2;
        $ob->koef = $koef;
        $ob->condition = $condition;
        $ob->price = $price;
        $ob->info = $info;
        $ob->created = $created;
        $ob->deleted = $deleted;

        array_push($array, $ob);
      }

      $this->propertiesData = $array;

      $stmt->close();
    }

    function getPropertyTenant($current) {
      $stmt = $this->connection->prepare("SELECT * FROM tenants WHERE deleted IS NULL AND prop_id = ?");
      $stmt->bind_param("i", $current);
      $stmt->bind_result($id, $prop_id, $name, $id_number, $number, $email, $realhome, $contract, $dhs, $deadline, $created, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $ob = new stdClass();
        $ob->id = $id;
        $ob->prop_id = $prop_id;
        $ob->name = $name;
        $ob->idnumber = $id_number;
        $ob->number = $number;
        $ob->email = $email;
        $ob->realhome = $realhome;
        $ob->contract = $contract;
        $ob->dhs = $dhs;
        $ob->deadline = $deadline;
        $ob->created = $created;
        $ob->deleted = $deleted;

        array_push($array, $ob);
      }

      echo(json_encode($array));

      $stmt->close();
    }

    function getPropertyDocs($belong) {
      $stmt = $this->connection->prepare("SELECT id, name, link, extension FROM documents WHERE category = 'properties' AND belong_id = ? AND deleted IS NULL ORDER BY extension");
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

    function filterProperties($areaid, $filters) {
      $areaid = (int)$areaid;
      //echo (string)$filters;
    //  echo($filters);
      if(!empty($filters)) {
      //  echo("1");
        if(strpos($filters, ',') !== false) {
          //echo("2");
          $filters = explode(",", $filters);
          //echo("te");
        }
      }

      if(is_array($filters)) {
      //  foreach($filters as $filter) {
          echo($filters[0]);
          if(in_array("sale", $filters) && in_array("free", $filters) && in_array("ending", $filters)) {

            $start = date("Y") . "-01-01";
            $end = date("Y") . "-12-31";
            $stmt = $this->connection->prepare("SELECT properties.id, area_id, address, rooms, space, m2, koef, conditions, price, info, properties.created, properties.deleted FROM properties INNER JOIN tenants ON tenants.prop_id = properties.id WHERE properties.deleted IS NULL AND tenants.deleted IS NULL AND price IS NOT NULL AND deadline BETWEEN '$start' AND '$end' AND NOT EXISTS (SELECT * FROM tenants WHERE tenants.prop_id = properties.id)");

          } else if(in_array("sale", $filters) && in_array("free", $filters)) {
            $stmt = $this->connection->prepare("SELECT * FROM properties WHERE NOT EXISTS (SELECT * FROM tenants WHERE tenants.prop_id = properties.id) AND price IS NOT NULL");

          } else if(in_array("free", $filters) && in_array("ending", $filters)) {
            $start = date("Y") . "-01-01";
            $end = date("Y") . "-12-31";
            $stmt = $this->connection->prepare("SELECT properties.id, area_id, address, rooms, space, m2, koef, conditions, price, info, properties.created, properties.deleted FROM properties
              INNER JOIN tenants ON tenants.prop_id = properties.id
              WHERE properties.deleted IS NULL AND tenants.deleted IS NULL
              AND deadline BETWEEN '$start' AND '$end' AND NOT EXISTS (SELECT * FROM tenants WHERE tenants.prop_id = properties.id)");

          } else if(in_array("sale", $filters) && in_array("ending", $filters)) {
            $stmt = $this->connection->prepare("SELECT properties.id, area_id, address, rooms, space, m2, koef, conditions, price, info, properties.created, properties.deleted FROM properties
              INNER JOIN tenants ON tenants.prop_id = properties.id
              WHERE properties.deleted IS NULL AND tenants.deleted IS NULL
              AND deadline BETWEEN '$start' AND '$end' AND price IS NOT NULL");

          } else {
            echo("katki");
            exit;

          }


            $stmt->bind_result($id, $area, $address, $rooms, $space, $m2, $koef, $condition, $price, $info, $created, $deleted);
            $stmt->execute();

            $array = array();

            while($stmt->fetch()) {
              $ob = new stdClass();
              $ob->id = $id;
              $ob->area = $area;
              $ob->address = $address;
              $ob->rooms = $rooms;
              $ob->space = $space;
              $ob->m2 = $m2;
              $ob->koef = $koef;
              $ob->condition = $condition;
              $ob->price = $price;
              $ob->info = $info;
              $ob->created = $created;
              $ob->deleted = $deleted;

              array_push($array, $ob);
            }

            $this->propertiesData = $array;

            $stmt->close();

            $this->getCorrectProperties($areaid);


          //}
      } else if($filters === "") {
        $this->getCorrectProperties($areaid);

      } else {
        //$filters = (string)$filters;
        if($filters === "sale") {
          $stmt = $this->connection->prepare("SELECT * FROM properties WHERE deleted IS NULL AND price IS NOT NULL");

        } else if($filters === "ending") {
          $start = date("Y") . "-01-01";
          $end = date("Y") . "-12-31";
          $stmt = $this->connection->prepare("SELECT properties.id, area_id, address, rooms, space, m2, koef, conditions, price, info, properties.created, properties.deleted FROM properties
            INNER JOIN tenants ON tenants.prop_id = properties.id
            WHERE properties.deleted IS NULL AND tenants.deleted IS NULL
            AND deadline BETWEEN '$start' AND '$end'");

          } else if($filters === "free") {
            $stmt = $this->connection->prepare("SELECT * FROM properties WHERE NOT EXISTS (SELECT * FROM tenants WHERE tenants.prop_id = properties.id)");
          }

          $stmt->bind_result($id, $area, $address, $rooms, $space, $m2, $koef, $condition, $price, $info, $created, $deleted);
          $stmt->execute();

          $array = array();

          while($stmt->fetch()) {
            $ob = new stdClass();
            $ob->id = $id;
            $ob->area = $area;
            $ob->address = $address;
            $ob->rooms = $rooms;
            $ob->space = $space;
            $ob->m2 = $m2;
            $ob->koef = $koef;
            $ob->condition = $condition;
            $ob->price = $price;
            $ob->info = $info;
            $ob->created = $created;
            $ob->deleted = $deleted;

            array_push($array, $ob);
          }

          $this->propertiesData = $array;

          $stmt->close();

          $this->getCorrectProperties($areaid);

      }





    }

    function getCorrectProperties($current) {
      //echo(explode(",", $filters));

      //echo $filters[0];
      $html = "<option selected>- Vali -</option>";

      if($current > 0) {

        //$this->filterProperties();

        foreach ($this->propertiesData as $data) {
          if($data->area == $current) {
            $html .= "<option value='" . $data->id . "'>" . $data->address . "</option>";
            /*if(!empty($filters)) {


            } else {
            }*/
          }


        }
      } else {
        foreach ($this->propertiesData as $data) {
          $html .= "<option value='" . $data->id . "'>" . $data->address. "</option>";
        }
      }

      echo $html;
    }

    function fillPropertiesSelect() {

      $html = "<select id='properties_select' class='form-control'>";
      $html .= "<option selected> - Vali - </option>";

      foreach ($this->propertiesData as $data) {
        $html .= "<option value='" . $data->id . "'>" . $data->address . "</option>";
      }

      $html .= "</select>";

      echo($html);

    }

    #################
    ### ÄRIPINNAD ###
    #################

    function insertBusiness($name, $address, $condition, $info) {
      $stmt = $this->connection->prepare("INSERT INTO businesses (name, address, conditions, info, created) VALUES (?, ?, ?, ?, NOW())");
      $stmt->bind_param("ssss", $name, $address, $condition, $info);
      $stmt->execute();
      $stmt->close();
      header("Location: aripinnad.php");

    }

    function updateBusiness($id, $name, $address, $condition, $info) {
      $stmt = $this->connection->prepare("UPDATE businesses SET name = ?, address = ?, conditions = ?, info = ? WHERE id = ?");
      $stmt->bind_param("ssssi", $name, $address, $condition, $info, $id);
      $stmt->execute();
      $stmt->close();
    }

    function deleteBusiness($id) {
      $stmt = $this->connection->prepare("UPDATE businesses SET deleted = NOW() WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $stmt->close();
    }

    function getBusinessesData() {
      $stmt = $this->connection->prepare("SELECT * FROM businesses WHERE deleted IS NULL");
      $stmt->bind_result($id, $name, $address, $condition, $info, $created, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $ob = new stdClass();
        $ob->id = $id;
        $ob->name = $name;
        $ob->address = $address;
        $ob->condition = $condition;
        $ob->info = $info;
        $ob->created = $created;
        $ob->deleted = $deleted;

        array_push($array, $ob);
      }

      $this->businessesData = $array;

      $stmt->close();
    }

    function fillBusinessesSelect() {

      $html = "<select id='businesses_select' class='form-control'>";
      $html .= "<option value='0' selected> - Vali - </option>";

      foreach ($this->businessesData as $data) {
        $html .= "<option value='" . $data->id . "'>" . $data->name . "</option>";
      }

      $html .= "</select>";

      echo($html);

    }

    function doBusinessTenant($prop_id, $name, $reg, $contact, $phone, $email, $dhs, $deadline, $price, $usedfor, $info, $tenant_id, $addnew) {
      $exists = false;

      if($addnew === false || $addnew === "false") {
        $stmt = $this->connection->prepare("SELECT id FROM business_tenants WHERE business_id = ? AND deleted IS NULL");
        $stmt->bind_param("i", $prop_id);
        $stmt->bind_result($new_id);
        $stmt->execute();
        if($stmt->fetch()) {
          $exists = true;
        }
        $stmt->close();
      }

      if($exists === true) {
        $stmt = $this->connection->prepare("UPDATE business_tenants SET name = ?, regcode = ?, contact = ?, phone = ?, email = ?, dhs = ?, deadline = ?, price = ?, usedfor = ?, info = ?, created = NOW() WHERE id = ? AND deleted IS NULL");
        $stmt->bind_param("ssssssssssi", $name, $reg, $contact, $phone, $email, $dhs, $deadline, $price, $usedfor, $info, $tenant_id);
        $stmt->execute();
        $stmt->close();
      } else {
        $stmt = $this->connection->prepare("INSERT INTO business_tenants (business_id, name, regcode, contact, phone, email, dhs, deadline, price, usedfor, info, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("issssssssss", $prop_id, $name, $reg, $contact, $phone, $email, $dhs, $deadline, $price, $usedfor, $info);
        $stmt->execute();
        $stmt->close();
      }
    }

    function getBusinessTenant($bid) {
      $stmt = $this->connection->prepare("SELECT * FROM business_tenants WHERE business_id = ? AND deleted IS NULL");
      $stmt->bind_param("i", $bid);
      $stmt->bind_result($id, $business, $name, $regcode, $contact, $phone, $email, $dhs, $deadline, $price, $usedfor, $info, $created, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $ob = new stdClass();
        $ob->id = $id;
        $ob->business = $business;
        $ob->name = $name;
        $ob->regcode = $regcode;
        $ob->contact = $contact;
        $ob->phone = $phone;
        $ob->email = $email;
        $ob->dhs = $dhs;
        $ob->deadline = $deadline;
        $ob->price = $price;
        $ob->usedfor = $usedfor;
        $ob->info = $info;
        $ob->created = $created;
        $ob->deleted = $deleted;

        array_push($array, $ob);
      }

      //$this->businessTenantData = $array;
      //$this->fillBusinessTenantSelect($array);
      echo json_encode($array);
      $stmt->close();

    }

    function deleteBusinessTenant($id) {
      $stmt = $this->connection->prepare("UPDATE business_tenants SET deleted = NOW() WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $stmt->close();
    }

    function getBusinessTenantArchive($prop_id) {
      $stmt = $this->connection->prepare("SELECT * FROM business_tenants WHERE business_id = ? AND deleted IS NOT NULL ORDER BY deleted DESC LIMIT 5");
      $stmt->bind_param("i", $prop_id);
      $stmt->bind_result($id, $business, $name, $regcode, $contact, $phone, $email, $dhs, $deadline, $price, $usedfor, $info, $created, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $ob = new stdClass();
        $ob->id = $id;
        $ob->business = $business;
        $ob->name = $name;
        $ob->regcode = $regcode;
        $ob->contact = $contact;
        $ob->phone = $phone;
        $ob->email = $email;
        $ob->dhs = $dhs;
        $ob->deadline = $deadline;
        $ob->price = $price;
        $ob->usedfor = $usedfor;
        $ob->info = $info;
        $ob->created = $created;
        $ob->deleted = $deleted;

        array_push($array, $ob);
      }

      echo json_encode($array);

      $stmt->close();
    }

    function getBusinessDocs($belong) {
      $stmt = $this->connection->prepare("SELECT id, name, link, extension FROM documents WHERE category = 'business' AND belong_id = ? AND deleted IS NULL ORDER BY extension");
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


    /*function fillBusinessTenantSelect($array) {
      $html = "<option selected> - Vali - </option>";

      foreach ($array as $data) {
        $html .= "<option value='" . $data->id . "'>" . $data->name . "</option>";
      }

      $html .= "</select>";

      echo($html);

    }*/



}

?>
