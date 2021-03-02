<?php
class question{
	public $db;
	public $userlogin = false;
	public $data = array();
	
	public function __construct($db){
		$this->db = $db;
	}
	function __destruct() {
		@mysqli_close($this->db);
    }
    public function getMCate($lang){
    	if(empty($lang)){
    		$lang = 1;
    	}
    	$result = array();
    	$result_question = $this->db->getdataQuery('view_'.PREFIX.'category','parent is null and language_id='.(int)$lang);
		if($result_question->num_rows > 0 ){
			$result = $result_question;
		}
		return $result;
    }
    public function getSubCate($mcate,$lang){
    	if(empty($lang)){
    		$lang = 1;
    	}
    	$result = array();
    	$result_question = $this->db->getdataQuery('view_'.PREFIX.'category','parent is not null and language_id='.(int)$lang.' and parent='.(int)$mcate);
		if($result_question->num_rows > 0 ){
			$result = $result_question;
		}
		return $result;
    }
    public function getDoctorQuestion($id_user,$orderField=""){
		if(!isset($orderField) || empty($orderField)){ $orderField = PREFIX."question.id asc"; }
    	$result = array();
    	$result_question = $this->db->getdata('question INNER JOIN '.PREFIX.'user ON '.PREFIX.'question.user_id_add = '.PREFIX.'user.user_id','approveTo='.(int)$id_user.' and '.PREFIX.'question.del=0',"*,".PREFIX."question.date_add as date_add_q",$orderField);
		if($result_question->num_rows > 0 ){
			$result = $result_question;
		}
		return $result;
    }
    public function getQuestion($id_question){
    	$result = array();
    	$where = '';
    	if(isset($id_question)){
    		if(!empty($id_question)){
    			$where = " and id=".(int)$id_question;
    		}
    	}
    	$result_question = $this->db->getdata('question INNER JOIN '.PREFIX.'user ON '.PREFIX.'question.user_id_add = '.PREFIX.'user.user_id','del=0'.$where);
		if($result_question->num_rows > 0 ){
			$result = $result_question;
		}
		return $result;
    }
    public function getQuestionDoctor($id_question,$user_id){
    	$result = array();
    	$where = '';
    	if(isset($id_question)){
    		if(!empty($id_question)){
    			$where = ' and '.PREFIX.'question.id='.(int)$id_question;
    		}
    	}
    	$result_question = $this->db->getdata('question INNER JOIN '.PREFIX.'user ON '.PREFIX.'question.user_id_add = chi_user.user_id',PREFIX.'question.del=0 and '.PREFIX.'question.approveTo='.(int)$user_id.$where,'*,'.PREFIX.'question.image as image_question');
    	//echo PREFIX.'question.del=0 and '.PREFIX.'question.status=1 and '.PREFIX.'question.approveTo='.(int)$user_id.$where;
    	//echo PREFIX.'question.del=0 and '.PREFIX.'question.status=1 and '.PREFIX.'question.approveTo='.(int)$user_id.$where;
		if($result_question->num_rows > 0 ){
			$result = $result_question;
		}
		return $result;
    }
    public function getReplyDoctor($id_question){
    	$result = array();
    	$result_question = $this->db->getdata('question_detail','ref_id='.(int)$id_question.' and del=0');
		if($result_question->num_rows > 0 ){
			$result = $result_question;
		}
		return $result;
    }
    public function getReplyDoctorDESC($id_question){
        $result = array();
        $result_question = $this->db->getdata('question_detail','ref_id='.(int)$id_question.' and del=0 order by id desc');
        if($result_question->num_rows > 0 ){
            $result = $result_question;
        }
        return $result;
    }
    public function getReply($id_question){
    	$result = array();
    	$result_question = $this->db->getdata('question_detail','ref_id='.(int)$id_question.' and status=1 and del=0');
		if($result_question->num_rows > 0 ){
			$result = $result_question;
		}
		return $result;
    }
    public function getReplyUpdate($id_question){
    	$result = array();
    	$result_question = $this->db->getdata('question_detail','ref_id='.(int)$id_question.' and status=0 and del=0');
		if($result_question->num_rows > 0 ){
			$result = $result_question;
		}
		return $result;
    }
}