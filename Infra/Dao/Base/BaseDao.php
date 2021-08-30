<?php

	namespace Infra\Dao\Base;

	use \Infra\ORM\BuildQuery\BuildParams;
	use \Infra\Connection\Connection;

	class BaseDao extends BuildParams {

		protected $_conn;
		protected $_object;

		public function __construct
		(
			Object $object,
			$_conn
		)
		{
			$this->_conn = $_conn;
			$this->_object = $object;

			parent::__construct($object);
		}

		protected function select($parameter = array()) {

			try {
				$select = $this->buildSelect($parameter);

				if($select) {
					return $this->_conn->select(
						$select["string"],
						$select["parameter"]
					);
				}

				return false;

			} catch (Exception $e) {

				return false;
			}
		}

		protected function update(Object $o) {

			try {
				$update = $this->buildUpdate($o);

				if($update) {
					return $this->_conn->query(
						$update["string"],
						$update["parameter"]
					);
				}

				return false;

			} catch (Exception $e) {

				return false;
			}
		}

		protected function insert(Object $o) {

			try {
				$insert = $this->buildInsert($o);

				if($insert) {
					return $this->_conn->query(
						$insert["string"],
						$insert["parameter"]
					);
				}

				return false;

			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
	}
?>
