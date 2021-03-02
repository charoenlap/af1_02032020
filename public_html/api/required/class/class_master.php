<?php
class master{
	public $db;
	public function __construct($db){
		$this->db = $db;
	}
	function __destruct() {
		@mysqli_close($this->db);
    }
    public function listProvinces(){
        $query = $this->db->getdata("other_province");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query->rows;
        }
        return $result;
    }
    public function listAmphurs($province_id=1){
        $query = $this->db->getdata("other_amphur","PROVINCE_ID='".(int)$province_id."'");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query->rows;
        }
        return $result;
    }
    public function listProvince(){
    	$query = $this->db->getdata("province");
    	$found = $query->num_rows;
    	$result = array();
    	if($found>0){
    		$result = $query->rows;
    	}
    	return $result;
    }
    public function listCase(){
        $query = $this->db->getdata("case");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query->rows;
        }
        return $result;
    }
    public function listPolice(){
        $query = $this->db->getdata("police");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query->rows;
        }
        return $result;
    }
    public function listBrand(){
        $query = $this->db->getdata("brand");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query->rows;
        }
        return $result;
    }
    public function getLocation($id){
        if(!empty($id)){
            global $lang_no;
            $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","sl_content.id = ".$id." and sl_language_detail.type=1 and language_id=".(!empty($lang_no)?$lang_no:'1')."",PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail");
            $found = $query->num_rows;
            $result = array();
            if($found>0){
                $result = $query->row['lang_name'];
            }
            return $result;
        }
    }
    public function getListLocation(){
        global $lang_no;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","cat = 3 and sl_language_detail.type=1 and language_id=".(!empty($lang_no)?$lang_no:'1')."  and del<>1 and hide<>1",PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getListCategory(){
        global $lang_no;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","cat = 4 and sl_language_detail.type=1 and language_id=".(!empty($lang_no)?$lang_no:'1')." and del<>1 and hide<>1",PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getListType(){
        global $lang_no;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","cat = 5 and sl_language_detail.type=1 and language_id=".(!empty($lang_no)?$lang_no:'1')."  and del<>1 and hide<>1",PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getListKind(){
        global $lang_no;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","cat = 6 and sl_language_detail.type=1 and language_id=".(!empty($lang_no)?$lang_no:'1')."  and del<>1 and hide<>1",PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getListOptional(){
        // echo "content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id WHERE cat = 7 and sl_language_detail.type=1 and language_id=".$lang_no."  and del<>1 and hide<>1 GROUP BY sl_language_detail.name order by sl_content.seq ASC";
        // echo 'PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail';
        global $lang_no;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","cat = 7 and sl_language_detail.type=1 and language_id=".(!empty($lang_no)?$lang_no:'1')."  and del<>1 and hide<>1 GROUP BY sl_content.id order by sl_content.seq ASC",PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getListInfrastructure(){
        global $lang_no;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","cat = 13 and sl_language_detail.type=1 and language_id=".(!empty($lang_no)?$lang_no:'1')."  and del<>1  and hide<>1",PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getBannerHome(){
        $query = $this->db->getdata("content_pic",'pid=57 order by seq ASC');
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function currency($currency){
        global $lan;
        $result = '';
        $result = "".number_format($currency)." <small>".$lan['baht']."</small>";
        return $result;
    }
    function get($val=""){
        $result = '';
        if(isset($_GET[$val])){
            $result = $_GET[$val];
        }
        return $result;
    }
    public function getpara($unset=NULL){
        $array_g = $_GET;
        $unset = explode(",",$unset);
        if($unset!=NULL){
            foreach($unset as $val){
                unset($array_g[$val]);
            }
        }
        $i=1;
        $para = '&';
        foreach($array_g as $key=>$val){
            $para .= "$key=$val";
            if($i!=count($array_g)){$para .= "&";}
            $i++;
        }
        return $para;
    }
    public function getPagination($page_total=0,$link,$active=1){
        $result = array();
        $para = $this->getpara('route,page');
        $link = $link.$para;
        if($page_total>0){
            for ($i=1; $i <= $page_total; $i++) { 
                $result_active = "";
                if($active==$i){
                    $result_active = "active";
                }
                $result['page'][] = array(
                    'href'=>$link."&page=".$i,
                    'page'=>$i,
                    'active'=>$result_active
                );
            }
            if($active>1){
                $result['previous'] = array('href'=>$link."&page=".($active-1));
            }
            if($active<$page_total){
                $result['next'] = array('href'=>$link."&page=".($active+1));
            }
        }
        return $result;
    }
    public function getProperties($limit=99,$page=0,$json=false,$agru=array()){
        global $lang_no;
        $sql_page = 0;
        $sql_limit = 0;
        if($limit>0){
            $sql_limit = $limit;
        }
        if($page>0){
            $page = $page-1;
            $sql_page = ($page*$sql_limit);
        }
        $where = "";
        if (get('transport_id')) {
            $result_transport = $this->db->getdata('take_transport','transport_id = '.get('transport_id'));
            $content_id_arr = '';
            foreach ($result_transport->rows as $key => $value) {
                $content_id_arr .= $value['content_id'].',';
            }
            $content_id_arr = substr($content_id_arr, 0,-1);
            if (!empty($content_id_arr)) {
                $where .= " and sl_content.id IN (".$content_id_arr.")";
            }
        }else{
            if(get('type') or !empty($agru['type'])){
                $type = get('type');
                if(!empty($agru['type'])){
                    $type = $agru['type'];
                }
                $where .= " and sl_content.`type`='".$type."'";
            }
            if(get('location')){
                $where .= " and sl_content.`location`='".get('location')."'";
            }
            if(get('category') or !empty($agru['category'])){
                $category = get('category');
                if(!empty($agru['category'])){
                    $category = $agru['category'];
                }
                $where .= " and sl_content.`category`='".$category."'";
            }
            if(get('kind')){
                $where .= " and sl_content.`kind`='".get('kind')."'";
            }
            if(get('room')){
                $where .= " and sl_content.`room`='".get('room')."'";
            }
            if(get('bath')){
                $where .= " and sl_content.`bath`='".get('bath')."'";
            }
            if(get('amphur')){
                $where .= " and sl_content.`AMPHUR_ID`='".get('amphur')."'";
            }
            if(get('province')){
                $where .= " and sl_content.`PROVINCE_ID`='".get('province')."'";
            }
            if(get('area')){ 
                $area = explode(' - ',get('area'));
                $areaMin = $area[0];
                $areaMax = $area[1];
                $where .= " and (sl_content.`area` between ".$areaMin." and ".$areaMax.")";
            }
            if(get('price')){ 
                $price = explode(' - ',get('price'));
                $priceMin = $price[0];
                $priceMax = $price[1];
                $where .= " and (sl_content.`price` between ".str_replace(',', '', $priceMin)." and ".str_replace(',', '', $priceMax).")";
            }
            if(get('optional')){
                foreach(get('optional') as $value){
                    $where .= " and sl_content.`optional` like '%".$value."%'";
                }
            }
            if (get('name')) {
                $where .= " and sl_language_detail.`name` like '%".urldecode(get('name'))."%'";
            }
            if (get('codecondo')) {
                $codecondo_replace = str_replace("TSC", '', get('codecondo'));
                $where .= " and (sl_content.`user_id` like '%".$codecondo_replace."%' or sl_content.`codecondo` like '%".$codecondo_replace."%') ";
            }
            // $where = urldecode($where);
            // echo $where;
            if (post('sort-by')=='name') {
                $where .= "  order by sl_language_detail.name DESC ";
            } else if (post('sort-by')=='time') {
                $where .= "  order by sl_content.time DESC ";
            } else {
                $where .= " order by seq DESC ";
            }
        }
        //echo "content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id WHERE cat = 1 and sl_language_detail.type=1 and language_id=".$lang_no." ".$where." limit ".(int)$sql_page.",".(int)$limit;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","cat = 1 and sl_language_detail.type=1 and language_id=".$lang_no." and del<>1 and hide<>1".$where." limit ".(int)$sql_page.",".(int)$limit,PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            if($json===true){
                
                foreach ($query->rows as $key => $value) {
                    $point = "";
                    $point = ($agru['id']==$value['id']?'1':'0');
                    $result[] = array(
                        $value['lang_name'],
                        $value['map_longitude'],
                        $value['map_latitude'],
                        $value['map_z'],
                        $value['type'],
                        number_format($value['price']),
                        $value['like'],
                        $value['picture1'],
                        $value['location'],
                        $value['bedroom'],
                        $value['bath'],
                        $value['garage'],
                        $value['gym'],
                        $value['area'],
                        $value['id'],
                        $point
                    );
                }
                $result = json_encode($result);
            }else{
                $result = $query;
            }
        }
        return $result;
    }
    public function getPropertiesSuggest($limit=99,$page=0,$json=false,$agru=array()){
        global $lang_no;
        $sql_page = 0;
        $sql_limit = 0;
        if($limit>0){
            $sql_limit = $limit;
        }
        if($page>0){
            $page = $page-1;
            $sql_page = ($page*$sql_limit);
        }
        $where = "";
        if(get('type') or !empty($agru['type'])){
            $type = get('type');
            if(!empty($agru['type'])){
                $type = $agru['type'];
            }
            $where .= " and sl_content.`type`='".$type."'";
        }
        if(get('location')){
            $where .= " and sl_content.`location`='".get('location')."'";
        }
        if(get('category') or !empty($agru['category'])){
            $category = get('category');
            if(!empty($agru['category'])){
                $category = $agru['category'];
            }
            $where .= " and sl_content.`category`='".$category."'";
        }
        if(get('kind')){
            $where .= " and sl_content.`kind`='".get('kind')."'";
        }
        if(get('room')){
            $where .= " and sl_content.`room`='".get('room')."'";
        }
        if(get('bath')){
            $where .= " and sl_content.`bath`='".get('bath')."'";
        }
        if(get('area')){ 
            $area = explode(' - ',get('area'));
            $areaMin = $area[0];
            $areaMax = $area[1];
            $where .= " and (sl_content.`area` between ".$areaMin." and ".$areaMax.")";
        }
        if(get('price')){ 
            $price = explode(' - ',get('price'));
            $priceMin = $price[0];
            $priceMax = $price[1];
            $where .= " and (sl_content.`price` between ".str_replace(',', '', $priceMin)." and ".str_replace(',', '', $priceMax).")";
        }
        if(get('optional')){
            foreach(get('optional') as $value){
                $where .= " and sl_content.`optional` like '%".$value."%'";
            }
        }
        if (get('name')) {
            $where .= " and sl_language_detail.`name` like '%".get('name')."%'";
        }
        if (get('codecondo')) {
            $where .= " and sl_content.`codecondo` like '%".get('codecondo')."%'";
        }
        if (get('transport_id')) {
            $result_transport = $this->db->getdata('take_transport','transport_id = '.get('transport_id'));
            $content_id_arr = '';
            foreach ($result_transport->rows as $key => $value) {
                $content_id_arr .= $value['content_id'].',';
            }
            $content_id_arr = substr($content_id_arr, 0,-1);
            if (!empty($content_id_arr)) {
                $where .= " and sl_content.id IN (".$content_id_arr.")";
            }
        }
        if (post('sort-by')=='name') {
            $where .= "  order by sl_language_detail.name DESC ";
        } else if (post('sort-by')=='time') {
            $where .= "  order by sl_content.time DESC ";
        } else {
            $where .= " order by seq DESC ";
        }
        //echo "content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id WHERE cat = 1 and sl_language_detail.type=1 and language_id=".$lang_no." ".$where." limit ".(int)$sql_page.",".(int)$limit;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","cat = 1 and sl_language_detail.type=1 and language_id=".$lang_no." and del<>1 and hide<>1 and suggest=1".$where." limit ".(int)$sql_page.",".(int)$limit,PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            if($json===true){
                
                foreach ($query->rows as $key => $value) {
                    $point = "";
                    $point = ($agru['id']==$value['id']?'1':'0');
                    $result[] = array(
                        $value['lang_name'],
                        $value['map_longitude'],
                        $value['map_latitude'],
                        $value['map_z'],
                        $value['type'],
                        number_format($value['price']),
                        $value['like'],
                        $value['picture1'],
                        $value['location'],
                        $value['bedroom'],
                        $value['bath'],
                        $value['garage'],
                        $value['gym'],
                        $value['area'],
                        $value['id'],
                        $point
                    );
                }
                $result = json_encode($result);
            }else{
                $result = $query;
            }
        }
        return $result;
    }
    public function getTotalProperties(){
        global $lang_no;
        $sql_page = 0;
        $sql_limit = 0;
        if($limit>0){
            $sql_limit = $limit;
        }
        if($page>0){
            $page = $page-1;
            $sql_page = ($page*$sql_limit);
        }
        $where = "";
        if(get('type')){
            $where .= " and sl_content.`type`='".get('type')."'";
        }
        if(get('location')){
            $where .= " and sl_content.`location`='".get('location')."'";
        }
        if(get('category')){
            $where .= " and sl_content.`category`='".get('category')."'";
        }
        if(get('kind')){
            $where .= " and sl_content.`kind`='".get('kind')."'";
        }
        if(get('room')){
            $where .= " and sl_content.`room`='".get('room')."'";
        }
        if(get('bath')){
            $where .= " and sl_content.`bath`='".get('bath')."'";
        }
        if(get('area')){ 
            $area = explode(' - ',get('area'));
            $areaMin = $area[0];
            $areaMax = $area[1];
            $where .= " and (sl_content.`area` between ".$areaMin." and ".$areaMax.")";
        }
        if(get('price')){ 
            $price = explode(' - ',get('price'));
            $priceMin = $price[0];
            $priceMax = $price[1];
            $where .= " and (sl_content.`price` between ".str_replace(',', '', $priceMin)." and ".str_replace(',', '', $priceMax).")";
        }
        if(get('optional')){
            foreach(get('optional') as $value){
                $where .= " and sl_content.`optional` like '%".$value."%'";
            }
        }
        if(get('amphur')){
            $where .= " and sl_content.`AMPHUR_ID`='".get('amphur')."'";
        }
        if(get('province')){
            $where .= " and sl_content.`PROVINCE_ID`='".get('province')."'";
        }

        if (get('name')) {
            $where .= " and sl_language_detail.`name` like '%".get('name')."%'";
        }
        if (get('codecondo')) {
            $where .= " and sl_content.`codecondo` like '%".get('codecondo')."%'";
        }
        if (get('transport_id')) {
            $result_transport = $this->db->getdata('take_transport','transport_id = '.get('transport_id'));
            $content_id_arr = '';
            foreach ($result_transport->rows as $key => $value) {
                $content_id_arr .= $value['content_id'].',';
            }
            $content_id_arr = substr($content_id_arr, 0,-1);
            if (!empty($content_id_arr)) {
                $where .= " and sl_content.id IN (".$content_id_arr.")";
            }
        }
        if (post('sort-by')=='name') {
            $where .= "  order by sl_language_detail.name DESC ";
        } else if (post('sort-by')=='time') {
            $where .= "  order by sl_content.time DESC ";
        } else {
            $where .= " order by seq DESC ";
        }
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","cat = 1 and sl_language_detail.type=1 and language_id=".$lang_no."  and del<>1 and hide<>1".$where,PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail");
        $found = $query->num_rows;
        return $found;
    }
    public function getPropertiedetail($id){
        global $lang_no;
        $result = array($id);
         $query = $this->db->getdata("content LEFT JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id LEFT JOIN sl_user ON sl_content.user_id = sl_user.user_id","sl_language_detail.type=1 and language_id=".(!empty($lang_no)?$lang_no:'1')." and del<>1 and hide<>1 and ".PREFIX."content.id=".(int)$id,PREFIX."content.*,".PREFIX."user.name AS user_name,".PREFIX."user.lname AS user_lname,".PREFIX."user.phone AS user_phone,".PREFIX."user.line_id AS user_line,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail,sl_user.user_code");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        // print_r($result);
        // exit();
        return $result;
    }
    public function getType($id){
        $result = "";
        if($id==8){
            $result = '<span class="label hot">buy</span>';
        }elseif($id==9){
            $result = '<span class="label sale">sale</span>';
        }elseif($id==10){
            $result = '<span class="label rent">rent</span>';
        }elseif($id==19){
            $result = '<span class="label hot">hot</span>';
        }
        return $result;
    }
    public function getCountKind($kind){
        $query = $this->db->getdata("content",'kind='.(int)$kind,'count(kind) as count_kind');
        $found = $query->num_rows;
        $result = 0;
        if($found>0){
            $result = $query->row['count_kind'];
        }
        return $result;
    }
    public function getBlogs($limit="0,2"){
        global $lang_no;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","cat = 10 and sl_language_detail.type=1 and language_id=".$lang_no." and sl_content.del=0 order by ".PREFIX."content.id DESC limit ".$limit,PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail,".PREFIX."language_detail.`detail_2` as detail_2");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getBlog($id){
        global $lang_no;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id",PREFIX."content.id = ".$id." and sl_language_detail.type=1 and language_id=".$lang_no." ",PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail,".PREFIX."language_detail.`detail_2` as detail_2");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getPerson(){
        global $lang_no;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id","cat = 11 and sl_language_detail.type=1 and language_id=".$lang_no."",PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getAbout($id){
        global $lang_no;
        $query = $this->db->getdata("content INNER JOIN sl_language_detail ON sl_content.id = sl_language_detail.ref_id",PREFIX."content.id = ".$id." and sl_language_detail.type=1 and language_id=".$lang_no."",PREFIX."content.*,".PREFIX."language_detail.`name` as lang_name,".PREFIX."content.id as id,".PREFIX."language_detail.`detail` as detail,".PREFIX."language_detail.`detail_2` as detail_2,".PREFIX."language_detail.`detail_3` as detail_3");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getPartner(){
        $query = $this->db->getdata("content_pic",'pid=40');
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getAllimg($id,$type=''){
        //  and type='".$type."'  limit 0,3
        $query = $this->db->getdata("content_pic",'pid='.(int)$id."  order by seq asc");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getTypeImage($id,$type=0){
        $query = $this->db->getdata("content_pic",'pid='.(int)$id." and type=".$type." order by seq asc limit 0,3");
        $found = $query->num_rows;
        $result = array();
        $result['num_rows'] = $found;
        if($found>0){
            $result['data'] = $query;
        }
        return $result;
    }
    public function getBts($transport_group="bts"){
        $query = $this->db->getdata("transport","transport_group='".$transport_group."'");
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getBtsCheck($content_id){
        $query = $this->db->getdata("take_transport Where content_id = ".(int)$content_id);
        $found = $query->num_rows;
        $result = array();
        if($found>0){
            $result = $query;
        }
        return $result;
    }
    public function getBusCheck($content_id,$name_bus){
        //take_transport INNER JOIN sl_transport ON sl_take_transport.transport_id = sl_transport.transport_id
        $query = $this->db->getdata("take_transport INNER JOIN sl_transport ON sl_take_transport.transport_id = sl_transport.transport_id Where content_id = ".(int)$content_id." and sl_transport.transport_group='".$name_bus."'");
        $found = $query->num_rows;
        // echo $found;exit();
        return $found;
    }
}