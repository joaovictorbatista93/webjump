<?php

namespace Application\Controller;

use \Application\Proccess\Base\BaseProccess;
use \Infra\Repository\ProdutoCategoriaRepository;
use \Domain\Entity\ProdutoCategoria;
use \Domain\Helper\Util;
use \Infra\Connection\Connection;
use \Application\Services\API;
use Exception;

class ProdutoCategoriaController
{

    protected $_baseProcess;
    protected $_produtoCategoriaRepository;
    protected $_produtoCategoria;
    protected $_util;
    protected $_conn;
    protected $_api;

    public function __construct
    (
        $baseProcess,
        $produtoCategoriaRepository,
        $produtoCategoria,
        $connection
    )
    {
        $this->_baseProcess = $baseProcess;
        $this->_produtoCategoriaRepository = $produtoCategoriaRepository;
        $this->_produtoCategoria = $produtoCategoria;
        $this->_conn = $connection;
        $this->_util = new Util();
        $this->_api = new API();
    }

    public function addNew($post = array())
    {
        try {

            $produtoCategoria = $this->_produtoCategoriaRepository->create();

            if (!$post) {

                return false;
            }

            foreach ($post as $key => $value) {
                if ($key != 'input') {
                    $produtoCategoria->$key = $value;
                }
            }

            $this->_produtoCategoriaRepository->save();
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

            if (isset($post[strtolower($this->_produtoCategoria::primary_key)])) {
                $produtoCategoria = $this->_produtoCategoriaRepository->loadById($post[$this->_produtoCategoria::primary_key]);
            } elseif (isset($post[strtoupper($this->_produtoCategoria::primary_key)])) {
                $produtoCategoria = $this->_produtoCategoriaRepository->loadById($post[$this->_produtoCategoria::primary_key]);
            } else {
                return false;
            }

            if (!isset($produtoCategoria->_original_data)) {
                throw new Exception("Error Processing Request");
            }

            foreach ($post as $key => $value) {
                if ($key != 'input') {
                    if (array_key_exists($key, $produtoCategoria->_original_data)) {
                        if ($produtoCategoria->_original_data[$key] != $value) {
                            if (!empty($value) && $value != "") {
                                $produtoCategoria->$key = $value;
                            }
                        }
                    }
                }
            }
            $this->_produtoCategoriaRepository->save();
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($post = array())
    {
        try {
            if (isset($post[strtolower($this->_produtoCategoria::primary_key)])) {
                $produtoCategoria = $this->_produtoCategoriaRepository->loadById($post[$this->_produtoCategoria::primary_key]);
            } elseif (isset($post[strtoupper($this->_produtoCategoria::primary_key)])) {
                $produtoCategoria = $this->_produtoCategoriaRepository->loadById($post[$this->_produtoCategoria::primary_key]);
            } else {
                return false;
            }
            if (!isset($produtoCategoria->_original_data)) {
                throw new Exception("Error Processing Request");
            }

            $produtoCategoria->excluido = 1;

            $this->_produtoCategoriaRepository->save();
        } catch (Exception $e) {
            return false;
        }
    }

    public function get($get = array())
    {
        try {
            $produtoCategoria = $this->_produtoCategoriaRepository->load($get);
            if (!isset($produtoCategoria->_original_data)) {
                return false;
            }

            return $produtoCategoria->_original_data;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getCategoriaByProduct($get = array())
    {
        try {
            $produtoCategoria = $this->_produtoCategoriaRepository->load($get);

            if (!isset($produtoCategoria->_original_data)) {
                return false;
            }

            return $produtoCategoria->_original_data;
        } catch (Exception $e) {
            return false;
        }
    }

}

?>
