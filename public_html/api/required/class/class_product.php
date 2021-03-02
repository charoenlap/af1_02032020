<?php
class product{
	public $page = "cp-products-cat.php";
	public $page_edit = "cp-products-cat-edit.php";
	public $table = "";
	public $table_sub = "";
	public $table_sub_sub = "";
	public function product(){
		$this->table = "product_cat";
		$this->table_sub = "product";
		$this->table_sub_sub = "sub_content";
	}
	public function showcat($cat,$page=1){
		global $obj_db;
		if($cat!=""){$pat = " and parent = $cat";}else{$pat = " and parent = 0 order by seq,time ASC";}
		$sql = $obj_db->select("$this->table INNER JOIN ".PREFIX."language_detail ON ".PREFIX."content_cat.id = ".PREFIX."language_detail.ref_id","del=0 and type=0 and language_id=1 $pat ","*,".PREFIX."content_cat.id as id,".PREFIX."language_detail.`name` as lang_name");
		return $sql;
		
	}
	public function showcatall($showmenu=""){
		global $obj_db;
		$pat = " order by seq,time ASC";
		$sql = $obj_db->select("$this->table INNER JOIN ".PREFIX."language_detail ON ".PREFIX."content_cat.id = ".PREFIX."language_detail.ref_id","del=0 and type=0 and language_id=1 $pat","*,".PREFIX."content_cat.id as id,".PREFIX."language_detail.`name` as lang_name");
		return $sql;
		
	}
	public function showcatallshow(){
		global $obj_db;
		$sql = $obj_db->select("$this->table INNER JOIN ".PREFIX."language_detail ON ".PREFIX."content_cat.id = ".PREFIX."language_detail.ref_id","del=0  and type=0 and language_id=1 and (show_menu=1 or show_sub=1) order by seq,time ASC","*,".PREFIX."content_cat.id as id,".PREFIX."language_detail.`name` as lang_name");
		return $sql;
		
	}
	public function showchild($cat,$where="",$order_arg=""){
		global $obj_db;
		if(!empty($where)){
			$where = ' and '.$where;
		}
		$order = " order by seq,time ASC";
		if(!empty($order_arg)){
			$order = $order_arg;
		}
		$sql = $obj_db->select("$this->table_sub","cat=$cat $where and del=0 ".$order);
		return $sql;
		
	}
	
	public function showchild_sub($id){
		global $obj_db;
		$sql = $obj_db->select("".PREFIX."content","id=$id and del=0");
		return $sql;
		
	}
	public function showcatname($cid){
		$result = '';
		return $result;
	}
	public function showcatnameBC($cid){
		global $obj_db;
		global $obj_con;
		$sql_language = $obj_con->getLanguageDetail('type=0 and ref_id='.(int)$cid.' and language_id=1');
        $count_rows = $sql_language->num_rows;
        if($count_rows>0){
            $input_language = array();
            while($result_language=$sql_language->fetch_assoc()){
                $id_language='';
                if(!empty($result_language['id'])) {
                    $id_language = $result_language['id'];
                }
                $id_language=$result_language['id'];
                $input_language = array(
                    'name'          => $result_language['name'],
                );
            }
        }


		return $input_language['name'];
	}
	public function find_parent($cat){
	global $obj_db;
	//$sql = $obj_db->select("$this->table","id = $cat");
	$sql = $obj_db->selectcat(PREFIX.$this->table.".id = $cat");
	$F = $sql->fetch_assoc();
	
		$sql2 = $obj_db->selectcat("".PREFIX.$this->table.".id = $F[parent]");
		//$obj_db->select("$this->table","id = $F[parent]");
		if($sql2->num_rows>0){
			$F2=$sql2->fetch_assoc();
			$this->find_parent($F2[id]);
			echo "<li><a href='$this->page?cat=$F2[id]'>$F2[lang_name]</a></li>";
			}
	}
	
public function set_del_all_cat($cat){
	global $obj_db;
	$sql = $obj_db->select("$this->table ","parent = $cat");
	$obj_db->query("update ".PREFIX."$this->table  set del = 1 where id = $cat");
		if(mysql_num_rows($sql)>0){
			while($F=mysql_fetch_assoc($sql)){
			$this->set_del_all_cat($F[id]);
			}
		}
	}
	
	public function set_show_all_cat($cat,$act){
	global $obj_db;
	$sql = $obj_db->select("$this->table","parent = $cat");
	$obj_db->query("update ".PREFIX."$this->table  set hide = $act where id = $cat");
		if(mysql_num_rows($sql)>0){
			while($F=mysql_fetch_assoc($sql)){
			$this->set_show_all_cat($F[id]);
			}
		}
	}
	
	
	
	public function manage_category($act,$fid,$seq=0,$cat=NULL){
		global $obj_db;
		if($act=="del"){
			$this->set_del_all_cat($fid);
			$obj_db->query("update ".PREFIX."$this->table_sub set del = 1 where cat =$fid ");
			return true;
		}
		if($act=="hide"){
			$this->set_show_all_cat($fid,1);
			$obj_db->query("update ".PREFIX."$this->table_sub set hide = 1 where cat =$fid ");
			return true;
		}
		if($act=="show"){
			$this->set_show_all_cat($fid,0);
			$obj_db->query("update ".PREFIX."$this->table_sub set hide = 0 where cat =$fid ");
			return true;
		}
		if($act=="edit"){ 
			header("location: $this->page_edit?eid=$fid&cat=$cat");
			exit;
		}
		
		if($act=="seq"){
			$obj_db->query("update ".PREFIX."$this->table set seq = $seq where id=$fid");
			return true;
		}
		if($act=="showmenu"){
			$obj_db->query("update ".PREFIX."$this->table set show_menu = 0 where id=$fid");
			return true;	
		}
		if($act=="hidemenu"){
			$obj_db->query("update ".PREFIX."$this->table set show_menu = 1 where id=$fid");
			return true;	
		}
		if($act=="showmenusub"){
			$obj_db->query("update ".PREFIX."$this->table set show_sub = 0 where id=$fid");
			return true;	
		}
		if($act=="hidemenusub"){
			$obj_db->query("update ".PREFIX."$this->table set show_sub = 1 where id=$fid");
			return true;	
		}
	
	} // end function
	
	
	public function manage_child($act,$fid,$seq=0){
		global $obj_db;
		if($act=="del"){
			$obj_db->query("update ".PREFIX."$this->table_sub set del = 1 where id =$fid ");
			return true;
		}
		if($act=="hide"){
			$obj_db->query("update ".PREFIX."$this->table_sub set hide = 1 where id =$fid ");
			return true;
		}
		if($act=="show"){
			$obj_db->query("update ".PREFIX."$this->table_sub set hide = 0 where id =$fid ");
			return true;
		}
		
		if($act=="seq"){
			
			
				$obj_db->query("update ".PREFIX."$this->table_sub set seq = $seq where id=$fid");
			//echo "update ".PREFIX."$this->table_sub set seq = $seq where id=$fid";exit();
			
			return true;
			
		}
	} // end function
	
	
	public function manage_child_sub($act,$fid,$seq=0){
	global $obj_db;
		if($act=="del"){
			$obj_db->query("update ".PREFIX."$this->table_sub_sub set del = 1 where id =$fid ");
			return true;
		}
		if($act=="hide"){
			$obj_db->query("update ".PREFIX."$this->table_sub_sub set hide = 1 where id =$fid ");
			return true;
		}
		if($act=="show"){
			$obj_db->query("update ".PREFIX."$this->table_sub_sub set hide = 0 where id =$fid ");
			return true;
		}
		
		if($act=="seq"){
			$result_sql = $obj_db->query("select * from $this->table_sub_sub where id='$fid'");
			$FG = $result_sql->fetch_assoc();
			
			$sql = $obj_db->query("select id,seq from $this->table_sub_sub where cat='$FG[cat]' and del=0 order by seq asc");
			$all_p =  $sql->num_rows;
			
			$i=1;
			
			while($FA = $sql->fetch_assoc()){
				if($FA[id]==$fid){$array_id[$seq]= $FA[id];}else{
				if($i==$seq){$i++;}
				$array_id[$i] = $FA[id];
				$i++;
				}
			}
			ksort($array_id);
	
			foreach($array_id as $key=>$val){
				$obj_db->query("update ".PREFIX."$this->table_sub_sub set seq = $key where id=$val");
			}
		
				return true;
				
		}
	} // end function
	public function status_manage($message,$sub=0){
		global $obj_db;
			if($sub==0){$table = $this->table;}elseif($sub==1){$table = $this->table_sub;}
			if($message==1){
				$sql_result = $obj_db->select("$table ",NULL,NULL,"time desc","0,1");
				$FL = $sql_result->fetch_assoc();
				
				?>
                <div class="nNote nSuccess hideit">
				<p><strong>SUCCESS: </strong><?=$FL[name]?> has been added!!!!</p>
                </div>
			<script>
			$(function(){
				$.jGrowl('<?=$FL[name]?> has been added',{ header: 'New Category', life: 10000});
			
			});
			</script>
			 <?php }?>
			 <?php if($message==2){
				$FL = mysql_fetch_assoc($obj_db->select("$table ",NULL,NULL,"time_update desc","0,1"));
				
				?>
				<div class="nNote nSuccess hideit">
								<p><strong>SUCCESS: </strong><?=$FL[name]?> has been edited!!!!</p>
					 </div>
			<script>
			$(function(){
				$.jGrowl('<?=$FL[name]?> has been edited',{ header: 'Edit Category', life: 10000});
			
			});
			</script>
			 <?php }
	}
	public function query_fetch($where){
		global $obj_db;
		$sql = $obj_db->select($this->table_sub,$where);
		$F = $sql->fetch_assoc();
		return $F;
	}
}
?>