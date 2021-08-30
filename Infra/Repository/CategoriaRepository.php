<?php

namespace Infra\Repository;

use \Domain\Entity\Categoria;
use \Infra\Repository\Base\BaseRepository;
use \Infra\Connection\Connection;

class CategoriaRepository extends BaseRepository {

    protected $_categoria;
    protected $_conn;

    public function __construct
    (
        Categoria $_categoria,
        $_conn
    )
    {
        $this->_categoria = $_categoria;
        $this->_conn = $_conn;
        parent::__construct($_categoria, $_conn);
    }
}
?>
