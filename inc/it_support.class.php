<?php
class IT_support {
    private $connection;
    public $objectNames;
    public $propertyNames;
    public $businessNames;
    public $playgroundNames;
    public $filterUser = "";
    public $filterStatus = [];
    public $filterObject = [];
    public $filterData = false;

    function __construct($mysqli)
    {
        $this->connection = $mysqli;
        $this->getObjectNames();
        $this->getPropertiesNames();
        $this->getBusinessNames();
        $this->getPlaygroundNames();
    }

    function insertData($inserted, $object_type, $object_id, $location, $tv_id, $short, $problem_date, $priority, $long, $solution, $user, $deadline, $status)
    {
        $problem_date = substr($problem_date,6,4) . "-" . substr($problem_date,3,2) . "-" . substr($problem_date,0,2);
        $deadline = substr($deadline,6,4) . "-" . substr($deadline,3,2) . "-" . substr($deadline,0,2);

        $this->taskSend($inserted, $long, $user);
        $stmt = $this->connection->prepare("INSERT INTO it_support (inserted, object_type, object_id, location, remote_id, short_description, date, priority, long_description, solution, user, deadline, status, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiisssssssiss", $inserted, $object_type, $object_id, $location, $tv_id, $short, $problem_date, $priority, $long, $solution, $user, $deadline, $status);
        $stmt->execute();
        $stmt->close();
    }

    function updateData($id, $object_type, $object_id, $location, $tv_id, $short, $problem_date, $priority, $long, $solution, $user, $deadline, $status)
    {
      $problem_date = substr($problem_date,6,4) . "-" . substr($problem_date,3,2) . "-" . substr($problem_date,0,2);
      $deadline = substr($deadline,6,4) . "-" . substr($deadline,3,2) . "-" . substr($deadline,0,2);


        $stmt = $this->connection->prepare("UPDATE it_support SET object_type = ?, object_id = ?, location = ?, remote_id = ?, short_description = ?, date = ?, priority = ?, long_description = ?, solution = ?, user = ?, deadline = ?, status = ? WHERE id = ?");
        $stmt->bind_param("iisssssssissi", $object_type, $object_id, $location, $tv_id, $short, $problem_date, $priority, $long, $solution, $user, $deadline, $status, $id);
        $stmt->execute();
        $stmt->close();
    }

    function deleteTask($id)
    {
        $stmt = $this->connection->prepare("UPDATE it_support SET deleted = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    function countMyTasks($user)
    {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM tasks WHERE deleted IS NULL AND user = ? AND status != 'Tehtud'");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();

        echo($count);

        $stmt->close();
    }

    function getMyTasks($start, $end, $user)
    {
        $stmt = $this->connection->prepare("SELECT * FROM tasks WHERE deleted IS NULL AND user = ? AND status != 'Tehtud' ORDER BY created DESC LIMIT $start, $end");
        $stmt->bind_param("s", $user);
        $stmt->bind_result($id, $inserted, $object_type, $object_id, $location, $source, $short, $date, $priority, $type, $long, $solution, $user, $deadline, $status, $created, $deleted);
        $stmt->execute();

        $array = array();

        while ($stmt->fetch()) {
            $a = new Stdclass();
            $a->id = $id;
            $a->inserted = $inserted;
            $a->object_type = $object_type;

            if ($object_type === 1) {
                $new = $this->objectNames;
            } else if ($object_type === 2) {
                $new = $this->propertyNames;
            } else if ($object_type === 3) {
                $new = $this->businessNames;
            } else {
                $new = $this->playgroundNames;
            }

            for ($i = 0; $i < count($new); $i++) {
                if ($new[$i]->id === $object_id) {
                    $a->object_id = $new[$i]->name;
                }
            }

            $a->location = $location;
            $a->source = $source;
            $a->short = $short;
            $a->date = $date;
            $a->priority = $priority;
            $a->type = $type;
            $a->long = $long;
            $a->solution = $solution;
            $a->user = $user;
            $a->deadline = $deadline;
            $a->status = $status;
            $a->created = $created;
            $a->deleted = $deleted;

            array_push($array, $a);
        }

        echo(json_encode($array));

    }

    function getData()
    {
        $stmt = $this->connection->prepare("SELECT * FROM it_support
                                            INNER JOIN users ON users.user_id = it_support.user
                                            WHERE it_support.deleted IS NULL");
        $stmt->bind_result($id, $inserted, $object_type, $object_id, $location, $tv_id, $short, $date,
                           $priority, $long, $solution, $user, $deadline, $status, $created, $deleted, $user_id,
                           $user_name, $user_first, $user_last, $user_email, $user_pass, $user_group, $user_rights,
                           $user_join, $user_deleted);
        $stmt->execute();

        $array = array();

        while ($stmt->fetch()) {
            $allowData = false;
            $allowGroup = false;
            $rights = array();
            $a = new Stdclass();

            if ($_SESSION['rights'] !== NULL) {
                $rights = explode(",", $_SESSION['rights']);

                for ($i = 0; $i < count($rights); $i++) {
                    if ($object_type === 1) {
                        if ((int)$rights[$i] === (int)$object_id) {
                            $allowGroup = true;
                        }
                    }
                }
            } else {
                $allowGroup = true;
            }

            if ($allowGroup === true) {

                if ($this->filterData === true) {

                    if ($this->filterUser !== NULL && count($this->filterStatus) === 0 && count($this->filterObject) === 0) {
                        if ((int)$this->filterUser === $user) {
                            $allowData = true; // Kasutaja järgi filter
                        }
                    } else if (count($this->filterStatus) > 0 && $this->filterUser === NULL && count($this->filterObject) === 0) {
                        for ($k = 0; $k < count($this->filterStatus); $k++) {
                            if ($this->filterStatus[$k] === $status) {
                                $allowData = true; // Staatuse järgi filter
                            }
                        }
                    } else if (count($this->filterStatus) === 0 && $this->filterUser === NULL && count($this->filterObject) > 0) {
                      for ($k = 0; $k < count($this->filterObject); $k++) {
                          if ((int)$this->filterObject[$k] === (int)$object_id) {
                              $allowData = true; // Objekti järgi filter
                          }
                      }
                    } else if ($this->filterUser !== NULL && count($this->filterStatus) > 0 && count($this->filterObject) === 0) {
                        for ($k = 0; $k < count($this->filterStatus); $k++) {
                            if ($this->filterStatus[$k] === $status && (int)$this->filterUser === $user) {
                                $allowData = true; // Kasutaja ja staatuse järgi filter
                            }
                        }

                    } else if ($this->filterUser !== NULL && count($this->filterStatus) === 0 && count($this->filterObject) > 0) {
                        for ($k = 0; $k < count($this->filterObject); $k++) {
                            if ((int)$this->filterObject[$k] === (int)$object_id && (int)$this->filterUser === $user) {
                                $allowData = true; // Kasutaja ja objekti järgi filter
                            }
                        }

                    } else if ($this->filterUser === NULL && count($this->filterStatus) > 0 && count($this->filterObject) > 0) {
                        for ($k = 0; $k < count($this->filterObject); $k++) {
                            if ((int)$this->filterObject[$k] === (int)$object_id) {
                              for ($l = 0; $l < count($this->filterStatus); $l++) {
                                  if ($this->filterStatus[$l] === $status) {
                                      $allowData = true; // Staatuse ja objekti järgi filter
                                  }
                              }
                            }
                        }

                    } else {
                      for ($k = 0; $k < count($this->filterObject); $k++) {
                          if ((int)$this->filterObject[$k] === (int)$object_id) {
                            for ($l = 0; $l < count($this->filterStatus); $l++) {
                                if ($this->filterStatus[$l] === $status && (int)$this->filterUser === $user) {
                                    $allowData = true; // Staatuse ja objekti järgi filter
                                }
                            }
                          }
                      }
                    }

                } else {
                    $allowData = true;
                }


                if ($allowData === true) {
                    $a->id = $id;
                    $a->inserted = $inserted;
                    $a->object_type = $object_type;

                    if ($object_type === 1) {
                        $new = $this->objectNames;
                    } else if ($object_type === 2) {
                        $new = $this->propertyNames;
                    } else if ($object_type === 3) {
                        $new = $this->businessNames;
                    } else {
                        $new = $this->playgroundNames;
                    }

                    for ($i = 0; $i < count($new); $i++) {
                        if ($new[$i]->id === $object_id) {
                            $a->object_id = $new[$i]->name;
                        }
                    }


                    $a->location = $location;
                    $a->tv_id = $tv_id;
                    $a->short = $short;
                    $a->date = $date;
                    $a->priority = $priority;
                    $a->long = $long;
                    $a->solution = $solution;
                    $a->user = $user;
                    $a->deadline = $deadline;
                    $a->status = $status;
                    $a->created = $created;
                    $a->deleted = $deleted;
                    $a->user_first = $user_first;
                    $a->user_last = $user_last;

                    array_push($array, $a);
                }

            }


        }

        if (count($array) > 0) {
            echo json_encode($array);

        } else {
            echo json_encode(new stdClass);
        }


        $stmt->close();

    }

    function getDataByID($dbid)
    {
        $stmt = $this->connection->prepare("SELECT * FROM it_support WHERE deleted IS NULL AND id = ?");
        $stmt->bind_param("i", $dbid);
        $stmt->bind_result($id, $inserted, $object_type, $object_id, $location, $tv_id, $short, $date, $priority, $long, $solution, $user, $deadline, $status, $created, $deleted);
        $stmt->execute();


        if ($stmt->fetch()) {
            $a = new Stdclass();
            $a->id = $id;
            $a->inserted = $inserted;
            $a->object_type = $object_type;
        }

        if ($object_type === 1) {
            $new = $this->objectNames;
        } else if ($object_type === 2) {
            $new = $this->propertyNames;
        } else if ($object_type === 3) {
            $new = $this->businessNames;
        } else {
            $new = $this->playgroundNames;
        }

        for ($i = 0; $i < count($new); $i++) {
            if ($new[$i]->id === $object_id) {
                $a->object_id = $new[$i]->name;
            }
        }

        $a->real_object_id = $object_id;
        $a->location = $location;
        $a->tv_id = $tv_id;

        $a->short = $short;
        $a->date = $date;
        $a->priority = $priority;

        $a->long = $long;
        $a->solution = $solution;
        $a->user = $user;
        $a->deadline = $deadline;
        $a->status = $status;
        $a->created = $created;
        $a->deleted = $deleted;



        $stmt->close();

        foreach ($this->getAllUsers() as $userthis) {
          if((int)$userthis->user_id === (int)$inserted) {
            $a->insertedname = utf8_encode($userthis->firstname) . " " . utf8_encode($userthis->lastname);
          }
        }
        //var_dump($a);
        echo json_encode($a);
    }

    function searchUser($keyword = "")
    {
        if ($keyword == "") {
            $search = "%%";
        } else {
            $search = "%" . $keyword . "%";
        }

        $stmt = $this->connection->prepare("SELECT user_id, user_name FROM users WHERE (user_name LIKE ?) LIMIT 3");
        $stmt->bind_param("s", $search);
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

        echo json_encode($array);

        $stmt->close();
    }

    function getObjectNames()
    {
        $stmt = $this->connection->prepare("SELECT id, name FROM objects WHERE deleted IS NULL");
        $stmt->bind_result($id, $name);
        $stmt->execute();
        $array = array();
        $rights = array();

        while ($stmt->fetch()) {
            $ob = new stdClass();
            $allowGroup = false;

            if ($_SESSION['rights'] !== NULL) {
                $rights = explode(",", $_SESSION['rights']);

                for ($i = 0; $i < count($rights); $i++) {
                    if ((int)$rights[$i] === (int)$id) {
                        $allowGroup = true;
                    }
                }
            } else {
                $allowGroup = true;
            }

            if ($allowGroup === true) {
                $name = utf8_encode($name);
                $ob->id = $id;
                $ob->name = $name;
                array_push($array, $ob);
            }
        }
        $this->objectNames = $array;
        $stmt->close();
    }

    function getPropertiesNames()
    {
        $stmt = $this->connection->prepare("SELECT id, address FROM properties WHERE deleted IS NULL");
        $stmt->bind_result($id, $name);
        $stmt->execute();
        $array = array();
        while ($stmt->fetch()) {
            $ob = new stdClass();
            //$name = utf8_encode($name);
            $ob->id = $id;
            $ob->name = $name;
            array_push($array, $ob);
        }
        $this->propertyNames = $array;
        $stmt->close();
    }

    function getBusinessNames()
    {
        $stmt = $this->connection->prepare("SELECT id, name FROM businesses WHERE deleted IS NULL");
        $stmt->bind_result($id, $name);
        $stmt->execute();
        $array = array();
        while ($stmt->fetch()) {
            $ob = new stdClass();
            //$name = utf8_encode($name);
            $ob->id = $id;
            $ob->name = $name;
            array_push($array, $ob);
        }
        $this->businessNames = $array;
        $stmt->close();
    }

    function getPlaygroundNames()
    {
        $stmt = $this->connection->prepare("SELECT id, name FROM playgrounds WHERE deleted IS NULL");
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
        $this->playgroundNames = $array;
        $stmt->close();
    }

    //Maili saatmine
    function sendTask($inserted, $object_type, $object_id, $location, $problem_type, $short, $problem_date, $priority, $source, $long, $solution, $user, $deadline, $status)
    {
      foreach ($this->getAllUsers() as $user) {
        if($user->user_id === $inserted) {
          $inserted = $user->firstname . " " . $user->lastname;
        }
      }

        $to = 'info@rae.ee';
        $subject = 'Teile on lisatud tööülesanne haldustarkvaras';
        $message = 'Kasutaja ' . $inserted . ' sisestas uue probleemi.\r\n
               Probleemi sisuks on: ' . $long;
        $headers = 'Content-Type: text/html; charset=UTF-8' . "\r\n" .
            'From: haldustarkvara@rae.ee' . "\r\n" .
            'Reply-To: haldustarkvara@rae.ee' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }

    public function getAllUsers()
    {
        $users = [];
        if ($sth = $this->connection->query("SELECT user_id, user_name, firstname, lastname FROM users WHERE deleted IS NULL")) {
            while ($row = $sth->fetch_object()) {
                $users[] = $row;
            }
            return $users;
        }
    }

    public function taskSend($inserted, $long, $user)
    {
        $user_mail = $this->connection->query("SELECT user_email FROM users WHERE user_id = '$user'")->fetch_object()->user_email;

        foreach ($this->getAllUsers() as $user) {
          if($user->user_id === $inserted) {
            $inserted = $user->firstname . " " . $user->lastname;
          }
        }

        require 'PHPMailerAutoload.php';

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->setLanguage('et');
        $mail->isHTML(true);

        $mail->setFrom('haldustarkvara@rae.ee', 'Haldustarkvara');
        $mail->AddAddress($user_mail);

        $message = "<h4>Tere</h4>";
        $message .= "Kasutaja <b>".$inserted."</b> lisas sinu jaoks uue probleemi. <br>";
        $message .= "Probleemi sisuks on: <br>";
        $message .= "<b>".$long."</b><br>";

        $mail->Subject = "Teie kasutajale on lisatud uus probleem";
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'VIGA: ' . $mail->ErrorInfo;
        } else {
            //echo 'Sõnum saadetud';
        }
    }

    public function taskSendUpdate($task_id, $user, $long, $status, $solution)
    {
        $old_status = $this->connection->query("SELECT status FROM it_support WHERE id = $task_id")->fetch_object()->status;
        $created_by = $this->connection->query("SELECT inserted FROM it_support WHERE id = $task_id")->fetch_object()->inserted;
        $user_mail = $this->connection->query("SELECT user_email FROM users WHERE user_id = '$created_by'")->fetch_object()->user_email;

        foreach ($this->getAllUsers() as $data) {
          if($data->user_id === $user) {
            $user = $data->firstname . " " . $data->lastname;
          }
        }


        if ($old_status != $status) {
            require 'PHPMailerAutoload.php';

            $mail = new PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->setLanguage('et');
            $mail->isHTML(true);

            $mail->setFrom('haldustarkvara@rae.ee', 'Haldustarkvara');
            $mail->AddAddress($user_mail);

            $message = "<h4>Tere</h4>";
            $message .= "Kasutaja <b>".$user."</b> muutis probleemi staatust.<br>";
            $message .= "Vana staatus oli <b>".$old_status."</b><br>";
            $message .= "Uueks staatuseks on <b>".$status."</b><br>";
            $message .= "Probleemi sisuks on: <br>";
            $message .= "<b>".$long."</b><br>";
			$message .= "Probleemi lahendus/kirjeldus on: <br>";
            $message .= "<b>".$solution."</b><br>";

            $mail->Subject = "Probleemi on muudetud";
            $mail->Body = $message;

            if (!$mail->send()) {
                echo 'VIGA: ' . $mail->ErrorInfo;
            } else {
                echo 'Sõnum saadetud';
            }
        }
    }

    function sendMailPriorityHigh() {
      $stmt = $this->connection->prepare("SELECT * FROM tasks
                                          INNER JOIN users ON users.user_id = tasks.user
                                          WHERE deleted IS NULL AND priority = 'Kõrge' AND deadline = CURDATE() + INTERVAL 7 DAY AND (status = 'Registreeritud' OR status = 'Pooleli')");
      $stmt->bind_result($id, $inserted, $object_type, $object_id, $location, $source, $short, $date, $priority, $type,
                         $long, $solution, $user, $deadline, $status, $created, $deleted, $user_id, $user_name,
                         $user_first, $user_last, $user_email, $user_pass, $user_group, $user_rights, $user_join, $user_deleted);

      $stmt->execute();

      while($stmt->fetch()) {
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->setLanguage('et');
        $mail->isHTML(true);

        $mail->setFrom('haldustarkvara@rae.ee', 'Haldustarkvara');
        $mail->AddAddress($user_email);

        $message = "Tere";
        $message .= "<br><br>";
        $message .= "Ülesande <b>" . $short . "</b> tähtaeg läheneb.<br>";
        $message .= "Ülesande prioriteet on <b>" . $priority . ".</b><br>";
        $message .= "<br>";
        $message .= "Tegele ülesandega esimesel võimalusel!";

        $mail->Subject = "Ülesande tähtaeg läheneb";
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'VIGA: ' . $mail->ErrorInfo;
        }


      }


    }

    function sendMailPriorityMedium() {
      $stmt = $this->connection->prepare("SELECT * FROM tasks
                                          INNER JOIN users ON users.user_id = tasks.user
                                          WHERE deleted IS NULL AND priority = 'Keskmine' AND deadline = CURDATE() + INTERVAL 5 DAY AND (status = 'Registreeritud' OR status = 'Pooleli')");
      $stmt->bind_result($id, $inserted, $object_type, $object_id, $location, $source, $short, $date, $priority, $type,
                         $long, $solution, $user, $deadline, $status, $created, $deleted, $user_id, $user_name,
                         $user_first, $user_last, $user_email, $user_pass, $user_group, $user_rights, $user_join, $user_deleted);

      $stmt->execute();

      while($stmt->fetch()) {
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->setLanguage('et');
        $mail->isHTML(true);

        $mail->setFrom('haldustarkvara@rae.ee', 'Haldustarkvara');
        $mail->AddAddress($user_email);

        $message = "Tere";
        $message .= "<br><br>";
        $message .= "Ülesande <b>" . $short . "</b> tähtaeg läheneb.<br>";
        $message .= "Ülesande prioriteet on <b>" . $priority . ".</b><br>";
        $message .= "<br>";
        $message .= "Tegele ülesandega esimesel võimalusel!";

        $mail->Subject = "Ülesande tähtaeg läheneb";
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'VIGA: ' . $mail->ErrorInfo;
        }


      }

    }

    function sendMailPriorityLow() {
      $stmt = $this->connection->prepare("SELECT * FROM tasks
                                          INNER JOIN users ON users.user_id = tasks.user
                                          WHERE deleted IS NULL AND priority = 'Madal' AND deadline = CURDATE() + INTERVAL 3 DAY AND (status = 'Registreeritud' OR status = 'Pooleli')");
      $stmt->bind_result($id, $inserted, $object_type, $object_id, $location, $source, $short, $date, $priority, $type,
                         $long, $solution, $user, $deadline, $status, $created, $deleted, $user_id, $user_name,
                         $user_first, $user_last, $user_email, $user_pass, $user_group, $user_rights, $user_join, $user_deleted);

      $stmt->execute();

      while($stmt->fetch()) {
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->setLanguage('et');
        $mail->isHTML(true);

        $mail->setFrom('haldustarkvara@rae.ee', 'Haldustarkvara');
        $mail->AddAddress($user_email);

        $message = "Tere";
        $message .= "<br><br>";
        $message .= "Ülesande <b>" . $short . "</b> tähtaeg läheneb.<br>";
        $message .= "Ülesande prioriteet on <b>" . $priority . ".</b><br>";
        $message .= "<br>";
        $message .= "Tegele ülesandega esimesel võimalusel!";

        $mail->Subject = "Ülesande tähtaeg läheneb";
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'VIGA: ' . $mail->ErrorInfo;
        }


      }

    }

}

?>
