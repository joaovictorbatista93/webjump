<?php 

	namespace Domain\Entity\Base;
	
	class BaseEntity {

		public $_data;
		public $_original_data;

	    public function __get($name) 
	    {

	        if(isset($this->_original_data["$name"])){
				return $this->_original_data["$name"];				
			}

			return $this->_data[$name];
	    }

	    public function __set($name, $value) 
	    {

	    	if(!isset($this->_data[$name])) {

	    		$this->_data[$name] = $value;
	    	}
	    	else {
	    		$this->_original_data[$name] = $value;
	    	}
	    }													
	}

?>