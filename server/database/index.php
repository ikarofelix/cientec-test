<?php
  class Database {
    private $db;

    public function __construct() {
      $this->db = new mysqli(
        getenv('DB_HOST'),
        getenv('DB_USER'),
        getenv('DB_PASSWORD'),
        getenv('DB_NAME')
      );

      if ($this->db->connect_error) {
        die("Connection failed: " . $this->db->connect_error);
      }

      $sql = "CREATE TABLE IF NOT EXISTS users (
        nis VARCHAR(11) PRIMARY KEY,
        name VARCHAR(30) NOT NULL
      )";

      if ($this->db->query($sql) === false) {
        die("Error creating table: " . $this->db->error);
      }
    }

    public function getAllUsers() {
      $result = $this->db->query("SELECT * FROM users");
      $users = [];
      while ($row = $result->fetch_assoc()) {
        $users[] = $row;
      }
      return $users;
    }

    public function insertUser($name) {
      do {
        $nis = str_pad(mt_rand(0, 99999999999), 11, '0', STR_PAD_LEFT);
        $stmt = $this->db->prepare("INSERT INTO users (nis, name) VALUES (?, ?)");
        $stmt->bind_param("ss", $nis, $name);
        $result = $stmt->execute();

        if ($result === false) {
          // Code for duplicate entry (NIS)
          if ($this->db->errno == 1062) {
            continue;
          } else {
            // Throw if its another error
            throw new Exception($this->db->error);
          }
        }

        return ((object)['nis' => $nis, 'name' => $name]);
      } while (true);
    }
    
    public function getUser($nis) {
      $stmt = $this->db->prepare("SELECT * FROM users WHERE nis = ?");
      $stmt->bind_param("s", $nis);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_assoc();

      if ($user) {
        return array('nis' => $user['nis'], 'name' => $user['name']);
      } else {
        return null;
      }
    }
  }
?>