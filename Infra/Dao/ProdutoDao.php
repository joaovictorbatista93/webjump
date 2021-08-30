<?php

namespace Infra\Dao;

use \Infra\Connection\Connection;
use \Application\Domain\Entity\Produto;
use \Infra\Dao\Base\BaseDao;

class ProdutoDao extends BaseDao {

    protected $_conn;
    protected $_produto;

    public function __construct
    (
        Produto $_produto,
        Conexao $_conn
    )
    {
        $this->_conn = $_conn;
        $this->_produto = $_produto;

        parent::__construct($_produto, $_conn);
    }
}
?>
