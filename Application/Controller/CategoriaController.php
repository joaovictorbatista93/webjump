<?php

namespace Application\Controller;

use \Application\Proccess\Base\BaseProccess;
use \Infra\Repository\CategoriaRepository;
use \Domain\Entity\Categoria;
use \Domain\Helper\Util;
use \Infra\Connection\Connection;
use \Application\Services\API;
use Exception;

class CategoriaController
{

    protected $_baseProcess;
    protected $_categoriaRepository;
    protected $_categoria;
    protected $_util;
    protected $_conn;
    protected $_api;

    public function __construct
    (
        $baseProcess,
        $categoriaRepository,
        $categoria,
        $connection
    )
    {
        $this->_baseProcess = $baseProcess;
        $this->_categoriaRepository = $categoriaRepository;
        $this->_categoria = $categoria;
        $this->_conn = $connection;
        $this->_util = new Util();
        $this->_api = new API();
    }

    public function addNew($post = array())
    {
        try {

            $categoria = $this->_categoriaRepository->create();

            if (!$post) {

                return false;
            }

            foreach ($post as $key => $value) {
                if ($key != 'input') {
                    $categoria->$key = $value;
                }
            }

            $this->_categoriaRepository->save();
            return true;

        } catch (Exception $e) {
            return false;
        }
    }

    public function update($post = array())
    {
        try {
            if (!$post) {
                return false;
            }

            if (isset($post[strtolower($this->_categoria::primary_key)])) {
                $categoria = $this->_categoriaRepository->loadById($post[$this->_categoria::primary_key]);
            } elseif (isset($post[strtoupper($this->_categoria::primary_key)])) {
                $categoria = $this->_categoriaRepository->loadById($post[$this->_categoria::primary_key]);
            } else {
                return false;
            }

            if (!isset($categoria->_original_data)) {
                throw new Exception("Error Processing Request");
            }

            foreach ($post as $key => $value) {
                if ($key != 'input') {
                    if (array_key_exists($key, $categoria->_original_data)) {
                        if ($categoria->_original_data[$key] != $value) {
                            if (!empty($value) && $value != "") {
                                $categoria->$key = $value;
                            }
                        }
                    }
                }
            }
            $this->_categoriaRepository->save();
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($post = array())
    {
        try {
            if (isset($post[strtolower($this->_categoria::primary_key)])) {
                $categoria = $this->_categoriaRepository->loadById($post[$this->_categoria::primary_key]);
            } elseif (isset($post[strtoupper($this->_categoria::primary_key)])) {
                $categoria = $this->_categoriaRepository->loadById($post[$this->_categoria::primary_key]);
            } else {
                return false;
            }
            if (!isset($categoria->_original_data)) {
                throw new Exception("Error Processing Request");
            }

            $categoria->excluido = 1;

            $this->_categoriaRepository->save();
        } catch (Exception $e) {
            return false;
        }
    }

    public function get($get = array())
    {
        try {
            $categoria = $this->_categoriaRepository->load($get);
            if (!isset($categoria->_original_data)) {
                return false;
            }

            return $categoria->_original_data;
        } catch (Exception $e) {
            return false;
        }
    }
}

?>
