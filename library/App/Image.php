<?php

require_once 'App/Image/Exception.php';

/**
 * Image handling class.
 *
 * This class help you to manipulate an image.
 *
 * @category   Zend
 * @package    Zend_Image
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */ 
class App_Image 
{ 
	
	const TYPE_GIF	 = 1;
	const TYPE_JPEG	 = 2;
	const TYPE_PNG	 = 3;
	
	public $_imageSource;
	protected $_image;
	
    /**
     * Constructor setup.
     */ 
    public function __construct($image) {
    	$this->setImage($image);
    } 
     
    /**
     * Destructor setup.
     */     
    public function __destruct() 
    {
    	if ($this->getImage() != null) {
    		imagedestroy($this->getImage());
    	}
    } 
 
################################################################################     
#    Private function 
################################################################################     
     
    /**
     * Get the image type.
     * @return     integer
     */     
    private function getImageType() 
    {
    	$mime = new finfo((defined('FILEINFO_MIME_TYPE') ? FILEINFO_MIME_TYPE : FILEINFO_MIME));
		switch ($mime->file($this->_imageSource)) {
			case 'image/gif' :
					return self::TYPE_GIF;
				break;
		
			case 'image/jpeg' :
					return self::TYPE_JPEG;
				break;
		
			case 'image/png' :
					return self::TYPE_PNG;
				break;
		}
    	return false;
    } 
 
################################################################################     
#    Public function 
################################################################################     
     
    /**
     * Set the image file to manipulate.
     * @param     string    $image    Image filename.
     */     
    public function setImage($image) 
    {
    	$this->_imageSource = (string) $image;
    	
		require_once 'Zend/Validate/File/IsImage.php';
		
		$validateImage = new Zend_Validate_File_IsImage();
		$validateImage->getMimeType(false);
    	if (!$validateImage->isValid($this->_imageSource)) {
    		if ($validateImage->getMessages() != null) {
    			$_message = array();
    			foreach ($validateImage->getMessages() as $message) {
    				$_message[] = $message;
    			}
	    		throw new App_Image_Exception(implode("\r\n", $_message));
    		} else {
	    		throw new App_Image_Exception('Selected file is not an image!');
    		}
    	}
    	
    	switch ($this->getImageType()) {
    		case self::TYPE_GIF :
    				$this->_image = imagecreatefromgif($this->_imageSource);
    			break;
    		case self::TYPE_JPEG :
    				$this->_image = imagecreatefromjpeg($this->_imageSource);
    			break;
    		case self::TYPE_PNG :
    				$this->_image = imagecreatefrompng($this->_imageSource);
    			break;
    	}
    } 
    
    public function getImage()
    {
    	return $this->_image;
    }
    
    /**
     * Resize an image so that the image max size is not bigger the the new size (this will make the image size to have one part smaller/equal then what we need)
     * @param     integer    $width    Number of pixel.
     * @param     integer    $height    Number of pixel.
     */     
    public function resizeToHigher($newWidth, $newHeight, $strict = false) 
    {
		if($strict || $this->getWidth() > $newWidth || $this->getHeight() > $newHeight){
	    	$ratioWidth	 = $this->getWidth()/$newWidth;
	    	$ratioHeight = $this->getHeight()/$newHeight;
	    	
			if( $ratioWidth < $ratioHeight){
				$newWidth = $this->getWidth()/$ratioHeight; 
			}else{ 
				$newHeight = $this->getHeight()/$ratioWidth; 
			}
			
		} else {
			$newWidth	 = $this->getWidth();
			$newHeight	 = $this->getHeight();
		}
		
    	$this->_imageResampled = imagecreatetruecolor($newWidth, $newHeight);
    	imagecopyresampled($this->_imageResampled, $this->getImage(), 0, 0, 0, 0, $newWidth, $newHeight, $this->getWidth(), $this->getHeight());
    	$this->save($this->_imageResampled, $this->_imageSource);
    } 
     
    /**
     * Resize an image so that the image min size is not smaller the the new size (this will make the image size to have one part bigger/equal then what we need)
     * This function is used when we want to crop a thumbnail and tage the middle portion of a resized image
     * @param     integer    $width    Number of pixel.
     * @param     integer    $height    Number of pixel.
     */     
    public function resizeToLower($newWidth, $newHeight, $strict = false) 
    {
		if($strict || $this->getWidth() > $newWidth || $this->getHeight() > $newHeight){
	    	$ratioWidth	 = $this->getWidth()/$newWidth;
	    	$ratioHeight = $this->getHeight()/$newHeight;
	    	
			if( $ratioWidth > $ratioHeight){
				$newWidth = $this->getWidth()/$ratioHeight; 
			}else{ 
				$newHeight = $this->getHeight()/$ratioWidth; 
			}
			
		} else {
			$newWidth	 = $this->getWidth();
			$newHeight	 = $this->getHeight();
		}
		
    	$this->_imageResampled = imagecreatetruecolor($newWidth, $newHeight);
    	imagecopyresampled($this->_imageResampled, $this->getImage(), 0, 0, 0, 0, $newWidth, $newHeight, $this->getWidth(), $this->getHeight());
    	$this->save($this->_imageResampled, $this->_imageSource);
    } 
     
    /**
     * Resize an image from his width.
     * @param     integer    $width    Number of pixel.
     */ 
    public function resizeToWidth($newWidth) 
    {
    	$ratio = $this->getWidth()/$newWidth;
    	$newHeight = $this->getHeight()/$ratio;
    	
    	$this->_imageResampled = imagecreatetruecolor($newWidth, $newHeight);
    	imagecopyresampled($this->_imageResampled, $this->getImage(), 0, 0, 0, 0, $newWidth, $newHeight, $this->getWidth(), $this->getHeight());
    	$this->save($this->_imageResampled, $this->_imageSource);
    } 
     
    /**
     * Resize an image from his height.
     * @param     integer    $height    Number of pixel.
     */ 
    public function resizeToHeight($newHeight) 
    {
    	$ratio = $this->getHeight()/$newHeight;
    	$newWidth = $this->getWidth()/$ratio;
    	
    	$this->_imageResampled = imagecreatetruecolor($newWidth, $newHeight);
    	imagecopyresampled($this->_imageResampled, $this->getImage(), 0, 0, 0, 0, $newWidth, $newHeight, $this->getWidth(), $this->getHeight());
    	$this->save($this->_imageResampled, $this->_imageSource);
    } 
     
    /**
     * Resize an image from a percentage.
     * @param     integer    $percentage    Number between 0 and 100.
     */ 
    public function resizeToPercentage($percentage) 
    {
    	$newWidth	 = $this->getWidth()*$percentage;
    	$newHeight	 = $this->getHeight()*$percentage;
    	
    	$this->_imageResampled = imagecreatetruecolor($newWidth, $newHeight);
    	imagecopyresampled($this->_imageResampled, $this->getImage(), 0, 0, 0, 0, $newWidth, $newHeight, $this->getWidth(), $this->getHeight());
    	$this->save($this->_imageResampled, $this->_imageSource);
    } 
    
    public function cropThumbnail($width, $height) 
    {
    	/* resize the image */
    	$this->resizeToLower($width, $height);
    	$this->setImage($this->_imageSource);

    	$this->_imageResampled = imagecreatetruecolor($width, $height);
    	imagecopyresampled($this->_imageResampled, $this->getImage(), 0, 0, round(($this->getWidth()-$width)/2), round(($this->getHeight()-$height)/2), $this->getWidth(), $this->getHeight(), $this->getWidth(), $this->getHeight());
    	
    	$this->save($this->_imageResampled, $this->_imageSource);
    } 
     
    /**
     * Save an image.
     * @param     string    $filename    New image filename.
     */ 
    public function save($imageRes, $filename = null) 
    {
    	switch ($this->getImageType()) {
    		case self::TYPE_GIF :
    				imagegif($imageRes, $filename);
    			break;
    		case self::TYPE_JPEG :
    				imagejpeg($imageRes, $filename);
    			break;
    		case self::TYPE_PNG :
    				imagepng($imageRes, $filename);
    			break;
    	}
    }     
     
    /**
     * Get the height of the image.
     * 
     * @return    integer    Height in pixel.
     */ 
    public function getHeight() 
    {
    	return imagesy($this->getImage());
    } 
 
    /**
     * Get the width of the image.
     * 
     * @return    integer    Width in pixel.
     */ 
    public function getWidth() 
    {
    	return imagesx($this->getImage());
    }
	     
}


