<?php

namespace app\controllers;

require_once(__DIR__ . '/../utils/Response.php');

use app\utils\Response;

class UploadController
{
  public function imageAdmin()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['photoAdmin']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['photoAdmin'];
      $size_max = 4916838; //4MB
      $accept  = array("jpg", "png", "jpeg");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      if ($file['size'] >= $size_max) {
        $return = ['error' => true, 'msg' => "Erro: A imagem excedeu o tamanho máximo de 4MB!"];
        echo json_encode($return);
      } else {
        if (in_array($extension, $accept)) {
          // echo "Permitido";
          $folder = '_imagesDb/admin/';

          if (!is_dir($folder)) {
            mkdir($folder, 755, true);
          }

          // Nome temporário do arquivo
          $tmp = $file['tmp_name'];
          // Novo nome do arquivo
          $newName = "img_admin-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";

          if (move_uploaded_file($tmp, $folder . $newName)) {
            $image_admin = 'https://api.kandengueatrevido.com/_imagesDb/admin/' . $newName;

            Response::send(200, ['error' => false, 'msg' => "Upload da imagem realizado com sucesso", 'imageUrl' => $image_admin]);
            // echo "Upload realizado com sucesso!";
          } else {
            $return = ['error' => true, 'msg' => "Erro: ao realizar Upload..."];
            echo json_encode($return);
          }
        } else {
          $return = ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitido!"];
          echo json_encode($return);
        }
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      $return = ['error' => true, 'msg' => 'Não selecionou a sua fotografia.'];
      echo json_encode($return);
    }
  }
  public function imageUser()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['photoUser']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['photoUser'];
      $size_max = 4916838; //4MB
      $accept  = array("jpg", "png", "jpeg");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      if ($file['size'] >= $size_max) {
        $return = ['error' => true, 'msg' => "Erro: A imagem excedeu o tamanho máximo de 4MB!"];
        echo json_encode($return);
      } else {
        if (in_array($extension, $accept)) {
          // echo "Permitido";
          $folder = '_imagesDb/user/';

          if (!is_dir($folder)) {
            mkdir($folder, 755, true);
          }

          // Nome temporário do arquivo
          $tmp = $file['tmp_name'];
          // Novo nome do arquivo
          $newName = "img_user-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";

          if (move_uploaded_file($tmp, $folder . $newName)) {
            $image_user = 'https://api.kandengueatrevido.com/_imagesDb/user/' . $newName;

            Response::send(200, ['error' => false, 'msg' => "Upload da imagem realizado com sucesso", 'imageUrl' => $image_user]);
            // echo "Upload realizado com sucesso!";
          } else {
            $return = ['error' => true, 'msg' => "Erro: ao realizar Upload..."];
            echo json_encode($return);
          }
        } else {
          $return = ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitido!"];
          echo json_encode($return);
        }
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      $return = ['error' => true, 'msg' => 'Não selecionou a sua fotografia.'];
      echo json_encode($return);
    }
  }

  public function imageCandidacy()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['photoUser']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['photoUser'];
      $size_max = 4916838; //4MB
      $accept  = array("jpg", "png", "jpeg");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      if ($file['size'] >= $size_max) {
        $return = ['error' => true, 'msg' => "Erro: A imagem excedeu o tamanho máximo de 4MB!"];
        echo json_encode($return);
      } else {
        if (in_array($extension, $accept)) {
          // echo "Permitido";
          $folder = '_imagesDb/candidacy/';

          if (!is_dir($folder)) {
            mkdir($folder, 755, true);
          }

          // Nome temporário do arquivo
          $tmp = $file['tmp_name'];
          // Novo nome do arquivo
          $newName = "img_candidacy-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";

          if (move_uploaded_file($tmp, $folder . $newName)) {
            $image_candidacy = 'https://api.kandengueatrevido.com/_imagesDb/candidacy/' . $newName;

            Response::send(200, ['error' => false, 'msg' => "Upload da imagem realizado com sucesso", 'imageUrl' => $image_candidacy]);
            // echo "Upload realizado com sucesso!";
          } else {
            $return = ['error' => true, 'msg' => "Erro: ao realizar Upload..."];
            echo json_encode($return);
          }
        } else {
          $return = ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitido!"];
          echo json_encode($return);
        }
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      $return = ['error' => true, 'msg' => 'Não selecionou a sua fotografia.'];
      echo json_encode($return);
    }
  }

  public function imagesVehicle()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['photosVehicle'])) {
      // Obtém as informações dos arquivos
      $files = $_FILES['photosVehicle'];

      // Array para armazenar os caminhos das imagens
      $imagePaths = [];

      // Percorre cada arquivo
      foreach ($files['name'] as $key => $name) {
        $size_max = 4916838; // 4MB
        $accept = array("jpg", "png", "jpeg");
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        // Verifica o tamanho do arquivo
        if ($files['size'][$key] >= $size_max) {
          $return = ['error' => true, 'msg' => "Erro: A imagem excedeu o tamanho máximo de 4MB!"];
          echo json_encode($return);
          return; // Retorna imediatamente se o tamanho exceder o limite
        }

        // Verifica a extensão do arquivo
        if (!in_array($extension, $accept)) {
          $return = ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"];
          echo json_encode($return);
          return; // Retorna imediatamente se a extensão não for permitida
        }

        // Diretório para armazenar os arquivos
        $folder = '_imagesDb/vehicle/';

        if (!is_dir($folder)) {
          mkdir($folder, 0755, true);
        }

        // Nome temporário do arquivo
        $tmp = $files['tmp_name'][$key];
        // Novo nome do arquivo
        $newName = "img_vehicle-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
        // Caminho completo para o novo arquivo
        $newPath = $folder . $newName;

        // Move o arquivo para o diretório de destino
        if (move_uploaded_file($tmp, $newPath)) {
          $image_vehicle = 'https://api.kandengueatrevido.com/' . $newPath;
          $imagePaths[] = $image_vehicle;
        } else {
          $return = ['error' => true, 'msg' => "Erro: falha ao realizar o upload do arquivo."];
          echo json_encode($return);
          return; // Retorna imediatamente se ocorrer um erro ao mover o arquivo
        }
      }

      // Retorna a resposta com sucesso
      $return = ['error' => false, 'msg' => "Upload da imagem realizado com sucesso", 'imagesUrl' => $imagePaths];
      echo json_encode($return);
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      $return = ['error' => true, 'msg' => 'Não selecionou a(s) fotografia(s) do(s) veiculo.'];
      echo json_encode($return);
    }
  }

  public function docIdentityCard()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['identityCardData'])) {
      // Obtém as informações dos arquivos
      $files = $_FILES['identityCardData'];

      // Array para armazenar os caminhos das imagens
      $imagePaths = [];

      // Percorre cada arquivo
      foreach ($files['name'] as $key => $name) {
        $size_max = 4916838; // 4MB
        $accept = array("pdf", "jpg", "png", "jpeg");
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        // Verifica o tamanho do arquivo
        if ($files['size'][$key] >= $size_max) {
          $return = ['error' => true, 'msg' => "Erro: O BI excedeu o tamanho máximo de 4MB!"];
          echo json_encode($return);
          return; // Retorna imediatamente se o tamanho exceder o limite
        }

        // Verifica a extensão do arquivo
        if (!in_array($extension, $accept)) {
          $return = ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"];
          echo json_encode($return);
          return; // Retorna imediatamente se a extensão não for permitida
        }

        // Diretório para armazenar os arquivos
        $folder = '_docDb/identityCard/';

        if (!is_dir($folder)) {
          mkdir($folder, 755, true);
        }

        // Nome temporário do arquivo
        $tmp = $files['tmp_name'][$key];
        // Novo nome do arquivo
        $newName = "doc_identityCard-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
        // Caminho completo para o novo arquivo
        $newPath = $folder . $newName;

        // Move o arquivo para o diretório de destino
        if (move_uploaded_file($tmp, $newPath)) {
          $doc_identityCard = 'https://api.kandengueatrevido.com/' . $newPath;
          $imagePaths[] = $doc_identityCard;
        } else {
          $return = ['error' => true, 'msg' => "Erro: falha ao realizar Upload..."];
          echo json_encode($return);
        }
      }

      // Retorna a resposta com sucesso
      $return = ['error' => false, 'msg' => "Upload do B.I. realizado com sucesso", 'pdfUrl' => $imagePaths];
      echo json_encode($return);
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      $return = ['error' => true, 'msg' => 'Não selecionou o seu BI.'];
      echo json_encode($return);
    }
  }
  public function docCurriculum()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['curriculumData']['tmp_name'])) {
      // Obtém as informações do arquivo
      $file = $_FILES['curriculumData'];
      $size_max = 3897152; //3MB
      $accept  = array("pdf", "jpg", "png", "jpeg");
      $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

      if ($file['size'] >= $size_max) {
        $return = ['error' => true, 'msg' => "Erro: O curriculo excedeu o tamanho máximo de 3MB!"];
        echo json_encode($return);
      } else {
        if (in_array($extension, $accept)) {
          // echo "Permitido";
          $folder = '_docDb/curriculum/';

          if (!is_dir($folder)) {
            mkdir($folder, 755, true);
          }

          // Nome temporário do arquivo
          $tmp = $file['tmp_name'];
          // Novo nome do arquivo
          $newName = "doc_curriculum-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";

          if (move_uploaded_file($tmp, $folder . $newName)) {
            $doc_curriculum = 'https://api.kandengueatrevido.com/_docDb/curriculum/' . $newName;

            $return = ['error' => false, 'msg' => "Upload do curriculo em pdf realizado com sucesso", 'pdfUrl' => $doc_curriculum];
            echo json_encode($return);
          } else {
            $return = ['error' => true, 'msg' => "Erro: ao realizar Upload..."];
            echo json_encode($return);
          }
        } else {
          $return = ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitido!"];
          echo json_encode($return);
        }
      }
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      $return = ['error' => true, 'msg' => 'Não selecionou o seu curriculo.'];
      echo json_encode($return);
    }
  }
  public function docVehicle()
  {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['docVehicle'])) {
      // Obtém as informações dos arquivos
      $files = $_FILES['docVehicle'];

      // Array para armazenar os caminhos das imagens
      $imagePaths = [];

      // Percorre cada arquivo
      foreach ($files['name'] as $key => $name) {
        $size_max = 4916838; // 4MB
        $accept  = array("pdf", "jpg", "png", "jpeg");
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        // Verifica o tamanho do arquivo
        if ($files['size'][$key] >= $size_max) {
          $return = ['error' => true, 'msg' => "Erro: O BI excedeu o tamanho máximo de 4MB!"];
          echo json_encode($return);
          return; // Retorna imediatamente se o tamanho exceder o limite
        }

        // Verifica a extensão do arquivo
        if (!in_array($extension, $accept)) {
          $return = ['error' => true, 'msg' => "Erro: Extensão ($extension) não permitida!"];
          echo json_encode($return);
          return; // Retorna imediatamente se a extensão não for permitida
        }

        // Diretório para armazenar os arquivos
        $folder = '_docDb/docVehicle/';

        if (!is_dir($folder)) {
          mkdir($folder, 755, true);
        }

        // Nome temporário do arquivo
        $tmp = $files['tmp_name'][$key];
        // Novo nome do arquivo
        $newName = "doc_docVehicle-" . date('d-m-Y') . '-' . date('H') . 'h-' . uniqid() . ".$extension";
        // Caminho completo para o novo arquivo
        $newPath = $folder . $newName;

        // Move o arquivo para o diretório de destino
        if (move_uploaded_file($tmp, $newPath)) {
          $doc_docVehicle = 'https://api.kandengueatrevido.com/' . $newPath;
          $imagePaths[] = $doc_docVehicle;
        } else {
          $return = ['error' => true, 'msg' => "Erro: falha ao realizar Upload..."];
          echo json_encode($return);
        }
      }

      // Retorna a resposta com sucesso
      $return = ['error' => false, 'msg' => "Upload do documento do veiculo em pdf realizado com sucesso", 'pdfUrl' => $imagePaths];
      echo json_encode($return);
    } else {
      // Caso nenhum arquivo tenha sido enviado, você pode tratar esse caso de acordo com a lógica do seu sistema.
      $return = ['error' => true, 'msg' => 'Não selecionou o documento do veiculo.'];
      echo json_encode($return);
    }
  }
}
