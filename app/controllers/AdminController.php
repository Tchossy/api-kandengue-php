<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/Admin.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\Admin;
use app\utils\Response;
use Database;
use PDO;

class AdminController
{
  private $db;
  private $adminModel;

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
    $this->adminModel = new Admin($this->db);
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

      $result = $this->adminModel->getByEmailAndPassword($email, $new_password);
      $num = $result->rowCount();

      if ($num > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $admin_item = array(
          'id' => $id,
          'photo' => $photo,
          'first_name' => $first_name,
          'last_name' => $last_name,
          'phone' => $phone,
          'email' => $email,
          'status' => $status,
          'gender' => $gender,
          'password' => $password,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );
        $return = ['adminInfo' => $admin_item, 'msg' => 'Login efetuado com sucesso.'];
      } else {
        $return = ['error' => true, 'msg' => 'Dados de aceeso incorretos, tente novamente.'];
      }
    }

    echo json_encode($return);
  }

  public function getAll()
  {
    $result = $this->adminModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $admins_arr = array();
      $admins_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $admin_item = array(
          'id' => $id,
          'photo' => $photo,
          'first_name' => $first_name,
          'last_name' => $last_name,
          'phone' => $phone,
          'email' => $email,
          'status' => $status,
          'gender' => $gender,
          'password' => $password,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($admins_arr['data'], $admin_item);
      }

      Response::send(200, $admins_arr);
    } else {
      Response::send(404, array('error' => true, 'msg' => 'Nenhum administrador encontrado.', $admins_arr));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->adminModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $admin_item = array(
        'id' => $id,
        'photo' => $photo,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'phone' => $phone,
        'email' => $email,
        'status' => $status,
        'gender' => $gender,
        'password' => $password,
        'date_create' => $date_create,
        'date_update' => $date_update,
      );

      Response::send(200, $admin_item);
    } else {
      Response::send(404, array('error' => true, 'msg' => 'Administrador não encontrado.'));
    }
  }
  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->adminModel->getByTerm($term);
    $num = $result->rowCount();

    $admins_arr = array();

    if ($num > 0) {
      $admins_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $admin_item = array(
          'id' => $id,
          'photo' => $photo,
          'first_name' => $first_name,
          'last_name' => $last_name,
          'phone' => $phone,
          'email' => $email,
          'status' => $status,
          'gender' => $gender,
          'password' => $password,
          'date_create' => $date_create,
          'date_update' => $date_update,
        );

        array_push($admins_arr['data'], $admin_item);
      }

      Response::send(200, $admins_arr);
    } else {
      Response::send(404, array('error' => true, 'msg' => 'Nenhum administrador encontrado.', $admins_arr));
    }
  }

  public function create()
  {
    // Obtém o conteúdo do corpo da requisição
    $requestBody = file_get_contents('php://input');

    // Decodifica o JSON em um array associativo
    $data = json_decode($requestBody, true);

    $photo = $data['photo'] ?? '';
    $first_name = $data['first_name'] ?? '';
    $last_name = $data['last_name'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? '';
    $status = $data['status'] ?? '';
    $gender = $data['gender'] ?? '';
    $password = $data['password'] ?? '';
    $new_password = md5($password);

    $result = $this->adminModel->getByEmail($email);
    $num_row = $result->rowCount();

    $row_email = $data['email'];

    if ($row_email !== $email) {
    }

    if ($num_row > 0) {
      Response::send(200, array('error' => true, 'msg' => 'Este email já encontra-se registado'));
    } else {
      if (empty($first_name)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo primeiro nome está vazio'));
      } elseif (empty($last_name)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo ultimo número de telefone está vazio'));
      } elseif (empty($phone)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo número de telefone está vazio'));
      } elseif (empty($email)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo email está vazio'));
      } elseif (empty($status)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
      } elseif (empty($gender)) {
        Response::send(200, array('error' => true, 'msg' => 'O campo genero está vazio'));
      } else {

        if ($this->adminModel->createNew(
          $photo,
          $first_name,
          $last_name,
          $phone,
          $email,
          $status,
          $gender,
          $new_password
        )) {
          Response::send(200, array('error' => false, 'msg' => 'O seu cadastro foi um com sucesso.'));
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
    $first_name = $data['first_name'] ?? '';
    $last_name = $data['last_name'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? '';
    $status = $data['status'] ?? '';
    $gender = $data['gender'] ?? '';
    $new_password = '';

    $result_data = $this->adminModel->getById($id_doc);
    $num_row_data = $result_data->rowCount();
    $row = $result_data->fetch(PDO::FETCH_ASSOC);
    $row_email = $row['email'];

    $result_count = $this->adminModel->getByEmail($email);
    $num_row = $result_count->rowCount();

    if ($num_row_data <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Admin não encontrado'));
    } else {
      if ($num_row > 0 && $email !== $row_email) {
        Response::send(200, array('error' => true, 'msg' => 'Este email já encontra-se registado'));
      } else {
        $result = $this->adminModel->getById($id_doc);

        if (empty($photo_body)) {
          $photo_body = $row['photo'];
        }
        if (!empty($data['password'])) {
          $new_password = md5($data['password']);
        } else {
          $new_password = $row['password'];
        }

        if (empty($first_name)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo primeiro nome está vazio'));
        } elseif (empty($last_name)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo ultimo número de telefone está vazio'));
        } elseif (empty($phone)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo número de telefone está vazio'));
        } elseif (empty($email)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo email está vazio'));
        } elseif (empty($status)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo status está vazio'));
        } elseif (empty($gender)) {
          Response::send(200, array('error' => true, 'msg' => 'O campo genero está vazio'));
        } else {
          if ($this->adminModel->update(
            $id_doc,
            $photo_body,
            $first_name,
            $last_name,
            $phone,
            $email,
            $status,
            $gender,
            $new_password
          )) {
            Response::send(200, array('error' => false, 'msg' => 'Administrador atualizado com sucesso.', 'tsx' => $new_password, 'tts' => $photo_body));
          } else {
            Response::send(500, array('error' => true, 'msg' => 'Ocorreu um erro ao atualizar o administrador.'));
          }
        }
      }
    }
  }

  public function delete()
  {
    $id = $this->lastPart;

    $result = $this->adminModel->getById($id);
    $num_row = $result->rowCount();

    if ($num_row <= 0) {
      Response::send(200, array('error' => true, 'msg' => 'Admin não encontrado'));
    } else {

      if ($this->adminModel->deleteById($id)) {
        Response::send(200, array('msg' => 'Administrador excluído com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o usúario.'));
      }
    }
  }

  // 404
  public function notFound()
  {
    echo "404";
  }
}
