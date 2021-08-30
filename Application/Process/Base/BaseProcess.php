<?php

namespace Application\Process\Base;

use \Domain\Helper\Util;

class BaseProcess
{

    protected $_repository;
    protected $_factory;
    protected $_domain;
    protected $_conn;
    protected $_application;
    protected $_get;
    protected $_post;
    protected $_session;
    protected $_request;
    protected $_util;

    public function getGet()
    {
        return $this->_get;
    }

    public function getPost()
    {
        return $this->_post;
    }

    public function getSession()
    {
        return $this->_session;
    }

    public function getRequest()
    {
        return $this->_request;
    }

    public function setGet($get = array())
    {
        $this->_get = $this->handleDataEntry($get);
    }

    public function setPost($post = array())
    {
        $this->_post = $this->handleDataEntry($post);
    }

    public function setSession($session = array())
    {
        $this->_session = $this->handleDataEntry($session);
    }

    public function setRequest($request = array())
    {
        $this->_request = $this->handleDataEntry($request);
    }

    public function __construct
    (
        $repository,
        $domain,
        $conn
    )
    {
        $this->_util = new Util();
        $this->_repository = $repository;
        $this->_domain = $domain;
        $this->_conn = $conn;
        $this->setGet(isset($_GET) ? $_GET : null);
        $this->setPost(isset($_POST) ? $_POST : null);
        $this->setSession(isset($_SESSION) ? $_SESSION : null);
        $this->setRequest(isset($_REQUEST) ? $_REQUEST : null);
    }

    public function execute()
    {
        if (!empty($this->_get)) {

            if (!$this->_domain) {
                return false;
            }

            $app = $this->_util->convertClassName($this->_domain::table);

            $new_class = "\\Application\\Controller\\" . $app . "Controller";

            $this->_application = new $new_class
            (
                $this,
                $this->_repository,
                $this->_domain,
                $this->_conn
            );

            $function = $this->getGet()['action'];

            return $this->_application->$function($this->getPost());
        }
    }

    private function handleDataEntry($data = array())
    {
        $newData = array();

        if ($data) {

            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $newData[strtolower($key)] = htmlspecialchars(addslashes(trim($this->_util->validateDate($value))));
                }
            }

            return $newData;
        } else {

            return null;
        }
    }
}

?>
