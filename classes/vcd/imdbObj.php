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
 * @package Vcd
 * @version $Id$
 */
 
?>
<? 
/** 
	Class imdbObj
	Container for the IMDB data on the current movie.
*/

	class imdbObj implements XMLable {

	private $imdb;
	private $title;
	private $alt_title1;
	private $alt_title2;
	private $image;
	private $year;
	private $plot;
	private $director;
	private $cast;
	private $rating;
	private $runtime;
	private $country;
	private $genre;
	

	/**
	 * Function contructor
	 *
	 * @param array $dataArr
	 * @return imdbObj
	 */
	function imdbObj($dataArr = null) {
		if (is_array($dataArr)) {
			$this->imdb 		= $dataArr[0];
			$this->title		= $dataArr[1];
			$this->alt_title1	= $dataArr[2];
			$this->alt_title2	= $dataArr[3];
			$this->image		= $dataArr[4];
			$this->year			= $dataArr[5];
			$this->plot			= $dataArr[6];
			$this->director		= $dataArr[7];
			$this->cast			= $dataArr[8];
			$this->rating		= $dataArr[9];
			$this->runtime		= $dataArr[10];
			$this->country		= $dataArr[11];
			$this->genre		= $dataArr[12];
			}
		}

		
	/**
	 * Get the IMDB plot
	 *
	 * @return string
	 */
	public function getPlot() {
		return $this->plot;
	}
		
	
	/**
	 * Set the IMDB plot
	 *
	 * @param string $strPlot
	 */
	public function setPlot($strPlot) {
		$this->plot = stripslashes($strPlot);
	}
	
	/**
	 * Get IMDB cast
	 *
	 * @param bool $format
	 * @return string
	 */
	public function getCast($format = true) {
		if ($format) {
			return $this->formatCast($this->cast);
		} else {
			return $this->cast;
		}
		
	}

	/**
	 * Set the IMDB cast
	 *
	 * @param string $strCast
	 */
	public function setCast($strCast) {
		$this->cast = $strCast;
	}
	
	/**
	 * Get the IMDB title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * Set the IMDB title
	 *
	 * @param string $strTitle
	 */
	public function setTitle($strTitle) {
		$this->title = stripslashes($strTitle);
	}
	
	/**
	 * Get the IMDB alternative title
	 *
	 * @return string
	 */
	public function getAltTitle() {
		return $this->alt_title1;
	}
	
	/**
	 * Set the IMDB alternative title
	 *
	 * @param string $strAltTitle
	 */
	public function setAltTitle($strAltTitle) {
		$this->alt_title1 = $strAltTitle;
	}
	
	/**
	 * Get the IMDB year
	 *
	 * @return int
	 */
	public function getYear() {
		return $this->year;
	}
	
	/**
	 * Set the IMDB year
	 *
	 * @param int $strYear
	 */
	public function setYear($strYear) {
		$this->year = $strYear;
	}
	
	/**
	 * Get the Director
	 *
	 * @return string
	 */
	public function getDirector() {
		return $this->director;
	}
	
	/**
	 * Set the director
	 *
	 * @param string $strDirector
	 */
	public function setDirector($strDirector)  {
		$this->director = stripslashes($strDirector);
	}
	
	/**
	 * Get the IMDB rating
	 *
	 * @return double
	 */
	public function getRating() {
		return $this->rating;
	}
	
	/**
	 * Set the IMDB ratings
	 *
	 * @param double $strRating
	 */
	public function setRating($strRating) {
		$this->rating = $strRating;
	}
	
	/**
	 * Get the IMDB runtime in minutes
	 *
	 * @return int
	 */
	public function getRuntime() {
		if (!is_numeric($this->runtime)) {
			return 0;
		} else {
			return $this->runtime;
		}
		
	}
	
	/**
	 * Set the runtime in minutes
	 *
	 * @param int $strRuntime
	 */
	public function setRuntime($strRuntime) {
		$this->runtime = $strRuntime;
	}
	
	/**
	 * Get the country list origin of the movie
	 *
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}
	
	/**
	 * Set the procuction countries of this movie.
	 *
	 * @param string $strCountry
	 */
	public function setCountry($strCountry) {
		$this->country = $strCountry;
	}
	
	/**
	 * Get the IMDB genres
	 *
	 * @return string
	 */
	public function getGenre() {
		return $this->genre;
	}
	
	/**
	 * Set the IMDB genres
	 *
	 * @param string $strGenre
	 */
	public function setGenre($strGenre) {
		$this->genre = $strGenre;
	}
	
	/**
	 * Get the IMDB id
	 *
	 * @return string
	 */
	public function getIMDB() {
		return $this->imdb;
	}
	
	/**
	 * Set the IMDB id
	 *
	 * @param string $strIMDB
	 */
	public function setIMDB($strIMDB) {
		$this->imdb = $strIMDB;
	}
	
	/**
	 * Set the image associated with this movie
	 *
	 * @param string $strImage
	 */
	public function setImage($strImage) {
		$this->image = $strImage;
	}
	
	/**
	 * Get the image associated with this IMDB object
	 *
	 * @return string
	 */
	public function getImage() {
		return $this->image;
	}
	
		
	/**
	 * Draw the rating of the IMDB object, writes html image star icons
	 *
	 */
	public function drawRating() {
		$max = 10;
		$stjornur = round($this->rating);
		$tomar = $max - $stjornur;
		$counter = 0;
		for (;$counter < $stjornur; $counter++) {
			echo("<img src=\"images/goldstar.gif\" border=\"0\" alt=\"$stjornur stars\"/>");
		}

		$counter = 0;
		for (;$counter < $tomar; $counter++) {
			echo("<img src=\"images/greystar.gif\" border=\"0\" alt=\"$stjornur stars\"/>");
		}
		
	
	}
	
	
	/**
	 * Format the cast list and prints the cast list.
	 *
	 * @param string $cast
	 */
	public function formatCast($cast)	{
		$cast = ereg_replace(13,"<br>",$cast);
		
		$pieces = explode("<br>", $cast);
		$st		= count($pieces);
		$counter = 0;
			for ($n=0; $n < $st-1; $n++ ) {
				$tmp = explode("...",$pieces[$n]);
				$role = strstr($pieces[$n],'....');
				$role = str_replace('....','',$role);
				
				$imdb = explode(" ",$tmp[0]); // the IMDB url
				$tmp[0] = "<a href=\"search.php?searchstring=".trim($tmp[0])."&amp;by=actor\">$tmp[0]</a>";
				$actor = trim($tmp[0]);
			
				// Create imdb url for actor
				if (isset($imdb[2])) { 
					$urlid = "<a href=\"http://us.imdb.com/Name?$imdb[2],+$imdb[0]+$imdb[1]\" target=\"_new\">[imdb]</a>";
				} elseif(isset($imdb[1])) {
					$urlid = "<a href=\"http://us.imdb.com/Name?$imdb[1],+$imdb[0]\" target=\"_new\">[imdb]</a>";
				} else {
					$urlid = "<a href=\"http://us.imdb.com/Name?$imdb[0]\" target=\"_new\">[imdb]</a>";
				}
				print "<span class=\"item\"><strong>$actor</strong>&nbsp;&nbsp;$urlid<br/>$role</span>";
			}
	}

			
	/**
	 * Print html link to the IMDB page of the movie.
	 *
	 * @param string $align
	 */
	public function printImageLink($align = "") {
		if (!empty($align)) {
			$align = "align=\"$align\"";
		}
		
		print "<a href=\"http://www.imdb.com/title/tt".$this->imdb."\" target=\"_new\"><img src=\"images/imdb-logo.gif\" style=\"padding-right:15px;\" alt=\"\" title=\"Detailed info\" border=\"0\" ".$align."/></a>";
	}
			
	
	
	
	/**
	 * Return the XML representation of the IMDB object.
	 *
	 * @return string
	 */
	public function toXML() {
		
			$xmlstr  = "<imdb>\n";
			$xmlstr .= "<imdb_id>".$this->imdb."</imdb_id>\n";
			$xmlstr .= "<title><![CDATA[".$this->title."]]></title>\n";
			$xmlstr .= "<alt_title><![CDATA[".$this->alt_title1."]]></alt_title>\n";
			$xmlstr .= "<image>".$this->image."</image>\n";
			$xmlstr .= "<year>".$this->year."</year>\n";
			$xmlstr .= "<plot><![CDATA[".$this->plot."]]></plot>\n";
			$xmlstr .= "<director><![CDATA[".$this->director."]]></director>\n";
			//$xmlstr .= "<cast><![CDATA[".$this->cast."]]></cast>\n";
			$xmlstr .= "<cast><![CDATA[".$this->formatCastForXmlExport()."]]></cast>\n";
			$xmlstr .= "<rating>".$this->rating."</rating>\n";
			$xmlstr .= "<runtime>".$this->runtime."</runtime>\n";
			$xmlstr .= "<country>".$this->country."</country>\n";
			$xmlstr .= "<genre>".$this->genre."</genre>\n";
			$xmlstr .= "</imdb>\n";
			
			return $xmlstr;
	}
	
	
	/**
	 * Format the cast in XML compatible manner.
	 *
	 * @return string
	 */
	private function formatCastForXmlExport() {
		$export_cast = ereg_replace(13,"|",$this->cast);
		return $export_cast;
	}
	
	
	
}

?>