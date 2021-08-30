<?php

class Connection {

	public $conn;

	private $db_host = "mysql";
    private $db_name = "webjump";
    private $db_user = "root";
    private $db_pass = "root";

	public function __construct()
	{
		try {
			$options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
			$this->conn = new PDO("mysql:host={$this->db_host};dbname={$this->db_name}",$this->db_user,$this->db_pass, $options);
	        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    }
	    catch(PDOException $e){
	        echo $e->getMessage();
	    }
	}

	private function setParams($stmt, $parametros = array())
	{
		foreach ($parametros as $key => $value) {
			$this->setParam($stmt, $key, $value);
		}
	}

	private function setParam($stmt, $key, $value)
	{
		$stmt->bindParam($key, $value);
	}

	public function query($query, $parametros = array())
	{
        $query = strtolower($query);
		try {
			$stmt = $this->conn->prepare($query);
			$this->setParams($stmt, $parametros);
			$stmt->execute();
			return $stmt;
		} catch (\Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function select($query, $parametros = array())
	{
	    $query = strtolower($query);

		try {
			$stmt = $this->query($query, $parametros);
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		catch (\Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
