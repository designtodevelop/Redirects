<?php

class Anny_Redirects_Helper_Data extends Mage_Core_Helper_Abstract {
	const MATCH_TYPE_FULL = 'full';
	const MATCH_TYPE_PARTIAL = 'partial';
	const MATCH_TYPE_REGEX = 'regex';

	public function isEnabled() {
		return (bool)Mage::getStoreConfig('redirects/general/enabled');
	}

	public function isNorouteRedirectOnly() {
		return (bool)Mage::getStoreConfig('redirects/general/noroute_only');
	}

	public function testAgainstFullUrl() {
		return (bool)Mage::getStoreConfig('redirects/match/include_host');
	}

	public function getMatchType() {
		return Mage::getStoreConfig('redirects/match/type');
	}

	public function shouldEscapeSlashesForRegex() {
		return (bool)Mage::getStoreConfig('redirects/match/type_regex_escape_slashes');
	}

	public function shouldBreakOnMatch() {
		return (bool)Mage::getStoreConfig('redirects/general/use_first_match');
	}

	public function getCsvFilePath() {
		return Mage::getBaseDir('var').DS.'redirects'.DS.Mage::getStoreConfig('redirects/match/csv_file');
	}

	public function getCsvFileHandle( $path=null, $mode='r' ) {
		if( ! $path ) {
			$path = $this->getCsvFilePath();
		}

		if( file_exists($path)) {
			$handle = fopen( $path, $mode );
			return $handle;
		}

		return false;
	}

	public function closeCsvFileHandle($handle) {
		fclose($handle);
	}
}
