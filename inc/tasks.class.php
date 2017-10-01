<?php
class Tasks {
    private $connection;
    private $userlist;
    public $objectNames;
    public $propertyNames;
    public $businessNames;
    public $playgroundNames;
    public $filterUser = "";
    public $filterStatus = [];
    public $filterObject = [];
    public $filterData = false;
    public $lastInsert = 0;

    function __construct($mysqli)
    {
        $this->connection = $mysqli;
        $this->userlist = $this->getAllUsers();
        $this->getObjectNames();
        $this->getPropertiesNames();
        $this->getBusinessNames();
        $this->getPlaygroundNames();
    }

    function insertData($inserted, $object_type, $object_id, $location, $problem_type, $short, $problem_date, $priority, $source, $long, $solution, $deadline, $status)
    {
        $problem_date = substr($problem_date,6,4) . "-" . substr($problem_date,3,2) . "-" . substr($problem_date,0,2);
        $deadline = substr($deadline,6,4) . "-" . substr($deadline,3,2) . "-" . substr($deadline,0,2);

        //MEILI SAATMINE
        //$this->taskSend($inserted, $long, $user);

        $stmt = $this->connection->prepare("INSERT INTO tasks (inserted, object_type, object_id, location, source, short_description, date, priority, type, long_description, solution, deadline, status, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiissssssssss", $inserted, $object_type, $object_id, $location, $problem_type, $short, $problem_date, $priority, $source, $long, $solution, $deadline, $status);
        $stmt->execute();
        $this->lastInsert = $this->connection->insert_id;
        $stmt->close();
    }

    function insertResponsibles($users) {
      $done = array();
      foreach($users as $user) {
        array_push($done, 0);
      }
      foreach (array_keys($users, "") as $key) {
        unset($users[$key]);
        unset($done[$key]);
      }

      join("", array_filter($done));
      join("", array_filter($users));
      $done = implode(",", $done);
      $users = implode(",", $users);

      $stmt = $this->connection->prepare("INSERT INTO task_responsible (task_id, users, done) VALUES (?, ?, ?)");
      $stmt->bind_param("iss", $this->lastInsert, $users, $done);
      $stmt->execute();
      $stmt->close();
    }

    function editResponsibles($task, $users, $done) {

      $stmt = $this->connection->prepare("UPDATE task_responsible SET users = ?, done = ? WHERE task_id = ?");
      $stmt->bind_param("ssi", $users, $done, $task);
      $stmt->execute();
      $stmt->close();

    }


    function updateData($id, $object_type, $object_id, $location, $problem_type, $short, $problem_date, $priority, $source, $long, $solution, $deadline, $status) {

      $problem_date = substr($problem_date,6,4) . "-" . substr($problem_date,3,2) . "-" . substr($problem_date,0,2);
      $deadline = substr($deadline,6,4) . "-" . substr($deadline,3,2) . "-" . substr($deadline,0,2);

      $stmt = $this->connection->prepare("UPDATE tasks SET object_type = ?, object_id = ?, location = ?, source = ?, short_description = ?, date = ?, priority = ?, type = ?, long_description = ?, solution = ?, deadline = ?, status = ? WHERE id = ?");
      $stmt->bind_param("iissssssssssi", $object_type, $object_id, $location, $problem_type, $short, $problem_date, $priority, $source, $long, $solution, $deadline, $status, $id);
      $stmt->execute();
      $stmt->close();

    }

    function deleteTask($id)
    {
        $stmt = $this->connection->prepare("UPDATE tasks SET deleted = NOW() WHERE id = ?");
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

        $stmt = $this->connection->prepare("SELECT * FROM task_responsible
                                            INNER JOIN tasks ON tasks.id = task_responsible.task_id
                                            WHERE tasks.deleted IS NULL GROUP BY task_responsible.task_id");

        $stmt->bind_result($res_id, $res_task, $res_user, $res_done, $res_created, $res_deleted,
                           $id, $inserted, $object_type, $object_id, $location, $source, $short, $date, $priority, $type,
                           $long, $solution, $deadline, $status, $created, $deleted);

        /*

        foreach ($this->getAllUsers() as $data) {
          if($data->user_id === $user) {
            $user = $data->firstname . " " . $data->lastname;
          }

          $user_id, $user_name,
          $user_first, $user_last, $user_email, $user_pass, $user_group, $user_rights, $user_join, $user_delete

          */


        /*$stmt = $this->connection->prepare("SELECT * FROM tasks
                                            INNER JOIN users ON users.user_id = tasks.user
                                            WHERE tasks.deleted IS NULL");
        $stmt->bind_result($id, $inserted, $object_type, $object_id, $location, $source, $short, $date, $priority, $type,
                           $long, $solution, $user, $deadline, $status, $created, $deleted, $user_id, $user_name,
                           $user_first, $user_last, $user_email, $user_pass, $user_group, $user_rights, $user_join, $user_delete);*/

        $stmt->execute();

        $array = array();

        while ($stmt->fetch()) {
            $allowData = false;
            $allowGroup = false;
            $rights = array();
            $a = new Stdclass();

            $temp_users = explode(",",$res_user);
            $temp_done = explode(",",$res_done);

            for($i = 0; $i < count($temp_users); $i++) {
              if($temp_done[$i] === "0") {
                $user = (int)$temp_users[$i];
                break;
              } else if(count($temp_users) - 1 === $i && $temp_done[$i] === "1") {
                $user = (int)$temp_users[$i];
              }
            }



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

                    foreach($this->userlist as $list) {
                      if((int)$user === (int)$list->user_id) {

                        $a->user_first = $list->firstname;
                        $a->user_last = $list->lastname;
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

            }


        }

        if (count($array) > 0) {
            echo json_encode($array);

        } else {
            echo json_encode(new stdClass);
        }


        $stmt->close();

    }

    function doesSimilarCalendarExists($id, $start, $text) {
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

    function getDataByID($dbid)
    {

        $stmt = $this->connection->prepare("SELECT * FROM task_responsible
                                            INNER JOIN tasks ON tasks.id = task_responsible.task_id
                                            WHERE tasks.deleted IS NULL AND task_responsible.task_id = ?");

        $stmt->bind_param("i", $dbid);

        $stmt->bind_result($res_id, $res_task, $res_users, $res_dones, $res_created, $res_deleted,
                           $id, $inserted, $object_type, $object_id, $location, $source, $short, $date, $priority, $type,
                           $long, $solution, $deadline, $status, $created, $deleted);

        //$stmt = $this->connection->prepare("SELECT * FROM tasks WHERE deleted IS NULL AND id = ?");
        //$stmt->bind_result($id, $inserted, $object_type, $object_id, $location, $source, $short, $date, $priority, $type, $long, $solution, $deadline, $status, $created, $deleted);
        $stmt->execute();

        $a = new Stdclass();
        /*$a->user = array();
        $a->isdone = array();*/

        while ($stmt->fetch()) {
            $a->id = $id;
            $a->inserted = $inserted;
            $a->object_type = $object_type;
            $a->user = $res_users;
            $a->isdone = $res_dones;
        }

        /*foreach($res_users as $res_user) {
          array_push($a->user, $res_user);
        }
        foreach($res_dones as $res_done) {
          array_push($a->isdone, $res_done);
        }*/

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
        $a->source = $source;
        $a->short = $short;
        $a->date = $date;
        $a->priority = $priority;
        $a->type = $type;
        $a->long = $long;
        $a->solution = $solution;
        $a->deadline = $deadline;
        $a->status = $status;
        $a->created = $created;
        $a->deleted = $deleted;
        //$a->user = $res_user;

        $stmt->close();

        //$oncalendar = $this->doesSimilarCalendarExists($object_id, $deadline . " 15:00", $long);
        //$a->oncalendar = $oncalendar;

        foreach ($this->getAllUsers() as $userthis) {
          if((int)$userthis->user_id === (int)$inserted) {
            $a->insertedname = utf8_encode($userthis->firstname) . " " . utf8_encode($userthis->lastname);
          }
        }

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
        $old_status = $this->connection->query("SELECT status FROM tasks WHERE id = $task_id")->fetch_object()->status;
        $created_by = $this->connection->query("SELECT inserted FROM tasks WHERE id = $task_id")->fetch_object()->inserted;
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
			$message .= "Probleemi lahenduseks on: <br>";
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
                                          WHERE tasks.deleted IS NULL AND priority = 'Kõrge' AND deadline = CURDATE() + INTERVAL 7 DAY AND (status = 'Registreeritud' OR status = 'Pooleli')");
      $stmt->bind_result($id, $inserted, $object_type, $object_id, $location, $source, $short, $date, $priority, $type,
                         $long, $solution, $user, $deadline, $status, $created, $deleted, $user_id, $user_name,
                         $user_first, $user_last, $user_email, $user_pass, $user_group, $user_rights, $user_join, $user_delete);

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
                                          WHERE tasks.deleted IS NULL AND priority = 'Keskmine' AND deadline = CURDATE() + INTERVAL 5 DAY AND (status = 'Registreeritud' OR status = 'Pooleli')");
      $stmt->bind_result($id, $inserted, $object_type, $object_id, $location, $source, $short, $date, $priority, $type,
                         $long, $solution, $user, $deadline, $status, $created, $deleted, $user_id, $user_name,
                         $user_first, $user_last, $user_email, $user_pass, $user_group, $user_rights, $user_join, $user_delete);

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
                                          WHERE tasks.deleted IS NULL AND priority = 'Madal' AND deadline = CURDATE() + INTERVAL 3 DAY AND (status = 'Registreeritud' OR status = 'Pooleli')");
      $stmt->bind_result($id, $inserted, $object_type, $object_id, $location, $source, $short, $date, $priority, $type,
                         $long, $solution, $user, $deadline, $status, $created, $deleted, $user_id, $user_name,
                         $user_first, $user_last, $user_email, $user_pass, $user_group, $user_rights, $user_join, $user_delete);

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
