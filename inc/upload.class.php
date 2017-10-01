<?php
class Upload {

    private $connection;

    function __construct($mysqli){
        $this->connection = $mysqli;
    }

    function addDocument($type, $cat, $id, $name, $link, $ext) {
      $stmt = $this->connection->prepare("INSERT INTO documents (type, category, belong_id, name, link, extension, created) VALUES (?, ?, ?, ?, ?, ?, NOW())");
      $stmt->bind_param("isisss", $type, $cat, $id, $name, $link, $ext);
      $stmt->execute();
      $stmt->close();
    }

    function addServiceDocument($service, $name, $link, $ext) {
      $stmt = $this->connection->prepare("INSERT INTO service_documents (service_id, name, link, extension, created) VALUES (?, ?, ?, ?, NOW())");
      $stmt->bind_param("isss", $service, $name, $link, $ext);
      $stmt->execute();
      $stmt->close();
    }

    function addMaintanceDocument($maintance, $name, $link, $ext) {
      $stmt = $this->connection->prepare("INSERT INTO maintance_documents (fill_id, name, link, extension, created) VALUES (?, ?, ?, ?, NOW())");
      $stmt->bind_param("isss", $maintance, $name, $link, $ext);
      $stmt->execute();
      $stmt->close();
    }


}

?>
