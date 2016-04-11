<?php 
	require_once("connection.php");
	require_once("style.php");	
	
class StylesManager{
	// no attributes
	
		static public function all(){
		//return a list of all styles
		$aStyles = array();
		
		// query all style IDs
		$oCon = new Connection();
		$sSql = "SELECT  `StyleID` FROM  `style` ORDER BY StyleName";
		
		$oResultSet = $oCon->query($sSql);
		
		// load all styles and add to array
		while($aRow = $oCon->fetchArray($oResultSet)){
			$iStyleID = $aRow["StyleID"];

			$oStyle = new Style();
			$oStyle->load($iStyleID);
			$aStyles[] = $oStyle; // add styles to list
		}
			$oCon->close();
			
			return $aStyles;
		}
	
}

$aAllStyles = StylesManager::all();

echo "<pre>";
print_r($aAllStyles);
echo "</pre>";

?>