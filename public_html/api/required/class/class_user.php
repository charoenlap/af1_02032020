<?php
class user{
	public $db;
	public $userlogin = false;
	public $data = array();
	public $advisor_id = "";
	
	// User level const
	const uLvDoctor = 1;
	const uLvRegular = 2;
	const uLvSilver = 3;
	const uLvGold = 4;
	
	public function __construct($db){
		$this->db = $db;
	}
	function __destruct() {
		@mysqli_close($this->db);
    }
    
    public function getUserAllMessage($user_id,$from_user_id){
    	$result = array();
    	$result_message = $this->db->getdata('message LEFT JOIN '.PREFIX.'user ON '.PREFIX.'message.from_user_id = '.PREFIX.'user.user_id',PREFIX.'message.to_user_id ='.(int)$user_id.' and '.PREFIX.'message.from_user_id ='.(int)$from_user_id." or ".PREFIX.'message.to_user_id ='.(int)$from_user_id.' and '.PREFIX.'message.from_user_id ='.(int)$user_id.' order by '.PREFIX.'message.date_send ASC');
		if($result_message->num_rows > 0 ){
			$result = $result_message;
		}
		return $result;
    }
    public function getUserMessage($user_id){
    	$result = array();
    	$result_message = $this->db->query('select SQL_CALC_FOUND_ROWS *,a.read_message as noti_update
from (select * from '.PREFIX.'message s_a  
LEFT JOIN '.PREFIX.'user ON s_a.from_user_id = '.PREFIX.'user.user_id 
where s_a.to_user_id = '.(int)$user_id.' 
order by s_a.date_send DESC) a
group by a.from_user_id order by a.read_message DESC');

		if($result_message->num_rows > 0 ){
			$result = $result_message;
		}
		return $result;
    }
    public function getUserInterest($user_id){
    	$result = array();
    	$result_dupplicate = $this->db->getdata('favorite INNER JOIN '.PREFIX.'question ON '.PREFIX.'favorite.id_question = '.PREFIX.'question.id INNER JOIN '.PREFIX.'user ON '.PREFIX.'question.user_id_add = '.PREFIX.'user.user_id',PREFIX."favorite.id_user='".$user_id."' and ".PREFIX."question.`status`=1 and ".PREFIX."question.del=0",'*,'.PREFIX.'question.id as id_question');
    	//echo 'favorite INNER JOIN '.PREFIX.'question ON '.PREFIX.'favorite.id_question = '.PREFIX.'question.id INNER JOIN '.PREFIX.'user ON '.PREFIX.'question.user_id_add = '.PREFIX.'user.user_id';
    	//echo PREFIX."favorite.id_user='".$user_id."' and ".PREFIX."question.`status`=0 and ".PREFIX."question.del=0",'*,'.PREFIX.'question.id as id_question';
		if($result_dupplicate->num_rows > 0 ){
			$result = $result_dupplicate;
		}
		return $result;
    }
	public function login($user,$pass,$user_status=1,$user_level_id=1){
	// public function login($user,$pass,$user_status=1,$user_level_id=1){
		$user_id = 0;

		// $queryUser = $this->db->select("user","email='".$this->db->real_escape_string($user)."' and password='".$pass."' and user_status=".$user_status." and user_level_id=".$user_level_id);
		$queryUser = $this->db->select("user","email='".$this->db->real_escape_string($user)."' and password='".md5($this->db->real_escape_string($pass))."' and user_status=".$user_status);
    	$founduser = $queryUser->num_rows;
    	if($founduser>0){
    		$datauser = $queryUser->fetch_assoc();
    		$datauser['user'] = $user;
    		$this->takeLogin($datauser);
    	}
    	return $founduser;
	}
	public function takeLogin($data){
		$datauser 					= $data;
		$this->data 				= $datauser;
		$token 						= md5($datauser['email'].$datauser['user_id']);
		$this->userlogin 			= true; 
		$user_id 					= $datauser['user_id'];
		#update token to database
		if(isset($datauser['id'])){
			if(!empty($datauser['id'])){
				$dataupdate_user['fb_id'] = $datauser['id'];
			}
		}
		$dataupdate_user['token'] = $token;
		$this->db->update("user",$dataupdate_user,'user_id='.$datauser['user_id']);
		$_SESSION['token']			= $token;
		$_SESSION['user_id'] 		= $datauser['user_id'];
		$_SESSION['email'] 			= $datauser['email'];
		$_SESSION['username'] 		= $datauser['username'];
		// $_SESSION['euser']			= base64url_encode($datauser['username']);
		// $_SESSION['epass']			= base64url_encode($datauser['password']);
		$_SESSION['user_level_id']		= $datauser['user_level_id'];
		$_SESSION['code_user']		= $datauser['code_user'];
		$_SESSION['user_full_name']		= $datauser['name'].' '.$datauser['lname'];
		$_SESSION['profile_path']		= isset($datauser['image']) && !empty($datauser['image']) ? $datauser['image'] : "assets/images/default-profile.jpg";
		#get level
		$queryLevel 					= $this->db->select("user_level","user_level_id='".$datauser['user_level_id']."'");
		$datalevel 						= $queryLevel->fetch_assoc();
		$this->data['level']			= $datalevel['level_name'];
		$this->data['user_level_id']	= $datauser['user_level_id'];
		#insert login status to log "s_user_log_login"

    	$resultLogin = $this->db->insert("user_log_login",array('user_id'=>$user_id,'username'=>$datauser['user'],'datelogin'=>date('Y-m-d H:i:s')));
		return $resultLogin;
	}
	public function fblogin($fb_profile){
		$haveuser = $this->getUserfb($fb_profile['id'],$fb_profile['email']);
		$return = 0;
		$user_id = 0;
		if($haveuser){
			//login
			$haveuser['user'] = $haveuser['username'];
			$user_id = $haveuser['user_id'];
			$this->takeLogin($haveuser);
			$return = 1;
		}else{
			//register
			$email = (isset($fb_profile['email'])?$fb_profile['email']:'');
			$username = (isset($fb_profile['email'])?$fb_profile['email']:$fb_profile['id']);
			$data_insert = array(
				'username' => $username,
				'password' => md5($fb_profile['id']),
				'user_level_id' => '2',
				'sex' => ($fb_profile['gender']=="male"?'M':'F'),
				'name' => $fb_profile['first_name'],
				'lname' => $fb_profile['last_name'],
				'birthday' => $fb_profile['birthday'],
				'nation_id' => '',
				'opc' => '',
				'nation_id' => 1,
				'height' => '',
				'weight' => '',
				'congenital' => '',
				'hisdrug' => '',
				'email' => $email,
				'tel_no' => '',
				'image' => $fb_profile['picture']['data']['url'],
				'date_add' => date('Y-m-d H:i:s'),
				'token' => '',
				'user_status' => 1,
				'user_del'=>0,
				'fb_id' => $fb_profile['id'],
			);
			$data_insert['user_id'] = $this->db->insert("user",$data_insert);
			$user_id = $data_insert['user_id'];
			$data_insert['user'] = $username;
			$this->takeLogin($data_insert);
			$return = 1;
		}

		$queryCheckField = $this->getUserEmpty($user_id);
		if($queryCheckField==0){
			$return = 0;
		}
		return $return;
	}
	public function getUserEmpty($user_id){
		$result = 1;
		$queryUser = $this->db->getdata("user","user_id='".(int)$user_id."'");
    	$founduser = $queryUser->num_rows;
    	if($founduser>0){
    		if(empty($queryUser->row['username'])){
    			$result = 0;
    		}
    	}
    	return $result;
	}
	public function getUserfb($fb_id,$fb_email){
		$datauser = array();
		$queryUser 	= $this->db->select("user","fb_id='".$this->db->real_escape_string($fb_id)."' or email='".$this->db->real_escape_string($fb_email)."'");
		$founduser = $queryUser->num_rows;
		if($founduser>0){
			$datauser	= $queryUser->fetch_assoc();
		}else{
			$datauser = '';
		}
		return $datauser;
	}
	public function checklogin(){
		
		if(isset($_SESSION['token']) and isset($_SESSION['user_id'])){
			
			$queryUser 	= $this->db->select('user',"token='".$_SESSION['token']."' and user_id='".$_SESSION['user_id']."'");
			$founduser = $queryUser->num_rows;
			
			if($founduser>0){
				$this->userlogin = true;
				$datauser 					= $queryUser->fetch_assoc();
				$this->data 				= $datauser;
				#get level
	    		$queryLevel 				= $this->db->select("user_level","user_level_id='".$datauser['user_id']."'");
	    		$datalevel 					= $queryLevel->fetch_assoc();
	    		$this->data['level']		= $datalevel['level_name'];
			}
		}
		return $this->userlogin;
	}
	public function logout(){
		$this->data 		= array();
		$this->userlogin = false;
		$_SESSION['token']			= '';
    	$_SESSION['user_id'] 		= '';
    	header('location: page_login.php');
	}
	public function listperson(){
		$datauser = array();
		$queryUser 	= $this->db->getdata('user');
		$founduser = $queryUser->num_rows;
		if($founduser>0){
			$datauser	= $queryUser;
		}
		return $datauser;
	}
	
	public function listuser(){
		$datauser = array();
		$queryUser 	= $this->db->select('user', 'del = 0');
		$founduser = $queryUser->num_rows;
		if($founduser>0){
			$datauser	= $queryUser;
		}
		return $datauser;
	}
	public function getusersDoctor(){
		$datauser = array();
		$queryUser 	= $this->db->getdata("user","user_level_id=1");
		$founduser = $queryUser->num_rows;
		if($founduser>0){
			$datauser	= $queryUser;
		}
		return $datauser;
	}
	public function getuser($user_id){
		$datauser = array();
		$queryUser 	= $this->db->select("user","user_id='".$this->db->real_escape_string($user_id)."'");
		$founduser = $queryUser->num_rows;
		if($founduser>0){
			$datauser	= $queryUser->fetch_assoc();
		}
		return $datauser;
	}
	public function checkEmail($email){
		$datauser = array();
		$queryUser 	= $this->db->getdata("user","email='".$this->db->real_escape_string($email)."'");
		return $queryUser;
	}
	public function getuserGroup(){
		// $queryUserGroups 	= $this->db->select("user_level");
		$queryUserGroups 	= $this->db->getdata("user_level");
		$found = $queryUserGroups->num_rows;
		$datauserGroups = array();
		if($found>0){
			$datauserGroups	= $queryUserGroups;
		}
		return $datauserGroups;
	}
	public function getuserLevel($user_level_id){
		$datauserLevelid = array();
		$queryUserLevelid 	= $this->db->select("user_level","user_level_id='".$this->db->real_escape_string($user_level_id)."'");
		$founduserLevelid = $queryUserLevelid->num_rows;
		if($founduserLevelid>0){
			$datauserLevelid	= $queryUserLevelid->fetch_assoc();
		}
		return $datauserLevelid;
	}
	public function addUser($input=array()){
		$return = false;
		$queryUser 	= $this->db->getdata("user","email='".$this->db->real_escape_string($input['email'])."' and username='".$this->db->real_escape_string($input['username'])."'");
		$founduser = $queryUser->num_rows;
		if($founduser==0){
			if($input['confirmpassword']==$input['password']){
				unset($input['confirmpassword']);
				$input['password'] = md5($input['password']);
				$input['birthday'] = date('Y-m-d H:i:s',strtotime($input['birthday']));
				$input['date_add'] = date('Y-m-d H:i:s');
				$input['user_status'] = "0";
				$input['image'] = "assets/img/rs-avatar-64x64.jpg";
				$return = $this->db->insert('user',$input);
			}else{
				$return = false;
			}
		}else{
			$return = false;
		}
		return $return;
	}
	public function editUser($input=array()){
		$return = false;
		if(!empty($input['user_id'])){
			if($input['confirmpassword']==$input['password']){
				if(!empty($input['password'])){
					unset($input['confirmpassword']);
					
					if(isset($input['change_password']) && !empty($input['change_password']) && $input['change_password'] == "Y"){
						$input['password'] = md5($input['password']);
						unset($input['change_password']);
					}
				}else{
					unset($input['confirmpassword'],$input['password']);
				}
				$this->db->update('user',$input,'user_id='.$input['user_id']);
				$return = true;
			}else{
				$return = false;
			}
			$datauser = array();
			$queryUser 	= $this->db->select("user","user_id='".$this->db->real_escape_string($input['user_id'])."'");
			$founduser = $queryUser->num_rows;
			if($founduser>0){
				$datauser	= $queryUser->fetch_assoc();
			}else{
				$datauser = '';
			}
			$this->takeLogin($datauser);
		}
		
		return $return;
	}
	
	public function editUserProfile($input=array()){
		$return = false;
		if(!empty($input['user_id'])){
			$input['birthday'] = date('Y-m-d H:i:s',strtotime($input['birthday']));
			$this->db->update('user',$input,'user_id='.$input['user_id']);
			$return = true;
		}
		
		return $return;
	}
	
	public function addUserGroup($input=array()){
		$return = true;
		$datamenu = $input['menu'];
		$query = $this->db->select("user_page_permission","page_menu=1 order by page_sort,sub_page_sort ASC");
		unset($input['permission'],$input['menu'],$input['page_act']);
		$user_level_id = $this->db->insert('user_level',$input);
		foreach ($datamenu as $key => $value) {
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
		return $return;
	}
	public function getTotalMember(){
		$user = $this->db->getdata('user');
		$result = $user->num_rows;
		return $result;
	}
}