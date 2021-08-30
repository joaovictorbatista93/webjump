<?php

require_once("/var/www/html/webjump/Config/AutoLoad.php");

use \Application\Process\Base\BaseProcess;
use \Domain\Entity\ProdutoCategoria;
use \Infra\Repository\ProdutoCategoriaRepository;

$return = array();

if (isset($_GET['action'])) {

    $produtoCategoria = new ProdutoCategoria();
    $connection = new Connection();
    $produtoCategoriaRepository = new ProdutoCategoriaRepository($produtoCategoria, $connection);

    if ($connection->conn->beginTransaction()) {
        try {

            $process = new BaseProcess
            (
                $produtoCategoriaRepository,
                $produtoCategoria,
                $connection
            );
            $return["produto_categoria"] = $process->execute();
            $connection->conn->commit();

        } catch (Exception $e) {

            $connection->conn->rollBack();
            throw new Exception("Error Processing Request");
        }
    }
}

echo json_encode($return);

?>
