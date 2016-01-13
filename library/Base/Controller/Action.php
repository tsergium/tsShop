<?php
/**
 * Created by PhpStorm.
 * User: sergiu
 * Date: 9/23/15
 * Time: 2:04 PM
 */
class Base_Controller_Action extends Zend_Controller_Action
{
    /**
     * init, initializes flash messenger
     */
    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function ajaxInit()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();
        $bootstrap = $this->getInvokeArg('bootstrap');
        if ($bootstrap->hasResource('db')) {
            $this->db = $bootstrap->getResource('db');
        }
    }

    /**
     * @param $formPassword
     * @param $account
     */
    protected function changePassword($formPassword, $account)
    {
        if ($formPassword->isValid($this->getRequest()->getPost())) {
            $post = $this->getRequest()->getPost();
            if (md5($post['oldPassword']) == $account->getPassword()) {
                $account->setPassword(md5($post['password']));
                if ($account->save()) {
                    $this->_flashMessenger->addMessage('<span class="mess-true">Modificarile au fost efectuate cu succes</span>');
                } else {
                    $this->_flashMessenger->addMessage('<span class="mess-true">Eroare! Parola nu a putut fi modificata.</span>');
                }
            } else {
                $this->_flashMessenger->addMessage('<span class="mess-false">Eroare! Parola veche eronata!</span>');
            }
        }
    }

    /**
     * handle request, used to handle social request between users
     * @param bool $accept
     * @throws Exception
     */
    protected function handleRequest($accept = true)
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_SocialUserConnections();
        if ($model->find($id)) {
            if ($accept) {
                $model->setIsConfirmed(1);
                $model->save();
            } else {
                $model->delete();
            }
        }
        $this->_redirect('/account/user-requests');
    }

    /**
     * Check if user authenticated and return account model if so
     * @return bool|mixed
     */
    protected function isAuthenticated()
    {
        $auth = Zend_Auth::getInstance();
        $authAccount = $auth->getStorage()->read();
        if (null != $authAccount) {
            $result = $authAccount;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Safely delete files
     * @param $filePaths
     */
    protected function safeDelete($filePaths)
    {
        $filePathData = (array)$filePaths;

        foreach ($filePathData as $filePath) {
            if (file_exists($filePath)) {
                unlink($filePath);
            } else {
                // ToDo: catch exception
            }
        }
    }

    protected function safeRemoveDir($dirNames)
    {
        $dirNameData = (array)$dirNames;

        foreach ($dirNameData as $dirName) {
            if (is_dir($dirName)) {
                rmdir($dirName);
            } else {
                // ToDo: catch exception
            }
        }
    }

    /**
     * paginate result and send it to Zend_View
     * @param $result
     * @param string $return
     * @param int $count
     * @throws Zend_Paginator_Exception
     */
    protected function paginateResult($result, $return = 'result', $count = 25)
    {
        $paginator = Zend_Paginator::factory($result);
        $paginator->setItemCountPerPage($count);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setPageRange(5);
        $this->view->$return = $paginator;
        $this->view->itemCountPerPage = $paginator->getItemCountPerPage();
        $this->view->totalItemCount = $paginator->getTotalItemCount();

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('_pagination.phtml');
    }

    /**
     * paginate result and send it to Zend_View
     * @param $select
     * @param string $return
     * @param int $count
     * @throws Zend_Paginator_Exception
     */
    protected function paginateSelect($select, $return = 'result', $count = 25)
    {
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
        $paginator->setItemCountPerPage($count);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setPageRange(5);
        $this->view->$return = $paginator;
        $this->view->itemCountPerPage = $paginator->getItemCountPerPage();
        $this->view->totalItemCount = $paginator->getTotalItemCount();

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('_pagination.phtml');
    }

    /**
     * subscribe to newsletter
     * @param $email
     * @return bool
     */
    protected function subscribe($email)
    {
        $model = new Default_Model_NewsletterSubscribers();
        $model->setEmail($email);
        if ($model->save()) {
            return true;
        }
        return false;
    }

    /**
     * send activation email to new user
     * @param $user
     * @return bool
     * @throws Zend_Mail_Exception
     */
    protected function activate($user)
    {
        $url            = "http://".$_SERVER['SERVER_NAME'];
        $username		= $user->getUsername();
        $email          = $user->getEmail();
        $activationLink = '<a href="'.$url.'/auth/activation?code='.$user->getActivationcode().'">Activare</a>';

        $signUp = new Default_Model_Templates();
        $signUp->find('signUp');

        $subject = $signUp->getSubjectro();
        $message = $signUp->getValuero();

        $message = str_replace('{'.'$'.'username}', $username, $message);
        $message = str_replace('{'.'$'.'email}', $email, $message);
        $message = str_replace('{'.'$'.'activationlink}', $activationLink, $message);

        // ToDo: change hardcoded variables
        $emailcompany = 'contact@sexypitipoanca.ro';
        $institution = 'SexyPitipoanca.ro';

        $mail = new Zend_Mail();
        $mail->setFrom($emailcompany, $institution);
        $mail->setSubject($subject);
        $mail->setBodyHtml($message);
        $mail->addTo($user->getEmail());
        if ($mail->send()) {
            return true;
        }
        return false;
    }

    /**
     * save user status
     * @param $userId
     * @return bool
     */
    protected function saveUserStatus($userId)
    {
        $modelArticle = new Default_Model_CatalogProducts();
        $modelArticle->setUser_id($userId);
        $modelArticle->setDescription($this->getRequest()->getPost('updateStatus'));
        $modelArticle->setType('status');
        $modelArticle->setStatus(1);
        if ($modelArticle->save())
        {
            return true;
        }
        return false;
    }

    /**
     * save user photo
     * @param $userId
     */
    protected function saveUserPhoto($userId)
    {
        // BEGIN: Save article
        $modelArticle = new Default_Model_CatalogProducts();
        $modelArticle->setUser_id($userId);
        $modelArticle->setName($this->getRequest()->getPost('photo_title'));
        $modelArticle->setDescription($this->getRequest()->getPost('photo_description'));
        $modelArticle->setCategory_id($this->getRequest()->getPost('photo_categ'));
        $modelArticle->setType('gallery');
        $modelArticle->setStatus(1);
        $productId = $modelArticle->save();
        // END: Save article

        if($productId)
        {
            // BEGIN: Save tags
            $tags = $this->getRequest()->getPost('photo_tags');
            TS_Catalog::saveTags($productId, $tags);
            // END: Save tags

            // BEGIN: Save images
            $allowed = "/[^a-z0-9\\-\\_]+/i";
            $folderName = preg_replace($allowed,"-", strtolower(trim($this->getRequest()->getPost('photo_title'))));
            $folderName = trim($folderName,'-');
            if(!file_exists(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId. '/' . $folderName . '/')) {
                mkdir(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName . '/', 0777, true);
                mkdir(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName . '/big', 0777, true);
                mkdir(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName . '/small', 0777, true);
            }

            $imageCount = $this->getRequest()->getPost('uploader_count');
            for ($i = 0; $i < $imageCount; $i++)
            {
                $imageName = $this->getRequest()->getPost('uploader_'.$i.'_name');
                $tmpName = $this->getRequest()->getPost('uploader_'.$i.'_tmpname');
                TS_Catalog::saveImage($productId, $imageName, $tmpName, $folderName);
            }
            // END: Save images
        }
    }

    /**
     * save user video
     * @param $userId
     * @return bool
     */
    protected function saveUserVideo($userId)
    {
        $modelArticle = new Default_Model_CatalogProducts();
        $modelArticle->setUser_id($userId);
        $modelArticle->setName($this->getRequest()->getPost('video_title'));
        $description = $this->getRequest()->getPost('video_description');
        if($description != 'Descriere. . . '){
            $modelArticle->setDescription($description);
        }
        $modelArticle->setCategory_id($this->getRequest()->getPost('video_categ'));
        $modelArticle->setType('embed');
        $modelArticle->setStatus(1);
        $productId = $modelArticle->save();
        if($productId)
        {
            // BEGIN: Save tags
            $tags = $this->getRequest()->getPost('video_tags');
            if($tags != 'Adauga tag-uri separe prin virgula'){
                TS_Catalog::saveTags($productId, $tags);
            }
            // END: Save tags
            $modelVideo = new Default_Model_Video();
            $modelVideo->setProductId($productId);
            $modelVideo->setUrl($this->getRequest()->getPost('video_url'));
            if ($modelVideo->save())
            {
                return true;
            }
        }
        return false;
    }

    /**
     * save tags
     * @param string $tags
     * @param $productId
     */
    protected function saveTags($tags, $productId)
    {
        $tagsArray = explode(',', trim($tags));
        foreach($tagsArray as $tag) {
            $tag = trim($tag);
            $model2 = new Default_Model_Tags();
            $select = $model2->getMapper()->getDbTable()->select()
                ->where('name = ?', $tag);
            $result = $model2->fetchAll($select);
            if ($result) {
                $model3 = new Default_Model_CatalogProductTags();
                $select = $model3->getMapper()->getDbTable()->select()
                    ->where('product_id = ?', $productId)
                    ->where('tag_id = ?', $result[0]->getId());
                $result2 = $model3->fetchAll($select);
                if (!$result2) {
                    $model4 = new Default_Model_CatalogProductTags();
                    $model4->setProduct_id($productId);
                    $model4->setTag_id($result[0]->getId());
                    $model4->save();
                }
            } else {
                $model3 = new Default_Model_Tags();
                $model3->setName($tag);
                $tagId = $model3->save();
                if ($tagId)
                {
                    $model4 = new Default_Model_CatalogProductTags();
                    $model4->setProduct_id($productId);
                    $model4->setTag_id($tagId);
                    $model4->save();
                }
            }
        }
    }

    /**
     * @param $userId
     * @param $folderName
     * @param $product
     * @throws Zend_File_Transfer_Exception
     */
    public function uploadArticleImages($userId, $folderName, $product)
    {
        $upload = new Zend_File_Transfer_Adapter_Http();
        $upload->addValidator('Size', false, 2000000, 'image');
        $upload->setDestination('media/catalog/products/' . $userId . '/' . $folderName . '/');
        $files = $upload->getFileInfo();
        $i = 1;
        foreach ($files as $file => $info) {
            if ($upload->isValid($file)) {
                if ($upload->receive($file)) {
                    $tmp = pathinfo($info['name']);
                    $extension = (!empty($tmp['extension'])) ? $tmp['extension'] : null;
                    $pozaNume = $folderName . '-' . rand(99, 9999) . '.' . $extension;
                    $model2 = new Default_Model_CatalogProductImages();
                    $model2->setProduct_id($product->getId());
                    $model2->setPosition('999');
                    $model2->setName($pozaNume);
                    if ($model2->save()) {
                        require_once APPLICATION_PUBLIC_PATH . '/library/Needs/tsThumb/ThumbLib.inc.php';
                        $thumb = PhpThumbFactory::create(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName . '/' . $info['name']);
                        $thumb->resize(600, 600)
                            ->tsWatermark(APPLICATION_PUBLIC_PATH . "/media/watermark-small.png")
                            ->save(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName . '/big/' . $pozaNume);
                        $thumb->tsResizeWithFill(150, 150, "ffffff")->save(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName . '/small/' . $pozaNume);
                        $this->safeDelete('media/catalog/products/' . $userId . '/' . $folderName . '/' . $info['name']);
                    }
                } else {
                    $this->_flashMessenger->addMessage('<div class="mess-info">Eroare upload!</div>');
                    $this->_redirect('/admin/catalog/products-edit/id/' . $product->getId());
                }
            }
            $i++;
        }
    }

    /**
     * @param $articleId
     * @return null|object
     */
    protected function getProductImages($articleId)
    {
        $model2 = new Default_Model_CatalogProductImages();
        $select = $model2->getMapper()->getDbTable()->select()
            ->where('product_id = ?', $articleId)
            ->order('position ASC');
        $result = $model2->fetchAll($select);
        if ($result) {
            return $result;
        }

        return null;
    }

    /**
     * @param $imageCollection
     * @return array
     */
    protected function parseProductImages($imageCollection)
    {
        $images = [];

        foreach ($imageCollection as $image) {
            $images[$image->getId()] = $image->getName();
        }

        return $images;
    }

    /**
     * @param $userId
     * @param $newName
     * @param $oldName
     * @return array
     */
    protected function createArticleFolders($userId, $newName, $oldName)
    {
        // create user folder
        if (!file_exists(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/')) {
            mkdir(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId, 0777, true);
        }

        // create article folders
        $allowed = "/[^a-z0-9\\-\\_]+/i";
        $folderName = preg_replace($allowed, "-", strtolower(trim($newName)));
        $folderName = trim($folderName, '-');
        if (!file_exists(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName . '/')) {
            mkdir(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName . '/', 0777, true);
            mkdir(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName . '/big', 0777, true);
            mkdir(APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName . '/small', 0777, true);
        }

        $folderName2 = preg_replace($allowed, "-", strtolower(trim($oldName)));
        $folderName2 = trim($folderName2, '-');
        return array($folderName, $folderName2);
    }

    /**
     * @param $imagesForEdit
     * @param $product
     * @param $folderName2
     * @param $folderName
     * @param $userId
     * @param $form
     */
    protected function renameArticleFolders($imagesForEdit, $product, $folderName2, $folderName, $userId, $form)
    {
        foreach ($imagesForEdit as $valueImg) {
            $model2 = new Default_Model_CatalogProductImages();
            $model2->find($valueImg->getId());
            $oldPozaNume = $model2->getName();
            $oldPath = 'media/catalog/products/' . ($product->getUser_id() ? $product->getUser_id() : '0') . '/' . $folderName2 . '/big/' . $oldPozaNume;
            $tmp = pathinfo($oldPath);
            $extension = (!empty($tmp['extension'])) ? $tmp['extension'] : null;
            $pozaNume = $folderName . '-' . rand(99, 9999) . '.' . $extension;
            $model2->setName($pozaNume);
            if ($model2->save()) {
                copy(
                    APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName2 . '/big/' . $oldPozaNume,
                    APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $form->getValue('user') . '/' . $folderName . '/big/' . $pozaNume
                );
                copy(
                    APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName2 . '/small/' . $oldPozaNume,
                    APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $form->getValue('user') . '/' . $folderName . '/small/' . $pozaNume
                );

                $this->safeDelete([
                    APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName2 . '/big/' . $oldPozaNume,
                    APPLICATION_PUBLIC_PATH . '/media/catalog/products/' . $userId . '/' . $folderName2 . '/small/' . $oldPozaNume
                ]);
            }
        }
    }

    /**
     * @return Zend_Mail
     * @throws Zend_Mail_Exception
     */
    protected function sendContactMail()
    {
        $message = '<table border="0" cellpadding="5" cellspacing="0">';
        $message .= '<tr>';
        $message .= '<th align="right">Nume : </th>';
        $message .= '<td align="left">' . $this->getRequest()->getPost('name') . '</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<th align="right">Email : </th>';
        $message .= '<td align="left">' . $this->getRequest()->getPost('email') . '</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<th align="right">Comentariu: </th>';
        $message .= '<td align="left">' . $this->getRequest()->getPost('message') . '</td>';
        $message .= '</tr>';
        $message .= '</table>';

        $emailCompany = 'contact@sexypitipoanca.ro';
        $institution = 'SexyPitipoanca';
        $mail = new Zend_Mail();
        $mail->setFrom($emailCompany, $institution);
        $mail->setSubject('Contact pitipoanca: ' . $this->getRequest()->getPost('subject'));
        $mail->setBodyHtml($message);
        $mail->addTo($emailCompany);

        if($mail->send()) {
            return true;
        }

        return false;
    }
}