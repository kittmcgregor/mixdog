<?php	
 function pagination(){
	if (isset($_GET['page'])) {
	   $pageno = $_GET['page'];
	} else {
	   $pageno = 1;
	} // if
	
	$aAllActive = Beer::AllActive();
	
	$rows_per_page = 10;
	$lastpage      = ceil($aAllActive/$rows_per_page);

	echo "pagination test beers = ".$aAllActive."<br/>";
	echo "lastpage=".$lastpage."<br/>";
	
	$pageno = (int)$pageno;
	if ($pageno > $lastpage) {
	   $pageno = $lastpage;
	} // if
	if ($pageno < 1) {
	   $pageno = 1;
	} // if

	$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

		echo "<pre>";
		echo "limit= ";
		echo $limit;
		echo "</pre>";
		
	$oPaginatedBeers = Beer::loadPaginatedSegment($limit);

		echo "<pre>";
		print_r($oPaginatedBeers);
		echo "</pre>";
/*
	$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '<ul id="listings">';
			for($iCount=0;$iCount<count($oPaginatedBeers->allBeers);$iCount++){
				$oBeer = $oPaginatedBeers->allBeers[$iCount];

				$sHTML .= '<li>
								<div class="row">
										<div class="col itemTitleCol">
											<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>
										</div>
								</div>
							</li>';
			}
			$sHTML .= '</ul>';
			$sHTML .= '</div>';
	echo $sHTML;
*/

	
/*
		echo "<pre>";
		print_r($oPaginatedBeers);
		echo "</pre>";
*/
	
	if ($pageno == 1) {
	   echo " FIRST PREV ";
	} else {
	   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1'>FIRST</a> ";
	   $prevpage = $pageno-1;
	   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage'>PREV</a> ";
	} // if


	echo " ( Page $pageno of $lastpage ) ";
	
	if ($pageno == $lastpage) {
	   echo " NEXT LAST ";
	} else {
	   $nextpage = $pageno+1;
	   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage'>NEXT</a> ";
	   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage'>LAST</a> ";
	} // if


	//$oAllBeers->load();

/*
		echo "<pre>";
		print_r(count($oAllBeers->allBeers));
		echo "</pre>";
*/



		
}

?>