<?php
class db{
	public $check=FALSE;
	public $badword = array("\"", "/");
	function db($strHost,$strDB,$strUser,$strPassword)
	{
			$this->objConnect = mysql_connect($strHost,$strUser,$strPassword, true);
			$this->DB = mysql_select_db($strDB);
			mysql_query('SET NAMES utf8');
	}
	function __destruct() {
			return @mysql_close($this->objConnect);
    }
	public function getTable($table,$where=NULL)
	{ 
		if(!empty($table)){
			if($where!=NULL){$where="where $where";}
			$sql_t = "SHOW COLUMNS FROM $table $where";
			$sql = mysql_query($sql_t)or die("error $sql_t");
			return $sql;
		}
	}
	public function addCol($table,$arr=NULL)
	{ 
		if(!empty($table)){
			foreach($arr as $key =>$val){
				$sql = $this->getTable($table,"Field = '".$key."'");
				$FM = mysql_num_rows($sql);
				if($FM==0){
					$sql_t = "ALTER TABLE $table ADD COLUMN ".$key." TEXT";
					$sql = mysql_query($sql_t)or die("error $sql_t");
				}
			}
		}
	}
	public function select($table,$where=NULL,$field=NULL,$order=NULL,$limit=NULL)
	{ 
		
		if($field==NULL){$field="*";}
		if($where!=NULL){$where="where $where";}
		if($order!=NULL){$order="order by $order";}
		if($limit!=NULL){$limit="limit $limit";}
		
		$sql_t = "select $field from $table $where $order $limit";
		if($this->check==TRUE){echo $sql_t;}
		$sql = mysql_query($sql_t)or die("error $sql_t");
		return $sql;
	}
	public function selectcat($where=NULL,$field=NULL,$order=NULL,$limit=NULL)
	{ 
		
		if($field==NULL){$field="*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content_cat.id as id";}
		if($where!=NULL){$where=" and $where";}
		if($order!=NULL){$order="order by $order";}
		if($limit!=NULL){$limit="limit $limit";}
		
		$sql_t = "select $field from ".PREFIX."content_cat INNER JOIN ".PREFIX."language_detail ON ".PREFIX."content_cat.id = ".PREFIX."language_detail.ref_id where type = 0 $where $order $limit";
		if($this->check==TRUE){echo $sql_t;}
		$sql = mysql_query($sql_t)or die("error $sql_t");
		return $sql;
	}
	public function selectcontent($where=NULL,$field=NULL,$order=NULL,$limit=NULL)
	{ 
		
		if($field==NULL){$field="".PREFIX."content.*,".PREFIX."language_detail.`name` as name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail";}
		if($where!=NULL){$where=" and $where";}
		if($order!=NULL){$order="order by $order";}
		if($limit!=NULL){$limit="limit $limit";}
		
		$sql_t = "select $field from ".PREFIX."content INNER JOIN ".PREFIX."language_detail ON ".PREFIX."content.id = ".PREFIX."language_detail.ref_id where ".PREFIX."language_detail.type = 1 $where $order $limit";
		
		if($this->check==TRUE){echo $sql_t;}
		$sql = mysql_query($sql_t)or die("error $sql_t");
		return $sql;
	}
	public function query($text_sql){
		$query = @mysql_query($text_sql)or die(mysql_error());
		if($query)
		{ 
			return $query;
		   
		}

	}
	public function function_error($input){
			$file = dirname(__FILE__).DIRECTORY_SEPARATOR.'../MyLog.txt';
			$sort = "";
			$current = file_get_contents($file);
			$sort = date("d-m-Y H:i:s")." ".$input."\n".$current;
			file_put_contents($file, $sort);
	}
	public function insert($table,$input)
	{
	//if($input[name]){$input[name] = str_replace($this->badword, "", $input[name]);}
	//if($input[name_en]){$input[name_en] = str_replace($this->badword, "", $input[name_en]);}
	$insert = 'insert into '.$table.' set';	
	$i=1;
		foreach($input as $key => $value){
			if(!isset($_SESSION[AU])){$value = mysql_real_escape_string($value);}
			if($value==""){$insert .= " $key = ''";}
			else{
				$insert .= " $key = '".$value."'";
			}
		if($i!=count($input))
		{
		$insert .= ",";
		}
		$i++;
		
		}
	if($this->check==TRUE){echo $insert;exit();}
	$query = @mysql_query($insert)or die(mysql_error());
	if($query)
	{ 
		return mysql_insert_id();
	   
	}

	}
	public function update($table,$input,$where)
	{
	
	//if($input[name]){$input[name] = str_replace($this->badword, "", $input[name]);}
	//if($input[name_en]){$input[name_en] = str_replace($this->badword, "", $input[name_en]);}
	
	$update = 'update '.$table.' set';	
	$i=1;
		foreach($input as $key => $value){
			
			if(!isset($_SESSION['AU'])){$value = mysql_real_escape_string($value);}
		if($value==""){$update .= " $key = NULL";}
		else{
			$value = iconv(mb_detect_encoding($value, mb_detect_order(), true), "UTF-8", $value);
			$update .= " $key = '".$value."'";
		}
		
		if($i!=count($input))
		{
		$update .= ",";
		}
		$i++;
		}
	$update .= " where $where";
	if($this->check == TRUE)
	{echo 'not found where statement.';exit();}

	$query = @mysql_query($update);
	if($query)
    { 
        return true;
    }

	}
	public function check($table,$field,$input)
	{
	$field = explode(",",$field);
	$input = explode(",",$input);
	for($i=0;$i<=count($input)-1;$i++){
	$fees .= "$field[$i] = '$input[$i]'";
	if($i!=count($input)-1){$fees .=" and ";}
	}

	$check = "select * from $table where $fees";	
	if($this->check == TRUE)
	{echo $check;}
	$sql = mysql_query($check)or die(mysql_error());
	
	if(mysql_num_rows($sql)>0){return true;}
	else{ return false;}

	}
	
	
	public function delete($table,$where){
		$delete = "delete from $table where $where";
		$query = @mysql_query($delete)or die($delete);
		if($query)
            { 	
				mysql_query("REPAIR TABLE $table");
				mysql_query("OPTIMIZE TABLE $table");
                return true;
            }

	}
	
}
?>