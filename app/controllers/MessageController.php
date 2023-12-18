<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/Message.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\Message;
use app\utils\Response;
use Database;
use PDO;

class MessageController
{
  private $db;
  private $messageModel;

  public $completeDate;
  public $lastPart;

  public function __construct()
  {
    $currentURL = $_SERVER['REQUEST_URI'];
    // Obtém a última parte da URI
    $parts = explode('/', $currentURL);

    $database = new Database();
    $this->lastPart = end($parts);
    $this->db = $database->getConnection();
    $this->messageModel = new Message($this->db);
  }

  public function getAll()
  {
    $result = $this->messageModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $messages_arr = array();
      $messages_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $message_item = array(
          'id' => $id,
          'name' => $name,
          'email' => $email,
          'subject' => $subject,
          'message' => $message,
          'date_create' => $date_create,
        );

        array_push($messages_arr['data'], $message_item);
      }

      Response::send(200, $messages_arr);
    } else {
      Response::send(404, array('msg' => 'Nenhum mensagem encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->messageModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $message_item = array(
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'date_create' => $date_create,
      );

      Response::send(200, $message_item);
    } else {
      Response::send(404, array('msg' => 'Mensagem não encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->messageModel->getByTerm($term);
    $num = $result->rowCount();

    $messages_arr = array();

    if ($num > 0) {
      $messages_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $message_item = array(
          'id' => $id,
          'name' => $name,
          'email' => $email,
          'subject' => $subject,
          'message' => $message,
          'date_create' => $date_create,
        );

        array_push($messages_arr['data'], $message_item);
      }

      Response::send(200, $messages_arr);
    } else {
      Response::send(404, array('error' => true, 'msg' => 'Nenhum usuario encontrado.', $messages_arr));
    }
  }


  public function create()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $subject = $data['subject'] ?? '';
    $message = $data['message'] ?? '';

    if (empty($name)) {
      $return = ['error' => true, 'msg' => 'O campo nome está vazio'];
    } elseif (empty($email)) {
      $return = ['error' => true, 'msg' => 'O campo email está vazio'];
    } elseif (empty($subject)) {
      $return = ['error' => true, 'msg' => 'O campo assunto está vazio'];
    } elseif (empty($message)) {
      $return = ['error' => true, 'msg' => 'O campo mensagem está vazio'];
    } else {

      if ($this->messageModel->createNew(
        $name,
        $email,
        $subject,
        $message
      )) {
        $return = ['msg' => 'Sua mensagem foi enviada com sucesso.'];
      } else {
        $return = ['msg' => 'Ocorreu um erro ao emviar a mensagem.'];
      }
    }

    echo json_encode($return);
  }

  public function delete()
  {
    $id = $this->lastPart;

    if ($this->messageModel->deleteById($id)) {
      Response::send(200, array('msg' => 'Mensagem excluído com sucesso.'));
    } else {
      Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o mensagem.'));
    }
  }

  public function update()
  {
    $id = $this->lastPart;

    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';

    if (!empty($name) && !empty($email)) {
      if ($this->messageModel->update($id, $name, $email)) {
        Response::send(200, array('msg' => 'Mensagem atualizado com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao atualizar o mensagem.'));
      }
    } else {
      Response::send(400, array('msg' => 'Dados insuficientes para atualizar o mensagem.'));
    }
  }

  // 404
  public function notFound()
  {
    echo "404";
  }
}