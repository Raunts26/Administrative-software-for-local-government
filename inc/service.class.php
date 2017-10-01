<?php
class Service {

    private $connection;
    public $lastInsert;
    public $filterUser = "";
    public $filterStatus = [];
    public $filterObject = [];
    public $filterData = false;


    function __construct($mysqli){
        $this->connection = $mysqli;

    }

    function insertData($object, $name, $field, $contract, $deadline, $comments, $pay, $period) {
      $deadline = substr($deadline,6,4) . "-" . substr($deadline,3,2) . "-" . substr($deadline,0,2);
      $object = utf8_decode($object);
      $name = utf8_decode($name);
      $field = utf8_decode($field);
      $contract = utf8_decode($contract);
      $comments = utf8_decode($comments);
      $pay = utf8_decode($pay);
      $period = utf8_decode($period);

      $stmt = $this->connection->prepare("INSERT INTO service (object_id, main_article, sub_article, name, field, contract, deadline, comments, pay, period, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
      $stmt->bind_param("isssssss", $object, $name, $field, $contract, $deadline, $comments, $pay, $period);
      $stmt->execute();

      $this->lastInsert = $this->connection->insert_id;

      $stmt->close();

    }

    function insertContact($name, $field, $phone, $email, $comments) {
      $name = utf8_decode($name);
      $field = utf8_decode($field);
      $comments = utf8_decode($comments);

      $stmt = $this->connection->prepare("INSERT INTO service_contacts (service_id, name, field, phone, email, comments, inserted) VALUES (?, ?, ?, ?, ?, ?, NOW())");
      $stmt->bind_param("isssss", $this->lastInsert, $name, $field, $phone, $email, $comments);
      $stmt->execute();
      $stmt->close();

    }

    function insertNewContact($id, $name, $field, $phone, $email, $comments) {
      $name = utf8_decode($name);
      $field = utf8_decode($field);
      $comments = utf8_decode($comments);

      $stmt = $this->connection->prepare("INSERT INTO service_contacts (service_id, name, field, phone, email, comments, inserted) VALUES (?, ?, ?, ?, ?, ?, NOW())");
      $stmt->bind_param("isssss", $id, $name, $field, $phone, $email, $comments);
      $stmt->execute();
      $stmt->close();

    }

    function updateData($id, $object, $name, $field, $contract, $deadline, $comments, $pay, $period) {
      $deadline = substr($deadline,6,4) . "-" . substr($deadline,3,2) . "-" . substr($deadline,0,2);
      $object = utf8_decode($object);
      $name = utf8_decode($name);
      $field = utf8_decode($field);
      $contract = utf8_decode($contract);
      $comments = utf8_decode($comments);
      $pay = utf8_decode($pay);
      $period = utf8_decode($period);

      $stmt = $this->connection->prepare("UPDATE service SET object_id = ?, main_article = ?, sub_article = ?, name = ?, field = ?, contract = ?, deadline = ?, comments = ?, pay = ?, period = ? WHERE id = ?");
      $stmt->bind_param("isssssssi", $object, $name, $field, $contract, $deadline, $comments, $pay, $period, $id);
      $stmt->execute();
      $stmt->close();

    }

    function updateContact($id, $name, $field, $phone, $email, $comments) {
      $name = utf8_decode($name);
      $field = utf8_decode($field);
      $comments = utf8_decode($comments);

      $stmt = $this->connection->prepare("UPDATE service_contacts SET name = ?, field = ?, phone = ?, email = ?, comments = ? WHERE id = ?");
      $stmt->bind_param("sssssi", $name, $field, $phone, $email, $comments, $id);
      $stmt->execute();
      $stmt->close();

    }

    function deleteService($ID) {
      $stmt = $this->connection->prepare("UPDATE service SET deleted = NOW() WHERE id = ?");
      $stmt->bind_param("i", $ID);
      $stmt->execute();
      $stmt->close();
    }


    function deleteContact($id) {
      $stmt = $this->connection->prepare("UPDATE service_contacts SET deleted = NOW() WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $stmt->close();
    }



    function getData() {
      $stmt = $this->connection->prepare("SELECT * FROM service
                                          INNER JOIN articles_main ON articles_main.id = service.main_article
                                          INNER JOIN articles_sub ON articles_sub.id = service.sub_article
                                          INNER JOIN objects ON objects.id = service.object_id
                                          WHERE service.deleted IS NULL");
      $stmt->bind_result($id, $object, $main_article, $sub_article, $specification, $name, $contract, $pay, $period, $deadline, $comments, $created, $deleted,
                         $main_id, $main_number, $main_name, $main_created, $main_deleted,
                         $sub_id, $sub_main_id, $sub_number, $sub_name, $sub_created, $sub_deleted,
                         $oid, $otype, $oname, $oaddres, $ocode, $oyear, $ousedfor, $ocontact, $oemail, $onumber, $ocre, $odel);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $allowData = false;
        $allowGroup = false;
        $rights = array();
        $a = new Stdclass();
        $object_type = 1;

        $object = utf8_encode($object);
        $oname = utf8_encode($oname);
        $name = utf8_encode($name);
        $specification = utf8_encode($specification);
        $contract = utf8_encode($contract);
        $pay = utf8_encode($pay);
        $period = utf8_encode($period);
        $comments = utf8_encode($comments);


        if ($_SESSION['rights'] !== NULL) {
            $rights = explode(",", $_SESSION['rights']);

            for ($i = 0; $i < count($rights); $i++) {
                if ($object_type === 1) {
                    if ((int)$rights[$i] === (int)$object) {
                        $allowGroup = true;
                    }
                }
            }
        } else {
            $allowGroup = true;
        }

        if ($allowGroup === true) {

          if ($this->filterData === true) {

              if (count($this->filterStatus) > 0 && count($this->filterObject) === 0) {
                  for ($k = 0; $k < count($this->filterStatus); $k++) {
                      if ($this->filterStatus[$k] === $field) {
                          $allowData = true; // Valdkonna järgi filter
                      }
                  }
              } else if (count($this->filterStatus) === 0 && count($this->filterObject) > 0) {
                for ($k = 0; $k < count($this->filterObject); $k++) {
                    if ((int)$this->filterObject[$k] === (int)$object) {
                        $allowData = true; // Objekti järgi filter
                    }
                }
              } else if (count($this->filterStatus) > 0 && count($this->filterObject) > 0) {
                  for ($k = 0; $k < count($this->filterObject); $k++) {
                      if ((int)$this->filterObject[$k] === (int)$object) {
                        for ($l = 0; $l < count($this->filterStatus); $l++) {
                            if ($this->filterStatus[$l] === $field) {
                                $allowData = true; // Valdkonna ja objekti järgi filter
                            }
                        }
                      }
                  }

              } else {
                /*for ($k = 0; $k < count($this->filterObject); $k++) {
                    if ((int)$this->filterObject[$k] === (int)$object_id) {
                      for ($l = 0; $l < count($this->filterStatus); $l++) {
                          if ($this->filterStatus[$l] === $status && (int)$this->filterUser === $user) {
                              $allowData = true; // Staatuse ja objekti järgi filter
                          }
                      }
                    }
                }*/
              }

          } else {
              $allowData = true;
          }


          if ($allowData === true) {

            $a->id = $id;
            $a->object = $object;
            $a->main = $main_name;
            $a->sub = $sub_name;
            $a->object_name = $oname;
            $a->name = $name;
            $a->specification = $specification;
            $a->contract = $contract;
            $a->pay = $pay;
            $a->period = $period;
            $a->deadline = $deadline;
            $a->comments = $comments;
            $a->created = $created;
            $a->deleted = $deleted;

            array_push($array, $a);

          }

        }

      }

      echo json_encode($array);

      $stmt->close();

    }

    function getContacts($id) {
      $stmt = $this->connection->prepare("SELECT * FROM service_contacts WHERE service_id = ? AND deleted IS NULL");
      $stmt->bind_param("i", $id);
      $stmt->bind_result($contact_id, $contact_service_id, $contact_name, $contact_email, $contact_phone,
                         $contact_field, $contact_comments, $contact_inserted, $contact_deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $a = new Stdclass();

        $contact_name = utf8_encode($contact_name);
        $contact_field = utf8_encode($contact_field);
        $contact_comments = utf8_encode($contact_comments);


        $a->id = $contact_id;
        $a->name = $contact_name;
        $a->email = $contact_email;
        $a->phone = $contact_phone;
        $a->field = $contact_field;
        $a->comments = $contact_comments;

        array_push($array, $a);
      }

      echo json_encode($array);

      $stmt->close();

    }



    function GetServiceDataByID($dbid) {
      $stmt = $this->connection->prepare("SELECT * FROM service WHERE deleted IS NULL AND id = ?");
      $stmt->bind_param("i", $dbid);
      $stmt->bind_result($id, $object, $main, $sub, $name, $field, $contract, $pay, $period, $deadline, $comments, $created, $deleted);

      $stmt->execute();

      if($stmt->fetch()) {
        $a = new Stdclass();


        $object = utf8_encode($object);
        $name = utf8_encode($name);
        $field = utf8_encode($field);
        $contract = utf8_encode($contract);
        $pay = utf8_encode($pay);
        $period = utf8_encode($period);
        $comments = utf8_encode($comments);


        $a->id = $id;
        $a->object = $object;
        $a->main = $main;
        $a->sub = $sub;
        $a->name = $name;
        $a->field = $field;
        $a->contract = $contract;
        $a->pay = $pay;
        $a->period = $period;
        $a->deadline = $deadline;
        $a->comments = $comments;
        $a->created = $created;
        $a->deleted = $deleted;

	     }

       echo json_encode($a);
       $stmt->close();

   }

   function getServiceDocs($service) {
     $stmt = $this->connection->prepare("SELECT id, name, link, extension FROM service_documents WHERE service_id = ? AND deleted IS NULL ORDER BY extension");
     $stmt->bind_param("i", $service);
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

   function deleteServiceDocs($id) {
     $stmt = $this->connection->prepare("UPDATE service_documents SET deleted = NOW() WHERE id = ?");
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $stmt->close();
   }

}

?>
