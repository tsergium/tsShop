<?php
class CmsController extends Zend_Controller_Action
{
	public function init()
    {
		/* Initialize action controller here */
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->message = $this->_flashMessenger->getMessages();
    }

	public function viewAction()
	{
		$page = $this->getRequest()->getParam('page');
		if(null != $page) {
			$this->view->seoId = $page;
		}
		$model = new Default_Model_Cms();
		$select = $model->getMapper()->getDbTable()->select()
				->where('url = ?', $page)
				->limit('1');
		if(($result = $model->fetchAll($select))) {
			$this->view->cmspage = $result[0];
		}
	}
}