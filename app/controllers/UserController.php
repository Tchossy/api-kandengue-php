<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/User.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\User;
use app\utils\Response;
use Database;
use PDO;

class UserController
{
  private $db;
  private $userModel;

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
    $this->userModel = new User($this->db);
  }

  public function login()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $new_password = md5($password);

    if (empty($email)) {
      $return = ['error' => true, 'msg' => 'O campo email está vazio'];
    } elseif (empty($password)) {
      $return = ['error' => true, 'msg' => 'O campo palavra-passe de telefone está vazio'];
    } else {

      $result = $this->userModel->getByEmailAndPassword($email, $new_password);
      $num = $result->rowCount();

      if ($num > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $user_item = array(
          'id' => $id,
          'photo' => $photo,
          'name' => $name,
          'number' => $number,
          'email' => $email,
          'province' => $province,
          'gender' => $gender,
          'date_create' => $date_create,
        );
        $return = ['userInfo' => $user_item, 'msg' => 'Login efetuado com sucesso.'];
      } else {
        $return = ['error' => true, 'msg' => 'Dados de aceeso incorretos, tente novamente.'];
      }
    }

    echo json_encode($return);
  }

  public function getAll()
  {
    $result = $this->userModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $users_arr = array();
      $users_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $user_item = array(
          'id' => $id,
          'photo' => $photo,
          'name' => $name,
          'number' => $number,
          'email' => $email,
          'province' => $province,
          'gender' => $gender,
          'date_create' => $date_create,
        );

        array_push($users_arr['data'], $user_item);
      }

      Response::send(200, $users_arr);
    } else {
      Response::send(404, array('msg' => 'Nenhum usúario encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->userModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $user_item = array(
        'id' => $id,
        'photo' => $photo,
        'name' => $name,
        'number' => $number,
        'email' => $email,
        'province' => $province,
        'gender' => $gender,
        'date_create' => $date_create,
      );

      Response::send(200, $user_item);
    } else {
      Response::send(404, array('error' => true, 'msg' => 'Usuário não encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->userModel->getByTerm($term);
    $num = $result->rowCount();

    $users_arr = array();

    if ($num > 0) {
      $users_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $user_item = array(
          'id' => $id,
          'photo' => $photo,
          'name' => $name,
          'number' => $number,
          'email' => $email,
          'province' => $province,
          'gender' => $gender,
          'date_create' => $date_create,
        );

        array_push($users_arr['data'], $user_item);
      }

      Response::send(200, $users_arr);
    } else {
      Response::send(404, array('error' => true, 'msg' => 'Nenhum usuario encontrado.', $users_arr));
    }
  }

  public function create()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $photo = $data['photo'] ?? '';
    $name = $data['name'] ?? '';
    $number = $data['number'] ?? '';
    $email = $data['email'] ?? '';
    $province = $data['province'] ?? '';
    $gender = $data['gender'] ?? '';
    $password = $data['password'] ?? '';
    $new_password = md5($password);

    $result = $this->userModel->getByEmail($email);
    $num_row = $result->rowCount();

    if ($num_row > 0) {
      Response::send(200, array('error' => true, 'msg' => 'Este email já encontra-se registado'));
    } else {
      if (empty($name)) {
        $return = ['error' => true, 'msg' => 'O campo nome está vazio'];
      } elseif (empty($number)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo número de telefone está vazio'));
      } elseif (empty($email)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo email está vazio'));
      } elseif (empty($province)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo provincia está vazio'));
      } elseif (empty($gender)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo genero está vazio'));
      } else {

        if ($this->userModel->createNew(
          $photo,
          $name,
          $number,
          $email,
          $province,
          $gender,
          $new_password
        )) {
          Response::send(200, array('msg' => 'O seu cadastro foi um com sucesso.'));
        } else {
          Response::send(200, array('error' => true, 'msg' => 'Ocorreu um erro ao cadastra-lo, por favor tente novamnete.'));
        }
      }
    }
  }

  public function update()
  {
    $id_doc = $this->lastPart;

    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $photo_body = $data['photo'] ?? '';
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $number = $data['number'] ?? '';
    $province = $data['province'] ?? '';
    $gender = $data['gender'] ?? '';
    $new_password = '';

    $result_data = $this->userModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();
    $row = $result_data->fetch(PDO::FETCH_ASSOC);
    $row_email = $row['email'];

    $result_count = $this->userModel->getByEmail($email);
    $num_row = $result_count->rowCount();

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Usúario não encontrado'));
    } else {
      if ($num_row > 0 && $email !== $row_email) {
        Response::send(200, array('error' => true, 'msg' => 'Este email já encontra-se registado'));
      } else {

        if (empty($photo_body)) {
          $photo_body = $row['photo'];
        }
        if (!empty($data['password'])) {
          $new_password = md5($data['password']);
        } else {
          $new_password = $row['password'];
        }

        if (empty($name)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo nome está vazio'));
        } elseif (empty($email)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo email está vazio'));
        } elseif (empty($number)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo número de telefone está vazio'));
        } elseif (empty($province)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo provincia está vazio'));
        } elseif (empty($gender)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo genero está vazio'));
        } else {
          if ($this->userModel->update(
            $id_doc,
            $photo_body,
            $name,
            $email,
            $number,
            $province,
            $gender,
            $new_password
          )) {
            Response::send(200, array('error' => false, 'msg' => 'Usuário atualizado com sucesso.', 'tsx' => $new_password, 'tts' => $photo_body));
          } else {
            Response::send(500, array('error' => true, 'msg' => 'Ocorreu um erro ao atualizar o usúario.'));
          }
        }
      }
    }
  }

  public function delete()
  {
    $id = $this->lastPart;

    $result = $this->userModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Usúario não encontrado'));
    } else {

      if ($this->userModel->deleteById($id)) {
        Response::send(200, array('error' => false, 'msg' => 'Usúario excluído com sucesso.'));
      } else {
        Response::send(500, array('error' => true, 'msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 404
  public function notFound()
  {
    echo "404";
  }
}