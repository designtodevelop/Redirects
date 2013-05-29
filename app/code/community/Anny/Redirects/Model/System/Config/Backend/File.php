<?php

class Anny_Redirects_Model_System_Config_Backend_File extends Mage_Adminhtml_Model_System_Config_Backend_File {
	protected function _getUploadDir() {
		$dir = parent::_getUploadDir();
		if( ! is_dir($dir)) {
			// Create directory if it doesn't already exist
			mkdir($dir);
		}
		
		return $dir;
	}

	// Fix upload root to allow use of config attribute on upload_dir to change root upload directory
	protected function _getUploadRoot($token) {
		return Mage::getBaseDir($token);
	}
}
