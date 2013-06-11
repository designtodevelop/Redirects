<?php

class Anny_Redirects_Model_Redirector {
	protected function getHelper() {
		return Mage::helper('redirects');
	}

	public function doRedirectAfterRoute($observer) {
		if( ! $this->getHelper()->isEnabled()) return;

		$controller = $observer->getEvent()->getControllerAction();
		if( $this->getHelper()->isNorouteRedirectOnly()) {
			$noroute = Mage::getStoreConfig('web/default/no_route');
			$action = str_replace( '_', '/', $controller->getFullActionName());
			if( $action != $noroute ) return;
		}

		return $this->doRedirect($controller);
	}

	public function doRedirectOnNoroute($observer) {
		if( ! $this->getHelper()->isEnabled() || ! $this->getHelper()->isNorouteRedirectOnly()) return;
		$controller = $observer->getEvent()->getAction();
		return $this->doRedirect($controller);
	}

	protected function doRedirect($controller) {
		$request = $controller->getRequest();
		if( $this->getHelper()->testAgainstFullUrl()) {
			$url = rtrim( $request->getScheme().'://'.$request->getHttpHost().$request->getRequestUri());
		}
		else {
			$url = rtrim($request->getRequestUri());
		}

		if( $redirect = $this->testRedirects($url)) {
			$response = $controller->getResponse();
			$response->setRedirect( $redirect, 301 );
			$response->sendResponse();
			die;
		}
	}

	protected function testRedirects($url) {
		$helper = $this->getHelper();
		$redirect = null;

		if( $csv = $helper->getCsvFileHandle()) {
			$breakOnMatch = $this->getHelper()->shouldBreakOnMatch();

			switch( $helper->getMatchType()) {
				case $helper::MATCH_TYPE_PARTIAL:
					while( $line = fgetcsv($csv)) {
						list( $src, $dest ) = $line;
						if( strpos( $url, $src ) !== false ) {
							$redirect = $dest;
							if($breakOnMatch) break;
						}
					}
					break;
				case $helper::MATCH_TYPE_REGEX:
					$escape = $helper->shouldEscapeSlashesForRegex();
					while( $line = fgetcsv($csv)) {
						list( $src, $dest ) = $line;
						if($escape) {
							$regex = '/'.str_replace( '/', '\/', $src ).'/';
						}
						else {
							$regex = '/'.$src.'/';
						}
						if( @preg_match( $regex, $url )) {
							$redirect = $dest;
							if($breakOnMatch) break;
						}
					}
					break;
				default:
					while( $line = fgetcsv($csv)) {
						list( $src, $dest ) = $line;
						if( $src == $url ) {
							$redirect = $dest;
							if($breakOnMatch) break;
						}
					}
					break;
			}

			$helper->closeCsvFileHandle($csv);
		}

		return $redirect;
	}
}
