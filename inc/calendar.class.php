<?php
class Calendar {

    private $connection;
    public $filterJob = [];
    public $filterObject = [];
    public $filterData = false;

    function __construct($mysqli){
        $this->connection = $mysqli;
    }

    function insertData($object_id, $type, $start, $end, $dow, $text, $repeat) {
      $start = substr($start,6,4) . "-" . substr($start,3,2) . "-" . substr($start,0,2) . " " . substr($start,11,2) . ":" . substr($start,14,2);
      $end = substr($end,6,4) . "-" . substr($end,3,2) . "-" . substr($end,0,2) . " " . substr($end,11,2) . ":" . substr($end,14,2);

      $stmt = $this->connection->prepare("INSERT INTO calendar (object_id, type, start, end, dow, text, isrepeat, created) VALUES (?,?,?,?,?,?,?,NOW())");
      $stmt->bind_param("issssss", $object_id, $type, $start, $end, $dow, $text, $repeat);
      $stmt->execute();
      $stmt->close();
    }

    function deleteEvents($ids) {
      $ids = explode(",",$ids);
      foreach($ids as $id) {
        $stmt = $this->connection->prepare("UPDATE calendar SET deleted = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
      }

    }

    function updateEvents($ids, $type, $start, $end, $text) {
      $start = substr($start,6,4) . "-" . substr($start,3,2) . "-" . substr($start,0,2) . " " . substr($start,11,2) . ":" . substr($start,14,2);
      $end = substr($end,6,4) . "-" . substr($end,3,2) . "-" . substr($end,0,2) . " " . substr($end,11,2) . ":" . substr($end,14,2);

      $ids = explode(",",$ids);
      foreach($ids as $id) {
        $stmt = $this->connection->prepare("UPDATE calendar SET type = ?, start = ?, end = ?, text = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $type, $start, $end, $text, $id);
        $stmt->execute();
        $stmt->close();
      }
    }

    function getSimilarEvents($gtext, $gtype, $gstart) {
      $stmt = $this->connection->prepare("SELECT * FROM calendar WHERE deleted IS NULL AND text = ? AND type = ? AND start = ?");
      $stmt->bind_param("sss", $gtext, $gtype, $gstart);
      $stmt->bind_result($id, $object_id, $type, $start, $end, $dow, $text, $repeat, $created, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $a = new Stdclass();
        $a->id = $id;
        $a->object_id = $object_id;
        array_push($array, $a);
      }

      echo json_encode($array);

    }

    public static function doesSimilarExists($id, $start, $text) {
      $stmt = $this->connection->prepare("SELECT * FROM calendar WHERE object_id = ? AND type = 'Muu' AND start = ? AND text = ? AND deleted IS NULL");
      $stmt->bind_param("iss", $id, $start, $text);
      $stmt->bind_result($id, $object_id, $type, $start, $end, $dow, $text, $repeat, $created, $deleted);
      $stmt->execute();

      $answer = false;

      if($stmt->fetch()) {
        $answer = true;
      }

      return $answer;
    }

    function getFiveEvents() {

      if ($_SESSION['rights'] !== NULL) {
        $rights = array();
        $rights = explode(",", $_SESSION['rights']);
        $stmt = $this->connection->prepare("SELECT * FROM calendar
                                            INNER JOIN objects ON objects.id = calendar.object_id
                                            WHERE calendar.deleted IS NULL AND start > NOW() ORDER BY start ASC LIMIT 5");
      } else {
        $stmt = $this->connection->prepare("SELECT * FROM calendar
                                            INNER JOIN objects ON objects.id = calendar.object_id
                                            WHERE calendar.deleted IS NULL AND start > NOW() AND isrepeat IS NULL ORDER BY start ASC LIMIT 5");
      }

      $stmt->bind_result($id, $object_id, $type, $start, $end, $dow, $text, $repeat, $created, $deleted,
                         $oid, $otype, $oname, $oaddress, $ocode, $oyear, $ousedfor, $ocontact, $oemail, $onumber, $ocr, $odel);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $a = new Stdclass();
        $allowGroup = false;


        if ($_SESSION['rights'] !== NULL) {
          for ($i = 0; $i < count($rights); $i++) {
            if ((int)$rights[$i] === (int)$object_id) {
              $allowGroup = true;
            }
          }
        } else {
          $allowGroup = true;
        }

        if($allowGroup === true) {
          $a->id = $id;
          $a->object_id = $object_id;
          $a->object_name = utf8_encode($oname);
          $a->type = $type;
          $a->start = $start;
          $a->end = $end;
          $a->dow = $dow;
          $a->text = $text;
          $a->created = $created;
          $a->deleted = $deleted;
          array_push($array, $a);
        }

      }

      echo json_encode($array);

      $stmt->close();

    }

    function getEvents() {
      if($this->filterData === true) {
        $stmt = $this->connection->prepare("SELECT * FROM calendar WHERE deleted IS NULL");
      } else {
        $stmt = $this->connection->prepare("SELECT * FROM calendar WHERE deleted IS NULL AND isrepeat IS NULL");
      }
      $stmt->bind_result($id, $object_id, $type, $start, $end, $dow, $text, $repeat, $created, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $allowData = false;
        $allowGroup = false;
        $rights = array();
        $a = new Stdclass();


        if ($_SESSION['rights'] !== NULL) {
          $rights = explode(",", $_SESSION['rights']);

          for ($i = 0; $i < count($rights); $i++) {
            if ((int)$rights[$i] === (int)$object_id) {
                $allowGroup = true;
            }
          }
        } else {
          $allowGroup = true;
        }

        if ($allowGroup === true) {

          if ($this->filterData === true) {

            if(count($this->filterJob) > 0 && count($this->filterObject) > 0) {
              for ($k = 0; $k < count($this->filterJob); $k++) {
                for ($m = 0; $m < count($this->filterObject); $m++) {
                  if($this->filterJob[$k] === "0" && (int)$this->filterObject[$m] === 0) {
                    if($repeat === NULL) {
                      $allowData = true;
                    }
                  } else if($this->filterJob[$k] === $type && (int)$this->filterObject[$m] === 0) {
                    $allowData = true;
                  } else if($this->filterJob[$k] === "0" && (int)$this->filterObject[$m] === $object_id) {
                    $allowData = true;
                  } else if ($this->filterJob[$k] === $type && (int)$this->filterObject[$m] === $object_id) {
                    $allowData = true;
                  }
                }
              }
            } else if (count($this->filterJob) > 0) {
              for ($k = 0; $k < count($this->filterJob); $k++) {
                if($this->filterJob[$k] === "0") {
                  $allowData = true;
                } else if ($this->filterJob[$k] === $type) {
                    $allowData = true;
                }
              }
            } else if (count($this->filterObject) > 0) {
              for ($k = 0; $k < count($this->filterObject); $k++) {
                if((int)$this->filterObject[$k] === 0) {

                } else if((int)$this->filterObject[$k] === $object_id) {
                    $allowData = true;
                }
              }
            }

          } else {
              $allowData = true;
          }

          if ($allowData === true) {

            $a->id = $id;
            $a->object_id = $object_id;
            $a->type = $type;
            $a->start = $start;
            $a->end = $end;
            $a->dow = $dow;
            $a->text = $text;
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
