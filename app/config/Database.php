<?php

class Database
{
  private $host = 'localhost';
  private $dbname = 'kandengue';
  private $username = 'root';
  private $password = '';
  // private $host = 'localhost';
  // private $dbname = 'u405651667_kandengue';
  // private $username = 'u405651667_kandengue';
  // private $password = 'kandengue2023';

  public function getConnection()
  {
    $conn = null;

    try {
      $conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
    }

    return $conn;
  }
}
