<?phpclass Admin_Bootstrap extends Zend_Application_Module_Bootstrap{    /**     * Session Expiration Time in Seconds     */    const SESSION_EXP_SEC = 604800;    protected function _initAutoload()    {        $autoLoader = new Zend_Application_Module_Autoloader(array(            'namespace' => 'Admin_',            'basePath' => dirname(__FILE__),        ));        $ns = new Zend_Session_Namespace('session');        if ($ns->initialize == '') {            Zend_Session::start();            $ns->initialize = true;            $ns->setExpirationSeconds(self::SESSION_EXP_SEC);        }        return $autoLoader;    }}