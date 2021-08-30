<?php

namespace Infra\Dao;

use \Infra\Connection\Connection;
use \Application\Domain\Entity\ProdutoCategoria;
use \Infra\Dao\Base\BaseDao;

class ProdutoCategoriaDao extends BaseDao {

    protected $_conn;
    protected $_produtoCategoria;

    public function __construct
    (
        Categoria $_produtoCategoria,
        Conexao $_conn
    )
    {
        $this->_conn = $_conn;
        $this->_produtoCategoria = $_produtoCategoria;

        parent::__construct($_produtoCategoria, $_conn);
    }
}
?>
