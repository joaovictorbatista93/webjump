<?php

namespace Infra\Dao;

use \Infra\Connection\Connection;
use \Application\Domain\Entity\Categoria;
use \Infra\Dao\Base\BaseDao;

class CategoriaDao extends BaseDao {

    protected $_conn;
    protected $_categoria;

    public function __construct
    (
        Categoria $_categoria,
        Conexao $_conn
    )
    {
        $this->_conn = $_conn;
        $this->_categoria = $_categoria;

        parent::__construct($_categoria, $_conn);
    }
}
?>
