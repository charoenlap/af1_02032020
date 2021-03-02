<?php
class member{
	
	public $mem_loging;
	public $admin_loging;
	public $cookie_name;
	public $cookie_time;
	public $total_order;
	public $total_purchase;
	
	public function __construct(){
		$this->cookie_name = 'siteAuth';
		$this->cookie_time = 3600*24*30;
		
		if(isset($this->cookie_name))
		{
		if(isset($_COOKIE[$this->cookie_name]))
			{
			parse_str($_COOKIE[$this->cookie_name]);


				$sqlm = mysql_query("select * from ".PREFIX."admin where username = '$usr'  and user_level='admin'")or die("error");
				$FL =  mysql_fetch_assoc($sqlm);
			
				if(md5($FL[password])==$hash){
				$_SESSION['AU']['user'] = $usr;
				$_SESSION['AU']['level'] = $FL['user_level'];
				}
	
			}
		}

		
		if(isset($_SESSION[MU])){
			$this->setpara();
			
		}
		if(isset($_SESSION[AU])){
			$this->setpara_admin();
			
		}
	}
	

	public function setpara(){
		
		$obj_db = new db;
		$sql = $obj_db->select("".PREFIX."member","email='".$_SESSION[MU][user]."'");
		$F1 = mysql_fetch_assoc($sql);
		$this->mem_loging = $F1;

	}
	
	public function setpara_admin(){
		
		$obj_db = new db;
		$sql = $obj_db->select("".PREFIX."admin","username='".$_SESSION[AU][user]."'");
		$F1 = mysql_fetch_assoc($sql);
		$this->admin_loging = $F1;

	}
	
	
	public function login($username,$password,$nonpass=0){
		$obj_db = new db;
		
		$user = trim($username);
		$pass = md5(trim($password));

		if($nonpass==1){
			$w1 = "email,status";
			$w2 = "$username,1";
		}else{
			$w1 = "email,password,status";
			$w2 = "$user,$pass,1";
		}

		$ntime = time();
		if($obj_db->check("".PREFIX."member",$w1,$w2)){
			$_SESSION[MU][user] = $username;
			mysql_query("update ".PREFIX."member set time_update = $ntime where email = '$username'");
			$this->setpara();
			return true;
		}else{ 
			return false;
		}
	}
	
	public function login_admin($username,$password,$remem){
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		$remem = mysql_real_escape_string($remem);
		
		$obj_db = new db;
		$user = trim($username);
		$pass = md5(trim($password));
	
		$ntime = time();
		if($obj_db->check("".PREFIX."admin","username,password","$user,$pass")){
			
			$F = mysql_fetch_assoc($obj_db->select("".PREFIX."admin","username='$user'"));
			$_SESSION['AU']['user'] = $F[username];
			$_SESSION['AU']['level'] = $F[user_level];
			mysql_query("update ".PREFIX."admin set time_update = $ntime , xip_update ='".$_SERVER['REMOTE_ADDR']."' where username = '$username'");
			
			if($remem==1){
			$password_hash = md5($pass);
	
			setcookie ($this->cookie_name, 'usr='.$user.'&hash='.$password_hash, time() + $this->cookie_time);

			
			}
			$this->setpara_admin();
			return true;
		}else{ 
			$login_error = true;
			return false;
		}
	}
	
	public function testinfo(){
		
		print_r($this->mem_loging);
	}
	
	public function ask_info($email){
		$obj_db = new db;
		$sql = $obj_db->select("".PREFIX."order","MemUser='$email'");
		$sql2 = $obj_db->select("".PREFIX."order","MemUser='$email'","sum(TotalPriceNet) as tt");
		$F2 = mysql_fetch_row($sql2);
		$info["total_order"] = mysql_num_rows($sql);
		$info["total_sum"] = $F2[0];
		if($info["total_sum"]==""){$info["total_sum"]=0;}
		
		return $info;
	}

	public function check_auth(){
		$reurl = substr($_SERVER['PHP_SELF'],4);
		if($_SESSION['AU'][level]!="admin"){header("Location: login.php?prev=$reurl");}
		
	}
	
	public function add_action($txt){
		$ndate = date("Y-m-d H:i:s");
		$ntime = time();
		mysql_query("insert into ".PREFIX."action_record set admin_user = '".$_SESSION[AU][user]."' , detail = '$txt' , action_time = '$ndate',time = $ntime");	
		
	}

	
}