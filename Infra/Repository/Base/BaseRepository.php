<?php

	namespace Infra\Repository\Base;

	use \Infra\Connection\Connection;
	use \Infra\Dao\Base\BaseDao;

	class BaseRepository extends BaseDao{

		protected $_object;
		protected $_conn;

		public function __construct
		(
			Object $object,
			$_conn
		)
		{
			$this->_object = $object;
			$this->_conn = $_conn;

			parent::__construct($object, $_conn);
		}

		public function loadById($id)
		{
			try {
				$parameter = array(
					"id" => $id
				);

				$result = $this->select($parameter);

				if(!$result) {
					return $this->_object->_original_data = null;
				}
				$new_class = "\\" . get_class($this->_object);
				$this->_object = new $new_class();
				if (count($result) > 1) {
					foreach ($result as $key => $value) {
						$this->_object->_original_data[$key] = $value;
					}
				}
				else {
					foreach ($result[0] as $key => $value) {
						$this->_object->_original_data[$key] = $value;
					}
				}

				return $this->_object;
			} catch (Exception $e) {
				return false;
			}
		}

		public function loadByReference($reference)
		{
			try {
				$parameter = array(
					$this->_object::reference_key => $reference
				);
				$result = $this->select($parameter);
				if(!$result) {
					return $this->_object->_original_data = null;
				}
				$new_class = "\\" . get_class($this->_object);
				$this->_object = new $new_class();
				if (count($result) > 1) {
					foreach ($result as $key => $value) {
						$this->_object->_original_data[$key] = $value;
					}
				}
				else {
					foreach ($result[0] as $key => $value) {
						$this->_object->_original_data[$key] = $value;
					}
				}
				return $this->_object;
			} catch (Exception $e) {
				return false;
			}
		}

		public  function load($parameter = array()) {
			try {

				$result = $this->select($parameter);
				if(!$result) {
					return $this->_object->_original_data = null;
				}
				$new_class = "\\" . get_class($this->_object);
				$this->_object = new $new_class();
				if (count($result) > 1) {
					foreach ($result as $key => $value) {
						$this->_object->_original_data[$key] = $value;
					}
				}
				else {
					foreach ($result[0] as $key => $value) {
						$this->_object->_original_data[$key] = $value;
					}
				}
				return $this->_object;
			} catch (Exception $e) {
				return false;
			}
		}

		public function create()
		{
			try {
			    if (isset($this->_object->_data)) {
			        if ($this->_object->_data) {
                        $this->_object->_data = null;
                    }
                    return $this->_object;
                }
                $new_class = "\\" . get_class($this->_object);
                $this->_object = new $new_class();

                return $this->_object;

			} catch (Exception $e) {
				return false;
			}
		}

		public function save()
		{
			try {
				if (!$this->_object->_original_data) {
					return $this->insert($this->_object);
				}

				return $this->update($this->_object);
			} catch (Exception $e) {
				return false;
			}
		}
	}
?>
