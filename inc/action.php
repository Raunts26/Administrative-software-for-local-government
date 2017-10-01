<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $object_type = $object_id = $location = $problem_type = $short = $problem_date = $priority = $source = $long = $user = $deadline = $status = "";
  $firstname = $lastname = $email = $password = "";
  $error = [];
  $object_id = $type = $start = $end = $dow = $text = "";


  /*IT support*/
  if(isset($_POST['submit_itsupport'])) {

      if ($_POST['object_id'] !== "0") {
          $object_id = $_POST['object_id'];
      } else {
          array_push($error, "objekti tüüp");
      }

      $object_type = 1;
      $location = $_POST['location'];
      $tv_id = $_POST['tv_id'];

      $short = $_POST['short_description'];
      $problem_date = $_POST['problem_date'];
      $priority = $_POST['priority'];
      $long = $_POST['long_description'];	        $solution = $_POST['solution'];
      $user = $_POST['user_responsible'];
      $deadline = $_POST['deadline'];
      $status = $_POST['status'];


      if (count($error) == 0) {
          $IT_support->insertData($_SESSION['user_session'], $object_type, $object_id, $location, $tv_id, $short, $problem_date, $priority, $long, $solution, $user, $deadline, $status);

  //      $Tasks->sendTask($_SESSION['user_name'], $object_type, $object_id, $location, $problem_type, $short, $problem_date, $priority, $source, $long, $solution, $user, $deadline, $status);
          header("Location: it_support.php");

      } else {
          $_SESSION['error_msg'] = "Järgnevad väljad ei tohi olla tühjad: ";
      }

  }


  /*Kalender*/
  if(isset($_POST['submit_calendar'])) {

    if($_POST['object_id'] !== "0") {
      $object_id = $_POST['object_id'];
      //$object_id = explode(",", $object_id);
    } else {
      array_push($error, "objekt");
    }

    if($_POST['type'] !== "0") {
      $type = $_POST['type'];
    } else {
      array_push($error, "tüüp");
    }

    if($_POST['start'] !== "") {
      $start = $_POST['start'];
    } else {
      array_push($error, "algusaeg");
    }

    $end = $_POST['end'];
    $dow = $_POST['dow'];

    if($_POST['text'] !== "") {
      $text = $_POST['text'];
    } else {
      array_push($error, "kirjeldus");
    }


    if(count($error) == 0) {
      $count = $secondcount = 0;

      foreach($object_id as $object) {
        $doBefore = false;

        if($object === "koolidlasteaiad") {
          $new_object = $Objects->getSchoolsAndKinders();
          $doBefore = true;
        } elseif($object === "koolid") {
          $new_object = $Objects->getSchools();
          $doBefore = true;
        } elseif($object === "lasteaiad") {
          $new_object = $Objects->getKinders();
          $doBefore = true;
        }

        if($doBefore === true) {
          foreach($new_object as $new_id) {
            if($secondcount === 0) {
              $secondrepeat = NULL;
            } else {
              $secondrepeat = 1;
            }
            $Calendar->insertData($new_id, $type, $start, $end, $dow, $text, $secondrepeat);
            $secondcount++;
          }
        }

        if($count === 0) {
          $repeat = NULL;
        } else {
          $repeat = 1;
        }
        if($doBefore !== true) {
          $Calendar->insertData($object, $type, $start, $end, $dow, $text, $repeat);
        }
        $count++;
      }
      header("Location: kalender.php");
    } else {
      $_SESSION['error_msg'] = "Järgnevad väljad ei tohi olla tühjad: ";
    }


  }

  /*Hooldajad*/
  $object = 0;
  $name = $field = $contract = $deadline = $comments = $pay = $period = "";
  $c_name = $c_field = $c_phone = $c_email = $c_comments = "";

  if(isset($_POST['submit_service'])) {

    if($_POST["object_service"] > 0) {
      $object = $_POST["object_service"];
    } else {
      array_push($error, "Palun sisesta objekt!");
    }

    if(strlen($_POST['name']) > 0) {
      $name = $_POST['name'];
    } else {
      array_push($error, "Palun sisesta nimi!");
    }


    $field = $_POST['field'];
    $contract = $_POST['contract'];
    $deadline = $_POST['deadline'];
    $comments = $_POST['comments'];
    $pay = $_POST['pay'];
    $period = $_POST['period'];

    if($_POST['c_name'] !== "0") {
      $c_name = $_POST['c_name'];
    } else {
      array_push($error, "Palun sisesta nimi!");
    }

    $c_field = $_POST['c_field'];
    $c_phone = $_POST['c_phone'];
    $c_email = $_POST['c_email'];
    $c_comments = $_POST['c_comments'];

    if(count($error) == 0) {
      $Service->insertData($object, $name, $field, $contract, $deadline, $comments, $pay, $period);

      if(count($c_name) > 0) {
        for($i = 0; $i < count($c_name); $i++) {
          $Service->insertContact($c_name[$i], $c_field[$i], $c_phone[$i], $c_email[$i], $c_comments[$i]);
        }
      }

      header("Location: hooldajad.php");

    } else {
      $_SESSION['error_msg'] = "Järgnevad väljad ei tohi olla tühjad: ";
    }


  }


  /*Enda profiili muutmine*/

  if (isset($_POST['edit_profile'])) {
    if ($_POST['firstname'] !== "") {
      $firstname = $_POST['firstname'];
    } else {
      array_push($error, "eesnimi");
    }

    if ($_POST['lastname'] !== "") {
      $lastname = $_POST['lastname'];
    } else {
      array_push($error, "perekonnanimi");
    }

    if ($_POST['email'] !== "") {
      $email = $_POST['email'];
    } else {
      array_push($error, "email");
    }

    $password = $_POST['password'];


    if (count($error) == 0) {
      $login->editMe($_SESSION['user_session'], $firstname, $lastname, $email, $password);

      //      $Tasks->sendTask($_SESSION['user_name'], $object_type, $object_id, $location, $problem_type, $short, $problem_date, $priority, $source, $long, $solution, $user, $deadline, $status);
      header("Location: " . $_SERVER['BASE_PATH'] . "/views/profiil.php");

    } else {
      $_SESSION['error_msg'] = "Järgnevad väljad ei tohi olla tühjad: ";
    }


  }

  /*Äripinnad ja üüripinnad*/

  $area = $address = $rooms = $space = $price = $koef = $additional = $condition = $name = $info = "";
  $tenant_name = $tenant_nid = $tenant_number = $tenant_email = $tenant_real = $tenant_contract = $tenant_dhs = $tenant_deadline = "";
  $withTenant = false;
  $forsale = "off";




  if(isset($_POST['submit_property'])) {

    //Pildi kontroll, kontrollib kas tegu on pildiga
    /*$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
    $uploadOk = 1;
  } else {
  $uploadOk = 0;
  array_push($error, "Tegemist pole pildiga");
}*/

if(count($error) == 0) {
  // area_select, address, rooms, space, price, additional

  //Väljade kontroll
  if($_POST['area'] !== "") {
    $area = (int)$_POST['area_select'];
  } else {
    array_push($error, "piirkond");
  }
  if($_POST['address'] !== "") {
    $address = $_POST['address'];
  } else {
    array_push($error, "aadress");
  }
  if($_POST['rooms'] !== "") {
    $rooms = $_POST['rooms'];
  } else {
    $rooms = "";
  }
  if($_POST['condition'] !== "") {
    $condition = $_POST['condition'];
  } else {
    $condition = "";
  }
  if($_POST['space'] !== "") {
    $space = $_POST['space'];
  } else {
    array_push($error, "pindala");
  }
  if($_POST['price'] !== "") {
    $price = $_POST['price'];
  } else {
    array_push($error, "üürihind");
  }
  if($_POST['koef'] !== "") {
    $koef = $_POST['koef'];
  } else {
    array_push($error, "koefitsent");
  }
  if($_POST['additional'] !== "") {
    $additional = $_POST['additional'];
  } else {
    $additional = "";
  }
  if($_POST['forsale'] !== "") {
    $forsale = $_POST['forsale'];
  }

  if($_POST['tenant_name'] !== "") {
    $withTenant = true;
    $tenant_name = $_POST['tenant_name'];

    if($_POST['tenant_nid'] !== "") {
      $tenant_nid = $_POST['tenant_nid'];
    } else {
      array_push($error, "isikukood");

    }
    if($_POST['tenant_number'] !== "") {
      $tenant_number = $_POST['tenant_number'];

    } else {
      $tenant_number = "";

    }
    if($_POST['tenant_email'] !== "") {
      $tenant_email = $_POST['tenant_email'];

    } else {
      $tenant_email = "";
    }
    if($_POST['tenant_real'] !== "") {
      $tenant_real = $_POST['tenant_real'];

    } else {
      array_push($error, "sissekirjutus");

    }
    if($_POST['tenant_contract'] !== "") {
      $tenant_contract = $_POST['tenant_contract'];

    } else {
      $tenant_contract = "";
    }
    if($_POST['tenant_dhs'] !== "") {
      $tenant_dhs = $_POST['tenant_dhs'];

    } else {
      $tenant_dhs = "";
    }
    if($_POST['tenant_deadline'] !== "") {
      $tenant_deadline = $_POST['tenant_deadline'];

    } else {
      $tenant_deadline = "";

    }
  }



  if(count($error) == 0) {
    if($withTenant === true) {
      $Properties->insertData($area, $address, $rooms, $space, $price, $koef, $condition, $additional, $forsale);
      $Properties->doTenant($Properties->lastInsert, $tenant_name, $tenant_nid, $tenant_number, $tenant_email, $tenant_real, $tenant_contract, $tenant_dhs, $tenant_deadline);
      $Properties->redirect();
      //tenant_name, tenant_nid, tenant_number, tenant_email, tenant_real, tenant_contract, tenant_deadline
    } else {
      $Properties->insertData($area, $address, $rooms, $space, $price, $koef, $condition, $additional, $forsale);
      $Properties->redirect();
    }

    //$Properties->uploadPicture();

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

} else {
  echo "Tegemist pole pildiga!!";
}

}

#########################
### ÄRIPINNA LISAMINE ###
#########################

if(isset($_POST['submit_business'])) {

  if(count($error) == 0) {
    if($_POST['address'] !== "") {
      $address = $_POST['address'];
    } else {
      array_push($error, "aadress");
    }
    if($_POST['name'] !== "") {
      $name = $_POST['name'];
    } else {
      $name = "";
    }
    if($_POST['condition'] !== "") {
      $condition = $_POST['condition'];
    } else {
      $condition = $_POST['condition'];
    }
    if($_POST['info'] !== "") {
      $info = $_POST['info'];
    } else {
      $info = $_POST['info'];
    }

    if($_POST['tenant_name'] !== "") {
      $withTenant = true;
      $tenant_name = $_POST['tenant_name'];

      if($_POST['tenant_nid'] !== "") {
        $tenant_nid = $_POST['tenant_nid'];
      } else {
        array_push($error, "isikukood");

      }
      if($_POST['tenant_number'] !== "") {
        $tenant_number = $_POST['tenant_number'];

      } else {
        $tenant_number = "";

      }
      if($_POST['tenant_email'] !== "") {
        $tenant_email = $_POST['tenant_email'];

      } else {
        $tenant_email = "";
      }
      if($_POST['tenant_real'] !== "") {
        $tenant_real = $_POST['tenant_real'];

      } else {
        array_push($error, "sissekirjutus");

      }
      if($_POST['tenant_contract'] !== "") {
        $tenant_contract = $_POST['tenant_contract'];

      } else {
        $tenant_contract = "";
      }
      if($_POST['tenant_dhs'] !== "") {
        $tenant_dhs = $_POST['tenant_dhs'];

      } else {
        $tenant_dhs = "";
      }
      if($_POST['tenant_deadline'] !== "") {
        $tenant_deadline = $_POST['tenant_deadline'];

      } else {
        $tenant_deadline = "";

      }
    }



    if(count($error) == 0) {
      if($withTenant === true) {
        $Properties->insertBusiness($name, $address);
        $Properties->doTenant($Properties->lastInsert, $tenant_name, $tenant_nid, $tenant_number, $tenant_email, $tenant_real, $tenant_contract, $tenant_dhs, $tenant_deadline);
        $Properties->redirect();
      } else {
        $Properties->insertBusiness($name, $address, $condition, $info);
      }

      //$Properties->uploadPicture();

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

}


  /*Mänguväljakutega seotud kraam*/
  $area = $name = $address = $contact = $number = $attractions = "";

  if(isset($_POST['submit_playground'])) {

    //Pildi kontroll, kontrollib kas tegu on pildiga
    /*$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
    $uploadOk = 1;
  } else {
  $uploadOk = 0;
  array_push($error, "Tegemist pole pildiga");
}*/

  if(count($error) == 0) {

    //Väljade kontroll
    if($_POST['area'] !== "") {
      $area = (int)$_POST['area_select'];
    } else {
      array_push($error, "piirkond");
    }
    if($_POST['name'] !== "") {
      $name = $_POST['name'];
    } else {
      array_push($error, "nimi");
    }
    if($_POST['address'] !== "") {
      $address = $_POST['address'];
    } else {
      array_push($error, "aadress");
    }
    if($_POST['contact'] !== "") {
      $contact = $_POST['contact'];
    } else {
      array_push($error, "kontakt");
    }
    if($_POST['number'] !== "") {
      $number = $_POST['number'];
    } else {
      array_push($error, "number");
    }
    if($_POST['attractions'] !== "") {
      $attractions = $_POST['attractions'];
    } else {
      array_push($error, "atraktsioonid");
    }

    if(count($error) == 0) {
      $Playgrounds->insertData($area, $name, $address, $contact, $number, $attractions);
      $Playgrounds->uploadPicture();
      //header("Location: valjakud.php");

    } else {
      $_SESSION['error_msg'] = "Järgnevad väljad ei tohi olla tühjad: ";
    }

  } else {
    $_SESSION['error_msg'] = "Tegemist pole pildiga!!";
  }

  }


  /*Objekti plaani üleslaadimine*/

  if(isset($_POST["upload_new_plan"])) {
    if(!empty($_FILES["plan-img"]["name"]) && !empty($_FILES["plan-pdf"]["name"])) {
      $Objects->uploadPlan($_POST['plan_upload_id'], $_POST['plan_name'], $_FILES["plan-img"], $_FILES["plan-pdf"]);
    } else {
      $_SESSION['msg_seen'] = false;
      $_SESSION['error_msg'] = "Eksisteerima peab nii PILT kui ka PDF!";
    }
  }

  /* Ülesande lisamine */

  if (isset($_POST['submit_task'])) {

    if ($_POST['object_type'] !== "0") {
      $object_type = $_POST['object_type'];
    } else {
      array_push($error, "objekti tüüp");
    }

    if ($_POST['object_id'] !== "0") {
      $object_id = $_POST['object_id'];
    } else {
      array_push($error, "objekti tüüp");
    }

    $location = $_POST['location'];
    $problem_type = $_POST['problem_type'];
    $short = $_POST['short_description'];
    $problem_date = $_POST['problem_date'];
    $priority = $_POST['priority'];
    $source = $_POST['source'];
    $long = $_POST['long_description'];
    $solution = $_POST['solution'];
    $users = $_POST['users'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];


    if (count($error) == 0) {
      $Tasks->insertData($_SESSION['user_session'], $object_type, $object_id, $location, $problem_type, $short, $problem_date, $priority, $source, $long, $solution, $deadline, $status);
      $Tasks->insertResponsibles($users);


      if(isset($_POST['problem_tocalendar'])) {
        if($object_type === "1") {
          $Calendar->insertData($object_id, "Muu", $deadline . " 15:00", "", NULL, $short, NULL);
        } else {
          $Calendar->insertData(NULL, "Muu", $deadline . " 15:00", "", NULL, $short, NULL);
        }

      }

      //$Tasks->sendTask($_SESSION['user_name'], $object_type, $object_id, $location, $problem_type, $short, $problem_date, $priority, $source, $long, $solution, $user, $deadline, $status);
      header("Location: ulesanded.php");
    } else {
      $_SESSION['error_msg'] = "Järgnevad väljad ei tohi olla tühjad: ";
    }
  }
}


?>
