<?php
class pic{

		public function add_pic($file,$path,$prefix,$thumb=false)
	{ 
		$type = strrchr($file['name'],".");
		$type = strtolower($type);
		$pic_name = $prefix.time().$type;
		$thumb_name = $prefix."thumb_".time().$type;
		if (copy($file['tmp_name'] , $path.$pic_name))
			{
				if($thumb==true&&$type==".jpg"){
						$images = $file['tmp_name'];
						$images_orig = ImageCreateFromJPEG($images);
						$photoX = ImagesX($images_orig);
						$photoY = ImagesY($images_orig);
						$width=150;
						$size = GetimageSize($images);
						$height=round($width*$size[1]/$size[0]);
						$images_fin = ImageCreateTrueColor($width, $height);
						ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
						ImageJPEG($images_fin,$path.$thumb_name);
						ImageDestroy($images_orig);
						ImageDestroy($images_fin);
						$this->picture_thumb=$thumb_name;
				}
				$pic_name2 = $pic_name;
				$this->picture=$pic_name2;
		}
	}

	
}
?>