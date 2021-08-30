<?php

require_once("/var/www/html/webjump/Config/AutoLoad.php");

use \Application\Process\Base\BaseProcess;
use \Domain\Entity\Produto;
use \Infra\Repository\ProdutoRepository;

$return = array();

if (isset($_GET['action'])) {

    $produto = new produto();
    $connection = new Connection();
    $produtoRepository = new ProdutoRepository($produto, $connection);

    if ($connection->conn->beginTransaction()) {
        try {

            $process = new BaseProcess
            (
                $produtoRepository,
                $produto,
                $connection
            );
            $return["produto"] = $process->execute();
            $connection->conn->commit();

        } catch (Exception $e) {
            $connection->conn->rollBack();
            throw new Exception("Error Processing Request");
        }
    }
}

echo json_encode($return);

?>
