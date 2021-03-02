<?php
	// append by pingpong 590101
	class Utility {
		const EnDeKey = "ZnNvZnRwcm8="; // Base64 -> "fsoftpro"
		
		// Check Param Correct
		const paramCorrect = "CHIIWII";
		
	
		// Method
		const methodPost = "POST";
		const methodGet = "GET";
		
		// language
		const langTH = "TH";
		const langEN = "EN";
		
		// MsgAlert level > $msgLv
		const msgSuccess = "S";
		const msgInfo = "I";
		const msgWarning = "W";
		const msgDanger = "D";
		
		// Sex Const
		const cSexMale = "M";
		const cSexFemale = "F";
		
		// Default Format DATE : "cFormatDateFrom" need same Format in "Datepicker"
		const cFormatDateFrom = "m/d/Y";
		const cFormatDateTo = "m/d/Y"; 

		public function GetRequestMethod(){
			// Get Current Request Method > Compare with const(methodPost,methodGet)
			return $_SERVER["REQUEST_METHOD"];
		}
			
		public function AppDateFormat($dateStr,$formatDateTime = "d-m-Y H:i:s"){
			if(!$this->IsSetVal($dateStr))
			{
				$dateStr = date($formatDateTime);
			}
			return date($formatDateTime,strtotime($dateStr));
		}
			
		public function GotoPage($url){
			header("location: ".$url);
			exit();
		}
		
		public function NewProfileImgName($OldFileName){
			return $this->CreateNewFileName(UPLOAD_PROFILE_PATH."PF",$_SESSION['user_id']."U",$OldFileName);
		}
		
		public function NewQuestionImgName($OldFileName){
			return $this->CreateNewFileName("QS",$_SESSION['user_id']."U",$OldFileName);
		}
		
		public function CreateNewFileName($StartWithWord,$UniqueKey,$OldFileName){
			if($this->IsSetVal($StartWithWord) && $this->IsSetVal($UniqueKey) && $this->IsSetVal($OldFileName)){
				return $StartWithWord."_".$UniqueKey."_".$OldFileName;
			}else{
				return "";
			}
		}
		
		public function GetFileName($fileUploadName){
			return basename($_FILES[$fileUploadName]['name']);
		}
		
		public function GetFileType($fileUploadName){
			return $_FILES[$fileUploadName]['type'];
		}
		
		public function GetFileSize($fileUploadName){
			return $_FILES[$fileUploadName]['size'];
		}
		
		public function GetFileTmpName($fileUploadName,$GetContents = false){
			if($GetContents){
				return file_get_contents($_FILES[$fileUploadName]['tmp_name']);
			}else{
				return $_FILES[$fileUploadName]['tmp_name'];
			}		
		}
		
		public function GetFileExtensionFromElement($fileUploadName){
			if($this->IsSetVal($folder)){
				return pathinfo($this->GetFileName($fileUploadName), PATHINFO_EXTENSION);
			}else{
				return "";
			}
		}
		
		public function GetFileExtensionFromText($fileName){
			if($this->IsSetVal($folder)){
				return pathinfo(basename($fileName), PATHINFO_EXTENSION);
			}else{
				return "";
			}
		}
		
		public function IsPathExist($folder)
		{ //folder : support path with filename
			if($this->IsSetVal($folder)){
				$path = realpath(dirname($folder)); // check path only
				return ($path !== false AND is_dir($path)) ? $path : false;
			}else{
				return false;
			}
		}
		
		public function IsFileExist($fileNameWithPath){
			if($this->IsSetVal($fileNameWithPath)){
				return file_exists($fileNameWithPath);
			}else{
				return false;
			}
		}
		
		public function SaveFileFromElement($fileNameWithPathTarget,$fileUploadName){
			if($this->IsSetVal($fileNameWithPathTarget) && $this->IsSetVal($fileUploadName)){
				file_put_contents($fileNameWithPathTarget,$this->GetFileTmpName($fileUploadName,true));
			}
		}
		
		public function SaveFileFromContents($fileNameWithPathTarget,$contentsStr){
			if($this->IsSetVal($fileNameWithPathTarget) && $this->IsSetVal($contentsStr)){
				file_put_contents($fileNameWithPathTarget,$contentsStr);
				return true;
			}else{
				return false;
			}
		}
		
		public function DeleteFile($fileNameWithPath){
			if($this->IsSetVal($fileNameWithPath)){
				return unlink($fileNameWithPath);
			}else{
				return false;
			}
		}
		
		public function GetSexText($sexCode,$language = "TH"){
			$result = "";
			if($this->IsSetVal($sexCode)){
				$sexCode = trim($sexCode);
				switch ($language){ 
					case self::langTH:
						switch ($sexCode){ 
							case self::cSexMale:
								$result = "ชาย";
							break;
							case self::cSexFemale:
								$result = "หญิง";
							break;
						}
					break;
					case self::langEN:
						switch ($sexCode){ 
							case self::cSexMale:
								$result = "Male";
							break;
							case self::cSexFemale:
								$result = "Female";
							break;
						}
					break;
					default:
						switch ($sexCode){ 
							case self::cSexMale:
								$result = "ชาย";
							break;
							case self::cSexFemale:
								$result = "หญิง";
							break;
						}
				}
			}
			return $result;
		}
		
		public function IsSetVal($val){
			$result = false;
			if (is_string($val)){ $val = trim($val); }
			if(isset($val) && !empty($val)){
				$result = true;
			}	
			return $result;
		}
		
		public function formatAmt($amt,$digitPoint = 2){
			$result = 0.00;
			if(isset($amt) && !empty($amt)){
				if(is_numeric($amt)){
					$result = number_format($amt,$digitPoint);
				}
			}
			return $result;
		}
		
		public function formatDate($dateStr,$format){
			$result = "";
			$formatFrom = self::cFormatDateFrom;
			if($this->IsSetVal($dateStr)){
				$dateStr = trim($dateStr);
				if(!$this->IsSetVal($format)){
					$format = self::cFormatDateTo;
				}
				$newDate = DateTime::createFromFormat($formatFrom, $dateStr);
				$result = $newDate->format($format);
			}
			return $result;
		}

		public function encodeAction($input){
			return $this->encodeWithKey(strtoupper($input));
		}
		
		public function decodeAction($input){
			return strtoupper($this->decodeWithKey($input));
		}
		
		public function encodeWithKey($input){
			$encodeKey = self::EnDeKey;
			return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($encodeKey), $input, MCRYPT_MODE_CBC, md5(md5($encodeKey))));
		}
		
		public function decodeWithKey($input){
			$decodeKey = self::EnDeKey;
			return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($decodeKey), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($decodeKey))), "\0");
		}
			
		public function encodeParam($input)
		{
			return strtr(base64_encode($input), '+/=', '-_,');
		}
		public function decodeParam($input)
		{
			return base64_decode(strtr($input, '-_,', '+/='));
		}

		public function GenUniqueNo($startWithStr,$numberStr,$allDigit = ""){
			// Gen : year , month
			$dmPrefix = "".date("Y").date('m')."";
			$result = "";
			if(!isset($numberStr) || empty($numberStr)){ $numberStr = "1"; }
			if(!isset($allDigit) || empty($allDigit) || !is_numeric($allDigit)){ $allDigit = 5; }
			if(strlen($numberStr) >= $allDigit){ $allDigit = strlen($numberStr);}
			$result = $startWithStr.$dmPrefix."-".str_pad($numberStr, $allDigit, "0", STR_PAD_LEFT);
			return $result;
		}
		
		public function MsgAlert($msgWords,$selector,$msgLv){
			if($this->IsSetVal($msgWords)){
				$color = "";
				switch ($msgLv){ // bootstrap Class Name
					case self::msgSuccess:
						$color = "color:green;";
					break;
					case self::msgDanger:
						$color = "color:red;";
					break;
					default:
						$color = "color:red;";
				}
				if (!$this->IsSetVal($selector)){ $selector = "#dvAlert"; }
				return '<span onclick="ClearMsg(&#39;'.$selector.'&#39;)" style="'.$color.'font-weight: 100;font-size: smaller;padding-bottom:20px;">'.$msgWords.'</span>';
			}
		}
	}
	
	class DBUtility extends Utility{
		// FieldType 
		const fTypeText = "1";
		const fTypeNumeric = "2";
		const fTypeDate = "3";
		const fTypeDateTime = "4";
		const fTypeDateStr = "5";
		
		// Operator for split
		const oprSeparate = ",";
		
		// Operator or Symbol for Criteria SQL
		const oprEqual = "=";
		const oprLike = "LIKE";
		const oprIN = "IN"; 
		const oprGreaterThan = ">"; 
		const oprLessThan = "<"; 
		const oprGreaterEqual = ">="; 
		const oprLessEqual = "<="; 
		
		// Start with Conjuction when criteria is not empty
		const sConjAnd = "AND";
		const sConjOr = "OR";
		
		const userSessionIdFieldName = "user_id";
		const dateAddFieldName = "date_add";
		const dateUpdatedFieldName = "date_updated";
		const userAddFieldName = "user_id_add";
		const userUpdatedFieldName = "user_id_updated";
		
		public function StampAddUpdated(&$data,$IsSetUser = true){
			$this->StampAdd($data,$IsSetUser);
			$this->StampUpdated($data,$IsSetUser);
		}
		
		public function StampAdd(&$data,$IsSetUser = true){
			$data[self::dateAddFieldName] = $this->GetDBCurrentDate();
			if($IsSetUser){
				if(!$this->IsSetVal($data[self::userAddFieldName]) && $this->IsSetVal($_SESSION[self::userSessionIdFieldName])){
					$data[self::userAddFieldName] = $_SESSION[self::userSessionIdFieldName];
				}
			}
		}
		
		public function StampUpdated(&$data,$IsSetUser = true){
			$data[self::dateUpdatedFieldName] = $this->GetDBCurrentDate(); 
			if($IsSetUser){
				if(!$this->IsSetVal($data[self::userUpdatedFieldName]) && $this->IsSetVal($_SESSION[self::userSessionIdFieldName])){
					$data[self::userUpdatedFieldName] = $_SESSION[self::userSessionIdFieldName];
				}
			}
		}
		
		public function GetDBCurrentDate(){
			return date('Y-m-d H:i:s');
		}
		
		public function AddCriteriaSQL(&$criteriaSQL,$dbFieldName,$dbOperator,$dbFieldValue,$dbFieldType,$isNegation = false,$StartConjunction = "AND"){
			// $dbFieldName Must not null and not empty
			// $dbFieldValue Must not empty  but  can Null  >>   if want Criteria = ''   can send   '' in $dbFieldValue
			
			//  Ex.  if $dbOperator = "IN" >   $dbFieldValue can send multiple value like 1,2,3,4,5,6
			//  Ex.  if $dbOperator = "LIKE" >  percent(%) not Necessary or as want
			$result = "";
			if((!is_null($dbFieldName) && $dbFieldName != "") && ($dbFieldValue != "")) {
				if(!is_null($dbFieldValue)){			
					if (is_null($dbOperator) or $dbOperator == ""){
						$dbOperator = "="; // Default Operator is Equal(=)
					}
					
					if (trim($dbFieldValue) != "''"){
						
						switch ($dbFieldType){ 
							case self::fTypeText:
								$dbFieldValue = "&lsquo;".$dbFieldValue."&rsquo;";
							break;
							case self::fTypeNumeric:
								$dbFieldValue = $dbFieldValue;
							break;
							case self::fTypeDate:
								$dbFieldName = "DATE(".$dbFieldName.")";
								$dbFieldValue = "STR_TO_DATE('".$dbFieldValue."', '%d-%m-%Y')";
							break;
							case self::fTypeDateTime:
								$dbFieldName = "DATE(".$dbFieldName.")";
								$dbFieldValue = "STR_TO_DATE('".$dbFieldValue."', '%d-%m-%Y %H:%i:%s')";
							break;
							case self::fTypeDateStr:
								// field store date(type varchar)  >> value must converted to strtotime before use this function
								$dbFieldName = "FROM_UNIXTIME(".$dbFieldName.")";
								$dbFieldValue = "&lsquo;".date("Y-m-d",$dbFieldValue)."&rsquo;";
							break;
							default:
								$dbFieldValue = "&lsquo;".$dbFieldValue."&rsquo;";
						}
					}
				}else{
					// If $dbFieldValue is null Operator must be Equal(=) only
					$dbOperator = "="; 
				}
				
				switch ($dbOperator){ 
					case self::oprIN:
						$dbFieldValue = str_replace("&lsquo;","'",$dbFieldValue);
						$dbFieldValue = str_replace("&rsquo;","'",$dbFieldValue);
						$result = $dbFieldName." ".$dbOperator." (".$dbFieldValue.")";
					break;
					case self::oprLike:
						$dbFieldValue = str_replace("&lsquo;","'%",$dbFieldValue);
						$dbFieldValue = str_replace("&rsquo;","%'",$dbFieldValue);
						$result = $dbFieldName." ".$dbOperator." ".$dbFieldValue;
					break;
					default:
						$dbFieldValue = str_replace("&lsquo;","'",$dbFieldValue);
						$dbFieldValue = str_replace("&rsquo;","'",$dbFieldValue);
						$result = $dbFieldName." ".$dbOperator." ".$dbFieldValue;
				}
				
				if($isNegation == true && (!is_null($result) && $result != "")){
					$result = " NOT ( ".$result." ) ";
				}

				if (!is_null($criteriaSQL) && $criteriaSQL != ""){
					$criteriaSQL = $criteriaSQL." ".$StartConjunction." ";
				}else{
					$criteriaSQL = "";
				}
				
				$criteriaSQL = $criteriaSQL.$result;
			}
		}
		
	}
?>