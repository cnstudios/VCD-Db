<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  H�kon Birgsson <konni@konni.com>
 * @package Functions
 * @version $Id$
 */
?>
<? 

function display_topmenu() {
	
	global $language;
	
	if (VCDUtils::isLoggedIn()) {
		$user = $_SESSION['user'];
		echo "<a href=\"./?page=private&o=settings\">".$user->getFullname()."</a>";
		
		if ($user->isAdmin()) {
			?>| <a href="#" onclick="openAdminConsole()"><?=$language->show('MENU_CONTROLPANEL')?></a><?
		}
		
		?> | <a href="./?do=logout"><?=$language->show('MENU_LOGOUT')?></a><?
				
	} else {
		?><a href="./?page=register"><?=$language->show('MENU_REGISTER')?></a><?
	}
	
	?>| <a href="./?page=detailed_search"><?=$language->show('SEARCH_EXTENDED')?></a> |<?
	
}

function display_userlinks() {
	global $language;
	global $ClassFactory;
	$SETTINGSClass = $ClassFactory->getInstance('vcd_settings');
	$CLASSVcd = new vcd_movie();
	$rssLink = "";
	if (sizeof($SETTINGSClass->getRssFeedsByUserId($_SESSION['user']->getUserID()))>0) {
		$rssLink = "<span class=\"nav\"><a href=\"./?page=private&o=rss\" class=\"navx\">".$language->show('MENU_RSS')."</a></span>";
	}
	?>
	<div class="topic"><?=$language->show('MENU_MINE')?></div>
	<span class="nav"><a href="./?page=private&amp;o=settings" class="navx"><?=$language->show('MENU_SETTINGS')?></a></span>
	<? if (strcmp($_SESSION['user']->getRoleName(), 'Viewer') != 0) { ?>
	<span class="nav"><a href="./?page=private&amp;o=movies" class="navx"><?=$language->show('MENU_MOVIES')?></a></span>
	<span class="nav"><a href="./?page=private&amp;o=new" class="navx"><?=$language->show('MENU_ADDMOVIE')?></a></span>
	<span class="nav"><a href="./?page=private&amp;o=loans" class="navx"><?=$language->show('MENU_LOANSYSTEM')?></a></span>
	<? } 
	// Check for shared wishlists and if so .. display the "others wishlist link"
		if ($SETTINGSClass->isPublicWishLists($_SESSION['user']->getUserID())) {
		?><span class="nav"><a href="./?page=private&amp;o=publicwishlist" class="navx"><?=$language->show('MENU_WISHLISTPUBLIC')?></a></span><?
	}
	?>
	<span class="nav"><a href="./?page=private&amp;o=wishlist" class="navx"><?=$language->show('MENU_WISHLIST')?></a></span>
	<? if ($CLASSVcd->getMovieCount($_SESSION['user']->getUserID()) > 0)  {?>
	<span class="nav"><a href="./?page=private&amp;o=stats" class="navx"><?=$language->show('MENU_STATISTICS')?></a></span>
	<? } ?>
	<?=$rssLink?>
	<?
}

function display_adultmenu() {
	global $language;
	global $ClassFactory;
	$SETTINGSClass = $ClassFactory->getInstance('vcd_settings');
	
	$show_adult = false;
	if (VCDUtils::isLoggedIn()) {
		$show_adult =& $_SESSION['user']->getPropertyByKey('SHOW_ADULT');
	}
	if (VCDUtils::isLoggedIn() && $SETTINGSClass->getSettingsByKey('SITE_ADULT') && $show_adult) {
		?> 
		<div class="topic">Pornstars</div>
		<ul>
		<li><a href="./?page=pornstars&amp;view=all">View all</a></li>
		<li><a href="./?page=pornstars&amp;view=active">View active</a></li>
		</ul>
		
		<?
	}

}

function display_imdbLinks($imdb_id) {
	global $language;
	
	print "<h2>".$language->show('I_LINKS')."</h2>";
	print "<ul>";
	print "<li><a href=\"http://www.imdb.com/Title?".$imdb_id."\" target=\"new\">".$language->show('I_DETAILS')."</a></li>";
	print "<li><a href=\"http://www.imdb.com/Plot?".$imdb_id."\" target=\"new\">".$language->show('I_PLOT')."</a></li>";
	print "<li><a href=\"http://www.imdb.com/Gallery?".$imdb_id."\" target=\"new\">".$language->show('I_GALLERY')."</a></li>";
	print "<li><a href=\"http://www.imdb.com/Trailers?".$imdb_id."\" target=\"new\">".$language->show('I_TRAILERS')."</a></li>";
	print "</ul>";
}


function display_toggle() {
	global $CURRENT_PAGE;
	if ($CURRENT_PAGE == "") {
	?> 
	
	<div class="topic">Toggle preview</div>
	<div class="forms" align="center">
	<a href="javascript:show('r-col')">[on]</a>-<a href="javascript:hide('r-col')">[off]</a>
	</div>
	
	
	<? }
}

function display_topusers() {
	global $ClassFactory;
	global $language;
	$USERClass = $ClassFactory->getInstance('vcd_user');
	$list = $USERClass->getUserTopList();
	if (sizeof($list) > 0) {
		$i = 0;
		print "<ul>";
		foreach ($list as $item) {
			if ($i > 5) break;
			print "<li>" . $item[0] . " (".$item[1]. ")</li>";
			$i++;
		}
		print "</ul>";
		unset($list);
	} else {
		print "<ul><li>".$language->show('X_NOUSERS')."</li></ul>";
	}
}

function display_moviecategories() {
	global $ClassFactory;
	global $language;
	
	?>	<div class="topic"><?=$language->show('MENU_CATEGORIES')?></div> 	<?
	
	
	$SETTINGSClass = $ClassFactory->getInstance("vcd_settings");
	$categories = $SETTINGSClass->getMovieCategoriesInUse();
	$adult_id = $SETTINGSClass->getCategoryIDByName('adult');	
	$show_adult = (bool)$SETTINGSClass->getSettingsByKey('SITE_ADULT');
	if (VCDUtils::isLoggedIn()) {
		$show_adult =& $_SESSION['user']->getPropertyByKey('SHOW_ADULT');
	}
		
	
	$curr_catid = -1;
	if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
		$curr_catid = $_GET['category_id'];
	}
	
	if (sizeof($categories) > 0) {
				
		if ($language->isUsingDefault()) {
		
			foreach ($categories as $category) {
				
				$cssclass = "nav";
				if ($category->getID() == $curr_catid) {
					$cssclass = "navon";
				}
				
				if ($category->getID() == $adult_id) {
					if ($show_adult) {
						print "<span class=\"".$cssclass."\"><a href=\"./?page=category&amp;category_id=".$category->getID()."\" class=\"navx\">" . $category->getName() . "</a></span>";			
					}				
				} else {
					print "<span class=\"".$cssclass."\"><a href=\"./?page=category&amp;category_id=".$category->getID()."\" class=\"navx\">" . $category->getName() . "</a></span>";			
				}
			}
		
		} else {
			$mapping = getCategoryMapping();			
			
			$arrList = array();
			$arrTranslatedList = array();
			
			foreach ($categories as $category) {
				array_push($arrList, $category->getList());
			}
			
			foreach ($arrList as $catObj => $item) {
				if (isset($mapping[$item['name']])) {
					$translated = $mapping[$item['name']];
					$newKey = $language->show($translated);
				} else {
					$newKey = $item['name'];
				}
				
				
				
				if ($item['id'] == $adult_id) {
					if ($show_adult) {
						$arrTranslatedList[$item['id']] = $newKey;
					}
				} else {
					$arrTranslatedList[$item['id']] = $newKey;
				}
								
			}
			
			unset($arrList);			
			unset($mapping);
			unset($categories);
			
			asort($arrTranslatedList);
			
			foreach ($arrTranslatedList as $id => $name) {
				$cssclass = "nav";
				if ($id == $curr_catid) {
					$cssclass = "navon";
				}
				print "<span class=\"".$cssclass."\"><a href=\"./?page=category&amp;category_id=".$id."\" class=\"navx\">" .$name. "</a></span>";
			}
			unset($arrTranslatedList);
		
		}
		
		
		
	} else {
		print "<ul><li>".$language->show('X_NOCATS')."</li></ul>";
	}
	
	
	unset($categories);
}

/*  display pager for scrolling through recordsets on page */
function pager($totalRecords, $current_pos, $url) {
	
	global $CURRENT_PAGE;
	global $ClassFactory;
	
	$SetttingsClass = $ClassFactory->getInstance("vcd_settings");
	$recordCount = $SetttingsClass->getSettingsByKey("PAGE_COUNT");
	$totalPages = floor($totalRecords / $recordCount);
		
		
	if ($totalRecords < $recordCount) {
		return;
	}
	
			
	$nextpos = $current_pos + 1;
	$backpos = $current_pos - 1;
	
	if ($current_pos > 0) {
		$first = "<a href=\"./?page=".$CURRENT_PAGE."&amp;".$url."&amp;batch=0\">&lt;</a>";
	} else {
		$first = "&lt;";
	}
	
	if ($current_pos >= $totalPages) {
		$last  = "&gt;";
	} else {
		$last  = "<a href=\"./?page=".$CURRENT_PAGE."&amp;".$url."&amp;batch=$totalPages\">&gt;</a>";
	}
	
	if ($current_pos > 0) {
		$back  = "<a href=\"./?page=".$CURRENT_PAGE."&amp;".$url."&amp;batch=$backpos\">&lt;</a>";
	} else {
		$back  = "&lt;";
	}
	
	
	if ($current_pos >= $totalPages) {
		$next  = "&gt;";
	} else {
		$next  = "<a href=\"./?page=".$CURRENT_PAGE."&amp;".$url."&amp;batch=$nextpos\">&gt;</a>";
	}
	
	$page = ($current_pos+1) . " of " . ($totalPages+1);
	
	print "<div id=\"pager\">" . $first . $back ." [$page] " . $next . $last . "</div>";
	
}

function hidelayer($layername) {
	print "<script>hide('".$layername."')</script>";
}

function display_search() {
	
	global $language;
	
	// Check for last search method
	$lastkey = "";
	if (isset($_SESSION['searchkey'])) {
		$lastkey = $_SESSION['searchkey'];
	}
	
	?>
	<div class="topic"><?=$language->show('SEARCH')?></div>
	<div class="forms"> 
	<form action="search.php" method="get">
	<input type="text" name="searchstring" class="dashed" style="width:78px;"/>&nbsp;<input type="submit" value="<?=$language->show('SEARCH')?>" class="buttontext"/><br/>
	<input type="radio" name="by" value="title" <? if ($lastkey == '' || $lastkey == 'title') {print "checked=\"checked\"";} ?> class="nof"/><?=$language->show('SEARCH_TITLE')?><br/>
	<input type="radio" name="by" value="actor" <? if ($lastkey == 'actor') {print "checked=\"checked\"";} ?> class="nof"/><?=$language->show('SEARCH_ACTOR')?><br/>
	<input type="radio" name="by" value="director" <? if ($lastkey == 'director') {print "checked=\"checked\"";} ?> class="nof"/><?=$language->show('SEARCH_DIRECTOR')?><br/>
	</form>
	</div>
<?
}

function reloadandclose() {
	print "onload=\"window.opener.location.reload();window.close()\"";
}


/* Uses the getList from the Object to dynamicly create dropdown  */
function evalDropdown($arrObjects, $selected_index = -1, $showtitle = true, $title = "") {
	
	// Check for preliminaries ..
	if (sizeof($arrObjects) == 0) {
		return;
	}
	
	// Check if class exists and if he implements the getList function
	$objType = $arrObjects[0];
		
	if (class_exists(get_class($objType))) {
		if (!method_exists($objType, 'getList')) {
			VCDException::display(get_class($objType) . " must implement getList<break>before using function evalDropdown");
			return;
		}
	} else {
		VCDException::display("Class " . get_class($objType) . " does not exist");
		return;
	}
	
	// ok we are all set to display the dropdown
	if ($showtitle) {
		if ($title == "") {
			print "<option value=\"null\">Select</option>";
		} else {
			print "<option value=\"null\">".$title."</option>";
		}
		
	}
		
		
	foreach ($arrObjects as $obj) {
		$data = $obj->getList();		
		if ($selected_index == $data['id']) {
				print "<option value=\"".$data['id']."\" selected>".$data['name']."</option>";
			} else {
				print "<option value=\"".$data['id']."\">".$data['name']."</option>";
			}
	}
	print "</select>";
}



function make_pornstarlinks($pornstar_id, $pornstar_name, $movie_id) {
		global $language;
		?>
		<td>
			<a href="javascript:jumpTo('<?=$pornstar_name ?>','excalibur')"><img src="../images/excalibur.gif" border="0"/></a>
		</td>
		<td>
			<a href="javascript:jumpTo('<?=$pornstar_name ?>','goliath')"><img src="../images/gol.gif" border="0" alt="Search Goliath Films for <?=$pornstar_name ?>"/></a>
		</td>
		<td>
			<a href="javascript:jumpTo('<?=$pornstar_name ?>','searchextreme')"><img src="../images/extreme.gif" border="0" alt="Search searchextreme.com for <?=$pornstar_name ?>"/></a>
		</td>
		<td>
			<a href="javascript:jumpTo('<?=$pornstar_name ?>','eurobabe')"><img src="../images/eurobabe.gif" border="0" alt="Search eurobabeindex.com for <?=$pornstar_name ?>"/></a>
		</td>
		<td>
			<a href="javascript:changePornstar(<?=$pornstar_id ?>)">[<?=$language->show('X_CHANGE')?>]</a>
		</td>
		<td>
			&nbsp;&nbsp;<a href="#" onClick="del_actor(<?=$pornstar_id ?>,<?=$movie_id?>)">[<?=$language->show('X_DELETE')?>]</a>
		</td>
		<?
	
}

function getCategoryMapping() {
	$mapping = array(
		'Action' 		=> 'CAT_ACTION',
		'Adult' 		=> 'CAT_ADULT',
		'Adventure' 	=> 'CAT_ADVENTURE',
		'Animation' 	=> 'CAT_ANIMATION',
		'Anime / Manga' => 'CAT_ANIME',
		'Comedy' 		=> 'CAT_COMEDY',
		'Crime' 		=> 'CAT_CRIME',
		'Documentary' 	=> 'CAT_DOCUMENTARY',
		'Drama' 		=> 'CAT_DRAMA',
		'Family' 		=> 'CAT_FAMILY',
		'Fantasy' 		=> 'CAT_FANTASY',
		'Film-Noir' 	=> 'CAT_FILMNOIR',
		'Horror' 		=> 'CAT_HORROR',
		'James Bond' 	=> 'CAT_JAMESBOND',
		'Music Video' 	=> 'CAT_MUSICVIDEO',
		'Musical' 		=> 'CAT_MUSICAL',
		'Mystery' 		=> 'CAT_MYSTERY',
		'Romance' 		=> 'CAT_ROMANCE',
		'Sci-Fi' 		=> 'CAT_SCIFI',
		'Short' 		=> 'CAT_SHORT',
		'Thriller' 		=> 'CAT_THRILLER',
		'Tv Shows' 		=> 'CAT_TVSHOWS',
		'War' 			=> 'CAT_WAR',
		'Western' 		=> 'CAT_WESTERN',
		'X-Rated' 		=> 'CAT_XRATED'
	);
	return $mapping;
}


function parseCategoryList($strList) {

	global $ClassFactory;
	global $language;
	
	$SETTINGSClass = $ClassFactory->getInstance('vcd_settings');
	$categories = $SETTINGSClass->getAllMovieCategories();
	$mapping = getCategoryMapping();
	$inArr = explode(",", $strList);

	$strResult = "";
	foreach ($inArr as $cat) {
		
		$cat_id = $SETTINGSClass->getCategoryIDByName($cat);
		$cat_name = $cat;
		
		if (is_numeric($cat_id) && $cat_id != 0) {
			$catObj = $SETTINGSClass->getMovieCategoryByID($cat_id);
			
			if (!$language->isUsingDefault() && isset($mapping[$catObj->getName()])) {
				$translated =  $mapping[$catObj->getName()];
				$cat_name = $language->show($translated);
			} else {
				$cat_name = $catObj->getName();
			}
		} 
		
		if ($cat_id != 0) {
			$strResult .= " <a href=\"./?page=category&amp;category_id=".$cat_id."\">".$cat_name."</a>,";
		} else { 
			$strResult .= " ". $cat_name . ",";
		}
				
	}
	
	return substr($strResult, 0, (strlen($strResult)-1));
	
	
}


function server_url()
{  
   $proto = "http" .
       ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://";
   $server = isset($_SERVER['HTTP_HOST']) ?
       $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
   return $proto . $server;
}
   

// Default redirection to home
function redirect($relative_url = '.?')
{
   $url = server_url() . dirname($_SERVER['PHP_SELF']) . "/" . $relative_url;
   if (!headers_sent())
   {
       header("Location: $url");
       exit();
   }
   else
   {
       print "<script>location.href='".$url."'</script>";
   }
}

function inc_tooltipjs() {
	
	global $CURRENT_PAGE;
	if ($CURRENT_PAGE == 'pornstar' ) {
		?>
		<script src="includes/js/dw_event.js" type="text/javascript"></script>
		<script src="includes/js/dw_viewport.js" type="text/javascript"></script>
		<script src="includes/js/dw_tooltip.js" type="text/javascript"></script>
		<?
	}
}

function rightbar() {
	global $CURRENT_PAGE;
	$subaction = "";
	if (isset($_GET['o'])) {
		$subaction = $_GET['o'];
	}
	
	if ($CURRENT_PAGE == '') {
		// Check if user is logged in and wished to disable sidebar
		if (VCDUtils::isLoggedIn()) {
			global $ClassFactory;
			$SETTINGSClass = $ClassFactory->getInstance('vcd_settings');
			$arr = $SETTINGSClass->getMetadata(0, $_SESSION['user']->getUserID(), 'frontbar');
			if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj && $arr[0]->getMetadataValue() == 0) {
				return false;
			}
		}
		return true;
		
	} else {
		return false;
	}
}


function createObjFilter(&$arr, $start, $pageCount) {
	$newarr = array();
	for ($i = 0; $i < sizeof($arr); $i++) {
		if ($i >= $start && $i < ($start+$pageCount)) {
			array_push($newarr, $arr[$i]);	
		}
		
		if ($i > ($start+$pageCount)) {
			break;
		}
	}	
	
	return $newarr;
}

function aSortBySecondIndex($multiArray, $secondIndex) { 
		   while (list($firstIndex, ) = each($multiArray)) 
		       $indexMap[$firstIndex] = $multiArray[$firstIndex][$secondIndex]; 
		   asort($indexMap); 
		   while (list($firstIndex, ) = each($indexMap)) 
		       if (is_numeric($firstIndex)) 
		           $sortedArray[] = $multiArray[$firstIndex]; 
		       else $sortedArray[$firstIndex] = $multiArray[$firstIndex]; 
		   return $sortedArray; 
} 

function showPlot($strPlot) {
	
	global $language;
	
	$showLen = 280;
	$plot = ereg_replace(13,"<br/>",$strPlot); 
	$len = strlen($plot);
	if ($len > $showLen) {
		$first = substr($plot, 0, $showLen); 
		print "<div style=\"padding-right:20px\" id=\"first\">".$first." ...<br/>&nbsp;&nbsp;<a href=\"#plot\" onclick=\"hide('first');show('rest')\">".$language->show('X_SHOWMORE')." &gt;&gt;</a></div>";
		print "<div id=\"rest\" style=\"visibility:hidden;display:none;\">".$plot."
				<br/>&nbsp;&nbsp;<a href=\"#plot\" onclick=\"hide('rest');show('first')\">&lt;&lt; ".$language->show('X_SHOWLESS')."</a>";

	} else {
		print $plot;
	}
	
	
		
}

function fixFormat($str) {
	$len = 10;
	$cats = explode(",",$str);
	asort($cats);
	if (sizeof($cats) == 1) {
		return $cats[0];
	}
	$catstr = $str;
	if (strlen($catstr) > $len) {
		return "<span title=\"".$str."\">".$cats[0].", ...</span>";
	} else {
		return implode(",", $cats);
	}
}


function checkInstall() {
	return (is_dir('setup'));
}


function filterLoanList($arrMovies, $arrLoans) {

	// create array with movie id's that are in loan ..
	$loanIds = array();
	foreach ($arrLoans as $loanObj) {
		array_push($loanIds, $loanObj->getCDID());
	}
	
	$arrAvailable = array();
	foreach ($arrMovies as $vcdObj) {
		if (!in_array($vcdObj->getId(), $loanIds)) {
			array_push($arrAvailable, $vcdObj);
		}
	}
	
	unset($loanIds);
	return $arrAvailable;
	
	
}

function ShowOneRSS($url, $showdescription = false) { 
    
	global $ClassFactory;
	$maxtitlelen = 44;
	$rss = $ClassFactory->getInstance('lastRSS');
	$rss->cache_dir = CACHE_FOLDER;
	$rss->cache_time = RSS_CACHE_TIME;
	
    if ($rs = $rss->get($url)) { 
    	
    	$title = $rs['title'];
    	if (strlen($title) > $maxtitlelen) {
    		$title = VCDUtils::shortenText($title, $maxtitlelen);
    	}
    	
    	
        echo "<h1><em><a href=\" ".$rs['link']."\" title=\"".$rs['title']."\">".$title."</a></em></h1>\n"; 
        if ($showdescription)
        	echo $rs['description']."<br/>\n"; 

            echo "<ul>\n"; 
            foreach ($rs['items'] as $item) { 
            	
            	  $alt = $item['title'];
            	  if (isset($item['description'])) {
            	  	$alt = $item['description'];
            	  }
            	
	              echo "\t<li><a href=\"".str_replace('<![CDATA[&]]>', '&amp;', $item['link'])."\" target=\"_new\" title=\"".$alt."\">".unhtmlentities(str_replace("&apos;", "'", $item['title']))."</a></li>\n"; 
            } 
            echo "</ul>\n"; 
    } 
} 


function unhtmlentities ($string)
{
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
	$trans_tbl = array_flip ($trans_tbl);
	return strtr ($string, $trans_tbl);
}



function printStatistics($show_logo = true, $width = "230", $style = "statsTable") {
	global $ClassFactory;
	$SETTINGSClass = $ClassFactory->getInstance('vcd_settings');
	$statObj = $SETTINGSClass->getStatsObj();
	
	if (strcmp($style, "statsTable") == 0) {
		$header = "stata";
	?>
	<h3 align="center">
	<? if ($show_logo) { ?>
	<img src="images/logotest.gif" width="187" align="middle" height="118" alt="" border="0"/>
	<br/>
	<? } ?>
	Todays report
	</h3>
	<? }  else {$header = "header";} ?>
	<div align="center">
	<table cellspacing="1" cellpadding="1" border="0" class="<?=$style?>" style="width:<?=$width?>px">
	<tr>
		<td class="<?=$header?>" colspan="2">Movies in database</td>
	</tr>
	<tr>
		<td align="left">Total</td>
		<td align="right"><?=$statObj->getMovieCount()?></td>
	</tr>
	<tr>
		<td align="left">Added today</td>
		<td align="right"><?=$statObj->getMovieTodayCount()?></td>
	</tr>
	<tr>
		<td align="left">Added this week</td>
		<td align="right"><?=$statObj->getMovieWeeklyCount()?></td>
	</tr>
	<tr>
		<td align="left">Added this month</td>
		<td align="right"><?=$statObj->getMovieMonthlyCount()?></td>
	</tr>
	
	<tr>
		<td class="<?=$header?>" colspan="2">Top categories</td>
	</tr>
	<? 
		foreach ($statObj->getBiggestCats() as $catObj) {
			print "<tr>";
				print "<td align=\"left\"><a href=\"./?page=category&amp;category_id=".$catObj->getID()."\">".$catObj->getName()."</a></td>";
				print "<td align=\"right\">".$catObj->getCategoryCount()."</td>";
			print "</tr>";
		}
	?>
	
	<tr>
		<td class="<?=$header?>" colspan="2">Most active categories</td>
	</tr>
	<? 
		foreach ($statObj->getBiggestMonhtlyCats() as $catObj) {
			print "<tr>";
				print "<td align=\"left\"><a href=\"./?page=category&amp;category_id=".$catObj->getID()."\">".$catObj->getName()."</a></td>";
				print "<td align=\"right\">".$catObj->getCategoryCount()."</td>";
			print "</tr>";
		}
	?>
	
	<tr>
		<td colspan="2" class="<?=$header?>">Covers in database</td>
	</tr>
	<tr>
		<td align="left">Total</td>
		<td align="right"><?=$statObj->getTotalCoverCount()?></td>
	</tr>
	<tr>
		<td align="left">Added this week</td>
		<td align="right"><?=$statObj->getWeeklyCoverCount()?></td>
	</tr>
	<tr>
		<td align="left">Added this month</td>
		<td align="right"><?=$statObj->getMonthlyCoverCount()?></td>
	</tr>
	</table>
	</div>
	
	<?

}


/**
 * Get the play command for specified movie.
 *
 * Returns true if all presetiquites as met for playing the movie
 * and movie file location has been saved.  Otherwise returns false.
 * Param $playcommand will then contain the command for playing the movie.
 *
 * @param vcdObj $vcd_id
 * @param int $user_id
 * @param string $playcommand
 */
function getPlayCommand($vcdObj, $user_id, &$playcommand) {
	if (VCDUtils::isLoggedIn() && VCDUtils::isOwner($vcdObj) && $_SESSION['user']->getPropertyByKey('PLAYOPTION')) {
		global $ClassFactory;
		$SETTINGSClass = $ClassFactory->getInstance('vcd_settings');
		
		$player = "";
		$playerparams = "";
		$filename = "";
		
		// check for filename
		$fileArr = $SETTINGSClass->getMetadata($vcdObj->getID(), $user_id, 'filelocation');
		if (is_array($fileArr) && sizeof($fileArr) == 1 && $fileArr[0] instanceof metadataObj) {
			$filename = $fileArr[0]->getMetaDataValue();
		} 
		
		
		// check for player settings 
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'player');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$player = $arr[0]->getMetaDataValue();
		} 
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'playerpath');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$playerparams = $arr[0]->getMetaDataValue();
		} 
		
		if (strcmp($player, "") !=0 && strcmp($filename, "") != 0) {
			$playcommand = $player . " " . $filename . " " . $playerparams;
			$playcommand = str_replace('\\','#', $playcommand);
			return true;
		} else {
			return false;
		}
		
		
	}
	
	return false;
}

function getPublicPlayCommand($vcdObj, $user_id, &$playcommand) {
		global $ClassFactory;
		$SETTINGSClass = $ClassFactory->getInstance('vcd_settings');
		
		$player = "";
		$playerparams = "";
		$filename = "";
		
		// check for filename
		$fileArr = $SETTINGSClass->getMetadata($vcdObj->getID(), $user_id, 'filelocation');
		if (is_array($fileArr) && sizeof($fileArr) == 1 && $fileArr[0] instanceof metadataObj) {
			$filename = $fileArr[0]->getMetaDataValue();
		} 
		
		
		// check for player settings 
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'player');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$player = $arr[0]->getMetaDataValue();
		} 
		$arr = $SETTINGSClass->getMetadata(0, $user_id, 'playerpath');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$playerparams = $arr[0]->getMetaDataValue();
		} 
		
		if (strcmp($player, "") !=0 && strcmp($filename, "") != 0) {
			$playcommand = $player . " " . $filename . " " . $playerparams;
			$playcommand = str_replace('\\','#', $playcommand);
			return true;
		} else {
			return false;
		}
		
	
	return false;
}




function getLocalizedCategories($categoryObjArr = null) {
	global $language;
	global $ClassFactory;
	$SETTINGSClass = $ClassFactory->getInstance('vcd_settings');
	
	
	if ($categoryObjArr == null) {
		$categoryObjArr = $SETTINGSClass->getAllMovieCategories();
	} 
	
	
	// Translate category names
	$mapping = getCategoryMapping();			
	$altLang = $language->isUsingDefault();
	// Create translated category array
	$arrCategories = array();
	if ($altLang) {
		foreach ($categoryObjArr as $categoryObj) {
			array_push($arrCategories, $categoryObj->getList());
		}
		
	} else {
		foreach ($categoryObjArr as $categoryObj) {
			$category_name = $categoryObj->getName();
			if (!$altLang && key_exists($category_name, $mapping)) {
				$category_name = $language->show($mapping[$category_name]);
			}
			$arr = array("id" => $categoryObj->getID(), "name" => $category_name);
			array_push($arrCategories, $arr);
		}
		$arrCategories = aSortBySecondIndex($arrCategories, 'name');
	}
	
	return $arrCategories;
	
}


// Check if mod_rewrite is enabled and call for page parsing on contents if it is.
function start_mrw() {
	if (defined("MOD_REWRITE") && (strcmp(MOD_REWRITE, "1") == 0)) {
		ob_start("doRewrite");
	}
}

// Flush buffer if mod_rewrite is enabled
function end_mrw() {
	if (defined("MOD_REWRITE") && (strcmp(MOD_REWRITE, "1") == 0)) {
		ob_end_flush();
	}
}

function doRewrite($buffer) {
	
  $root = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], "index.php"));   
	
  $arrDefault = array(
  		//STYLE."style.css",
    	"./?page=category&amp;category_id=",
    	"./?page=adultcategory&amp;category_id=",
    	"./?page=adultcategory&amp;studio_id=",
    	"./?page=cd&amp;vcd_id=",
    	"./?page=pornstar&amp;pornstar_id=",
    	"images/",
    	"upload",
    	"includes",
    	"./?"
    	
      	
  );
	
  $arrRewrite = array(
  		//$root.STYLE."style.css",
  		$root."cat/",
  		$root."xcat/",
  		$root."studio/",
  		$root."cd/",
  		$root."pornstar/",
  		$root."images/",
  		$root."upload",
  		$root."includes",
  		$root."?",
  );
	      
  return str_replace($arrDefault, $arrRewrite, $buffer);
}


?>