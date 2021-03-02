<?php
// Append by pingpong
class DataAccess{
	
	const delShowed = "0";
	const delDeleted = "1";
	
	const doctorStamp = "N";
	const doctorStamped = "Y";
	
	const statusQuestion = "0";
	
	public $db;
	public function __construct($db){
		$this->db = $db;
	}
	function __destruct() {
		@mysqli_close($this->db);
    }
	
	
	
	// Ex. Receive Data :  list($countrow,$query) = LoadData(....);
	public function LoadData($table,$where=NULL,$field=NULL,$order=NULL,$limit=NULL){
		$result = $this->db->getdata($table,$where,$field,$order,$limit);
		$totalRows = $result->num_rows;
		return array($totalRows,$result);
	}
	
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> USERS LOGIN <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
		public function IsDupUser($where=NULL){
			$result = false;
			$numRowDup = $this->db->getdata("user",$where)->num_rows;
			if(isset($numRowDup) && !empty($numRowDup)){
				if($numRowDup > 0){
					$result = true;
				}
			}	
			return $result;
		}
		public function LoadUsers($where=NULL,$field=NULL,$order=NULL,$limit=NULL){
			if(isset($where) && $where != null && !empty($where)){$where="where ".$where;}
			if(!isset($field) || $field == null || empty($field)){$field = "*";}
			if(isset($order) && $order != null && !empty($order)){$order="order by $order";}
			if(isset($limit) && $limit != null && !empty($limit)){$limit="limit $limit";}
			
			$sqlTxt = "SELECT SQL_CALC_FOUND_ROWS ".$field." FROM view_".PREFIX."user ".$where." ".$order." ";
			$result = $this->db->query($sqlTxt." ".$limit);
			$totalRows = $result->num_rows;
			return array($totalRows,$result);
		}
		
		
		public function LoadUserLevel($where=NULL,$field=NULL,$order=NULL,$limit=NULL){
			return $this->LoadData("user_level",$where,$field,$order,$limit);
		}

		public function IsDupUserLevel($where=NULL){
			$result = false;
			$numRowDup = $this->db->getdata("user_level",$where)->num_rows;
			if(isset($numRowDup) && !empty($numRowDup)){
				if($numRowDup > 0){
					$result = true;
				}
			}	
			return $result;
		}
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> USERS LOGIN <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
	
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> LIKE <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
	
	public function DeleteLikeQuestion($idQuestion=NULL,$idSubQuestion=NULL,$del=1){ // 1 = del , 0 = not del
		$where = $where." id_question=".$idQuestion." ";
		if(!is_null($idSubQuestion) && $idSubQuestion != ""){
			$where = $where." AND id_sub_question=".$idSubQuestion." ";
		}
		$data = array('question_del' => $del);
    	return $this->db->update('like',$data,$where);
    }
	
	public function LoadLikeQuestion($idQuestion=NULL,$idSubQuestion=NULL,$userId=NULL){
		$where = $where." id_question=".$idQuestion." ";
		if(!is_null($userId) && $userId != ""){$where = " AND id_user_press=".$userId." ";}
		if(!is_null($idSubQuestion) && $idSubQuestion != ""){
			$where = $where." AND id_sub_question=".$idSubQuestion." ";
		}else{
			$where = $where." AND id_sub_question = 0 ";
		}
		return $this->LoadData("like",$where,NULL,NULL,NULL);
    }
	
	public function MngLikeQuestion($idQuestion=NULL,$idSubQuestion=NULL,$userIdAddTopic=NULL,$userId=NULL){
		$result = false;
		if(!is_null($userId) && $userId != "" && !is_null($userIdAddTopic) && $userIdAddTopic != "" && !is_null($idQuestion) && $idQuestion != ""){
			$where = " id_user_press=".$userId." ";
			$where = $where." AND id_question=".$idQuestion." ";
			if(!is_null($idSubQuestion) && $idSubQuestion != ""){
				$where = $where." AND id_sub_question=".$idSubQuestion." ";
			}else{
				$where = $where." AND id_sub_question = 0 ";
			}
			$numRowFound = $this->db->getdata("like",$where)->num_rows;
			if($numRowFound == 0){
				$data = array(
					'id_question'=>$idQuestion,
					'id_sub_question'=>$idSubQuestion,
					'id_user_recv'=>$userIdAddTopic,
					'id_user_press'=>$userId,
					'question_del'=>0
				);
				$result = $this->db->insert('like',$data);
			}else if($numRowFound > 0){
				$result = $this->db->delete('like',$where);
			}
		}
		return $result;
    }
	
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> LIKE  <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
	
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> REPORT QUESTION <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
	
	public function AddReportQuestion($idSubQuestion=NULL,$textReport=NULL,$userId=NULL){
		$result = false;
		if(!is_null($userId) && $userId != "" && !is_null($idSubQuestion) && $idSubQuestion != "" ){
			$data = array(
				'id_question_detail'=>$idSubQuestion,
				'user_id'=>$userId,
				'text'=>$textReport,
				'date_report'=>date('Y-m-d H:i:s')
			);
			$result = $this->db->insert('report',$data);
		}
		return $result;
    }
	
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> REPORT QUESTION  <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
	
	
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> NOTIFICATION <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
	
	public function UpdateNotiAllFlag($approveTo=NULL,$doctorStampFlag=NULL,$sawAllFlag=NULL){
    	$result = false;
		if(!is_null($approveTo) && $approveTo != "" && !is_null($doctorStampFlag) && $doctorStampFlag != ""){
			$data = array(
				'saw_all_flag'=>$sawAllFlag
			);
			if($this->db->update('question',$data,"approveTo=".$approveTo." AND doctor_stamp_flag='".$doctorStampFlag."'")){
				$result = true;
			}
		}
		return $result;
    }
	
	public function UpdateNotiSubFlag($approveTo=NULL,$doctorStampFlag=NULL,$sawSubFlag=NULL,$ref_id=NULL){
		$result = false;
		if(!is_null($approveTo) && $approveTo != "" && !is_null($doctorStampFlag) && $doctorStampFlag != "" && !is_null($ref_id) && $ref_id != ""){
			$data = array(
				'saw_sub_flag'=>$sawSubFlag
			);
			if($this->db->update('question',$data,"approveTo=".$approveTo." AND doctor_stamp_flag='".$doctorStampFlag."' AND id=".$ref_id)){
				$result = true;
			}
		}
		return $result;
    }
	
	public function LoadNotiCount($approveTo=NULL,$doctorStampFlag=NULL,$sawAllFlag=NULL,$sawSubFlag=NULL){
		if($approveTo == null || $approveTo == ""){$approveTo = "0";}// ทำให้ไม่พบ
		if($doctorStampFlag == null || $doctorStampFlag == ""){$doctorStampFlag = self::doctorStamp;}// default New question
		if(!is_null($sawAllFlag)){
			$sawAllFlag = " AND saw_all_flag=".$sawAllFlag;
		}
		if(!is_null($sawSubFlag)){
			$sawSubFlag = " AND saw_sub_flag=".$sawSubFlag;
		}
		$where = "approveTo=".$approveTo." AND doctor_stamp_flag='".$doctorStampFlag."' ";
		$where = $where.$sawAllFlag.$sawSubFlag;
		$sqlTxt = "SELECT SQL_CALC_FOUND_ROWS * FROM ".PREFIX."question where ".$where;
		$result = $this->db->query($sqlTxt);
		return $result->num_rows;
	}
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> NOTIFICATION  <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
	
	
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> LIST MASTER <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
	public function LoadNations($where=NULL,$field=NULL,$order=NULL,$limit=NULL){
		if(isset($where) && $where != null && !empty($where)){$where="where ".$where;}
		if(!isset($field) || $field == null || empty($field)){$field = "*";}
		if(isset($order) && $order != null && !empty($order)){$order="order by $order";}
		if(isset($limit) && $limit != null && !empty($limit)){$limit="limit $limit";}
		
		$sqlTxt = "SELECT SQL_CALC_FOUND_ROWS ".$field." FROM view_".PREFIX."nationality ".$where." ".$order." ";
		$result = $this->db->query($sqlTxt." ".$limit);
		$totalRows = $result->num_rows;
		return array($totalRows,$result);
	}
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> LIST MASTER  <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
	
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> CATEGORY <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
		
	public function LoadCategory($where=NULL,$field=NULL,$order=NULL,$limit=NULL){
		if(isset($where) && $where != null && !empty($where)){$where="where ".$where;}
		if(!isset($field) || $field == null || empty($field)){$field = "*";}
		if(isset($order) && $order != null && !empty($order)){$order="order by $order";}
		if(isset($limit) && $limit != null && !empty($limit)){$limit="limit $limit";}
		
		$sqlTxt = "SELECT SQL_CALC_FOUND_ROWS ".$field." FROM view_".PREFIX."category ".$where." ".$order." ";
		$result = $this->db->query($sqlTxt." ".$limit);
		$totalRows = $result->num_rows;
		return array($totalRows,$result);
	}
	
		
		
		
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> CATEGORY <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
	
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> QUESTION <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
	
	public function LoadQuestions($where=NULL,$field=NULL,$order=NULL,$limit=NULL,$del=0){
		if(isset($where) && $where != null && !empty($where)){$where="where ".$where." AND status=1 AND del=".$del;}else{ $where="where del=".$del." and status=1"; }
		if(!isset($field) || $field == null || empty($field)){$field = "*";}
		if(isset($order) && $order != null && !empty($order)){$order="order by $order";}
		if(isset($limit) && $limit != null && !empty($limit)){$limit="limit $limit";}
		
		$sqlTxt = "SELECT SQL_CALC_FOUND_ROWS ".$field." FROM view_".PREFIX."question ".$where." ".$order." ";
		$result = $this->db->query($sqlTxt." ".$limit);
		$totalRows = $result->num_rows;
		return array($totalRows,$result);
	}
	
	public function LoadQuestionDetails($where=NULL,$field=NULL,$order=NULL,$limit=NULL,$del=0){
		if(isset($where) && $where != null && !empty($where)){$where="where ".$where." AND del=".$del;}else{ $where="where del=".$del; }
		if(!isset($field) || $field == null || empty($field)){$field = "*";}
		if(isset($order) && $order != null && !empty($order)){$order="order by $order";}
		if(isset($limit) && $limit != null && !empty($limit)){$limit="limit $limit";}
		
		$sqlTxt = "SELECT SQL_CALC_FOUND_ROWS ".$field." FROM view_".PREFIX."question_detail ".$where." ".$order." ";
		$result = $this->db->query($sqlTxt." ".$limit);
		$totalRows = $result->num_rows;
		return array($totalRows,$result);
	}
	
	public function AddQuestion($data=array()){
		$result = false;
		$data['doctor_stamp_flag'] = self::doctorStamp;
		$data['status']= self::statusQuestion;
		$data['del']= self::delShowed;
		if($this->db->insert('question',$data)){
			$result = true;
		}
    	return $result;
	}
	
	public function AddQuestionDetail($data=array()){
		$result = false;
		$data['status']= self::statusQuestion;
		$data['del']= self::delShowed;
		if($this->db->insert('question_detail',$data)){
			$result = true;
		}
    	return $result;
	}
	
	public function UpdateQuestion($data=array()){
    	$where = "";
    	$result = false;
		$question_id = $data['id'];
		if($this->db->update('question',$data,"id=".$question_id)){
			$result = true;
		}
    	return $result;
    }
	public function UpdateQuestionDetail($data=array()){
    	$where = "";
    	$result = false;
		$question_D_id = $data['id'];
		$question_id = $data['ref_id'];
		if($this->db->update('question_detail',$data,"id=".$question_D_id." AND ref_id=".$question_id)){
			$result = true;
		}
    	return $result;
    }
	public function DeleteQuestion($question_id){
    	$where = "";
    	$result = false;
		$data = array('del' => self::delDeleted);
    	if($this->db->update('question',$data,"id=".$question_id)){
			$result = true;
		}
    	return $result;
    }
	public function DeleteQuestionDetail($question_D_id,$question_id){
    	$where = "";
    	$result = false;
		$data = array('del' => self::delDeleted);
		$question_d_cri = "";
		if(!is_null($question_D_id) || $question_D_id != ""){
			$question_d_cri = "id=".$question_D_id." AND ";
		}
    	if($this->db->update('question_detail',$data,$question_d_cri." ref_id=".$question_id)){
			$result = true;
		}
    	return $result;
    }
	
	/* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> QUESTION <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
}