<?php
class content extends product {
	public $page = "cp-content-cat.php";
	public $page_edit = "cp-content-cat-edit.php";
	public $table = "";
	public $table_sub = "";
	public function content(){
		$this->table = "content_cat";
		$this->table_sub = "content";
	}
	public function getLanguage(){ 
		global $obj_db;
		$sql_t = "language order by sort asc";
		$sql = $obj_db->select($sql_t)or die("error $sql_t");
		return $sql;
	}
	public function getLanguageDetail($where){
		global $obj_db;
		if(!empty($where)){
			$where = ' and '.$where;
		}
		$sql_t = "language_detail where id<>'' $where";
		$sql = $obj_db->select($sql_t)or die("error $sql_t");
		return $sql;
	}
}

?>