<?php
class Admin_SliderController extends Zend_Controller_Action
{
    public function init()
    {
    	/* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
		$model = new Default_Model_Slider();
		$select = $model->getMapper()->getDbTable()->select()
				->order('created DESC');
		$slides = $model->fetchAll($select);
		if(null != $slides){
			$this->view->result = $slides;
		}
    }

    public function addAction()
    {
		$form = new Admin_Form_Slider();
		$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/slider/SliderAdd.phtml'))));
		if($this->getRequest()->isPost()) {
			if($form->isValid($this->getRequest()->getPost())) {
				$post = $this->getRequest()->getPost();
				$model = new Default_Model_Slider();
				$model->setOptions($post);
            	if($form->image->receive()) {
					if($form->image->getFileName()) {
						$tmp = pathinfo($form->image->getFileName());
						$extension = (!empty($tmp['extension']))?$tmp['extension']:null;
						$filename = md5(uniqid(mt_rand(), true)).".".$extension;
						if(copy($form->image->getFileName(), APPLICATION_PUBLIC_PATH.'/media/slides/'.$filename)) {
//							require_once APPLICATION_PUBLIC_PATH.'/library/App/tsThumb/ThumbLib.inc.php';
//							$thumb = PhpThumbFactory::create(APPLICATION_PUBLIC_PATH.'/media/slides/'.$filename);
//							$thumb->resize(735, 348)->save(APPLICATION_PUBLIC_PATH.'/media/slides/'.$filename);
							$model->setImage($filename);
						}
					}
            	}
            	if($model->save()) {
	           		$this->_flashMessenger->addMessage('<div class="mess-true">Slide-ul a fost adaugat cu succes!</div>');
            	} else {
	           		$this->_flashMessenger->addMessage('<div class="mess-false">Eroare la adaugarea slide-ului!</div>');
            	}
			}
	    	$this->_redirect('admin/slider');
		}
		$this->view->form = $form;
    }

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$model = new Default_Model_Slider();
		$model->find($id);
		if(null != $model){
			$form = new Admin_Form_Slider();
			$form->edit($model);
			$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/slider/SliderEdit.phtml'))));
			   
			if($this->getRequest()->isPost()) {
				    echo "<pre>" . print_r($form->isValid($this->getRequest()->getPost()), 1) . "</pre>";
			    if($form->isValid($this->getRequest()->getPost())) {
				$post = $this->getRequest()->getPost();
				$oldImg = $model->getImage();
				$model->setOptions($post);				
				if($form->image->receive()) {
				    if($form->image->getFileName()) {
					    $tmp = pathinfo($form->image->getFileName());
					    $extension = (!empty($tmp['extension']))?$tmp['extension']:null;
					    $filename = md5(uniqid(mt_rand(), true)).".".$extension;
					    if(copy($form->image->getFileName(), APPLICATION_PUBLIC_PATH.'/media/slides/'.$filename)) {
//						    require_once APPLICATION_PUBLIC_PATH.'/library/App/tsThumb/ThumbLib.inc.php';
//						    $thumb = PhpThumbFactory::create(APPLICATION_PUBLIC_PATH.'/media/slides/'.$filename);
//						    $thumb->resize(735, 348)->save(APPLICATION_PUBLIC_PATH.'/media/slides/'.$filename);
//						 
						    @unlink(APPLICATION_PUBLIC_PATH.'/media/slides/'.$oldImg);
						    $model->setImage($filename);
					    }
				    }
				}
			
				if($model->save()) {
				    $this->_flashMessenger->addMessage('<div class="mess-true">Slide-ul a fost modificat cu succes!</div>');
				} else {
				    $this->_flashMessenger->addMessage('<div class="mess-false">Eroare la modificarea slide-ului!</div>');
				}
				$this->_redirect('admin/slider/');
			    }
			
		    }
			
		    $this->view->form = $form;
		}else{
			$this->_flashMessenger->addMessage('<div class="mess-false">Slide-ul selectat nu exista!</div>');
			$this->_redirect('admin/slider');
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$model = new Default_Model_Slider();
		$model->find($id);
		if(null != $model){
			$image = $model->getImage();
			if($model->delete()){
				@unlink(APPLICATION_PUBLIC_PATH.'/media/slides/'.$image);
				$this->_flashMessenger->addMessage('<div class="mess-true">Slide-ul a fost sters!</div>');
			}else{
				$this->_flashMessenger->addMessage('<div class="mess-false">Eroare la stergerea slide-ului!</div>');
			}
		}else{
			$this->_flashMessenger->addMessage('<div class="mess-false">Slide-ul selectat nu exista!</div>');
		}
		$this->_redirect('admin/slider');
	}
}