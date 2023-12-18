<?php

namespace app\models;

use PDO;

class Admin
{
  private $conn;
  private $table = 'admin';

  public $id;
  public $photo;
  public $first_name;
  public $last_name;
  public $phone;
  public $email;
  public $status;
  public $gender;
  public $password;
  public $date_create;

  public function __construct($db)
  {
    $data = date('D');
    $mes = date('M');
    $dia = date('d');
    $ano = date('Y');

    $semana = array(
      'Sun' => 'Domingo',
      'Mon' => 'Segunda-Feira',
      'Tue' => 'Terca-Feira',
      'Wed' => 'Quarta-Feira',
      'Thu' => 'Quinta-Feira',
      'Fri' => 'Sexta-Feira',
      'Sat' => 'Sábado'
    );

    $mes_extenso = array(
      'Jan' => 'Janeiro',
      'Feb' => 'Fevereiro',
      'Mar' => 'Marco',
      'Apr' => 'Abril',
      'May' => 'Maio',
      'Jun' => 'Junho',
      'Jul' => 'Julho',
      'Aug' => 'Agosto',
      'Nov' => 'Novembro',
      'Sep' => 'Setembro',
      'Oct' => 'Outubro',
      'Dec' => 'Dezembro'
    );

    $extensiveDate =  $semana["$data"] . ", {$dia} de " . $mes_extenso["$mes"] . " de {$ano}";
    // $this->date_create = $extensiveDate;
    $this->date_create = date("d/m/Y");

    $this->conn = $db;
  }

  public function getAll()
  {
    $query = 'SELECT * FROM ' . $this->table . ' ORDER BY id DESC';
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  public function getById($id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id ORDER BY id DESC LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt;
  }
  public function getByEmail($email)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email ORDER BY id DESC LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt;
  }
  public function getByTerm($term)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE first_name LIKE :searchTerm OR last_name LIKE :searchTerm OR email LIKE :searchTerm ORDER BY id DESC';
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':searchTerm', '%' . $term . '%', PDO::PARAM_STR);

    $stmt->execute();
    return $stmt;
  }
  public function getByEmailAndPassword($email, $password)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email AND password= :password ORDER BY id DESC LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    return $stmt;
  }

  public function deleteById($id)
  {
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function createNew(
    $photo,
    $first_name,
    $last_name,
    $phone,
    $email,
    $status,
    $gender,
    $password
  ) {
    $date_now = $this->date_create;

    $query = 'INSERT INTO ' . $this->table . ' (photo, first_name, last_name, phone, email, status, gender, password, date_create) VALUES (:photo, :first_name, :last_name, :phone, :email, :status, :gender, :password, :date_create) ';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':photo', $photo);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':date_create', $date_now);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function update(
    $id,
    $photo,
    $first_name,
    $last_name,
    $phone,
    $email,
    $status,
    $gender,
    $password
  ) {
    $query = 'UPDATE ' . $this->table . ' SET photo = :photo, first_name = :first_name, last_name = :last_name, phone = :phone, email = :email, status = :status, gender = :gender, password = :password WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':photo', $photo);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}
