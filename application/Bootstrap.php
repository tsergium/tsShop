<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * @var array Auto Load Namespaces
     */
    protected $autoLoadNamespaces = ['Base_'];

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

    protected function _initView()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $view->doctype('HTML5');
    }

    protected function _initAutoload()
    {
        foreach ($this->autoLoadNamespaces as $namespace) {
            Zend_Loader_Autoloader::getInstance()->registerNamespace($namespace);
        }

        $autoLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default_',
            'basePath' => dirname(__FILE__),
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