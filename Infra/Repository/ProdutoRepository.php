<?php

namespace Infra\Repository;

use \Domain\Entity\Produto;
use \Infra\Repository\Base\BaseRepository;
use \Infra\Connection\Connection;

class ProdutoRepository extends BaseRepository {

    protected $_produto;
    protected $_conn;

    public function __construct
    (
        Produto $_produto,
        $_conn
    )
    {
        $this->_produto = $_produto;
        $this->_conn = $_conn;
        parent::__construct($_produto, $_conn);
    }
}
?>
