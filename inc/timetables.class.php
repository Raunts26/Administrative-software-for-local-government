<?phpclass Timetable {

    private $connection;

    function __construct($mysqli){
        $this->connection = $mysqli;
    }


    function getBlockedCars($cars, $start, $end) {
      $cars = (int)$cars;

      $stmt = $this->connection->prepare("SELECT name, date, hour FROM timetable WHERE id_cars = ? AND deleted IS NULL AND date BETWEEN ? AND ?");
      $stmt->bind_param("iss", $cars, $start, $end);
      $stmt->bind_result($name, $date, $hour);
      if($stmt->execute()) {

      } else {
        printf($stmt->error);

      }
      $array = array();
      while($stmt->fetch()) {
        $name = utf8_encode($name);

        $object = new StdClass();
        $object->name = $name;
        $object->date = $date;
        $object->hour = $hour;
        array_push($array, $object);
      }

      //var_dump($array);
      echo json_encode($array);
      $stmt->close();
    }

    function addTimeCars($name, $car, $date, $hour) {
      $car = (int)$car;
      $name = utf8_decode($name);

      $stmt = $this->connection->prepare("INSERT INTO timetable (name, id_cars, date, hour, created) VALUES (?, ?, ?, ?, NOW())");
      $stmt->bind_param("siss", $name, $car, $date, $hour);

      if ($stmt->execute()) {

      } else {
        printf("Error: %s.\n", $stmt->error);
      }
      $stmt->close();
    }

    function deleteTimeCars($date, $hour) {

      $stmt = $this->connection->prepare("UPDATE timetable SET deleted = NOW() WHERE date = ? AND hour = ?");
      $stmt->bind_param("si", $date, $hour);
      if($stmt->execute()) {

      }
      $stmt->close();
    }

    /* Ruumide bronn */

    function getBlockedRooms($room, $start, $end) {
      $cars = (int)$cars;

      $stmt = $this->connection->prepare("SELECT name, date, hour FROM timetable_rooms WHERE id_rooms = ? AND deleted IS NULL AND date BETWEEN ? AND ?");
      $stmt->bind_param("iss", $room, $start, $end);
      $stmt->bind_result($name, $date, $hour);
      if($stmt->execute()) {

      } else {
        printf($stmt->error);

      }
      $array = array();
      while($stmt->fetch()) {
        $name = utf8_encode($name);

        $object = new StdClass();
        $object->name = $name;
        $object->date = $date;
        $object->hour = $hour;
        array_push($array, $object);
      }

      //var_dump($array);
      echo json_encode($array);
      $stmt->close();
    }

    function addTimeRooms($name, $room, $date, $hour) {
      $room = (int)$room;
      $name = utf8_decode($name);

      $stmt = $this->connection->prepare("INSERT INTO timetable_rooms (name, id_rooms, date, hour, created) VALUES (?, ?, ?, ?, NOW())");
      $stmt->bind_param("siss", $name, $room, $date, $hour);

      if ($stmt->execute()) {

      } else {
        printf("Error: %s.\n", $stmt->error);
      }
      $stmt->close();
    }

    function deleteTimeRooms($date, $hour) {

      $stmt = $this->connection->prepare("UPDATE timetable_rooms SET deleted = NOW() WHERE date = ? AND hour = ?");
      $stmt->bind_param("si", $date, $hour);
      if($stmt->execute()) {

      }
      $stmt->close();
    }





}

?>