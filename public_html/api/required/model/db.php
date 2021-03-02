<?php
class db{
	public $db;
	function db($host,$db,$user,$pass){
		$this->db = new mysqli($host,$user,$pass,$db);
		$this->db->query('SET NAMES utf8');
		if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}
		$this->db->set_charset("utf8");
		$this->db->query("SET SQL_MODE = ''");
	}
	function __destruct(){
		$this->db->close();
    }
    public function escape($text_escape){
    	return $this->db->real_escape_string($text_escape);
    }
    public function real_escape_string($string){
    	return $this->db->real_escape_string($string);
    }
    
    public function query($sql) {
		$query = $this->db->query($sql);
		
		if (!$this->db->errno) {
			if ($query instanceof \mysqli_result) {
				$data = array();

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}
				$result = new \stdClass();

				$query = $this->db->query("SELECT FOUND_ROWS()");
				$var = $query->fetch_row();
				if($var){
					$result->num_rows = $var[0];
				}else{
					$result->num_rows = 0;
				}

				//$result->num_rows = $this->db->query("SELECT FOUND_ROWS()")->fetch_row()['0'];
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;

				$query->close();

				return $result;
			} else {
				return true;
			}
		} else {
			trigger_error($sql.'Error: ' . $this->db->error  . '<br />Error No: ' . $this->db->errno . '<br />' . $sql);
		}
	}
	public function getdata($table,$where=NULL,$field=NULL,$order=NULL,$limit=NULL){
    	if($where!=NULL){$where="where ".$where;}
    	if($field==NULL){$field="*";}
    	if($order!=NULL){$order="order by $order";}
		if($limit!=NULL){$limit="limit $limit";}
    	$sql_txt = "select SQL_CALC_FOUND_ROWS ".$field." from ".PREFIX.$table." ".$where." ".$order." ".$limit;
    	$query = $this->db->query($sql_txt) or die("ERROR: ".$sql_txt);
    	if (!$this->db->errno) {
			if ($query instanceof \mysqli_result) {
				$data = array();

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}
				
				// echo $sql_txt;

				$result = new \stdClass();
				$query = $this->db->query("SELECT FOUND_ROWS()");
				$var = $query->fetch_row();
				if($var){
					$result->num_rows = $var[0];
				}else{
					$result->num_rows = 0;
				}
				//$result->num_rows = $this->db->query("SELECT FOUND_ROWS()")->fetch_row()['0'];
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;

				$query->close();

				return $result;
			} else {
				return true;
			}
		} else {
			trigger_error('Error: ' . $this->db->error  . '<br />Error No: ' . $this->db->errno . '<br />' . $sql);
		}
    	return $sql;
    }
    public function getdataQuery($table,$where=NULL,$field=NULL,$order=NULL,$limit=NULL){
    	if($where!=NULL){$where="where ".$where;}
    	if($field==NULL){$field="*";}
    	if($order!=NULL){$order="order by $order";}
		if($limit!=NULL){$limit="limit $limit";}
    	$sql_txt = "select SQL_CALC_FOUND_ROWS ".$field." from ".$table." ".$where." ".$order." ".$limit;
    	$query = $this->db->query($sql_txt) or die("ERROR: ".$sql_txt);
    	if (!$this->db->errno) {
			if ($query instanceof \mysqli_result) {
				$data = array();

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$query = $this->db->query("SELECT FOUND_ROWS()");
				$var = $query->fetch_row();
				if($var){
					$result->num_rows = $var[0];
				}else{
					$result->num_rows = 0;
				}
				//$result->num_rows = $this->db->query("SELECT FOUND_ROWS()")->fetch_row()['0'];
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;

				$query->close();

				return $result;
			} else {
				return true;
			}
		} else {
			trigger_error('Error: ' . $this->db->error  . '<br />Error No: ' . $this->db->errno . '<br />' . $sql);
		}
    	return $sql;
    }
    public function select($table,$where=NULL,$field=NULL,$order=NULL,$limit=NULL){
    	if($where!=NULL){$where="where ".$where;}
    	if($field==NULL){$field="*";}
    	if($order!=NULL){$order="order by $order";}
		if($limit!=NULL){$limit="limit $limit";}
    	$sql_txt = "select ".$field." from ".PREFIX.$table." ".$where." ".$order." ".$limit;
    	$sql = $this->db->query($sql_txt) or die("ERROR: ".$sql_txt);
    	return $sql;
    }
    public function update($table,$input,$where){
    	$result = false;
			$update = 'update '.PREFIX.$table.' set';	
			$i=1;
			foreach($input as $key => $value){
				$value = $this->db->real_escape_string($value);
				if($value==""){ $update .= " $key = NULL"; }else{
					$value = iconv(mb_detect_encoding($value, mb_detect_order(), true), "UTF-8", $value);
					$update .= " $key = '".$value."'";
				}
				if($i!=count($input)){ $update .= ","; }
				$i++;
			}
			$update .= " where $where";
			$query = $this->db->query($update);
			
			// echo $update;

			$result = ($query?true:false);
	    return $result;
	}
	public function insert($table,$input){
		$insert = 'insert into '.PREFIX.$table.' set';	
		$i=1;

		foreach($input as $key => $value){
			$value = $this->db->real_escape_string($value);
			$insert .= " `$key` = '".$value."'";
			if($i!=count($input)){ $insert .= ","; }
			$i++;
		}
		$query = $this->db->query($insert) or die(preg_replace('`(.*)`', '<span style="color:red;">`$1`</span>', str_replace("'", '<span style="color:green;font-weight:bold;">\'</span>', str_replace("=", '<span style="color:green;font-weight:bold;">=</span>', $insert)))."<br>".$this->db->errno."<br><span style='color:red;'>".$this->db->error."</span>");
		if (!$this->db->errno) {
			$result = ($query?$this->getLastId():false);
		} else {
			trigger_error('Error: ' . $this->db->error  . '<br />Error No: ' . $this->db->errno . '<br />' . $insert);
		}
	    return $result;
	}
	public function delete($table,$where){
		$result = false;
		$delete = "delete from ".PREFIX."$table where $where";
		$query = $this->db->query($delete) or die("Error: ".$delete);
		if($query){ 	
            $result = true;
        }
        return $result;
	}
	public function getLastId() {
		return $this->db->insert_id;
	}
	public function selectcat($where=NULL,$field=NULL,$order=NULL,$limit=NULL)
	{ 
		
		if($field==NULL){$field="*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content_cat.id as id";}
		if($where!=NULL){$where=" and $where";}
		if($order!=NULL){$order="order by $order";}
		if($limit!=NULL){$limit="limit $limit";}
		
		$sql_t = "select $field from ".PREFIX."content_cat INNER JOIN ".PREFIX."language_detail ON ".PREFIX."content_cat.id = ".PREFIX."language_detail.ref_id where type = 0 $where $order $limit";
		
		$sql = $this->db->query($sql_t)or die("error $sql_t");
		return $sql;
	}
	public function selectcontent($where=NULL,$field=NULL,$order=NULL,$limit=NULL)
	{ 
		if($field==NULL){$field="".PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail";}
		if($where!=NULL){$where=" and $where";}
		if($order!=NULL){$order="order by $order";}
		if($limit!=NULL){$limit="limit $limit";}
		
		$sql_t = "select $field from ".PREFIX."content INNER JOIN ".PREFIX."language_detail ON ".PREFIX."content.id = ".PREFIX."language_detail.ref_id where ".PREFIX."language_detail.type = 1 $where $order $limit";
		//echo $sql_t;
		$sql = $this->db->query($sql_t)or die("error $sql_t");
		return $sql;
	}
	public function content($where=NULL,$field=NULL,$order=NULL,$limit=NULL)
	{ 
		if($field==NULL){$field="".PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail";}
		if($where!=NULL){$where=" and $where";}
		if($order!=NULL){$order="order by $order";}
		if($limit!=NULL){$limit="limit $limit";}
		
		$sql_t = " where ".PREFIX."language_detail.type = 1 $where $order $limit";
		// echo $sql_t;
		$sql = $this->getdata("content INNER JOIN ".PREFIX."language_detail ON ".PREFIX."content.id = ".PREFIX."language_detail.ref_id",PREFIX."language_detail.type = 1 ".$where,$field);
		return $sql;
	}
	public function cat($where=NULL,$field=NULL,$order=NULL,$limit=NULL)
	{ 
		
		if($field==NULL){$field="*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content_cat.id as id";}
		if($where!=NULL){$where=" and $where";}
		if($order!=NULL){$order="order by $order";}
		if($limit!=NULL){$limit="limit $limit";}
		
		//$sql_t = "select $field from ".PREFIX."content_cat INNER JOIN ".PREFIX."language_detail ON ".PREFIX."content_cat.id = ".PREFIX."language_detail.ref_id where type = 0 $where $order $limit";
		
		$sql = $this->getdata("content_cat INNER JOIN ".PREFIX."language_detail ON ".PREFIX."content_cat.id = ".PREFIX."language_detail.ref_id",PREFIX."language_detail.type = 0 ".$where,$field);
		return $sql;
	}
}
?>