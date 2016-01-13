<?php
/**
 * Created by PhpStorm.
 * User: tsergium
 * Date: 10/29/2015
 * Time: 12:59 AM
 */

class Base_Api_Response
{
    private $success;
    private $code;
    private $message;
    private $description;
    private $body;

    public function setSuccess($success)
    {
        $this->success = $success;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function sendResponse()
    {
        if ($this->success) {
            $response = $this->prepareSuccessMessage();
        } else {
            $response = $this->prepareErrorMessage();
        }

        echo Zend_Json_Encoder::encode($response);
    }

    protected function prepareSuccessMessage()
    {
        return $this->body;
    }

    protected function prepareErrorMessage()
    {
        $response = [
            'code'          => $this->code,
            'message'       => $this->message,
            'description'   => $this->description
        ];
        return $response;
    }
}