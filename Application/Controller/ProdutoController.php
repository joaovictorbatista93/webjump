<?php

namespace Application\Controller;

use \Application\Proccess\Base\BaseProccess;
use Domain\Entity\ProdutoCategoria;
use \Infra\Repository\ProdutoRepository;
use \Domain\Entity\Produto;
use \Domain\Helper\Util;
use \Infra\Connection\Connection;
use \Application\Services\API;
use Infra\Repository\ProdutoCategoriaRepository;
use Exception;

class ProdutoController
{

    protected $_baseProcess;
    protected $_produtoRepository;
    protected $_produto;
    protected $_util;
    protected $_conn;
    protected $_produtoCategoriaRepository;

    public function __construct
    (
        $baseProcess,
        $produtoRepository,
        $produto,
        $connection
    )
    {
        $this->_produtoCategoriaRepository = new ProdutoCategoriaRepository
        (
            new ProdutoCategoria(),
            $connection
        );

        $this->_baseProcess = $baseProcess;
        $this->_produtoRepository = $produtoRepository;
        $this->_produto = $produto;
        $this->_conn = $connection;
        $this->_util = new Util();
    }

    public function addNew($post = array())
    {

        try {

            $produto = $this->_produtoRepository->create();

            if (!$post) {

                return false;
            }

            foreach ($post as $key => $value) {
                if ($key != 'input' && $key != 'categoria') {
                    if (!empty($value) && $value != "") {
                        if ($key == "preco") {
                            $value = str_replace(",", ".", $value);
                        }
                        $produto->$key = $value;
                    }
                }
            }

            $this->_produtoRepository->save();

            if (!empty($post["categoria"])) {

                $obj = $this->_produtoRepository->loadByReference($post["sku"]);

                $categorias = explode(",", $post["categoria"]);

                foreach ($categorias as $cat) {
                    $objCategoria = $this->_produtoCategoriaRepository->create();
                    $objCategoria->produto = $obj->id;
                    $objCategoria->categoria = $cat;
                    $this->_produtoCategoriaRepository->save();
                }
            }

            if(isset($_FILES['file']['name']))
            {
                $ext = strtolower(substr($_FILES['file']['name'],-4));
                $new_name = date("Y.m.d-H.i.s") . $ext;
                $dir = './imagens/';
                move_uploaded_file($_FILES['file']['tmp_name'], $dir.$new_name);
            }

            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update($post = array())
    {
        try {
            if (!$post) {
                return false;
            }

            if (isset($post[strtolower($this->_produto::primary_key)])) {
                $produto = $this->_produtoRepository->loadById($post[$this->_produto::primary_key]);
            } elseif (isset($post[strtoupper($this->_produto::primary_key)])) {
                $produto = $this->_produtoRepository->loadById($post[$this->_produto::primary_key]);
            } else {
                return false;
            }

            if (!isset($produto->_original_data)) {
                throw new Exception("Error Processing Request");
            }

            foreach ($post as $key => $value) {
                if ($key != 'input') {
                    if (array_key_exists($key, $produto->_original_data)) {
                        if ($produto->_original_data[$key] != $value) {
                            if (!empty($value) && $value != "") {
                                $produto->$key = $value;
                            }
                        }
                    }
                }
            }
            if (isset($post["categoria"])) {

                $obj = $this->_produtoRepository->loadByReference($post["sku"]);

                $categorias = explode(",", $post["categoria"]);
                $novaCategoria = [];

                foreach ($categorias as $value) {
                    $value = intval($value);
                    if (is_numeric($value) && $value > 0) {
                        $novaCategoria[$value] = $value;
                    }
                }

                foreach ($novaCategoria as $key => $cat) {
                    $cat = intval($cat);
                    if ($cat) {

                        $produtoCategoria = $this->_produtoCategoriaRepository->loadByReference($obj->id);

                        if ($produtoCategoria->_original_data) {
                            $newOriginalData = [];
                            foreach ($produtoCategoria->_original_data as $o) {
                                $newOriginalData[$o->categoria] = $o;
                            }
                            if (!array_key_exists($cat, $newOriginalData)) {
                                $prodCatFactory = $this->_produtoCategoriaRepository->create();
                                $prodCatFactory->categoria = $cat;
                                $prodCatFactory->produto = $obj->id;
                                $this->_produtoCategoriaRepository->save();
                            } else {
                                foreach ($newOriginalData as $val) {
                                    if (!array_key_exists($val->categoria, $novaCategoria)) {

                                        $pC = $this->_produtoCategoriaRepository->loadByReference($obj->id);
                                        $catId = null;
                                        if ($pC->_original_data) {
                                            foreach ($pC->_original_data as $pco) {
                                                if ($pco->categoria == $val->categoria) {
                                                    $catId = $pco->id;
                                                }
                                            }
                                        }

                                        if ($catId) {
                                            $pcRepo = $this->_produtoCategoriaRepository->loadById($catId);
                                            $pcRepo->excluido = 1;

                                            $this->_produtoCategoriaRepository->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $this->_produtoRepository->save();
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($post = array())
    {
        try {
            if (isset($post[strtolower($this->_produto::primary_key)])) {
                $produto = $this->_produtoRepository->loadById($post[$this->_produto::primary_key]);
            } elseif (isset($post[strtoupper($this->_produto::primary_key)])) {
                $produto = $this->_produtoRepository->loadById($post[$this->_produto::primary_key]);
            } else {
                return false;
            }
            if (!isset($produto->_original_data)) {
                throw new Exception("Error Processing Request");
            }

            $produto->excluido = 1;

            $this->_produtoRepository->save();
        } catch (Exception $e) {
            return false;
        }
    }

    public function get($get = array())
    {
        try {
            $produto = $this->_produtoRepository->load($get);
            if (!isset($produto->_original_data)) {
                return false;
            }

            return $produto->_original_data;
        } catch (Exception $e) {
            return false;
        }
    }
}

?>
