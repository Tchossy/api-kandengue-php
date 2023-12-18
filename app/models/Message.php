<?php

namespace app\models;

use PDO;

class Message
{
  private $conn;
  private $table = 'messages';

  public $id;
  public $name;
  public $email;
  public $subject;
  public $message;
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
    $this->date_create = $extensiveDate;

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
    $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id ORDER BY id DESC';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt;
  }

  public function getByTerm($term)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE subject LIKE :searchTerm OR message LIKE :searchTerm OR email LIKE :searchTerm ORDER BY id DESC';
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':searchTerm', '%' . $term . '%', PDO::PARAM_STR);

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
    $name,
    $email,
    $subject,
    $message
  ) {
    $date_now = $this->date_create;

    $query = 'INSERT INTO ' . $this->table . ' VALUES(null,?,?,?,?,?)';
    $stmt = $this->conn->prepare($query);

    $params = [
      $name,
      $email,
      $subject,
      $message,
      $date_now
    ];

    if ($stmt->execute($params)) {
      return true;
    } else {
      return false;
    }
  }

  public function update($id, $name, $email)
  {
    $query = 'UPDATE ' . $this->table . ' SET name = :name, email = :email WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}
