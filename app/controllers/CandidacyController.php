<?php

namespace app\controllers;

require_once(__DIR__ . '/../models/Candidacy.php');
require_once(__DIR__ . '/../utils/Response.php');
require_once(__DIR__ . '/../config/Database.php');

use app\models\Candidacy;
use app\utils\Response;
use Database;
use PDO;

class CandidacyController
{
  private $db;
  private $candidacyModel;

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
    $this->candidacyModel = new Candidacy($this->db);
  }

  public function getAll()
  {
    $result = $this->candidacyModel->getAll();
    $num = $result->rowCount();

    if ($num > 0) {
      $candidacys_arr = array();
      $candidacys_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $candidacy_item = array(
          'id' => $id,
          'photo' => $photo,
          'first_name' => $first_name,
          'last_name' => $last_name,
          'email' => $email,
          'phone' => $phone,
          'province' => $province,
          'gender' => $gender,
          'identity_card' => $identity_card,
          'curriculum' => $curriculum,
          'vehicle_Doc' => $vehicle_Doc,
          'vehicle_photo' => $vehicle_photo,
          'application_areas' => $application_areas,
          'type_candidacy' => $type_candidacy,
          'date_create' => $date_create,
        );

        array_push($candidacys_arr['data'], $candidacy_item);
      }

      Response::send(200, $candidacys_arr);
    } else {
      Response::send(404, array('msg' => 'Nenhum candidato encontrado.'));
    }
  }

  public function getById()
  {
    $id = $this->lastPart;

    $result = $this->candidacyModel->getById($id);
    $num = $result->rowCount();

    if ($num > 0) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $candidacy_item = array(
        'id' => $id,
        'photo' => $photo,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'province' => $province,
        'gender' => $gender,
        'identity_card' => $identity_card,
        'curriculum' => $curriculum,
        'vehicle_Doc' => $vehicle_Doc,
        'vehicle_photo' => $vehicle_photo,
        'application_areas' => $application_areas,
        'type_candidacy' => $type_candidacy,
        'date_create' => $date_create,
      );

      Response::send(200, $candidacy_item);
    } else {
      Response::send(404, array('msg' => 'Usuário não encontrado.'));
    }
  }

  public function searchByTerm()
  {
    // Obtém o conteúdo do corpo da requisição
    $term = $data['term'] ?? $this->lastPart;

    $result = $this->candidacyModel->getByTerm($term);
    $num = $result->rowCount();

    $candidacys_arr = array();

    if ($num > 0) {
      $candidacys_arr['data'] = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $candidacy_item = array(
          'id' => $id,
          'photo' => $photo,
          'first_name' => $first_name,
          'last_name' => $last_name,
          'email' => $email,
          'phone' => $phone,
          'province' => $province,
          'gender' => $gender,
          'identity_card' => $identity_card,
          'curriculum' => $curriculum,
          'vehicle_Doc' => $vehicle_Doc,
          'vehicle_photo' => $vehicle_photo,
          'application_areas' => $application_areas,
          'type_candidacy' => $type_candidacy,
          'date_create' => $date_create,
        );

        array_push($candidacys_arr['data'], $candidacy_item);
      }

      Response::send(200, $candidacys_arr);
    } else {
      Response::send(404, array('error' => true, 'msg' => 'Nenhum candidato encontrado.', $candidacys_arr));
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
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $province = $data['province'] ?? '';
    $gender = $data['gender'] ?? '';
    $identity_card = $data['identity_card'] ?? '';
    $curriculum = $data['curriculum'] ?? '';
    $vehicle_Doc = $data['vehicle_Doc'] ?? '';
    $vehicle_photo = $data['vehicle_photo'] ?? '';
    $application_areas = $data['application_areas'] ?? '';
    $type_candidacy = $data['type_candidacy'] ?? '';

    if (empty($first_name)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo primeiro nome está vazio'));
    } elseif (empty($last_name)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo ultimo nome está vazio'));
    } elseif (empty($email)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo email está vazio'));
    } elseif (empty($phone)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo número de telefone está vazio'));
    } elseif (empty($province)) {
      Response::send(200, array('error' => true, 'msg' => 'O campo privincia está vazio'));
    } elseif (empty($gender)) {
      Response::send(200, array('error' => true, 'msg' => 'Não selecionou o seu genero'));
    } elseif (empty($photo)) {
      Response::send(200, array('error' => true, 'msg' => 'Não enviou uma fotografia sua'));
    } elseif (empty($identity_card)) {
      Response::send(200, array('error' => true, 'msg' => 'Não enviou o bilhete de identidade'));
    } else {

      if ($this->candidacyModel->createNew(
        $photo,
        $first_name,
        $last_name,
        $email,
        $phone,
        $province,
        $gender,
        $identity_card,
        $curriculum,
        $vehicle_Doc,
        $vehicle_photo,
        $application_areas,
        $type_candidacy
      )) {
        Response::send(200, array('error' => false, 'msg' => 'Candidatura foi enviada com sucesso'));
      } else {
        Response::send(200, array('error' => true, 'msg' => 'Ocorreu um erro ao enviar a candidatura'));
      }
    }
  }

  public function delete()
  {
    $id = $this->lastPart;

    if ($this->candidacyModel->deleteById($id)) {
      Response::send(200, array('msg' => 'Candidato excluído com sucesso.'));
    } else {
      Response::send(500, array('msg' => 'Ocorreu um erro ao excluir o candidato.'));
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
      if ($this->candidacyModel->update($id, $name, $email)) {
        Response::send(200, array('msg' => 'Usuário atualizado com sucesso.'));
      } else {
        Response::send(500, array('msg' => 'Ocorreu um erro ao atualizar o candidato.'));
      }
    } else {
      Response::send(400, array('msg' => 'Dados insuficientes para atualizar o candidato.'));
    }
  }

  // 404
  public function notFound()
  {
    echo "404";
  }
}
