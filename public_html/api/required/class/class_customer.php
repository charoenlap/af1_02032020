<?php
class customer{
	public $db;
	public function __construct($db){
		$this->db = $db;
	}
	function __destruct() {
		@mysqli_close($this->db);
    }
    public function listCustomers(){
    	$query = $this->db->getdata("customer");
    	$found = $query->num_rows;
    	$result = array();
    	if($found>0){
    		$result = $query->rows;
    	}
    	return $result;
    }
}