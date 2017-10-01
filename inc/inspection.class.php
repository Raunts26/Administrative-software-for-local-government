<?php
class Inspection {

    private $connection;


    function __construct($mysqli){
        $this->connection = $mysqli;

    }

    function insertData($object_type, $object_id, $time, $time_approved) {
      $stmt = $this->connection->prepare("INSERT INTO inspection (object_type, object_id, time, time_approved, created_at) VALUES (?, ?, ?, ?, NOW())");
      $stmt->bind_param("iiss", $object_type, $object_id, $time, $time_approved);
      $stmt->execute();
      $stmt->close();

    }

    function updateData($object_type, $object_id, $time, $time_approved) {
      $stmt = $this->connection->prepare("UPDATE inspection SET object_type = ?, object_id = ?, time = ?, time_approved = ?, WHERE ID = ?");
      $stmt->bind_param("iiss", $object_type, $object_id, $time, $time_approved, $id);
      $stmt->execute();
      $stmt->close();

    }

    function deleteInspection($ID) {
      $stmt = $this->connection->prepare("UPDATE inspection SET deleted_at = NOW() WHERE ID = ?");
      $stmt->bind_param("i", $ID);
      $stmt->execute();
      $stmt->close();
    }






    function getData() {

      $stmt = $this->connection->prepare("SELECT * FROM inspection WHERE deleted_at IS NULL");

      $stmt->bind_result($id, $object_type, $object_id, $time, $time_approved, $created_at, $deleted_at);

      $stmt->execute();



      $array = array();



      while($stmt->fetch()) {

        $a = new Stdclass();

        $a->id = $id;

        $a->object_type = $object_type;

        $a->object_id = $object_id;

        $a->time = $time;

        $a->time_approved = $time_approved;
        $a->created_at = $created_at;

        $a->deleted_at = $deleted_at;



        array_push($array, $a);

      }





      echo json_encode($array);



      $stmt->close();



    }



    function GetInspectionDataByID($dbid) {
      $stmt = $this->connection->prepare("SELECT * FROM inspection WHERE deleted_at IS NULL AND id = ?");
      $stmt->bind_param("i", $dbid);
      $stmt->bind_result($id, $object_type, $object_id, $time, $time_approved, $created_at, $deleted_at);
      $stmt->execute();


      if($stmt->fetch()) {
        $a = new Stdclass();
         $a->id = $id;        $a->object_type = $object_type;        $a->object_id = $object_id;        $a->time = $time;        $a->time_approved = $time_approved;        $a->created_at = $created_at;        $a->deleted_at = $deleted_at;




      echo json_encode($a);

      $stmt->close();

	}










}
}

?>
