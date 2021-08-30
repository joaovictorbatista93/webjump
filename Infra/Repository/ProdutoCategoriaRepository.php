<?php

namespace Infra\Repository;

use \Domain\Entity\ProdutoCategoria;
use \Infra\Repository\Base\BaseRepository;
use \Infra\Connection\Connection;

class ProdutoCategoriaRepository extends BaseRepository
{

    protected $_produtoCategoria;
    protected $_conn;

    public function __construct
    (
        ProdutoCategoria $_produtoCategoria,
        $_conn
    )
    {
        $this->_produtoCategoria = $_produtoCategoria;
        $this->_conn = $_conn;
        parent::__construct($_produtoCategoria, $_conn);
    }
}

?>
