<?
	$pid = $_GET['pornstar_id'];
	$pornstarObj = PornstarServices::getPornstarByID($pid);
	if (!$pornstarObj instanceof pornstarObj ) {
		redirect();
	}
?>
<h1>Pornstar | <?=$pornstarObj->getName()?></h1>

<script type="text/javascript">
var messages = new Array();

<? 
	$i = 0;
	$CLASSCovers = VCDClassFactory::getInstance('vcd_cdcover');
	foreach ($pornstarObj->getMovies() as $id => $title) {
		$arrCovers = $CLASSCovers->getAllCoversForVcd($id);
		foreach ($arrCovers as $obj) {
			if ($obj->isThumbnail()) {
				print "messages[".$i++."] = [\"".$obj->getImagePath()."\", 145, 205];\n ";
			}	
		}
		
	}
?>



</script>

<table cellspacing="1" cellpadding="0" border="0" class="displist" width="100%">
		<tr>
			<td valign="top" width="170"><? $pornstarObj->showImage(); ?><br/>
			<div align="center"><strong><?= $pornstarObj->getIAFD() ?></strong></div></td>
			<td valign="top" style="padding-left:3px;text-indent:0px">
				<strong><?=VCDLanguage::translate('pornstar.name')?>:</strong> <?= $pornstarObj->getName() ?><br/>
				<strong><?=VCDLanguage::translate('pornstar.web')?>:</strong> <?= $pornstarObj->getHomepage() ?><br/>
				<strong><?=VCDLanguage::translate('pornstar.moviecount')?>:</strong> <? echo $pornstarObj->getMovieCount() ?><br/><br/>
				<?
					if ($pornstarObj->getMovieCount() > 0) {
						$i = 0;
						print "<ul>";
						foreach ($pornstarObj->getMovies() as $id => $title) {
							print "<li onmouseover=\"doTooltip(event,".$i++.")\" onmouseout=\"hideTip()\"><a href=\"./?page=cd&amp;vcd_id=".$id."\">".$title . "</a></li>";
						}
						print "</ul>";
					}
				?>				

			</td>
		</tr>
	</table>
<p>

<?
	$bio = ereg_replace("\n","<br/>",$pornstarObj->getBiography()) ;
	print $bio;	
?>
</p>
