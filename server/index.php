<?php
  require_once('database/index.php');

  class Server {
    private $data;
    private $db;

    public function __construct() {
      header('Connection: keep-alive');
      header('Content-Type: application/json');
      header('Access-Control-Allow-Origin: http://localhost:8080');
      
      $this->db = new Database();
    }

    public function handleRequest() {
      $method = $_SERVER['REQUEST_METHOD'];
      
      if ($method === 'POST') {
        $this->data = file_get_contents('php://input');
        $this->postRequest();
      } else if ($method === 'GET') {
        $this->data = $_GET['nis'];
        $this->getRequest();
      }
    }

    public function postRequest() {
      $name = $this->data;
      if (empty($name)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Nome esta vazio'));
        return;
      } else if (strlen($name) > 30) {
        http_response_code(400);
        echo json_encode(array('error' => 'Nome deve ser menor que 30 caracteres'));
        return;
      }

      $user = $this->db->insertUser($name);

      echo json_encode($user);
    }

    public function getRequest() {
      $nis = $this->data;
      
      if (strlen($nis) !== 11) {
        http_response_code(400);
        echo json_encode(array('error' => 'NIS deve ter 11 caracteres'));
        return;
      } else if (!is_numeric($nis)) {
        http_response_code(400);
        echo json_encode(array('error' => 'NIS deve ser numerico'));
        return;
      } else if ($nis < 0) {
        http_response_code(400);
        echo json_encode(array('error' => 'NIS deve ser positivo'));
        return;
      } else if (empty($nis)) {
        http_response_code(400);
        echo json_encode(array('error' => 'NIS esta vazio'));
        return;
      }

      $user = $this->db->getUser($nis);
      if (!$user) {
        http_response_code(404);
        echo json_encode(array('error' => 'Cidadao nao encontrado'));
        return;
      }

      echo json_encode($user);
    }
  }

  $server = new Server();
  $server->handleRequest();
?>