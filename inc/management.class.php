<?php
class Management {

    private $connection;
    public $lastInsert;
    public $filterUser = "";
    public $filterStatus = [];
    public $filterObject = [];
    public $filterData = false;
    public $main_articles = [];


    function __construct($mysqli){
        $this->connection = $mysqli;
        $this->getArticles();

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

      $stmt = $this->connection->prepare("INSERT INTO service (object_id, name, field, contract, deadline, comments, pay, period, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
      $stmt->bind_param("isssssss", $object, $name, $field, $contract, $deadline, $comments, $pay, $period);
      $stmt->execute();

      $this->lastInsert = $this->connection->insert_id;

      $stmt->close();

    }

    function getArticles() {
      $stmt = $this->connection->prepare("SELECT * FROM articles_main WHERE deleted IS NULL ORDER BY number");
      $stmt->bind_result($id, $number, $name, $created, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $a = new Stdclass();
        $a->id = $id;
        $a->number = $number;
        $a->name = $name;

        array_push($array, $a);
      }

      $this->main_articles = $array;

      $stmt->close();
    }

    function getData() {
      $stmt = $this->connection->prepare("SELECT * FROM service
                                          INNER JOIN objects ON objects.id = service.object_id
                                          WHERE service.deleted IS NULL");
      $stmt->bind_result($ID, $object, $name, $field, $contract, $pay, $period, $deadline, $comments, $created, $deleted,
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
        $field = utf8_encode($field);
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

            $a->ID = $ID;
            $a->object = $object;
            $a->object_name = $oname;
            $a->name = $name;
            $a->field = $field;
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



}

?>
