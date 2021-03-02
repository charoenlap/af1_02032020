<?php namespace App\Services\Medias;

class MediaRepository {

	public function uploadFile($fileObject)
	{
		$folder_name 	= date('ymd');
		$changed_name 	= date('ymd').sprintf('%03d', rand(0, 999));

		$original_name 	= $fileObject->getClientOriginalName();
		$extension 		= pathinfo($original_name, PATHINFO_EXTENSION);

		$fileObject->move(helperDirContent().'/'.$folder_name, $changed_name.'.'.$extension);
		return $folder_name.'/'.$changed_name.'.'.$extension;
	}

	public function removeFile($file_with_full_dirctory)
	{
		// If input is URL, transfer to Directory.
		if (strpos($file_with_full_dirctory, urlBase()) !== -1) {
			$file_with_full_dirctory = str_replace(urlBase(), helperDirBase(), $file_with_full_dirctory);
		}

		// Remove.
		if (!is_dir($file_with_full_dirctory)) {

			if (file_exists($file_with_full_dirctory)) unlink($file_with_full_dirctory);
		}
	}

	public function createThumbnail($fileObject, $name = '')
	{
		$thumbnail_ext	= 'jpg';
		$folder_name 	= date('ymd');

		$new_name = $name.date('ymdHi').sprintf('%03d', rand(0, 999));
		$new_name_temp 	= $new_name.'_tmp';

		$original_name 	= $fileObject->getClientOriginalName();
		$extension 		= pathinfo($original_name, PATHINFO_EXTENSION);

		// Move File.
		$fileObject->move(helperDirContent().'/'.$folder_name, $new_name_temp.'.'.$extension);

		if ($extension == 'jpg') {

			$thumbnail_file = imagecreatefromjpeg(helperDirContent().'/'.$folder_name.'/'.$new_name_temp.'.'.$extension);

		} elseif ($extension == 'png') {

			$thumbnail_file = imagecreatefrompng(helperDirContent().'/'.$folder_name.'/'.$new_name_temp.'.'.$extension);

		} else {

			return $folder_name.'.'.$new_name_temp.'.'.$extension;
		}

		// Create JPG File.
		imagejpeg($thumbnail_file, helperDirContent().'/'.$folder_name.'/'.$new_name.'.'.$thumbnail_ext, 40);

		// Remove Temp Source File
		$this->removeFile(helperDirContent().'/'.$folder_name.'/'.$new_name_temp.'.'.$extension);
		return $folder_name.'/'.$new_name.'.'.$thumbnail_ext;
	}
}