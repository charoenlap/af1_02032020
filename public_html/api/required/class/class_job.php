<?php
class job{
	public $db;
	public function __construct($db){
		$this->db = $db;
	}
	function __destruct() {
		@mysqli_close($this->db);
    }
    public function listJob($id_category){
    	$query = $this->db->getdata("job INNER JOIN ".PREFIX."brand ON ".PREFIX."job.brand_id = ".PREFIX."brand.brand_id INNER JOIN ".PREFIX."police ON ".PREFIX."job.police_id = ".PREFIX."police.police_id INNER JOIN ".PREFIX."province ON ".PREFIX."job.province_id = ".PREFIX."province.province_id INNER JOIN ".PREFIX."case ON ".PREFIX."job.case_id = ".PREFIX."case.case_id INNER JOIN ".PREFIX."user ON ".PREFIX."job.user_id = ".PREFIX."user.user_id","id_category = ".$id_category." order by ".PREFIX."job.job_update limit 0,15");
    	$found = $query->num_rows;
    	$result = array();
    	if($found>0){
    		foreach($query->rows as $value){
    			$result[] = array(
    				'id_job'		=> $value['id_job'],
    				'cus' 			=> $value['job_cus'],
    				'sl' 			=> $value['job_sl'],
    				'contact_no' 	=> $value['job_contract_type']." - ".$value['job_contract'],
    				'contact_info' 	=> $value['job_prefix']." ".$value['job_name']." ".$value['job_lname'],
    				'car_info' 		=> $value['brand_name'],
    				'lc'			=> $value['job_lc'],
    				'police'		=> $value['police_name'],
    				'province'		=> $value['province_name'],
    				'case'			=> $value['case_name'],
    				'staff'			=> $value['job_prefix']." ".$value['job_name']." ".$value['job_lname'],
    				'wd'			=> $value['job_wd'],
    				'od'			=> $value['job_od'],
    				'date_update'	=> $value['job_update'],
    				'status'		=> $value['job_status']
    			);
    		}
    	}
    	return $result;
    }
    public function getJobDetail($id_job=0){
    	$query = $this->db->getdata("job INNER JOIN ".PREFIX."brand ON ".PREFIX."job.brand_id = ".PREFIX."brand.brand_id INNER JOIN ".PREFIX."police ON ".PREFIX."job.police_id = ".PREFIX."police.police_id INNER JOIN ".PREFIX."province ON ".PREFIX."job.province_id = ".PREFIX."province.province_id INNER JOIN ".PREFIX."case ON ".PREFIX."job.case_id = ".PREFIX."case.case_id INNER JOIN ".PREFIX."user ON ".PREFIX."job.user_id = ".PREFIX."user.user_id","id_job = ".(int)$id_job);
    	$found = $query->num_rows;
    	$result = array();
    	if($found>0){
    		$result = $query;
    	}
    	return $result;
    }
    public function getLastJobHistoryDetail($id_job=0){
    	$query = $this->db->getdata("history INNER JOIN ".PREFIX."history_status ON ".PREFIX."history.history_status_id = ".PREFIX."history_status.history_status_id
	 INNER JOIN ".PREFIX."history_sub ON ".PREFIX."history.history_sub_id = ".PREFIX."history_sub.id_history_sub",PREFIX."history.id_job = ".(int)$id_job);
    	$found = $query->num_rows;
    	$result = array();
    	if($found>0){
    		$result = $query->row;
    	}
    	return $result;
    }
	public function listJobCategory(){
		$query = $this->db->getdata("category");
    	$found = $query->num_rows;
    	$result = array();
    	if($found>0){
    		$result = $query->rows;
    	}
    	return $result;
	}
	public function getJobCategory($id_category){
		$query = $this->db->getdata("category","id_category=".(int)$id_category);
    	$found = $query->num_rows;
    	$result = array();
    	if($found>0){
    		$result = $query;
    	}
    	return $result;
	}
}