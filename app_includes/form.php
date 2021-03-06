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
			$this->sHTML = '<form id="checkin" action="app_submit_checkin.php" method="GET" class="ajax-submit"><div class="list-block"><ul>';
			$this->aData = array();
			$this->aFiles = array();
			$this->aErrors = array();
			$this->sCustomError = "";
		}

			// form building method
	public function makeSliderInput($sControlLabel,$sControlName,$placeholder){
						$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			$sCustomError = "";
			
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<div class="error">'.$this->aErrors[$sControlName].'</div>';
			}
			$this->sHTML .= '<li>
								<div class="item-title label">Rating <span id="rating">3</span></div>
								<div class="item-input">
									<div class="range-slider"><input id="ratingslider" type="range" min="1" max="5" step="0.25"></div>
								</div>
							</li>';
  }
  
  
			// form building method
		public function makeAutoInput($sControlLabel,$sControlName,$placeholder){
			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			$sCustomError = "";
			
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<div class="error">'.$this->aErrors[$sControlName].'</div>';
			}
			
			$this->sHTML .= '<li>';
			$this->sHTML .= '	<div class="item-content">';
			$this->sHTML .= '		<div class="item-inner">
								        <div class="item-title label">'.$sControlLabel.'</div>
								        <div class="item-input">
								          <input type="text" id="autocomplete-dropdown-all"  placeholder="'.$placeholder.'" value="'.$sControlData.'"/>
								          <input id="brewcheckinid" type="hidden" name="'.$sControlName.'" value="">
								        </div>
								    </div>
								</div>
							</li>';
		}
	
	
	
	
		// form building method
		public function makeTextInput($sControlLabel,$sControlName,$placeholder){
			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			$sCustomError = "";
			
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<div class="error">'.$this->aErrors[$sControlName].'</div>';
			}
			
			$this->sHTML .= '<li>
								<div class="item-content">
							      <div class="item-inner"> 
							        <div class="item-title label">'.$sControlLabel.'</div>
							        <div class="item-input">
							          <input type="text" id="'.$sControlName.'" name="'.$sControlName.'" placeholder="'.$placeholder.'" value="'.$sControlData.'"/>
							        </div>
							      </div>
							    </div>
						   </li>';

			
			
/*
			$this->sHTML .= '<div id="help-'.$sControlName.'" class="helptip"></div>';
			$this->sHTML .= '<div class="form-group">';
			$this->sHTML .= '<label for="'.$sControlName.'" id="label-'.$sControlName.'">'.$sControlLabel.'</label>';
			$this->sHTML .=	'<input type="text" class="form-control" id="'.$sControlName.'" name="'.$sControlName.'" placeholder="'.$placeholder.'" value="'.$sControlData.'" />';
			$this->sHTML .=	$sError;
			$this->sHTML .= $sCustomError;
			$this->sHTML .= '</div>';
*/
		}
		
		public function makeHiddenInput($sControlLabel,$sControlName,$placeholder){
			$this->sHTML .=	'<input type="hidden" name="redirect" value="" />';
		}
		public function makeHiddenSelectInput($sControlLabel,$sControlName,$placeholder){
			$sError = "";
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<div class="error">'.$this->aErrors[$sControlName].'</div>';
			}
			$this->sHTML .=	'<input type="hidden" name="'.$sControlName.'" value="" />';
			$this->sHTML .=	$sError;
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
			$this->sHTML .=	'<select name="'.$sControlName.'" class="formValue">';
			
			// Build select drop menu list
			foreach ( $aOptions as $optionValue=>$optionName ) {
				if ($sControlData  == $optionValue) {
					$selected = 'selected="selected" ';
				} else {
					$selected = '';
				}
			  $this->sHTML .= '<option value="'.$optionValue.'" name="'.$optionName.'" '.$selected.'>'.$optionName.'</option>';
			 }

			$this->sHTML .=	'</select>';
			
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			//$this->sHTML .= $sCustomError;
		}

public function makeSelectBreweryInput($sControlLabel,$sControlName,$aOptions){
			
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
			$this->sHTML .=	'<select name="'.$sControlName.'" class="formValue">';
			
			// Build select drop menu list
			foreach ( $aOptions as $optionValue=>$optionName ) {
				if ($sControlData  == $optionValue) {
					$selected = 'selected="selected" ';
				} else {
					$selected = '';
				}
			  $this->sHTML .= '<option value="'.$optionValue.'" name="'.$optionName.'" '.$selected.'>'.$optionName.'</option>';
			 }

			$this->sHTML .=	'</select>';
			$this->sHTML .=	 ' or <a class="btn btn-default" href="http://brewhound.nz/addbrewery.php">add brewery</a>';
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			//$this->sHTML .= $sCustomError;
		}

public function makeSelectStyleInput($sControlLabel,$sControlName,$aOptions){
			
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
			$this->sHTML .=	'<select name="'.$sControlName.'" class="formValue">';
			
			// Build select drop menu list
			foreach ( $aOptions as $optionValue=>$optionName ) {
				if ($sControlData  == $optionValue) {
					$selected = 'selected="selected" ';
				} else {
					$selected = '';
				}
			  $this->sHTML .= '<option value="'.$optionValue.'" name="'.$optionName.'" '.$selected.'>'.$optionName.'</option>';
			 }

			$this->sHTML .=	'</select>';
			$this->sHTML .=	 ' or <a class="btn btn-default" href="http://brewhound.nz/addstyle.php">add style</a>';
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			//$this->sHTML .= $sCustomError;
		}

	public function makeRatingSelectInput($sControlLabel,$sControlName){
			
			$optionName = "rating";
			$selected = 0;
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
			$this->sHTML .=	'<select name="'.$sControlName.'" class="formValue">';
			
/*
			// Build select drop menu list
			foreach ( $aOptions as $optionValue=>$optionName ) {
				if ($sControlData  == $optionValue) {
					$selected = 'selected="selected" ';
				} else {
					$selected = '';
				}
			  $this->sHTML .= '<option value="'.$optionValue.'" name="'.$optionName.'" '.$selected.'>'.$optionName.'</option>';
			 }
*/
			$this->sHTML .= '<option value="null" name="'.$optionName.'" '.$selected.'>select</option>';
			$this->sHTML .= '<option value="1" name="'.$optionName.'" '.$selected.'>1</option>';
			$this->sHTML .= '<option value="2" name="'.$optionName.'" '.$selected.'>2</option>';
			$this->sHTML .= '<option value="3" name="'.$optionName.'" '.$selected.'>3</option>';
			$this->sHTML .= '<option value="4" name="'.$optionName.'" '.$selected.'>4</option>';
			$this->sHTML .= '<option value="5" name="'.$optionName.'" '.$selected.'>5</option>';
			
			$this->sHTML .=	'</select>';
			
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			//$this->sHTML .= $sCustomError;
		}
		
		public function makeRatingRadioInput($sControlLabel,$sControlName,$aOptions=array(),$selected=''){
			
/*
			print_r($aOptions);
			die($aOptions);
*/
			
		
			$sControlData = "";
			if(isset($this->aData[$sControlName])==true){
				$sControlData = $this->aData[$sControlName];
			}
			$sError = "";
			
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<p>'.$this->aErrors[$sControlName].'</p>';
			}
			$this->sHTML .= '<div class="form-group rating">';
			$this->sHTML .= '<label for="'.$sControlName.'">'.$sControlLabel.'</label>';
			
			
			foreach($aOptions as $key=>$value){
				
			
				if((string) $selected == (string) $key){
					
					$this->sHTML .= '<input type="radio" value="'.$key.'" name="'.$sControlName.'" checked>'.$value;
					
				}else{
					
					$this->sHTML .= '<input type="radio" value="'.$key.'" name="'.$sControlName.'">'.$value;
					
				}
				
				
				
			}
			
/*
			$this->sHTML .= '<input type="radio" value="2" name="'.$optionName.'" '.$selected.'>2</option>';
			$this->sHTML .= '<input type="radio" value="3" name="'.$optionName.'" '.$selected.'>3</option>';
			$this->sHTML .= '<input type="radio" value="4" name="'.$optionName.'" '.$selected.'>4</option>';
			$this->sHTML .= '<input type="radio" value="5" name="'.$optionName.'" '.$selected.'>5</option>';
*/
			
			
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			//$this->sHTML .= $sCustomError;
		}
		
		public function makeCheckboxInput($sControlLabel,$sControlName,$sPossibleValue,$checkboxLabel){
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
				$this->sHTML .=	'<input type="checkbox" class="checkbox checkbox-success" id="'.$sControlName.'" name="'.$sControlName.'" value="'.$sPossibleValue.'"/><label>'.$checkboxLabel.'</label>';
			}else{
				$this->sHTML .=	'<input type="checkbox" class="checkbox checkbox-success" id="'.$sControlName.'" name="'.$sControlName.'"  checked value="'.$sPossibleValue.'"/><label>'.$checkboxLabel.'</label>';
			}		
			$this->sHTML .= '</div>';
			
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			//$this->sHTML .= $sCustomError;
		}
		public function makeCheckboxResident($sControlLabel,$sControlName,$sPossibleValue){
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
				$this->sHTML .=	'<input type="checkbox" class="checkbox" id="'.$sControlName.'" name="'.$sControlName.'" value="'.$sPossibleValue.'"/><label>Always on Tap</label>';
			}else{
				$this->sHTML .=	'<input type="checkbox" class="checkbox" id="'.$sControlName.'" name="'.$sControlName.'"  checked value="'.$sPossibleValue.'"/><label>Tap Only</label>';
			}		
			$this->sHTML .= '</div>';
			
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			//$this->sHTML .= $sCustomError;
		}		
		public function makeCheckboxInputSet($sControlLabel,$sControlName,$aOptions){

			$aControlData = array();
			if(isset($this->aData[$sControlName])==true){
				$aControlData = $this->aData[$sControlName];
			}
			$sError = "";
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<p>'.$this->aErrors[$sControlName].'</p>';
			}

			$this->sHTML .= '<div class="form-group">';
			//$this->sHTML .= '<div>//under construction//</div>';
			
			$this->sHTML .= '<label for="'.$sControlName.'">'.$sControlLabel.'</label>';
			
/*
			if($sControlData == false){
				$this->sHTML .=	'<input type="checkbox" class="checkbox" id="'.$sControlName.'" name="'.$sControlName.'" value="'.$sPossibleValue.'"/>';
			}else{
				$this->sHTML .=	'<input type="checkbox" class="checkbox" id="'.$sControlName.'" name="'.$sControlName.'"  checked value="'.$sPossibleValue.'/>';
			}	
*/	
			//print_r($aControlData);
		
			foreach ( $aOptions as $optionValue=>$optionName ) {
// 				$_POST["location"] = $optionValue;

				
				if(in_array($optionValue,$aControlData)){
					$this->sHTML .= '<li><input type="checkbox" class="checkbox checkbox-success" id="'.$sControlName.'" name="'.$sControlName.'[]" value="'.$optionValue.'" checked /><label for="'.$sControlName.'">'.$optionName.'</label></li>'."\n";
					
				}else{
					$this->sHTML .= '<li><input type="checkbox" class="checkbox checkbox-success" id="'.$sControlName.'" name="'.$sControlName.'[]" value="'.$optionValue.'"/><label for="'.$sControlName.'">'.$optionName.'</label></li>'."\n";
					
				}
				
			 }
		
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			//$this->sHTML .= $sCustomError;
		}
		
	public function makeLocAvButtons($sControlLabel,$sControlName,$aOptions,$aExistinglocations,$iBreweryID,$iBeerID,$slug){
		
/*
			$aControlData = array();
			if(isset($this->aData[$sControlName])==true){
				$aControlData = $this->aData[$sControlName];
			}
*/
			$sError = "";
			if(isset($this->aErrors[$sControlName])==true){
				$sError = '<p>'.$this->aErrors[$sControlName].'</p>';
			}

			$this->sHTML .= '<div class="addremove" id="addLocAv">';
			//$this->sHTML .= '<div>//under construction//</div>';
			//$this->sHTML .= '<label for="'.$sControlName.'">'.$sControlLabel.'</label>';
			
/*
			if($sControlData == false){
				$this->sHTML .=	'<input type="checkbox" class="checkbox" id="'.$sControlName.'" name="'.$sControlName.'" value="'.$sPossibleValue.'"/>';
			}else{
				$this->sHTML .=	'<input type="checkbox" class="checkbox" id="'.$sControlName.'" name="'.$sControlName.'"  checked value="'.$sPossibleValue.'/>';
			}	
*/	
		
/*
			echo "<pre>";
			print_r($aExistinglocations);
			echo "</pre>";
*/

		
			foreach ( $aOptions as $optionValue=>$optionName ) {
// 				$_POST["location"] = $optionValue;
//				if(in_array($optionValue,$aExistingData['Locations'])){
				if(in_array($optionValue,$aExistinglocations)){
					
					$iAvailableID = Availability::findAvID($iBeerID,$optionValue);
					
					$this->sHTML .= '<li><a href="removeLocAv.php?quickremove=true&beerID='.$iBeerID.'&slug='.$slug.'&availableID='.$iAvailableID.'" class="btn btn-danger" id="'.$sControlName.'" name="'.$sControlName.'[]" value="'.$optionValue.'" checked />'.$optionName.' <i class="fa fa-minus"></i></a></li>'."\n";
					
				}else{
					$this->sHTML .= '<li><a href="addLocAv.php?quickadd=true&beerID='.$iBeerID.'&slug='.$slug.'&locationID='.$optionValue.'&breweryID='.$iBreweryID.'" class="btn btn-success" id="'.$sControlName.'" name="'.$sControlName.'[]" value="'.$optionValue.'" />'.$optionName.' <i class="fa fa-plus"></i></a><input type="checkbox" class="checkbox" id="" name=""  checked value=""/></li>'."\n";
					
				}
				
			 }
			$this->sHTML .= '<div class="clearfix"></div>';
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			//$this->sHTML .= $sCustomError;
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
			$this->sHTML .=	'<input type="file" class="button" id="'.$sControlName.'" name="'.$sControlName.'" value="'.$sControlData.'"/>';
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			//$this->sHTML .= $sCustomError;
		}
		
		public function makeLoginInput($sControlLabel,$sControlName,$placeholder){
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
			//$this->sHTML .= $sCustomError;

		}
		public function makePasswordInput($sControlLabel,$sControlName,$placeholder){
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
			$this->sHTML .=	'<input type="password" class="form-control" id="'.$sControlName.'" name="'.$sControlName.'"  placeholder="'.$placeholder.'" value="'.$sControlData.'"/>';
			$this->sHTML .= '</div>';
			$this->sHTML .=	$sError;
			//$this->sHTML .= $sCustomError;
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
			
			
			//$this->sHTML .= '<li class="align-top">';
			$this->sHTML .= '

<li>
							          <div class="item-title label">'.$sControlName.'</div>
							          <div class="item-input">
							            <textarea></textarea>
							          </div>
							        </div>
</li>

							   ';
			//$this->sHTML .= '</li>';
			
			
/*
			$this->sHTML .= '<div class="form-group">';
			$this->sHTML .= "\t\t\t".'<label for="'.$sControlName.'">'.$sControlLabel.'</label>'."\n";
			$this->sHTML .=	"\t\t\t".'<textarea id="'.$sControlName.'" name="'.$sControlName.'" placeholder="'.$placeholder.'" value="" class="form-control" rows="3"/>'.$sControlData.'</textarea>'."\n\n";
			$this->sHTML .= '</div>';
*/
			$this->sHTML .=	$sError;
		}
		
		public function makeSubmit($sControlLabel,$sControlName){
			
			//$this->sHTML .= '<div class="content-block">';
				$this->sHTML .= '<div class="row">';
					$this->sHTML .= '<div class="col-50">';
						$this->sHTML .= "\t\t\t".'<a href="#" class="button button-big button-fill color-red back" value="'.$sControlLabel.'"/>Cancel</a>'."\n";
					$this->sHTML .= "</div>";
					$this->sHTML .= '<div class="col-50">';
						$this->sHTML .= '<input type="submit" name="'.$sControlName.'" class="button button-big button-fill color-green" value="'.$sControlLabel.'"/>';
					$this->sHTML .= "</div>";					
				$this->sHTML .= "</div>";	
			//$this->sHTML .= "</div>";
		
		}
		
		public function makeSubmitLeft($sControlLabel,$sControlName){
			
			$this->sHTML .= '<div class="content-block">';
				$this->sHTML .= '<div class="row">';
					$this->sHTML .= '<div class="col-50">';
						$this->sHTML .= "\t\t\t".'<button type="submit" name="'.$sControlName.'" class="btn btn-primary" value="'.$sControlLabel.'"/>'.$sControlLabel.'</button>'."\n";
					$this->sHTML .= "</div>";
					$this->sHTML .= '<div class="col-50">';
						$this->sHTML .= "\t\t\t".'<button type="submit" name="'.$sControlName.'" class="btn btn-primary" value="'.$sControlLabel.'"/>'.$sControlLabel.'</button>'."\n";
					$this->sHTML .= "</div>";					
				$this->sHTML .= "</div>";	
			$this->sHTML .= "</div>";
		}		
		
		public function moveFile($sControlName,$sNewName){
			$sNewPath = dirname(__FILE__).'/../assets/images/'.$sNewName;
			move_uploaded_file($this->aFiles[$sControlName]['tmp_name'], $sNewPath);

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
					return $this->sHTML."\t\t"."</ul></div></form>";
					break;
				case 'valid';
					if(count($this->aErrors)==0){
						return true;
					}
					return false;
					break;
				case 'data';
					return $this->aData;
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