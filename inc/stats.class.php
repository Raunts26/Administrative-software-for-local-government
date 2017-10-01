<?phpclass Stats {

    private $connection;

    function __construct($mysqli){
        $this->connection = $mysqli;
    }

    function countObjects() {
      $stmt = $this->connection->prepare("SELECT count(id) FROM objects WHERE deleted IS NULL");
      $stmt->bind_result($id);
      $stmt->execute();

      $array = array();

      $stmt->fetch();

      return($id);

      $stmt->close();

    }

    function countUsers() {
      $stmt = $this->connection->prepare("SELECT count(user_id) FROM users");
      $stmt->bind_result($id);
      $stmt->execute();

      $array = array();

      $stmt->fetch();

      return($id);

      $stmt->close();

    }

    function countPlaygrounds() {
      $stmt = $this->connection->prepare("SELECT count(id) FROM playgrounds WHERE deleted IS NULL");
      $stmt->bind_result($id);
      $stmt->execute();

      $array = array();

      $stmt->fetch();

      return($id);

      $stmt->close();

    }

    function countProperties() {
      $stmt = $this->connection->prepare("SELECT count(id) FROM properties WHERE deleted IS NULL");
      $stmt->bind_result($id);
      $stmt->execute();

      $array = array();

      $stmt->fetch();

      return($id);

      $stmt->close();

    }



}

?>