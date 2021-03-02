<?php
class permission{
	const pmsLevelAll = "0";
	const pmsLevelView = "1";
	const pmsLevelAdd = "2";
	const pmsLevelUpdate = "3";
	const pmsLevelDelete = "4";
	
    public $db;
    public function __construct($db){
        $this->db = $db;
    }
    function __destruct() {
        @mysqli_close($this->db);
    }
	
	public function GetCurrentPageName(){
		return pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	}
	
	
	public function pmsThisLevelAct($page_access="",$userlevel=0){ // default current page
		$allText = "_allp";
		if($page_access == null || $page_access == ""){
			$page_access = $this->GetCurrentPageName();
		}
		$page_access_all = $page_access.$allText;
		$result = "";
		
		if($userlevel == 0 || $userlevel == '0'){
			if($_SESSION['user_level_id'] != 0 && $_SESSION['user_level_id'] != '0'){
				$userlevel = $_SESSION['user_level_id'];
			}
		}
		if($userlevel != 0 && $userlevel != '0'){
			$query = $this->db->getdata('user_level_permission',"user_level_id='".$userlevel."' and ( page_access='".$page_access."' or page_access='".$page_access_all."' )");
			if($query->num_rows > 0){
				foreach ($query->rows as $key => $value) {
					if($value['page_access'] == $page_access_all){
						$result = self::pmsLevelAll;
						break;
					}else if($value['page_access'] == $page_access){
						$result = $value['page_act'];
						break;
					}
				}
			}
			
		}
		return $result;
	}
	
	public function pmsThisPage($sys="Normal"){
		if($this->pmsThisLevelAct() == ""){
			header("location: no_permission.php?paramSys=".$sys);
			exit();
		}
	}
	public function pmsCanViewMenu($page_access){
		$result = false;
		if($page_access != null and $page_access != ""){
			$thisLevelAct = $this->pmsThisLevelAct($page_access);
			if($thisLevelAct == self::pmsLevelAll || $thisLevelAct == self::pmsLevelView){
				$result = true;
			}
		}
		return $result;
	}
	
	
	
	public function pmsAllMenuLeft($userlevel=0){
		$result=array();
		$sqlCmd = "";
		if($userlevel == 0 || $userlevel == '0'){
			if($_SESSION['user_level_id'] != 0 && $_SESSION['user_level_id'] != '0'){
				$userlevel = $_SESSION['user_level_id'];
			}
		}
		$main_menu = $this->db->getdata('main_menu');
		$sqlCmd = " select upp.page_access,upp.page_link,upp.page_name,upp.menu_id from ";
		$sqlCmd = $sqlCmd." s_user_page_permission upp ";
		$sqlCmd = $sqlCmd." inner join (select * from s_user_level_permission where user_level_id=".$userlevel.") ulp on ulp.page_access = upp.page_access ";
		$sqlCmd = $sqlCmd." where (upp.page_act = 1) or (upp.page_act = 0  and (upp.page_link is not null and not(upp.page_link = ''))) order by upp.menu_id";
		$sub_menu = $this->db->query($sqlCmd);
		if($sub_menu->num_rows > 0 && $main_menu->num_rows > 0){
			foreach ($main_menu->rows as $key => $value) {
				unset($sub_result);
				$sub_result[] = array();
				$chkPms = false;
				foreach ($sub_menu->rows as $key => $valueSub) {
					if($valueSub['menu_id'] == $value['menu_id']){
						$sub_result[] = array(
							'page_access' => $valueSub['page_access'],
							'page_link' => $valueSub['page_link'],
							'page_name' => $valueSub['page_name']
						);
						$chkPms = true;
					}
				}
				mysqli_data_seek($query,0);
				if($chkPms){
					$result[] = array(
						'menu_id'=> $value['menu_id'],
						'menu_name'=> $value['menu_name'],
						'font_icon_class'=> $value['font_icon_class'],
						'bg_class'=> $value['bg_class'],
						'sub_menu'=> $sub_result,
						'cnt_sub_menu'=> count($sub_result)
					);
				}
			}
		}
		return $result;
	}
	
	
	
	public function menu($userlevel=0){
		$query = $this->db->select("user_page_permission INNER JOIN s_user_level_permission ON s_user_page_permission.page_access = s_user_level_permission.page_access","page_menu=1 and (page_parent IS NULL or page_parent='') and user_level_id='".$userlevel."' and `page_status`=0 order by page_sort,sub_page_sort ASC");
        $found = $query->num_rows;
    	$result=array();
    	if($found>0){
            $i=0;
    		while($result_menu = $query->fetch_assoc()){

                $query_sub = $this->db->select("user_page_permission","page_menu=1 and page_parent='".$result_menu['page_access']."' and `page_status`=0 order by page_sort,sub_page_sort ASC");
                $found_sub = $query_sub->num_rows;
                $sub_menu = array();
                $j=0;
                while($result_menu_sub = $query_sub->fetch_assoc()){
                    $sub_menu[] = array(
                        'page_access' => $result_menu_sub['page_access'],
                        'page_link' => (!empty($result_menu_sub['page_link'])?$result_menu_sub['page_link']:'menu_'.$i.'_'.$j),
                        'page_name' => $result_menu_sub['page_name'],
						'page_act' => $result_menu_sub['page_act']
                    );
                    $j++;
                }
    			$result[] = array(
    				'page_access' => $result_menu['page_access'],
    				'page_link' => (!empty($result_menu['page_link'])?$result_menu['page_link']:'menu_'.$i),
    				'page_name' => $result_menu['page_name'],
                    'sub_menu' => $sub_menu,
					'page_act' => $result_menu['page_act']
					
    			);
                $i++;
    		}
    	}
    	return $result;
	}
    public function allmenu($userlevel=0){
        $checked = "";
        $checked_sub = "";
        $whereuserlevelid = "";
        if($userlevel>0){
            $whereuserlevelid=" and user_level_id='".$userlevel."'";
        }
        $query = $this->db->select("user_page_permission","page_menu=1 and (page_parent IS NULL or page_parent='') order by page_sort,sub_page_sort ASC");
        $found = $query->num_rows;
        $result=array();
        if($found>0){
            $i=0;
            while($result_menu = $query->fetch_assoc()){ 
                $checked = "";
                $query_sub = $this->db->select("user_page_permission","page_menu=1 and page_parent='".$result_menu['page_access']."' order by page_sort,sub_page_sort ASC");
                $found_sub = $query_sub->num_rows;
                $sub_menu = array();
                $j=0;
                while($result_menu_sub = $query_sub->fetch_assoc()){
                    $checked_sub = "";
                    $menu_sub_parent = $this->db->getdata('user_level_permission',"page_access<>'' ".$whereuserlevelid." and page_access='".$result_menu_sub['page_access']."'");
                    if($menu_sub_parent->num_rows > 0 && !empty($whereuserlevelid)){
                        $checked_sub = 'checked';
                    }
                    $sub_menu[] = array(
                        'page_access' => $result_menu_sub['page_access'],
                        'page_link' => (!empty($result_menu_sub['page_link'])?$result_menu_sub['page_link']:'menu_'.$i.'_'.$j),
                        'page_name' => $result_menu_sub['page_name'],
						'page_act' => $result_menu_sub['page_act'],
                        'checked' => $checked_sub
                    );
                    $j++;
                }
                $menu_parent = $this->db->getdata('user_level_permission',"page_access<>'' ".$whereuserlevelid." and page_access='".$result_menu['page_access']."'");
                if($menu_parent->num_rows > 0  && !empty($whereuserlevelid)){
                    $checked = 'checked';
                }
                $result[] = array(
                    'page_access' => $result_menu['page_access'],
                    'page_link' => (!empty($result_menu['page_link'])?$result_menu['page_link']:'menu_'.$i),
                    'page_name' => $result_menu['page_name'],
                    'sub_menu' => $sub_menu,
					'page_act' => $result_menu['page_act'],
                    'checked' => $checked
                );
                $i++;
            }
        }
        return $result;
    }
    public function saveMenu($data){
        $user_level_id = (int)$data['user_level_id'];
        $this->db->delete('user_level_permission','user_level_id='.$user_level_id);
		$query = $this->db->select("user_page_permission","page_menu=1 order by page_sort,sub_page_sort ASC");
        foreach ($data['menu'] as $key => $value) {
            if($user_level_id>0 and !empty($value)){
				$page_act = "";
				while($find_page_act = $query->fetch_assoc()){
					if($find_page_act['page_access'] == $value)
					{
						$page_act = $find_page_act['page_act'];
						break;
					}
				}
				mysqli_data_seek($query,0);
                $this->db->insert('user_level_permission',array('user_level_id'=>$user_level_id,'page_access'=>$value,'page_act'=>$page_act) );
            }
        }
        $this->db->update('user_level_permission','user_level_id='.$user_level_id,array('level_name'=>$data['level_name']));
        return true;
    }
}