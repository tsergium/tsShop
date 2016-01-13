<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Namespace for Module
	 */
	const NAMESPACE = 'Default_';

	/**
	 * Session Expiration Time in Seconds
	 */
	const SESSION_EXP_SEC = 604800;

	/**
	 * Initialise frontend routes
	 */
	protected function _initRoutes()
	{
		include APPLICATION_PATH . "/configs/routes.php";
	}

    protected function _initAutoload()
    {
		$autoLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => self::NAMESPACE,
            'basePath'  => dirname(__FILE__),
        ));
		
		$ns = new Zend_Session_Namespace('session');
		if ($ns->initialize == '') {
			Zend_Session::start();
			$ns->initialize = true;
			$ns->setExpirationSeconds(self::SESSION_EXP_SEC);
		}
		
        return $autoLoader;
    }
}