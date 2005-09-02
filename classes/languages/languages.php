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
 * @package Language
 * @version $Id$
 */
 
?>
<?php

define ("LANGUAGE_FILE_ROOT", "classes/languages/");
define ("DEFAULT_LANG","ENG");
define ("FILE_SIZE", 10);


class language
{
	
	private $language_name;
	private $language_tag;

	private $avail_language_tags = array();
	private $avail_languages = array();
	private $avail_language_files = array();
	private $avail_language_content = array();
	private $admin_mode = false;
		
	
	public function __construct($admin_mode = false) {
		$this->admin_mode = $admin_mode;
		$lang = DEFAULT_LANG;
		$this->init();
	}
	
		
	/**
	 * Return the language tag that is currently in use.
	 *
	 * @return string
	 */
	public function getLanguageTag() {
		return $this->language_tag;
	}
	
	/**
	 * Check if default language is beging used.
	 *
	 * @return bool
	 */
	public function isUsingDefault() {
		if(strcmp($this->language_tag, DEFAULT_LANG) == 0) {
			return true;
		} else {
			return false;
		}
		
	}
	
	/**
	 * Prepares the class for paths in upper directories.
	 *
	 */
	public function setLevelUp() {
		$this->admin_mode = true;
	}
	
	/**
	 * Return an array of all available language tags from the known language files on HD.
	 *
	 * @return array
	 */
	public function getAvailableLanguages() {
		return array('languages' => $this->avail_languages, 
					 'tags' => $this->avail_language_tags,
					 'files' => $this->avail_language_files);
	}
	
	/**
	 * Returns the contents from an whole language file.
	 *
	 * Returns the file contents as an 2 dimensional array containing all
	 * the language definition.
	 *
	 * @param int $index
	 * @return array
	 */
	public function getFileContents($index) {
		if ($this->admin_mode) {
			$tag = $this->avail_language_tags[$index];
			return $this->avail_language_content[$tag];
		}
	}
	
	public function getLangDump($index) {
		$tag = $this->avail_language_tags[$index];
		return $this->avail_language_content[$tag];
		
	}
	
	
/**
 * @return void
 * @param $language String
 * @desc Load language into the Language class
 */
	public function load($lang) {
		
		if (in_array ($lang, $this->avail_language_tags)) {
		    $this->language_tag = $lang;
		    
		  	$i = 0;
		  	$index = -1;
		  	foreach ($this->avail_language_tags as $tag)  {
		  		if (strcmp($tag, $lang) == 0) {
		  			$index = $i;
		  			break;
		  		}
		  		$i++;
		  	}
		    
		  	
		  	$this->language_name = $this->avail_languages[$index];
		  			  	
		  	//update current session with current language
		  	$_SESSION['vcdlang'] = $this->language_tag;
	  	
		}

	}
	
	
	
	/**
	 * Return an translated phrase by the given associated index.
	 *
	 * @param string $word
	 * @return string
	 */
	public function show($word) {
		if (isset($this->avail_language_content[$this->language_tag][$word])) {
			return $this->avail_language_content[$this->language_tag][$word];
		} else {
			return "undefined";
		}
	}
	
	
		
	/**
	* @return String
	* @desc Prints out the HTML dropdown box for language selection on the site.
 */
	public function printDropdownBox() {
		$i = 0;
		$html = "<div id=\"lang\"><form name=\"vcdlang\" method=\"post\" action=\"./?\"> ";
		$html .= "<select name=\"lang\" onchange=\"document.vcdlang.submit()\" class=\"inp\">";
		foreach ($this->avail_languages as $lang) { 
			$strSelected = "";
			if (strcmp($lang, $this->language_name) == 0) {
				$strSelected = "selected=\"selected\"";
			}
			
			$html .= "<option value=\"".$this->avail_language_tags[$i++]."\" $strSelected>$lang</option>";
		}
		$html .= "</select>";
		$html .= "</form></div>";
		return $html;
		
	}
		
 	
 	
 	/**
 	 * Extra initialation of the language class.
 	 *
 	 */
 	private function init() {
 		$this->getAvailableLanguageFiles();
 		$this->language_tag = DEFAULT_LANG;
 		$this->language_name = "English";
 	}
	
	
 	/**
	* @return Array
	* @param $arr Array of directories
	* @desc Scan the submitted directories for language files
	* @access Private
 */
	private function getAvailableLanguageFiles() {
		
		$arrLanguageFiles = array();
		$arrLanguageFiles = $this->findfile(LANGUAGE_FILE_ROOT,'/\.(php)$/');
		
		// Call from RSS probably
		if (!is_array($arrLanguageFiles)) {
			$arrLanguageFiles = $this->findfile("../".LANGUAGE_FILE_ROOT,'/\.(php)$/');	
		}
		
				
		foreach ($arrLanguageFiles as $file) {
			$filename = split('//',$file);
			$x = substr($filename[1],0,5);
			if (strcmp($x, 'lang_') == 0) {
				array_push($this->avail_language_files, $filename[1]);
			}
		}
		
				
		// Language files are now known to the class ..
		// Lets check out what languages we have
		
		if (!$this->admin_mode) { 
			foreach ($this->avail_language_files as $language_file) {
				
				if (file_exists(LANGUAGE_FILE_ROOT.$language_file)) {
	
					if (is_readable(LANGUAGE_FILE_ROOT.$language_file)) {
						require_once(LANGUAGE_FILE_ROOT.$language_file);
						$currLang = &$_;
					
						// Add the Language name and tag to our class
						array_push($this->avail_language_tags, $currLang['LANG_TYPE']);
						array_push($this->avail_languages, $currLang['LANG_NAME']);
						$this->avail_language_content[$currLang['LANG_TYPE']] = $currLang;
					}
				}
			}
		} else {
			foreach ($this->avail_language_files as $language_file) {
			
				if (file_exists("../".LANGUAGE_FILE_ROOT.$language_file)) {
			
					if (is_readable("../".LANGUAGE_FILE_ROOT.$language_file)) {
						require_once("../".LANGUAGE_FILE_ROOT.$language_file);
						$currLang = &$_;
					
						// Add the Language name and tag to our class
						array_push($this->avail_language_tags, $currLang['LANG_TYPE']);
						array_push($this->avail_languages, $currLang['LANG_NAME']);
						$this->avail_language_content[$currLang['LANG_TYPE']] = $currLang;
					}
				}
			}
		}
	}

	
	
	
	/**
	* @return Array
	* @param $location String
	* @param $fileregex String
	* @desc Search folder for files with certain extensions defined in the $fileregex parameter.
	* @access Private
 */
	private function findfile($location='',$fileregex='') {
   		if (!$location or !is_dir($location) or !$fileregex) {
       		return false;
   		}
 
		$matchedfiles = array();
	 
	   	$all = opendir($location);
	   	while ($file = readdir($all)) {
	       	if (is_dir($location.'/'.$file) and $file <> ".." and $file <> ".") {
	         	$subdir_matches = $this->findfile($location.'/'.$file,$fileregex);
	         	$matchedfiles = array_merge($matchedfiles,$subdir_matches);
	         	unset($file);
	       	}
	       	elseif (!is_dir($location.'/'.$file)) {
	         	if (preg_match($fileregex,$file)) {
	             	array_push($matchedfiles,$location.'/'.$file);
	         	}
		       }
	   		}	
	   	   closedir($all);
		   unset($all);
	       return $matchedfiles;
 	}
	
	
	
	
}


?>