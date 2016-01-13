<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Initialise frontend routes
	 */
	protected function _initRoutes()
	{
		include APPLICATION_PATH . "/configs/routes.php";
	}

    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default_',
            'basePath'  => dirname(__FILE__),
        ));
		
		$ns = new Zend_Session_Namespace('session');
		if ($ns->initialize == '') {
			Zend_Session::start();
			$ns->initialize = true;
			$ns->setExpirationSeconds(604800);
		}
		
        return $autoloader;
    }
}