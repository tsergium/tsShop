<?php
function ResizeImg($image,$newWidth,$newHeight) {
	if ( !file_exists($image) ) return false;

	$_image = pathinfo($image);
	$ext = $_image['extension'];							//extnsia fisierului
	$fis=substr($_image['basename'], 0,-(strlen($ext)+1));	//numele fisierului fara extensie
	$dir=$_image['dirname'];								//root-ul

	$destImage="{$dir}/{$fis}.{$ext}";			//fac fisier jpg
	switch($ext)
	{
		case "gif": $srcImage = ImageCreateFromGIF( $image ); break;
		case "jpg": $srcImage = ImageCreateFromJPEG( $image ); break;
		case "png": $srcImage = ImageCreateFromPNG( $image ); break;
		default: $srcImage = @ImageCreateFromJPEG( $image ); break;
	}

	$srcWidth = @ImageSX( $srcImage );
	$srcHeight = @ImageSY( $srcImage );

	if($srcWidth>$newWidth or $srcHeight>$newHeight){
		$ratioWidth = $srcWidth/$newWidth;
		$ratioHeight = $srcHeight/$newHeight;

		if( $ratioWidth < $ratioHeight){
			$destWidth = $srcWidth/$ratioHeight;
			$destHeight = $newHeight;
		}else{
			$destWidth = $newWidth;
			$destHeight = $srcHeight/$ratioWidth;
		}
	}else{
		$destWidth = $srcWidth;
		$destHeight = $srcHeight;
	}

	if (@function_exists('imagecreatetruecolor')) $dImage = @ImageCreateTrueColor( $destWidth, $destHeight);  // ar fi fain sa mearga, GD 2.0 .
	else $dImage = ImageCreate( $destWidth, $destHeight);

	if (@function_exists('imagecopyresampled')) ImageCopyreSampled( $dImage, $srcImage, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight );
 	else ImageCopyResized( $dImage, $srcImage, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight );

	switch($ext)
	{
		case "gif":
			if (function_exists('imagegif')) imagegif( $dImage,$destImage );
			else imagejpeg( $dImage,$destImage );
			break;
		case "jpg": imagejpeg( $dImage,$destImage ); break;
		case "png":
			if (function_exists('imagepng')) imagepng( $dImage,$destImage );
			else imagejpeg( $dImage,$destImage );
			break;
		default: @imagejpeg( $dImage,$destImage ); break;
	}

	@ImageDestroy( $srcImage );

	return "{$fis}.{$ext}";
}
?>