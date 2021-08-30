<?php

	namespace Infra\ORM\BuildQuery;

	class BuildParams {

		protected $_object;
		protected $_className;
		protected $_paramsQuery = array();
		protected $_typeQuery = array (
			"select" => "SELECT",
			"update" => "UPDATE",
			"delete" => "DELETE",
			"insert" => "INSERT"
		);

		public function __construct
		(
			Object $object
		)
		{
			$this->_object = $object;
		}

		protected function buildInsert(Object $object)
		{

			try {

				if ($object->_data) {

					$parameter = array();

					$query["string"] = $this->_typeQuery["insert"] .
					" " .
					"INTO " .
					$this->_object::table .
					" ( ";

					$count = count($object->_data);

					$i = 1;
					foreach ($object->_data as $key => $value) {

						if($i < $count) {
							$query["string"] .= $key . ", ";
							$parameter[":" . $key] = $value;
						}else {
							$query["string"] .= $key . " )";
							$parameter[":" . $key] = $value;
						}

						$i++;
					}

					$query["string"] .= " VALUES ( ";

					$i = 1;
					foreach ($parameter as $key => $value) {

						if($i < $count) {

							$query["string"] .= $key . ", ";

						}else {

							$query["string"] .= $key . " )";
						}

						$i++;
					}

					$query["parameter"] = $parameter;

					return $query;

				}

			} catch (Exception $e) {
				return false;
			}
		}

		protected function buildUpdate(Object $object)
		{
			try {

				if ($object->_data) {

					$parameter = array();

					$query["string"] = $this->_typeQuery["update"] .
					" " .
					$this->_object::table .
					" SET ";

					$count = count($object->_data);

					$i = 1;
					foreach ($object->_data as $key => $value) {

						if($i < $count) {
							$query["string"] .= $key . " = " . "'" . $value . "'" . ", ";
							$parameter[":" . $key] = $value;
						}else {
							$query["string"] .= $key . " = " . "'" . $value . "'";
							$parameter[":" . $key] = $value;
						}

						$i++;
					}

					$query["string"] .= " WHERE ";

					foreach ($object->_original_data as $key => $value) {
						if ($value !== null) {
							$query["string"] .= $key . " = " . ":" . $key . " AND ";
							$parameter[":" . $key] = $value;
						}
					}

					$query["string"] .= " 1 = 1";
					$query["parameter"] = $parameter;

                    $query["string"] = strtolower($query["string"]);
                    $query["parameter"] = $query["parameter"] ?? strtolower($query["parameter"]);

					return $query;
				}

				return false;

			} catch (Exception $e) {

				throw new Exception($e->getMessage(), 1);
			}
		}

		public function buildSelect($params = array())
		{
			try {

				$parameter = !$params ? []: $params;
				if (!empty($this->_object)) {

					if(!$this->_object->_original_data) {
						$this->_paramsQuery = $params;
					}
					else {

						foreach ($this->_object->_original_data as $key => $value) {
							$this->_paramsQuery[$key] = $value;
						}
					}

					$query["string"] = $this->_typeQuery["select"] .
						" " .
						"*" .
						" FROM " . $this->_object::table .
						" WHERE ";

					if(!empty($this->_paramsQuery)) {

						foreach ($parameter as $key => $value) {
							$parameter[":".$key] = $value;
							$this->_params[$key] = $value;
							unset($parameter[$key]);
						}

						$query["parameter"] = $parameter;

						$paramsQuery = implode(",", array_keys($query["parameter"]));

						if(empty($params)) {
							$params = implode(",", array_keys($this->_params));
						}
						else {
							$params = implode(",", array_keys($params));
						}

						foreach ($parameter as $key => $value) {

							if(substr($key, 0,1) != ":") {
								$query["string"] .= $key . " = " . " :" . $key . " AND ";
							}
							else {
								$query["string"] .= str_replace(":", "", $key) . " = " . $key . " AND ";
							}
						}

						$query["string"] .= "1 = 1 AND excluido = 0";
					}
					else {
						$query["string"] .= "1 = 1 AND excluido = 0";
						$query["parameter"] = array();
					}

					$query["string"] = $query["string"];

					return $query;
				}

				return false;

			} catch (Exception $e) {
				return false;
			}
		}
	}
?>
