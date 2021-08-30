<?php

require_once("/var/www/html/webjump/Config/AutoLoad.php");

use \Application\Process\Base\BaseProcess;
use \Domain\Entity\Categoria;
use \Infra\Repository\CategoriaRepository;

$return = array();

if (isset($_GET['action'])) {

    $categoria = new Categoria();
    $connection = new Connection();
    $categoriaRepository = new CategoriaRepository($categoria, $connection);

    if ($connection->conn->beginTransaction()) {
        try {

            $process = new BaseProcess
            (
                $categoriaRepository,
                $categoria,
                $connection
            );
            $return["categoria"] = $process->execute();
            $connection->conn->commit();

        } catch (Exception $e) {

            $connection->conn->rollBack();
            throw new Exception("Error Processing Request");
        }
    }
}

echo json_encode($return);

?>
