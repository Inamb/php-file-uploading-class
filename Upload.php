<?php

/**
* PHP File Uploading Class
*/
class Upload
{
	private $protocol;
	private $field_name;
	private $file_name;
	private $file_tmp_name;
	private $file_size_bytes;
	private $dir;
	private $errors = [];
	private $allowed_exts = ["jpg","png","png"];
	private $allowed_size_bytes;
	private $uploaded = false;

	function __construct( $field_name = null,$dir = null,$max_size_mb = 5,$allowed_exts = null )
	{
		if ($field_name == null	or $field_name == "" or !isset($_FILES[$field_name])) 
		{
			$this->errors['field_name_error'] = "YOU CAN'T LEAVE INPUT FIELD NAME PARAMETER EMPTY";
		
		}else{

			$this->field_name = $field_name;
			
			$this->file_tmp_name = $_FILES[$this->field_name]['tmp_name'];
			$this->file_name = $_FILES[$this->field_name]['name'];

			$this->file_size_bytes = $_FILES[$this->field_name]['size'];

		}

		if ( $dir == null or $dir == "" ) {
			
			$this->errors["upload_dir_error"] = "YOU CAN'T LEAVE UPLOAD DIRECTORY PARAMETER EMPTY";
		
		}else{

			$this->dir = $dir;

		}

		if ($max_size_mb < 1000) {
			
			$this->allowed_size_bytes = $this->toByte($max_size_mb);	
		
		}else{
			
			$this->errors['max_size_error'] = "ENTERED WRONG MAX SIZE VALUE CAN'T BE MORE THEN 1000 MB";

		}
		if ($allowed_exts != null) {
			if (is_array($allowed_exts)) {
				$this->allowed_exts = $allowed_exts;
			}else{
				$this->errors['allowed_extensions_error'] = "THERE IS SOME PROBLEM WITH YOUR PASSED ARRAY FOR ALLOWED EXTENSIONS";
			}
		}
		
	}

	private function toByte($mb)
	{
		return $mb*1048576;
	}
	private function toMB($bytes)
	{
		return $bytes/1048576;
	}

	private function validate_size()
	{

		if ($this->allowed_size_bytes < $this->file_size_bytes) {
			
			$this->errors['file_size'] = "FILE SIZE CAN'T BE MORE THEN ".$this->toMB($this->allowed_size_bytes)." MB. THIS FILE SIZE IS ".$this->toMB($this->file_size_bytes)." MB";

			return false;
		}
		return true;
	}

	public function do_upload()
	{
		if (count($this->errors) == 0 and $this->validate_size()) {
			$this->uploaded = move_uploaded_file($this->file_tmp_name, $this->dir . $this->file_name);
			if($this->uploaded){
				return true;
			}
		}
		return false;
	}
	public function file_name()
	{
		if (count($this->errors) == 0 and $this->uploaded) {
			return $_FILES[$this->field_name]['name'];
		}
	}

	public function file_url()
	{
		if (count($this->errors) == 0 and $this->uploaded) {
			if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
			    $this->protocol .= "https";
			}else{
			    $this->protocol .= "http";
			}
	    	return $this->protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$this->dir.$this->file_name;
    	}
	}

	public function errors()
	{
		return $this->errors;
	}

}