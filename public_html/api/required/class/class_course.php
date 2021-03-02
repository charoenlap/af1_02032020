<?php
class course{
	public $db;
	public $data = array();
	
	public function __construct($db){
		$this->db = $db;
	}
	function __destruct() {
		@mysqli_close($this->db);
    }
    
    public function getAllcourse(){
    	$result = array();
    	$result_course = $this->db->getdata('course LEFT JOIN sl_course_take_category ON sl_course.course_id = sl_course_take_category.course_id
	 LEFT JOIN sl_course_category ON sl_course_take_category.course_category_id = sl_course_category.course_category_id','','*,sl_course.course_id as course_id');
		if($result_course->num_rows > 0 ){
			$result = $result_course;
		}
		return $result;
    }
    public function getCourse($course_id){
    	$result = array();
    	$result_course = $this->db->getdata('course LEFT JOIN sl_course_take_category ON sl_course.course_id = sl_course_take_category.course_id
	 LEFT JOIN sl_course_category ON sl_course_take_category.course_category_id = sl_course_category.course_category_id','sl_course.course_id = '.(int)$course_id,'*,sl_course.course_id as course_id');
		if($result_course->num_rows > 0 ){
			$result = $result_course;
		}
		return $result;
    }
    public function getAllcategory(){
    	$result = array();
    	$result_course_category = $this->db->getdata('course_category');
		if($result_course_category->num_rows > 0 ){
			$result = $result_course_category;
		}
		return $result;
    }
    public function getCourseCategory($course_id){
    	$result = array();
    	$result_course = $this->db->getdata('course LEFT JOIN sl_course_take_category ON sl_course.course_id = sl_course_take_category.course_id','sl_course.course_id = '.(int)$course_id,'*,sl_course.course_id as course_id');
		if($result_course->num_rows > 0 ){
			$result = $result_course;
		}
		return $result;
    }
}