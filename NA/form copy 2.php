<?php
	
	
//$this->sHTML .= '<option value="'.$optionValue.'"'.($selected !== null && $selected == $optionValue?' selected="selected"':'').'>'.$optionName.'</option>';

	
	class Form{
		private $sHTML;
		private $aData;
		private $aFiles;
		private $aErrors;
		private $sCustomError;
		//private $sPmatch;

		public function __construct(){
			$this->sHTML = '<form action="" method="post" enctype="multipart/form-data">';
			$this->aData = array();
			$this->aFiles = array();
			$this->aErrors = array();
			
// 			$this->sCustomError = "";
		}


		// form building method
		public function makeTextInput($sControlLabel,$sControlName,$placeholder){
			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<div class="error">'.$this->aErrors[$sControlName].'</div>';
			}
			
			$this->sHTML .= '<div class="form-group">';
			$this->sHTML .= '<label for="'.$sControlName.'">'.$sControlLabel.'</label>';
			$this->sHTML .=	'<input type="text" class="form-control" id="'.$sControlName.'" name="'.$sControlName.'" placeholder="'.$placeholder.'" value="'.$sControlData.'" />';
			$this->sHTML .=	$sError;
			$this->sHTML .= $sCustomError;
			$this->sHTML .= '</div>';
		}		
		public function makeSelectInput($sControlLabel,$sControlName,$aOptions){
			
			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<p>'.$this->aErrors[$sControlName].'</p>';
			}
			$this->sHTML .= '<div class="form-group">';
			$this->sHTML .= '<label for="'.$sControlName.'">'.$sControlLabel.'</label>';
			//$this->sHTML .=	'<input type="text" class="form-control" id="'.$sControlName.'" name="'.$sControlName.'" value="'.$sControlData.'" />';
			
			$this->sHTML .=	'<select name="'.$sControlName.'" class="formValue">';
			
			foreach ( $aOptions as $optionValue=>$optionName ) {
				if ($_POST['StyleID'] == $optionValue) {
					$selected = 'selected="selected" ';
				} else {
					$selected = '';
				}
			  $this->sHTML .= '<option value="'.$optionValue.'" name="'.$optionName.'" '.$selected.'>'.$optionName.'</option>';
			 }

			$this->sHTML .=	'</select>';
			
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			$this->sHTML .= $sCustomError;
		}
		
		public function makeCheckboxInput($sControlLabel,$sControlName,$sPossibleValue){
			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<p>'.$this->aErrors[$sControlName].'</p>';
			}

			$this->sHTML .= '<div class="form-group">';
			$this->sHTML .= '<label for="'.$sControlName.'">'.$sControlLabel.'</label>';
			
			$this->sHTML .= '<div class="singleCheckbox">';
			if($sControlData == false){
				$this->sHTML .=	'<input type="checkbox" class="checkbox" id="'.$sControlName.'" name="'.$sControlName.'" value="'.$sPossibleValue.'"/><label>Active/Visible</label>';
			}else{
				$this->sHTML .=	'<input type="checkbox" class="checkbox" id="'.$sControlName.'" name="'.$sControlName.'"  checked value="'.$sPossibleValue.'"/><label>Active/Visible</label>';
			}		
			$this->sHTML .= '</div>';
			
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			$this->sHTML .= $sCustomError;
		}
		
		public function makeCheckboxInputSet($sControlLabel,$sControlName,$sPossibleValue,$aOptions){

			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<p>'.$this->aErrors[$sControlName].'</p>';
			}

			$this->sHTML .= '<div class="form-group">';
			$this->sHTML .= '<div>//under construction//</div>';
			$this->sHTML .= '<label for="'.$sControlName.'">'.$sControlLabel.'</label>';
			
/*
			if($sControlData == false){
				$this->sHTML .=	'<input type="checkbox" class="checkbox" id="'.$sControlName.'" name="'.$sControlName.'" value="'.$sPossibleValue.'"/>';
			}else{
				$this->sHTML .=	'<input type="checkbox" class="checkbox" id="'.$sControlName.'" name="'.$sControlName.'"  checked value="'.$sPossibleValue.'/>';
			}	
*/	
		
			foreach ( $aOptions as $optionValue=>$optionName ) {
			  $this->sHTML .= '<li><input type="checkbox" class="checkbox" id="'.$sControlName.'" name="'.array_push($_POST['locations'],$optionValue).'" value="'.$optionValue.'"/><label for="'.$sControlName.'">'.$optionName.'</label></li>';
			 }
		
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			$this->sHTML .= $sCustomError;
		}
		
		
		public function makeFileInput($sControlLabel,$sControlName){
			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<p>'.$this->aErrors[$sControlName].'</p>';
			}
			
			$this->sHTML .= '<div class="form-group">';
			$this->sHTML .= '<label for="'.$sControlName.'">'.$sControlLabel.'</label>';
			$this->sHTML .=	'<input type="file" class="form-control" id="'.$sControlName.'" name="'.$sControlName.'" value="'.$sControlData.'"/>';
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			$this->sHTML .= $sCustomError;
		}
		
		public function makeLoginInput($sControlLabel,$sControlName){
			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<p>'.$this->aErrors[$sControlName].'</p>';
			}
			
			$this->sHTML .= '<div class="form-group">';
			$this->sHTML .=	'<input type="text" class="form-control" id="'.$sControlName.'" name="'.$sControlName.'"  placeholder="'.$sControlLabel.'" value="'.$sControlData.'"/>';
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			$this->sHTML .= $sCustomError;

		}
		public function makePasswordInput($sControlLabel,$sControlName){
			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<p>'.$this->aErrors[$sControlName].'</p>';
			}
			
			$this->sHTML .= '<div class="form-group">';
			$this->sHTML .=	'<input type="password" class="form-control" id="'.$sControlName.'" name="'.$sControlName.'"  placeholder="'.$sControlLabel.'" value="'.$sControlData.'"/>';
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			$this->sHTML .= $sCustomError;
		}
		
		
		public function makeTextArea($sControlLabel,$sControlName,$placeholder){
			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<p>'.$this->aErrors[$sControlName].'</p>';
			}
			$this->sHTML .= '<div class="form-group">';
			$this->sHTML .= "\t\t\t".'<label for="'.$sControlName.'">'.$sControlLabel.'</label>'."\n";
			$this->sHTML .=	"\t\t\t".'<textarea id="'.$sControlName.'" name="'.$sControlName.'" placeholder="'.$placeholder.'" value="" class="form-control" rows="3"/>'.$sControlData.'</textarea>'."\n\n";
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
		}
		
		public function makeSubmit($sControlLabel,$sControlName){
			
			$this->sHTML .= '<div class="text-center">';
			$this->sHTML .= "\t\t\t".'<button type="submit" name="'.$sControlName.'" class="btn btn-primary" value="'.$sControlLabel.'"/>'.$sControlLabel.'</button>'."\n";
			$this->sHTML .= "</div>";
		}
		
/*
		public function makeLoginSubmit($sControlLabel){
			
			$this->sHTML .= '<div class="text-center">';
			$this->sHTML .= "\t\t\t".'<button type="submit" name="loginsubmit" class="btn btn-primary value="'.$sControlLabel.'"/><i class="fa fa-user-md"></i> '.$sControlLabel.'</button>'."\n";
			$this->sHTML .= "</div>";
		}
*/
		
		
		public function moveFile($sControlName,$sNewName){
			$sNewPath = dirname(__FILE__).'/../assets/images/'.$sNewName;
			move_uploaded_file($this->aFiles[$sControlName]['tmp_name'], $sNewPath);
			
			//echo $this->aFiles[$sControlName]['tmp_name'];
/*
			echo '<br>';
			echo $sNewPath;
			exit;
*/
	
		}
		
		// form checking methods
		public function checkRequired($sControlName){


						
			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = trim($this->aData[$sControlName]);
			}
			
			if(strlen($sControlData)==0){
				// report error
				$this->aErrors[$sControlName] = "Must not be empty";
			}
		}
		
		public function raiseCustomError($sControlName,$sMessage){
			$this->aErrors[$sControlName] = $sMessage;
		}
		
		public function compareFields($sControlName1,$sControlName2){
			
			$sControlData1 = $this->aData[$sControlName1];
			$sControlData2 = $this->aData[$sControlName2];

			if($sControlData1 != $sControlData2){
				// report error
				$this->sCustomError[$sControlName2] = "Passwords do not match";
			}
		}
		
		// getter method
		public function __get($var){
			switch ($var) {
				case 'html';
					return $this->sHTML."\t\t"."</form>";
					break;
				case 'valid';
					if(count($this->aErrors)==0){
						return true;
					}
					return false;
					break;
				default;
					die($var . "is not accessible");
					break;
			}
		}
		
		// setter method
		public function __set($var,$value){
			switch ($var) {
			case 'data';
				$this->aData = $value;
				break;
			case 'files';
				$this->aFiles = $value;
				break;
			default;
				die($var . "is not accessible");
				break;
			}
		}
		
	}


?>