<?php

class SubmitController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $payment = $this->getRequest()->getParam('payment');
        $courier = $this->getRequest()->getParam('courier');
        $deliveryCost = $this->getRequest()->getParam('deliveryCost');
        $msg = $this->getRequest()->getParam('msg');
        if (null != $msg) {
            $this->view->msg = $msg;
        }
        if ((null == $payment || null == $courier) && null == $msg) {
            $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Selectati metoda de plata si curierul!</p></div>");
            $this->_redirect('/cart'); // REDIRECT IF NOT AUTHENTICATED
        }

        $post = $this->getRequest()->getPost();
//		BEGIN:SAVE ACCOUNT
        if ($post['createaccount'] == '1' && $post['username'] != null && $post['password'] != null) {
            //	BEGIN:SHIPPING DETAILS FORM
            $usernameValidateDbNotExists = new Zend_Validate_Db_NoRecordExists(array('table' => 'ts_clients', 'field' => 'username'));
            $emailValidateDbNotExists = new Zend_Validate_Db_NoRecordExists(array('table' => 'ts_clients', 'field' => 'email'));
            if ($usernameValidateDbNotExists->isValid($post['username']) && $emailValidateDbNotExists->isValid($post['email'])) {
                $account = new Default_Model_Clients();
                $account->setPassword(md5($post['password']));
                $account->setClienttype('0');
                $account->setUsername($post['username']);
                $account->setFirstname($post['firstname']);
                $account->setLastname($post['lastname']);
                $account->setEmail($post['email']);
                $account->setAddress($post['address']);
                $account->setFirstnameS($post['firstnameS']);
                $account->setLastnameS($post['lastnameS']);
                $account->setAddressS($post['addressS']);
                $account->setStateS($post['stateS']);
                $account->setZipcodeS($post['zipcodeS']);
                $account->setCounty($post['county']);
                $account->setCity($post['city']);
                $account->setZip($post['zip']);
                $account->setPhone($post['phone']);
                $activation_code = substr(md5(uniqid(mt_rand(), true)), 0, 10);
                $account->setActivationcode($activation_code);
                $account->setStatus('0');

                if ($accountId = ($account->save())) {
                    $this->_flashMessenger->addMessage("<div class='message success'><p><strong></strong>Contul dumneavostra a fost creat cu succes. Va rugam verificati casuta postala pentru email-ul de confirmare.</p></div>");
                    try {
                        $signupTemplate = new Default_Model_Templates();
                        $signupTemplate->find('signUp');
                        //

                        $username = $account->getUsername();
                        $password = $account->getPassword();
                        $email = $account->getEmail();
                        $firstname = $account->getFirstname();
                        $lastname = $account->getLastname();
                        $link = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/auth/activation?code=" . $activation_code . "'>Activare</a>";
                        $signup = $signupTemplate->getValue();


                        $signup = str_replace("{" . "$" . "firstname}", $firstname, $signup);
                        $signup = str_replace("{" . "$" . "lastname}", $lastname, $signup);
                        $signup = str_replace("{" . "$" . "username}", $username, $signup);
                        $signup = str_replace("{" . "$" . "password}", $password, $signup);
                        $signup = str_replace("{" . "$" . "email}", $email, $signup);
                        $signup = str_replace("{" . "$" . "activationlink}", $link, $signup);

                        $companyEmail = $this->view->company[0]->getEmail();
                        $companyName = $this->view->company[0]->getInstitution();

                        $mail = new Zend_Mail();
                        $mail->setFrom($companyEmail, $companyName . '(' . $_SERVER['HTTP_HOST'] . ')');
                        $mail->setSubject($signupTemplate->getSubject());
                        $mail->setBodyHtml($signup);
                        $mail->addTo($account->getEmail(), $account->getFirstname() . " " . $account->getLastname());
                        try {
                            $mail->send();
                        } catch (Zend_Exception $e) {
                            $this->_flashMessenger->addMessage($e->getMessage());
                        }
                    } catch (Zend_Exception $e) {
                        $this->_flashMessenger->addMessage($e->getMessage());
                    }
                } else {
                    $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Eroare salvare cont. VA rugam incercati mai tarziu!</p></div>");
                    $this->_redirect('/cart'); // REDIRECT IF NOT AUTHENTICATED
                }
            } else {
                $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Acest username/email este deja folosit! Va rugam selectati altul.</p></div>");
                $this->_redirect('/cart'); // REDIRECT IF NOT AUTHENTICATED
            }
            //	END:SHIPPING DETAILS FORM

        } elseif ($post['createaccount'] == '1' && ($post['username'] == null || $post['password'] == null)) {
            $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Va rugam completati  usernameul si parola!</p></div>");
            $this->_redirect('/cart'); // REDIRECT IF NOT AUTHENTICATED
        }
//		END:SAVE ACCOUNT

        $companyName = $this->view->company[0]->getInstitution();
//		$companyFiscalCode = $this->view->company[0]->getFiscalcode();
//		$companyTradeRegister = $this->view->company[0]->getTraderegister();
//		$companyBank = $this->view->company[0]->getBank();
//		$companyIbanCode = $this->view->company[0]->getIbancode();
        $companyAddress = $this->view->company[0]->getAddress();
//		$companyCity = $this->view->company[0]->getCity();
//		$companyCounty = $this->view->company[0]->getCounty();
//		$companyZip = $this->view->company[0]->getZip();
        $companyPhone = $this->view->company[0]->getPhone();
//		$companyFax = $this->view->company[0]->getFax();
        $companyEmail = $this->view->company[0]->getEmail();
//		$companyUrl = $this->view->company[0]->getUrl();

        $gotsale = new Default_Model_Templates();
        if ($gotsale->find('gotSale')) {
            if ($lang == 'en') {
                $messagex = $gotsale->getValue();
            } else {
                $messagex = $gotsale->getValuero();
            }
        }

        // BEGIN: FETCH PRODUCTS FROM CART
        $var = new Default_Model_Cart();
        $select = $var->getMapper()->getDbTable()->select()
            ->where('cookie = ?', $_SESSION['cartId']);
        $cart = $var->fetchAll($select);
        if (null == $cart && null == $msg) {
            $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Cosul dumneavoastra este gol!</p></div>");
            $this->_redirect('/cart'); // REDIRECT IF NO PRODUCTS IN CART
        }
        // END: FETCH PRODUCTS FROM CART

        // BEGIN: SAVE ORDER
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest()->getParams();
            // BEGIN: FETCH USER DETAILS
            $firstname = '';
            $lastname = '';
            $address = '';
            $city = '';
            $zip = '';
            $county = '';
            $phone = '';
            $email = '';
            $fax = '';

            $auth = Zend_Auth::getInstance();
            $authAccount = $auth->getStorage()->read();
            if (null != $authAccount) {
                if (null != $authAccount->getId()) {
                    $user = new Default_Model_Clients();
                    if ($user->find($authAccount->getId())) {
                        $firstname = $user->getFirstname();
                        $lastname = $user->getLastname();
                        $address = $user->getAddress();
                        $city = $user->getCity();
                        $zip = $user->getZip();
                        $county = $user->getCounty();
                        $phone = $user->getPhone();
                        $email = $user->getEmail();
                        $fax = $user->getFax();
                        if ($user->getFirstnameS() == null) {
                            $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Va rugam completati datele de livrare!</p></div>");
                            $this->_redirect('/user/editaccount/page/order'); // REDIRECT IF NOT AUTHENTICATED
                        }
                        $firstnameS = $user->getFirstnameS();
                        $lastnameS = $user->getLastnameS();
                        $addressS = $user->getAddressS();
                        $stateS = $user->getStateS();
                        $zipcodeS = $user->getZipcodeS();
                    }
                }
            } else {
                $firstname = $post['firstname'];
                $lastname = $post['lastname'];
                $address = $post['address'];
                $city = $post['city'];
                $zip = $post['zip'];
                $county = $post['county'];
                $phone = $post['phone'];
                $email = $post['email'];
                $fax = $post['fax'];
                $firstnameS = $post['firstnameS'];
                $lastnameS = $post['lastnameS'];
                $addressS = $post['addressS'];
                $stateS = $post['stateS'];
                $zipcodeS = $post['zipcodeS'];
            }
            // BEGIN: FETCH USER DETAILS
            $plationlines = FALSE;
            if ($user != null) {
                if ($user->getId() == '22' || $user->getId() == '3') {
                    $plationlines = 'true';
                    $_SESSION['platio'] = $plationlines;
                } else {
                    if (!empty($_SESSION['platio'])) {
                        unset($_SESSION['platio']);
                    }
                }

            } else {
                if (!empty($_SESSION['platio'])) {
                    unset($_SESSION['platio']);
                }
            }

            // Get coupon
            $coupon = $post['coupon'];
            $couponM = new Default_Model_Coupon();
            $couponDB = $couponM->findByCode($coupon);
            if ($couponDB) {
                // Check if voucher exists and it is enabled
                $voucherM = new Default_Model_Voucher();
                $vouchersDB = $voucherM->fetchAll();
                foreach ($vouchersDB as $voucher) {
                    if ($voucher['status'] == 'active') {
                        if ($voucher['isProcentual']) {
                            $totalValue = (float)(100 - $voucher['value']) / 100 * ($post['totalCost'] - $deliveryCost);
                        } else {
                            $totalValue = (float)($post['totalCost'] - $deliveryCost) - $voucher['value'];
                        }
                        if ($totalValue < 0) {
                            $totalValue = 0;
                        }
                        break;
                    }
                }
            }


            $order = new Default_Model_Orders();
            if ($user != null && $user->getId() != null) {
                $order->setCustomerId($user->getId());
            } elseif (!empty($accountId)) {
                $order->setCustomerId($accountId);
            }
            $order->setPaymentId($payment);
            $order->setCourierId($courier);
            $order->setDeliveryCost($deliveryCost);
            $productcost = $post['totalCost'] - $deliveryCost;
            $order->setProductscost($productcost);
            if (isset($totalValue)) {
                $order->setTotalcost($totalValue + $deliveryCost);
                $order->setCouponId($couponDB['id']);
            } else {
                $order->setTotalcost($post['totalCost']);
            }

            $order->setClienttype('0');


            $order->setFirstnameS($firstnameS);
            $order->setLastnameS($lastnameS);
            $order->setAddressS($addressS);
            $order->setStateS($stateS);
            $order->setZipcodeS($zipcodeS);


            $order->setFirstname($firstname);
            $order->setLastname($lastname);
            $order->setAddress($address);
            $order->setCity($city);
            $order->setZip($zip);
            $order->setPhone($phone);
            $order->setEmail($email);
            $order->setCounty($county);
            $order->setFax($fax);


            $order->setComments($request['comments']);
            if ($payment == 3) {
                $order->setStatus('card_unconfirmed');
            } else {
                $order->setStatus('pending');
            }

            $products = '';
            $products .= '<table cellpadding="10" cellspacing="0" border="0">';
            $modelc = new Default_Model_Cart();
            $select = $modelc->getMapper()->getDbTable()->select()
                ->where('cookie = ?', $_SESSION['cartId']);
            $result = $modelc->fetchAll($select);

            foreach ($result as $value) {
                $model2 = new Default_Model_Product();
                $attribute = '';
                if ($model2->find($value->getProductId())) {

                    $categ = getProdCateg($model2->getId());
                    $categname = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($categ));
                    $productname = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($model2->getName()));
                    $linkProd = '#';
                    $linkProd = 'p' . $model2->getId() . '-' . $categname . '-' . $productname . '.html';

                    $attribute = "Marime:" . $value->getSizeName() . "&nbsp;&nbsp;Culoare:" . $value->getColorName();

                    $products .= '<tr>';
                    $products .= '<td align="center">Cantitate</td>';
                    $products .= '<td align="left">Produs</td>';
                    $products .= '<td align="left">Total</td>';
                    $products .= '</tr>';
                    $products .= '<tr>';
                    $products .= '<td align="center">' . $value->getQuantity() . ' x </td>';
                    $products .= '<td align="left"><strong><a href="' . WEBPAGE_ADDRESS . '/' . $linkProd . '">' . $model2->getName() . ($attribute ? ' <small>( ' . $attribute . ')</small>' : '') . '</a></strong></td>';
                    $products .= '<td align="right">' . number_format($model2->getPrice() * $value->getQuantity(), 2, ',', '.') . ' <small> RON</small></td>';
                    $products .= '</tr>';
                }

            }
            $products .= '</table>';

            if (($orderId = $order->save())) {
                if (isset($totalValue)) {
                    // Mark coupon as used
                    $couponM = new Default_Model_Coupon();
                    $couponM->find($couponDB['id']);
                    if (null != $couponM) {
                        $options = array('code' => $couponM->getCode(), 'status' => 'used');
                        $couponM->setOptions($options);
                    }
                    $couponM->save();
                }

                $gotsaleemail = new Default_Model_Templates();
                if ($gotsaleemail->find('gotSaleEmail')) {
                    $subject = $gotsaleemail->getSubject();
                    $message = $gotsaleemail->getValue();
                }
                $companyUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/';
                $companyUrlLink = '<a href="http://' . $_SERVER['HTTP_HOST'] . '/" >' . 'http://' . $_SERVER['HTTP_HOST'] . '/</a>';

                $companyName = $this->view->company[0]->getInstitution();
                $companyPhone = $this->view->company[0]->getPhone();
                $companyPhone2 = $this->view->company[0]->getPhone2();
                $companyEmail = $this->view->company[0]->getEmail();

                $message = str_replace('[comanda nr]', $orderId, $message);
                $message = str_replace('[produse total]', $order->getProductscost(), $message);
                $message = str_replace('[cost livrare]', $deliveryCost, $message);
                if (isset($totalValue)) {
                    $message = str_replace('[voucher]', '
Cupon: ' . $couponDB['code'] . '<br/>
<br/>', $message);
                    $message = str_replace('[total]', $totalValue + $deliveryCost, $message);
                } else {
                    $message = str_replace('[voucher]', '', $message);
                    $message = str_replace('[total]', $post['totalCost'], $message);
                }

                $message = str_replace('[metoda de plata]', $order->getPaymentName(), $message);
                $message = str_replace('[metoda de livrare]', $order->getCourierName(), $message);

                $message = str_replace('[nume]', $order->getFirstname(), $message);
                $message = str_replace('[prenume]', $order->getLastname(), $message);

                $message = str_replace('[numeS]', $order->getFirstnameS(), $message);
                $message = str_replace('[prenumeS]', $order->getLastnameS(), $message);
                $message = str_replace('[addressS]', $order->getAddressS(), $message);
                $message = str_replace('[stateS]', $order->getStateS(), $message);
                $message = str_replace('[zipcodeS]', $order->getZipcodeS(), $message);

                $message = str_replace('[email]', $order->getEmail(), $message);
                $message = str_replace('[telefon]', $order->getPhone(), $message);
                $message = str_replace('[adresa]', $order->getAddress(), $message);
                $message = str_replace('[judet]', $order->getCounty(), $message);
                $message = str_replace('[oras]', $order->getCity(), $message);
                $message = str_replace('[cod postal]', $order->getZip(), $message);
                $message = str_replace('[observatii]', $order->getComments(), $message);
                $message = str_replace('[continut comanda]', $products, $message);
                $message = str_replace('[nume site]', $companyName, $message);
                $message = str_replace('[telefon site]', $companyPhone, $message);
                $message = str_replace('[telefon site2]', $companyPhone2, $message);
                $message = str_replace('[email site]', $companyEmail, $message);
                $message = str_replace('[link site]', $companyUrlLink, $message);
                $message = str_replace('[raporteaza]', '<a target="_blank" href="' . $companyUrl . 'contact">aici</a>', $message);

                $mail = new Zend_Mail();
                $mail->setFrom($companyEmail, $companyName . '(' . $_SERVER['HTTP_HOST'] . ')');
                $mail->setSubject($subject);
                $mail->setBodyHtml($message);
                $mail->addTo($order->getEmail());
                try {
                    $mail->send();
                } catch (Exception $e) {
                    echo 'Eroare: ', $e->getMessage(), "\n";
                }

                $mail2 = new Zend_Mail();
                $mail2->setFrom($companyEmail, $company);
                $mail2->setSubject('Comanda Noua');
                $mail2->setBodyHtml($message);
                $mail2->addTo($companyEmail);
                try {
                    $mail2->send();
                } catch (Exception $e) {
                    echo 'Eroare: ', $e->getMessage(), "\n";
                }

                foreach ($cart as $value) {
                    $orderProd = new Default_Model_OrderProducts();
                    if ($user != null && $user->getId() != null) {
                        $orderProd->setCustomerId($user->getId());
                    }
                    $orderProd->setOrderId($orderId);
                    $orderProd->setProductId($value->getProductId());
                    $orderProd->setSizeId($value->getSizeId());
                    $orderProd->setColorId($value->getColorId());
                    $orderProd->setQuantity($value->getQuantity());
                    $orderProd->setPrice($value->getProduct()->getPrice());
                    $orderProd->save();

                    $ga_products .= "
						_gaq.push(['_addItem',
						'" . $orderId . "',           // order ID - required
						'" . $value->getProductId() . "',           // SKU/code - required
						'" . $value->getProduct()->getName() . "',        // product name
						'',   // category or variation
						'" . $value->getProduct()->getPrice() . "',          // unit price - required
						'" . $value->getQuantity() . "'               // quantity - required
					]);
					";

                    $value->delete();

                }
                unset($_SESSION['code']);
                unset($_SESSION['cart']);

                if (trim($ga_products) != '') {

                    $ga_totalcost = $order->getTotalcost();
                    $ga_deliverycost = $order->getDeliveryCost();
                    $ga_totalcost -= $ga_deliverycost;

                    $_SESSION['ga_script'] = "
				<script type=\"text/javascript\">

				  var _gaq = _gaq || [];
				  _gaq.push(['_setAccount', 'UA-29589280-1']);
				  _gaq.push(['_trackPageview']);
				  _gaq.push(['_addTrans',
				    '" . $orderId . "',           // order ID - required
				    'Lenjerie-de-lux',  // affiliation or store name
				    '" . $ga_totalcost . "',          // total - required
				    '',           // tax
				    '" . $ga_deliverycost . "',              // shipping
				    '" . $order->getCity() . "',       // city
				    '" . $order->getCounty() . "',     // state or province
				    'Romania'             // country
				  ]);

				  " . $ga_products . "

				  _gaq.push(['_trackTrans']); //submits transaction to the Analytics servers

				  (function() {
				    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				  })();

				</script>";
                }

                //BEGIN: CARD PAYMENT
                if ($payment == 3) {
                    $_SESSION['card']['orderNumber'] = $orderId;
                    $this->_redirect('/submit/index/msg/cardsubmit');
                }
                //END: CARD PAYMENT

                $this->_redirect('/submit/index/msg/success');

            } else {
                $this->_redirect('/submit/index/msg/fail');
            }
        }
        // END: SAVE ORDER


    }

    public function responseAction()
    {
        unset($_SESSION['card']['orderNumber']);
        if (!(function_exists('orderCardConfirm'))) {
            function orderCardConfirm($orderId, $onHold = false)
            {
                if (null != $orderId) {
                    $model = new Default_Model_Orders();
                    if ($model->find($orderId)) {
                        if ($onHold == true) {
                            $model->setStatus('card_onhold');
                        } else {
                            $model->setStatus('card_confirmed');
                        }
                        if ($model->save()) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
    }
}


