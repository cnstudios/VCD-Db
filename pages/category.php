<?php 
/* Display the movies in selected category */


$VCDClass = $ClassFactory->getInstance("vcd_movie");
$SETTINGSclass = $ClassFactory->getInstance("vcd_settings");

$cat_id = $_GET['category_id'];
$batch  = 0;

if (isset($_GET['batch']))
	$batch = $_GET['batch'];

$imagemode = false;
	if (isset($_GET['viewmode'])) {
		$imagemode = true;
		$viewbar = "(<a href=\"./?page=".$CURRENT_PAGE."&amp;category_id=".$cat_id."&amp;batch=".$batch."\">".$language->show('M_TEXTVIEW')."</a> / ".$language->show('M_IMAGEVIEW').")";		
	} else {
		$viewbar = "(".$language->show('M_TEXTVIEW')." / <a href=\"./?page=".$CURRENT_PAGE."&amp;category_id=".$cat_id."&amp;viewmode=img&amp;batch=".$batch."\">".$language->show('M_IMAGEVIEW')."</a>)";
	}
	
	
$showmine = false;
$checked = "";
if (isset($_SESSION['mine']) && $_SESSION['mine'] == true) {
	$showmine = true;
	$checked = "checked=\"checked\"";
}

if (VCDUtils::isLoggedIn()) {
	$viewbar .= "&nbsp; | <input type=\"checkbox\" class=\"nof\" onclick=\"showonlymine(".$cat_id.")\" ".$checked."/>".$language->show('M_MINEONLY')."";
}

	
$Recordcount = $SETTINGSclass->getSettingsByKey("PAGE_COUNT");
$offset = $batch*$Recordcount;


if ($showmine && VCDUtils::isLoggedIn()) {
	$movies = $VCDClass->getVcdByCategory($cat_id, $Recordcount, $offset, $_SESSION['user']->getUserID());
} elseif (VCDUtils::isLoggedIn() && VCDUtils::isUsingFilter($_SESSION['user']->getUserID())) {
	$movies = $VCDClass->getVcdByCategoryFiltered($cat_id, $Recordcount, $offset, $_SESSION['user']->getUserID());
} else {
	$movies = $VCDClass->getVcdByCategory($cat_id, $Recordcount, $offset);
}


?>
<h1><?=$language->show('M_BYCAT')?></h1>
<?

if (sizeof($movies) > 0 || $showmine) {
	
	
	// Display the pager
	if ($imagemode) {
		$suburl = "category_id=".$cat_id."&amp;viewmode=img";
	} else {
		$suburl = "category_id=".$cat_id;
	}

	if ($showmine && VCDUtils::isLoggedIn()) { 
		$categoryCount = $VCDClass->getCategoryCount($cat_id, false, $_SESSION['user']->getUserID());
	} elseif (VCDUtils::isLoggedIn() && VCDUtils::isUsingFilter($_SESSION['user']->getUserID())) {
		$categoryCount = $VCDClass->getCategoryCountFiltered($cat_id, $_SESSION['user']->getUserID());
	} else {
		$categoryCount = $VCDClass->getCategoryCount($cat_id);
	}
	
		
	print "&nbsp;<span class=\"bold\">".$language->show('M_CURRCAT')."</span>&nbsp;";
	print "&nbsp; (".$categoryCount." ".$language->show('X_MOVIES').") ".$viewbar."";
		
	
	pager($categoryCount, $batch, $suburl);
	
	if (!$imagemode) {
	
		print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"displist\">";
		print "<tr><td class=\"header\">".$language->show('M_TITLE')."</td><td nowrap=\"nowrap\" class=\"header\">".$language->show('M_YEAR')."</td><td class=\"header\">".$language->show('M_MEDIATYPE')."</td></tr>";
		foreach ($movies as $movie) {
			print "<tr>
					   <td width=\"70%\"><a href=\"./?page=cd&amp;vcd_id=".$movie->getID()."\">".$movie->getTitle()."</a></td>
				       <td nowrap=\"nowrap\">".$movie->getYear()."</td>
			           <td nowrap=\"nowrap\">".fixFormat($movie->showMediaTypes())."</td>
				   </tr>";
		}
		print "</table>";	
	
	} else {
			print "<hr/>";
			print "<div id=\"actorimages\">";
			foreach ($movies as $movie) {
								
				$coverObj = $movie->getCover('thumbnail');
				if ($coverObj instanceof cdcoverObj ) {
					print $coverObj->showCategoryImageAndLink("./?page=cd&amp;vcd_id=".$movie->getID()."",$movie->getTitle());
					
				}
			}
			
			print "</div>";
			
		}
	
	
} else {
	// Movie array has 0 entries, either because of filter or that user has no movies in this category.
	
}




?>