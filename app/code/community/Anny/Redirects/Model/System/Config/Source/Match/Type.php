<?php

class Anny_Redirects_Model_System_Config_Source_Match_Type {
	public function toArray() {
		$opts = $this->toOptionArray();
		$dict = array();
		foreach( $opts as $opt ) {
			$dict[$opt['value']] = $opt['label'];
		}

		return $dict;
	}

	public function toOptionArray() {
		return array(
			array( 'value' => 'full', 'label' => Mage::helper('redirects')->__('Full')),
			array( 'value' => 'partial', 'label' => Mage::helper('redirects')->__('Partial')),
			array( 'value' => 'regex', 'label' => Mage::helper('redirects')->__('Regular Expression'))
		);
	}
}
