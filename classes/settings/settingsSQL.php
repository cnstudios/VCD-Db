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
 * @package Settings
 * @version $Id$
 */
 
?>
<?PHP

	class settingsSQL {
		
		private $TABLE_settings   = "vcd_Settings";
		private $TABLE_sites      = "vcd_SourceSites";
		private $TABLE_mediatypes = "vcd_MediaTypes";
		private $TABLE_categories = "vcd_MovieCategories";
		private $TABLE_vcd		  = "vcd";
		private $TABLE_vcdtousers = "vcd_VcdToUsers";
		private $TABLE_borrowers  = "vcd_Borrowers";
		private $TABLE_loans 	  = "vcd_UserLoans";
		private $TABLE_rss		  = "vcd_RssFeeds";
		private $TABLE_wishlist	  = "vcd_UserWishList";
		private $TABLE_comments	  = "vcd_Comments";
		private $TABLE_users	  = "vcd_Users";
		private $TABLE_covers 	  = "vcd_Covers";
		private $TABLE_metadata   = "vcd_MetaData";
		/**
		 *
		 * @var ADOConnection
		 */
		private $db;
	 			
		public function __construct() {
			$conn = new Connection();
	 		$this->db = &$conn->getConnection();
		}
		
		
		public function getAllSettings() {
			try {
			
			$query = "SELECT settings_id, settings_key, settings_value, settings_description, isProtected, settings_type FROM
					  $this->TABLE_settings ORDER BY settings_key";
			
			$rs = $this->db->Execute($query);
						
			$arrSettingsObj = array();
			foreach ($rs as $row) {
	    		$obj = new settingsObj($row);
	    		array_push($arrSettingsObj, $obj);
			}
			
			$rs->Close();
			return $arrSettingsObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		
		public function updateSettings($settingsObj) {
			try {
				
			$query = "UPDATE $this->TABLE_settings SET 
					  settings_key = '".$settingsObj->getKey()."',
					  settings_value = ".$this->db->qstr($settingsObj->getValue()).",
					  settings_description = ".$this->db->qstr($settingsObj->getDescription()).",
					  isProtected = ".$settingsObj->isProtected()."
					  WHERE settings_id = ".$settingsObj->getID()."";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function saveSettings($settingsObj) {
			try {
				
			if (is_array($settingsObj)) {
				
				foreach ($settingsObj as $obj) {
					$query = "INSERT INTO $this->TABLE_settings 
							  (settings_key, settings_value, settings_description, isProtected) 
							   VALUES ('".$settingsObj->getKey()."','".$settingsObj->getValue()."','".$settingsObj->getDescription()."', ".$settingsObj->isProtected().")";
					$this->db->Execute($query);
				}
				
			} else {
				$query = "INSERT INTO $this->TABLE_settings 
						 (settings_key, settings_value, settings_description, isProtected) 
						  VALUES ('".$settingsObj->getKey()."',".$this->db->qstr($settingsObj->getValue()).",
						  ".$this->db->qstr($settingsObj->getDescription()).", ".$settingsObj->isProtected().")";
				$this->db->Execute($query);
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		
		public function deleteSettings($settings_id) {
			try {
				
			$query = "DELETE FROM $this->TABLE_settings WHERE settings_id = " .$settings_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function getSettingsByID($settings_id) {
			try {
				
			$query = "SELECT settings_id, settings_key, settings_value, settings_description, isProtected, settings_type FROM
					  $this->TABLE_settings WHERE settings_id = ". $settings_id;
			$rs = $this->db->Execute($query);
			if ($rs) {
				return new settingsObj($rs->FetchRow());
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		
		
		/* 
			SOURCE SITE FUNCTIONS
		
		*/
		
		public function getSourceSites() {
			try {
				
			$query = "SELECT site_id, site_name, site_alias, site_homepage, site_getCommand, site_isFetchable
					  FROM $this->TABLE_sites ORDER BY site_name";
			
			$rs = $this->db->Execute($query);
						
			$arrObj = array();
			foreach ($rs as $row) {
	    		$obj = new sourceSiteObj($row);
	    		array_push($arrObj, $obj);
			}
			
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function addSourceSite(sourceSiteObj $obj) {
			try {
				
			$query = "INSERT INTO $this->TABLE_sites
					  (site_name, site_alias, site_homepage, site_getCommand, site_isFetchable) VALUES 
					  (".$this->db->qstr($obj->getName()).",
					   ".$this->db->qstr($obj->getAlias()).",
					   ".$this->db->qstr($obj->getHomepage()).",
					   ".$this->db->qstr($obj->getCommand()).",".(int)$obj->isFetchable().")";
			
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function updateSourceSite(sourceSiteObj $obj) {
			try {
				
			$query = "UPDATE $this->TABLE_sites SET 
					  site_name = '".$obj->getName()."',
					  site_alias = '".$obj->getAlias()."',
					  site_homepage = '".$obj->getHomepage()."',
					  site_getCommand = '".$obj->getCommand()."',
					  site_isFetchable = ".(int)$obj->isFetchable()."
					  WHERE site_id = " . $obj->getsiteID();
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		public function deleteSourceSite($source_id) {
			try {
				
			$query = "DELETE FROM $this->TABLE_sites WHERE site_id = " . $source_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		/*
			MEDIA TYPE Functions			
		
		*/
		
		public function getAllMediaTypes() {
			try {
				
			$query = "SELECT media_type_id, media_type_name, parent_id, media_type_description
				      FROM $this->TABLE_mediatypes 
					  ORDER BY parent_id, media_type_name";
			
			$rs = $this->db->Execute($query);
						
			$arrObj = array();
			foreach ($rs as $row) {
	    		$obj = new mediaTypeObj($row);
	    		array_push($arrObj, $obj);
			}
			
			$rs->Close();
			
			
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function addMediaType(mediaTypeObj $mediaTypeObj) {
			try {
				
			$query = "INSERT INTO $this->TABLE_mediatypes 
					  (media_type_name, parent_id, media_type_description) 
					  VALUES ('".$mediaTypeObj->getName()."',
					  ".$mediaTypeObj->getParentID().",'".$mediaTypeObj->getDescription()."')";
		
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		public function deleteMediaType($mediatype_id) {
			try {
				
			$query = "DELETE FROM $this->TABLE_mediatypes WHERE media_type_id = " . $mediatype_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		public function updateMediaType(mediaTypeObj $mediaTypeObj) {
			try {
				
			$query = "UPDATE $this->TABLE_mediatypes SET media_type_name = '".$mediaTypeObj->getName()."',
					  parent_id = ".$mediaTypeObj->getParentID().",
					  media_type_description = '".$mediaTypeObj->getDescription()."'
					  WHERE media_type_id = ".$mediaTypeObj->getmediaTypeID()."";
			$this->db->Execute($query);
		
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function getMediaTypesOnCD($vcd_id) {
			try {
				
			$query = "SELECT DISTINCT media_type_id FROM $this->TABLE_vcdtousers WHERE vcd_id = ".$vcd_id."";
			$resultArr = array();
			$rs = $this->db->Execute($query);
			foreach ($rs as $row) {
				array_push($resultArr, $row[0]);
			}
			$rs->Close();
			return $resultArr;
			
		
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function getMediaTypesInUseByUserID($user_id) {
			try {
				
			$query = "SELECT m.media_type_id, m.media_type_name, COUNT(u.media_type_id) AS media_count
					  FROM $this->TABLE_mediatypes AS m
					  INNER JOIN $this->TABLE_vcdtousers AS u ON m.media_type_id = u.media_type_id 
						AND u.user_id = ".$user_id."
					  GROUP BY m.media_type_id, m.media_type_name
					  ORDER BY m.media_type_id";
			
			$rs = $this->db->Execute($query);
			$arr = $rs->GetRows();
			$rs->Close( );
			return $arr;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		public function getMediaCountByCategoryAndUserID($user_id, $category_id) {
			try {
				
			$query = "SELECT u.media_type_id, COUNT(v.vcd_id) AS media_count 
					  FROM $this->TABLE_vcd AS v
					  INNER JOIN $this->TABLE_vcdtousers as u ON v.vcd_id = u.vcd_id 
						AND u.user_id = ".$user_id."
					  LEFT OUTER JOIN $this->TABLE_mediatypes AS m ON u.media_type_id = m.media_type_id
					  WHERE v.category_id = ".$category_id."
					  GROUP BY u.media_type_id";
			
			return $this->db->Execute($query)->getArray();	
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getCountByMediaType($mediatype_id) {
			try {
				
			$query = "SELECT COUNT(vcd_id) FROM $this->TABLE_vcdtousers WHERE media_type_id = " . $mediatype_id;
			return $this->db->getOne($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getMediaTypeByName($media_name) {
			try {
				
			$query = "SELECT * FROM $this->TABLE_mediatypes WHERE media_type_name = " . $media_name;
			$this->db->Execute($query);
			
			$arrObj = array();
			foreach ($rs as $row) {
	    		$obj = new mediaTypeObj($row);
			}
			
			$rs->Close();
			return $obj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/* 
			Movie Categories functions
		*/
		
		public function getAllMovieCategories() {
			try {
				
			$query = "SELECT category_id, category_name
					  FROM $this->TABLE_categories ORDER BY category_name";
			
			$rs = $this->db->Execute($query);
						
			$arrObj = array();
			foreach ($rs as $row) {
	    		$obj = new movieCategoryObj($row);
	    		array_push($arrObj, $obj);
			}
			
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getMovieCategoriesInUse() {
			try {
				
			$query = "SELECT DISTINCT c.category_id, c.category_name FROM $this->TABLE_categories c, $this->TABLE_vcd v
					  WHERE c.category_id = v.category_id
					  ORDER BY c.category_name";
			$rs = $this->db->Execute($query);
						
			$arrObj = array();
			foreach ($rs as $row) {
	    		$obj = new movieCategoryObj($row);
	    		array_push($arrObj, $obj);
			}
			
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function getCategoriesInUseByUserID($user_id) {
			try {
				
			$query = "SELECT v.category_id, m.category_name FROM $this->TABLE_vcd v, 
					  $this->TABLE_vcdtousers u, $this->TABLE_categories m
					  WHERE v.vcd_id = u.vcd_id AND
					  u.user_id = ".$user_id." AND
					  v.category_id = m.category_id
					  GROUP BY v.category_id, m.category_name
					  ORDER BY m.category_name";
			
			$rs = $this->db->Execute($query);
						
			$arrObj = array();
			foreach ($rs as $row) {
	    		$obj = new movieCategoryObj($row);
	    		array_push($arrObj, $obj);
			}
			
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function addMovieCategory(movieCategoryObj $movieCategoryObj) {
			try {
				
			$query = "INSERT INTO $this->TABLE_categories (category_name) VALUES ('".$movieCategoryObj->getName()."')";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function deleteMovieCategory($category_id) {
			try {
				
			$query = "DELETE FROM $this->TABLE_categories WHERE category_id = " . $category_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function updateMovieCategory(movieCategoryObj $movieCategoryObj) {
			try {
				
			$query = "UPDATE $this->TABLE_categories SET category_name = '".$movieCategoryObj->getName()."'";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getMovieCategoriesByName($category_name) {
			try {
				
			$query = "SELECT * FROM $this->TABLE_categories WHERE category_name = " . $category_name;
			$this->db->Execute($query);
			
			$arrObj = array();
			foreach ($rs as $row) {
	    		$obj = new movieCategoryObj($row);
			}
			
			$rs->Close();
			return $obj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
			
		
		/* 
			Borrowers functions
		*/
		public function getBorrowerByID($borrower_id) {
			try {
				
			$query = "SELECT borrower_id, owner_id, name, email FROM $this->TABLE_borrowers
					  WHERE borrower_id = " . $borrower_id;
			$rs = $this->db->Execute($query);
			$arrObj = array();
			foreach ($rs as $row) {
	    		$obj = new borrowerObj($row);
	    		array_push($arrObj, $obj);
			}
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		public function getBorrowersByUserID($user_id) {
			try {
				
			$query = "SELECT borrower_id, owner_id, name, email FROM $this->TABLE_borrowers
					  WHERE owner_id = ".$user_id." ORDER BY name";
			$rs = $this->db->Execute($query);
			$arrObj = array();
			foreach ($rs as $row) {
	    		$obj = new borrowerObj($row);
	    		array_push($arrObj, $obj);
			}
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function addBorrower(borrowerObj $borrowerObj) {
			try {
				
			$query = "INSERT INTO $this->TABLE_borrowers (owner_id, name, email) 
					  VALUES (".$borrowerObj->getOwnerID().", ".$this->db->qstr($borrowerObj->getName()).", 
					  ".$this->db->qstr($borrowerObj->getEmail()).")";			
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function updateBorrower(borrowerObj $borrowerObj) {
			try {
				
			$query = "UPDATE $this->TABLE_borrowers SET 
					  name = ".$this->db->qstr($borrowerObj->getName()).",
					  email = ".$this->db->qstr($borrowerObj->getEmail())."
					  WHERE borrower_id = ".$borrowerObj->getID()."";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function deleteBorrower($borrower_id) {
			try {
			
				$query = "DELETE FROM $this->TABLE_borrowers WHERE borrower_id = " . $borrower_id;
				$this->db->Execute($query);
				
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/* 
			Loan system functions
		*/
		
		public function loanCDs($user_id, $borrower_id, $cd_id) {
			try {
				
			$query = "INSERT INTO $this->TABLE_loans (vcd_id, owner_id, borrower_id, date_out) 
					  VALUES (".$cd_id.", ".$user_id.", ".$borrower_id.", ".$this->db->DBDate(time()).")";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function loanReturn($loan_id) { 
			try {
				
			$query = "UPDATE $this->TABLE_loans SET date_in = ".$this->db->DBDate(time())." WHERE loan_id = " . $loan_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getLoans($user_id, $show_returned) { 
			try {
				
			if ($show_returned) {
				$query = "SELECT DISTINCT l.loan_id, l.vcd_id, v.title, l.borrower_id, l.date_out, l.date_in
						  FROM $this->TABLE_loans l, $this->TABLE_vcdtousers u, $this->TABLE_vcd v
						  WHERE l.owner_id = ".$user_id." AND l.vcd_id = u.vcd_id AND
						  u.user_id = ".$user_id." AND u.vcd_id = v.vcd_id
						  ORDER BY l.borrower_id, l.date_out DESC";
			} else {
				$query = "SELECT DISTINCT l.loan_id, l.vcd_id, v.title, l.borrower_id, l.date_out, l.date_in
						  FROM $this->TABLE_loans l, $this->TABLE_vcdtousers u, $this->TABLE_vcd v
						  WHERE l.date_in IS NULL AND
						  l.owner_id = ".$user_id." AND l.vcd_id = u.vcd_id AND
						  u.user_id = ".$user_id." AND u.vcd_id = v.vcd_id
						  ORDER BY l.borrower_id, l.date_out DESC";
			}
			
			$rs = $this->db->Execute($query);
			$arrObj = array();
			foreach ($rs as $row) {
				
				$data = array($row[0], $row[1], $row[2], $row[3], $this->db->UnixDate($row[4]), $this->db->UnixDate($row[5]));
	    		array_push($arrObj, $data);
			}
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		public function deleteLoanRecords($borrower_id) {
			try {
			
				$query = "DELETE FROM $this->TABLE_loans WHERE borrower_id = " . $borrower_id;
				$this->db->Execute($query);
				
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		
		
		/* RSS Feed functions */
		
		public function addRssfeed($user_id, $feed_name, $feed_url) {
			try {
				
			$query = "INSERT INTO $this->TABLE_rss (user_id, feed_name, feed_url) VALUES 
					 (".$user_id.",".$this->db->qstr($feed_name).", ".$this->db->qstr($feed_url).")";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function updateRssfeed($feed_id, $feed_name, $feed_url) {
			try {
				
			$query = "UPDATE $this->TABLE_rss SET feed_name = ".$this->db->qstr($feed_name).", 
					  feed_url = ".$this->db->qstr($feed_url)." WHERE feed_id =  " . $feed_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getRSSfeedByID($feed_id) {
			try {
				
			$query = "SELECT feed_id, feed_name, feed_url FROM $this->TABLE_rss WHERE feed_id = " . $feed_id;
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				$row = $rs->FetchRow();
				$arr = array('id' => $row[0], 'name' => $row[1], 'url' => $row[2]);
				$rs->Close();
				return $arr;
			} 
			return null;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getRssFeedsByUserId($user_id) {
			try {
				
			$query = "SELECT feed_id, feed_name, feed_url FROM $this->TABLE_rss WHERE user_id = ".$user_id." 
					  ORDER BY feed_name";
			$rs = $this->db->Execute($query);
			$arrResults = array();
			foreach ($rs as $row) {
				array_push($arrResults, array('id' => $row[0], 'name' => $row[1], 'url' => $row[2]));
			}
			
			$rs->Close();
			return $arrResults;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function delFeed($feed_id) {
			try {
				
			$query = "DELETE FROM $this->TABLE_rss WHERE feed_id = " . $feed_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getVCDIDsByUser($user_id) {
			try {
				
			$query = "SELECT vcd_id FROM $this->TABLE_vcdtousers WHERE user_id = " . $user_id;
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				$arrIDS = array();
				foreach ($rs as $row) {
					array_push($arrIDS, $row[0]);
				}
				$rs->Close();
				return $arrIDS;
			} else {
				return null;
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
	
		public function addToWishList($vcd_id, $user_id) {
			try {
				
			$query = "INSERT INTO $this->TABLE_wishlist (user_id, vcd_id) VALUES (".$user_id.", ".$vcd_id.")";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		public function getWishList($user_id) {
			try {
				
			$query = "SELECT w.vcd_id, v.title FROM $this->TABLE_wishlist w, $this->TABLE_vcd v
					  WHERE w.user_id = ".$user_id." AND w.vcd_id = v.vcd_id ORDER BY v.title";
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				return $rs->GetArray();
			} else {
				return null;
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		public function isOnWishList($vcd_id, $user_id) {
			try {
				
			$query = "SELECT vcd_id FROM $this->TABLE_wishlist WHERE user_id = ".$user_id." AND 
					  vcd_id = ".$vcd_id."";
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				$rs->Close();
				return true;
			} else {
				$rs->Close();
				return false;
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		
		public function removeFromWishList($vcd_id, $user_id) {
			try {
				
			$query = "DELETE FROM $this->TABLE_wishlist WHERE vcd_id = ".$vcd_id." AND user_id = ".$user_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/* Comments */
		
		public function addComment(commentObj $obj) {
			try {
				
			$query = "INSERT INTO $this->TABLE_comments (vcd_id, user_id, comment_date, comment, isPrivate) 
					  VALUES (".$obj->getVcdID().", ".$obj->getOwnerID().", ".$this->db->DBDate(time()).",
					  ".$this->db->qstr($obj->getComment()).", ".$obj->isPrivate().")";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function deleteComment($comment_id) {
			try {
				
			$query = "DELETE FROM $this->TABLE_comments WHERE comment_id = " . $comment_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		public function getCommentByID($comment_id)  {
			try {
				
			$query = "SELECT comment_id, vcd_id, user_id, comment_date, comment, isPrivate FROM
					  $this->TABLE_comments WHERE comment_id = ".$comment_id." ORDER BY comment_date DESC";
			$rs = $this->db->Execute($query);
			if ($rs) {
				return new commentObj($rs->FetchRow());
			}			
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getAllCommentsByUserID($user_id) {
			try {
				
			$query = "SELECT comment_id, vcd_id, user_id, comment_date, comment, isPrivate FROM
					  $this->TABLE_comments WHERE user_id = ".$user_id." ORDER BY comment_date DESC";
			
			$rs = $this->db->Execute($query);
			$arrObj = array();
			foreach ($rs as $row) {
	    		$obj = new commentObj($row);
	    		array_push($arrObj, $obj);
			}
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}
		
		public function getAllCommentsByVCD($vcd_id) {
			try {
				
			$query = "SELECT c.comment_id, c.vcd_id, c.user_id, c.comment_date, c.comment, 
					  c.isPrivate, u.user_name FROM
					  $this->TABLE_comments AS c
					  LEFT OUTER JOIN $this->TABLE_users AS u ON c.user_id = u.user_id
					  WHERE c.vcd_id = ".$vcd_id." ORDER BY c.comment_date DESC";
						
			$rs = $this->db->Execute($query);
			$arrObj = array();
			foreach ($rs as $row) {
				$data = array($row[0], $row[1], $row[2], $this->db->UnixDate($row[3]), $row[4], $row[5], $row[6]);
	    		$obj = new commentObj($data);
	    		array_push($arrObj, $obj);
			}
			$rs->Close();
			return $arrObj;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		/* Metadata objects */
		
		public function addMetadata(metadataObj $obj) {
			try {
				
			$query = "INSERT INTO $this->TABLE_metadata (record_id, user_id, metadata_name, metadata_value) VALUES (".$obj->getRecordID().", ".$obj->getUserID().", 
					  ".$this->db->qstr($obj->getMetadataName()).", ".$this->db->qstr($obj->getMetadataValue()).")";
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function updateMetadata(metadataObj $obj) {
			try {
				
			$query = "UPDATE $this->TABLE_metadata SET metadata_value = ".$this->db->qstr($obj->getMetadataValue())." 
					 WHERE metadata_id = " . $obj->getMetadataID();
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function deleteMetadata($metadata_id) {
			try {
				
			$query = "DELETE FROM $this->TABLE_metadata WHERE metadata_id = " . $metadata_id;
			$this->db->Execute($query);
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getMetadata($record_id, $user_id, $metadata_name) {
			try {
				
			$metaArr = array();
			if (strlen($metadata_name) == 0) {
				$query = "SELECT metadata_id, record_id, user_id, metadata_name, metadata_value FROM 
						  $this->TABLE_metadata WHERE record_id = ".$record_id." AND user_id = " . $user_id ." 
						  ORDER BY metadata_name";
			} else {
				$query = "SELECT metadata_id, record_id, user_id, metadata_name, metadata_value FROM 
						  $this->TABLE_metadata WHERE record_id = ".$record_id." AND user_id = " . $user_id . " 
				 		  AND metadata_name = " . $this->db->qstr($metadata_name) . " ORDER BY metadata_name";
			}
			
						
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				foreach ($rs as $row) {
					$obj = new metadataObj($row);
					array_push($metaArr, $obj);
				}
			}
			
			return $metaArr;
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		public function getRecordIDsByMetadata($user_id, $metadata_name) {
			try {
				
			$query = "SELECT record_id FROM $this->TABLE_metadata WHERE user_id = ".$user_id." 
					  AND metadata_name = " . $this->db->qstr($metadata_name) ." AND 
					  (metadata_value <> '0' AND metadata_value <> '')";
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				$arr = array();
				foreach ($rs as $row) {
					array_push($arr, $row[0]);
				}
				$rs->Close();
				return $arr;
			} else {
				return null;
			}
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
		
		public function getStatsObj() {
			try {
				
			$query = "SELECT COUNT(vcd_id) FROM $this->TABLE_vcd";
			$totalmoviecount = $this->db->getOne($query);
			
			$today = date('Y-m-d',time() - 3600*24);
			$week = date('Y-m-d',time() - 3600*24*7);
			$month = date('Y-m-d',time() - 3600*24*30);

			$zdate = $this->db->DBDate($today);		
			$query = "SELECT COUNT(vcd_id) FROM $this->TABLE_vcdtousers WHERE date_added > " . $zdate;
			$todaycount = $this->db->getOne($query);
			
			$zdate = $this->db->DBDate($week);		
			$query = "SELECT COUNT(vcd_id) FROM $this->TABLE_vcdtousers WHERE date_added > " . $zdate;
			$weekcount = $this->db->getOne($query);
			
			$zdate = $this->db->DBDate($month);		
			$query = "SELECT COUNT(vcd_id) FROM $this->TABLE_vcdtousers WHERE date_added > " . $zdate;
			$monthcount = $this->db->getOne($query);
			
			
			$monthArrCats = array();
			$ArrCats = array();
			
			// Most movies added to categories this month
			$query = "SELECT v.category_id, COUNT(v.category_id) AS Num FROM $this->TABLE_vcd v, 
					  $this->TABLE_vcdtousers u
					  WHERE u.vcd_id = v.vcd_id AND u.date_added > ".$zdate."
					  GROUP BY v.category_id ORDER BY Num DESC";
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				$monthArrCats = $rs->GetArray();
				$rs->Close();
			}
			
			// Biggest categories total
			$query = "SELECT v.category_id, COUNT(v.category_id) AS Num FROM $this->TABLE_vcd v, 
					  $this->TABLE_vcdtousers u
					  WHERE u.vcd_id = v.vcd_id GROUP BY v.category_id ORDER BY Num DESC";
			$rs = $this->db->Execute($query);
			if ($rs && $rs->RecordCount() > 0) {
				$ArrCats = $rs->GetArray();
				$rs->Close();
			}
						
			
			$query = "SELECT COUNT(cover_id) FROM $this->TABLE_covers";
			$coverCount = $this->db->getOne($query); 
			
			$zdate = $this->db->DBDate($week);
			$query = "SELECT COUNT(cover_id) FROM $this->TABLE_covers WHERE date_added > " . $zdate;
			$coverCountWeek = $this->db->getOne($query); 
			
			$zdate = $this->db->DBDate($month);
			$query = "SELECT COUNT(cover_id) FROM $this->TABLE_covers WHERE date_added > " . $zdate;
			$coverCountMonth = $this->db->getOne($query); 
			
			$obj = new statisticsObj();
			$obj->setMovieCount($totalmoviecount);
			$obj->setMovieTodayCount($todaycount);
			$obj->setMovieWeeklyCount($weekcount);
			$obj->setMovieMonthlyCount($monthcount);
			$obj->setCoverCount($coverCount, $coverCountWeek, $coverCountMonth);
			$obj->setBiggestCats($ArrCats);
			$obj->setBiggestMonhtlyCats($monthArrCats);
			
			return $obj;
			
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}			
		
		}
		
		
		
		public function getUserStatistics($user_id) {
			try {
			
				$stat_array = array();
				
				$query = "SELECT v.year as year, COUNT(v.vcd_id) as num FROM $this->TABLE_vcd v,
						  $this->TABLE_vcdtousers u WHERE v.vcd_id = u.vcd_id AND
						  u.user_id = ".$user_id." GROUP BY v.year
						  ORDER BY v.year DESC";
				
				$arr = $this->db->Execute($query)->GetArray();
				if (is_array($arr) && sizeof($arr) > 0) {
					$stat_array['year'] = $arr;
				}
				
								
				
				$query = "SELECT v.category_id, COUNT(v.vcd_id) as num FROM $this->TABLE_vcd v,
						  $this->TABLE_vcdtousers u WHERE v.vcd_id = u.vcd_id AND
						  u.user_id = ".$user_id." GROUP BY v.category_id
						  ORDER BY num DESC";
				
				$arr = $this->db->Execute($query)->GetArray();
				if (is_array($arr) && sizeof($arr) > 0) {
					$stat_array['category'] = $arr;
				}
				

				
				$query = "SELECT u.media_type_id, COUNT(v.vcd_id) as num FROM $this->TABLE_vcd v,
						  $this->TABLE_vcdtousers u WHERE v.vcd_id = u.vcd_id AND
						  u.user_id = ".$user_id." GROUP BY u.media_type_id
						  ORDER BY num DESC";
				
				$arr = $this->db->Execute($query)->GetArray();
				if (is_array($arr) && sizeof($arr) > 0) {
					$stat_array['media'] = $arr;
				}
				
				return $stat_array;
				
				
				
				
			
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		}
		
		
	}


?>