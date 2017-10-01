<?php
 class USER {
	private $conn;
	public $userdata;

	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }

	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}

	public function register($uname,$first,$last,$umail,$upass,$ugroup,$rights)
	{
		try
		{
			$new_password = password_hash($upass, PASSWORD_DEFAULT);

      if(count($rights) > 0) {
        $rights = implode(",", $rights);
      }

			$stmt = $this->conn->prepare("INSERT INTO users(user_name,firstname,lastname,user_email,user_pass,group_id,rights)
		                                               VALUES(:uname, :first, :last, :umail, :upass, :ugroup, :rights)");

			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":first", $first);
			$stmt->bindparam(":last", $last);
			$stmt->bindparam(":umail", $umail);
			$stmt->bindparam(":upass", $new_password);
			$stmt->bindparam(":ugroup", $ugroup);
			$stmt->bindparam(":rights", $rights);

			$stmt->execute();

			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function getMyData($uid) {
		try
		{
			$stmt = $this->conn->prepare("SELECT user_id, user_name, firstname, lastname, user_email, user_pass, group_id, rights FROM users WHERE user_id=:uid");
			$stmt->execute(array(':uid'=>$uid));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			return $userRow;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}

	}


	public function doLogin($uname,$umail,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT user_id, user_name, user_email, user_pass, group_id, rights FROM users WHERE user_name=:uname AND deleted IS NULL OR user_email=:umail AND deleted IS NULL ");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				if(password_verify($upass, $userRow['user_pass']))
				{
					$_SESSION['user_session'] = $userRow['user_id'];
					$_SESSION['user_group'] = $userRow['group_id'];
					$_SESSION['user_name'] = $userRow['user_name'];
					if((int)$userRow['group_id'] === 1) {
						$_SESSION['rights'] = $userRow['rights'];

					} else {
						$_SESSION['rights'] = NULL;

					}
					$this->logLogging($userRow['user_name'], 'logis sisse');
					return true;
				}
				else
				{
					$this->logLogging($userRow['user_name'], 'vale parool');
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	private function logLogging($user, $status) {
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO log_login(ip, user, status, logindate) VALUES(:uip, :uuser, :ustatus, NOW())");

			$stmt->bindparam(":uip", $_SERVER['REMOTE_ADDR']);
			$stmt->bindparam(":uuser", $user);
			$stmt->bindparam(":ustatus", $status);

			$stmt->execute();

			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}

	}

	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}



	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}

	public function getUsers() {
		$stmt = $this->conn->prepare("SELECT * FROM users WHERE deleted IS NULL");

		$array = array();

		if($stmt->execute()) {
			if($stmt->rowCount() > 0) {
	        while($result = $stmt->fetchObject()) {
	           array_push($array, $result);
	        }
        }
				return $array;
		}

		$stmt->close();
	}

	public function getUserLog() {
		$stmt = $this->conn->prepare("SELECT * FROM log_login ORDER BY logindate DESC");

		$array = array();

		if($stmt->execute()) {
			if($stmt->rowCount() > 0) {
					while($result = $stmt->fetchObject()) {
						 array_push($array, $result);
					}
				}
				echo json_encode($array);
		}

		//$stmt->close();
	}

	public function editMe($uid, $firstname, $lastname, $umail, $upass) {
		try
		{
			if(strlen($upass) > 0){
				$new_password = password_hash($upass, PASSWORD_DEFAULT);

				$stmt = $this->conn->prepare("UPDATE users SET firstname = :first, lastname = :last, user_email = :umail, user_pass = :upass WHERE user_id = :uid");
				$stmt->bindparam(":first", $firstname);
				$stmt->bindparam(":last", $lastname);
				$stmt->bindparam(":umail", $umail);
				$stmt->bindparam(":upass", $new_password);
				$stmt->bindparam(":uid", $uid);
			} else {
				$stmt = $this->conn->prepare("UPDATE users SET firstname = :first, lastname = :last, user_email = :umail WHERE user_id = :uid");
				$stmt->bindparam(":first", $firstname);
				$stmt->bindparam(":last", $lastname);
				$stmt->bindparam(":umail", $umail);
				$stmt->bindparam(":uid", $uid);
			}

			$stmt->execute();

			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function editUser($uid, $uname, $firstname, $lastname, $umail, $upass, $ugroup, $rights) {
		try
		{
			$rights = implode(",", $rights);

			if(strlen($upass) > 0){
				$new_password = password_hash($upass, PASSWORD_DEFAULT);
				$stmt = $this->conn->prepare("UPDATE users SET user_name = :uname, firstname = :first, lastname = :last, user_email = :umail, user_pass = :upass, group_id = :ugroup, rights = :urights WHERE user_id = :uid");
        $stmt->bindparam(":uname", $uname);
        $stmt->bindparam(":first", $firstname);
				$stmt->bindparam(":last", $lastname);
				$stmt->bindparam(":umail", $umail);
				$stmt->bindparam(":upass", $new_password);
				$stmt->bindparam(":ugroup", $ugroup);
				$stmt->bindparam(":urights", $rights);
				$stmt->bindparam(":uid", $uid);
		 	} else {
				$stmt = $this->conn->prepare("UPDATE users SET user_name = :uname, firstname = :first, lastname = :last, user_email = :umail, group_id = :ugroup, rights = :urights WHERE user_id = :uid");
				$stmt->bindparam(":uname", $uname);
        $stmt->bindparam(":first", $firstname);
        $stmt->bindparam(":last", $lastname);
        $stmt->bindparam(":umail", $umail);
				$stmt->bindparam(":ugroup", $ugroup);
				$stmt->bindparam(":urights", $rights);
				$stmt->bindparam(":uid", $uid);
			}

			$stmt->execute();

			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function deleteUser($uid) {
		try
		{

			$stmt = $this->conn->prepare("UPDATE users SET deleted = NOW() WHERE user_id = :uid");
			$stmt->bindparam(":uid", $uid);
			$stmt->execute();

			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

    public function mailSend($user, $first, $last, $user_mail, $password)
    {

        require 'PHPMailerAutoload.php';

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->setLanguage('et');
        $mail->isHTML(true);

        $mail->setFrom('haldustarkvara@rae.ee', 'Haldustarkvara');
        $mail->AddAddress($user_mail);

        $message = "<h4>Tere " . $first . " " . $last . "</h4>";
        $message .= "Teile loodi kasutaja Rae valla haldustarkvaras. <br>";
        $message .= "Kasutajanimi on „" . $user . "“ ja salasõna on „" . $password . "“.  Salasõna soovitame vahetada lehelt „Profiil“. <br>";
        $message .= "Tarkvara asub aadressil <a href='haldus.rae.ee'>haldus.rae.ee</a><br>";
        $message .= "Kõikide küsimustega palume pöörduda hannes.raimets@rae.ee";

        $mail->Subject = "Teile on loodud haldustarkvara konto!";
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'VIGA: ' . $mail->ErrorInfo;
        } else {
            //echo 'Sõnum saadetud';
        }
    }




}
?>
