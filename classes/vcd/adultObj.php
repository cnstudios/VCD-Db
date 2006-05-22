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
 * @package Kernel
 * @subpackage Vcd
 * @version $Id$
 */
 
?>
<? 
	class adultObj extends fetchedObj implements XMLable {
		
		private $studioName;
		private $categories = array();
		private $actors = array();
		private $screenshots = 0;
		private $screenshotimages = array();
			
		
		/**
		 * Set the number of screenshots found with this item
		 *
		 * @param int $iCount
		 */
		public function setScreenShotCount($iCount) {
			if (is_numeric($iCount)) {
				$this->screenshots = $iCount;	
			}
		}
		
		/**
		 * Get the number of screenshots found with this item.
		 *
		 * @return int
		 */
		public function getScreenShotCount() {
			return $this->screenshots;
		}
		
		/**
		 * Get a array of image file locations on the remote server.
		 *
		 * @return array
		 */
		public function getScreenShotImages() {
			if ($this->screenshots > 0) {
				return $this->screenshotimages;
			} else {
				return null;
			}
		}
		
		/**
		 * Add a new screenshot image location to the object.
		 *
		 * @param string $strScreenShotFile
		 */
		public function addScreenShotFile($strScreenShotFile) {
			array_push($this->screenshotimages, $strScreenShotFile);
			$this->screenshots++;
		}
		
		
		/**
		 * Set the Studio Name
		 *
		 * @param string $strStudio
		 */
		public function setStudio($strStudio) {
			$this->studioName = $strStudio;
		}
		
		
		/**
		 * Get the studio name of the fetched movie.
		 *
		 * @return unknown
		 */
		public function getStudio() {
			return $this->studioName;
		}
		
		/**
		 * Add new adult category to the movie
		 *
		 * @param string $strAdultcategory
		 */
		public function addCategory($strAdultcategory) {
			array_push($this->categories, trim($strAdultcategory));
		}
		
		/**
		 * Get the adult categories associated with this film
		 *
		 * @return array
		 */
		public function getCategories() {
			return $this->categories;
		}
		
		/**
		 * Add a new actor/pornstar to the movie
		 *
		 * @param string $strActor
		 */
		public function addActor($strActor) {
			array_push($this->actors, trim($strActor));
		}
		
		/**
		 * Get all actors/pornstars added to this movie.
		 *
		 * @return unknown
		 */
		public function getActors() {
			return $this->actors;
		}
		
		public function toXML() {
			
			
		}
		
		
		
	}
	
	
	
?>