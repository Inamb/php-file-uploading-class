<?php

/**
* PHP File Uploading Class
*/
class Upload
{
	private $protocol;
	private $uploaded = false;

	private $file_extension;
	private $field_name;
	private $file_name;
	private $file_tmp_name;
	private $file_size_bytes;


	private $dir;
	private $errors = [];
	private $allowed_extensions = ["jpg","png","gif"];
	private $allowed_size_bytes;
	

	function __construct( $field_name = null,$dir = null,$max_size_mb = 5,$allowed_extensions = null )
	{
		if ($field_name == null	or $field_name == "" or !isset($_FILES[$field_name])) 
		{
			$this->errors['field_name_error'] = "YOU CAN'T LEAVE INPUT FIELD NAME PARAMETER EMPTY";
		}else{
			$this->field_name = $field_name;
			$this->file_tmp_name = $_FILES[$this->field_name]['tmp_name'];
			$this->file_name = $_FILES[$this->field_name]['name'];
			$this->file_size_bytes = $_FILES[$this->field_name]['size'];
			$this->file_extension = pathinfo($this->file_name)['extension'];
		}

		if ( $dir == null or $dir == "" ) {
			$this->errors["upload_dir_error"] = "YOU CAN'T LEAVE UPLOAD DIRECTORY PARAMETER EMPTY";
		}else{
			$this->dir = $dir;
		}

		if ($max_size_mb < 1000) {
			$this->allowed_size_bytes = $this->to_byte($max_size_mb);	
		}else{
			$this->errors['max_size_error'] = "ENTERED WRONG MAX SIZE VALUE CAN'T BE MORE THEN 1000 MB";
		}
		if ($allowed_extensions != null and $allowed_extensions != "") {
			if (is_array($allowed_extensions)) {
				$this->allowed_extensions = $allowed_extensions;
			}else{
				$this->errors['allowed_extensions_error'] = "THERE IS SOME PROBLEM WITH YOUR PASSED ARRAY FOR ALLOWED EXTENSIONS";
			}
		}
		
	}

	private function to_byte($mb)
	{
		return $mb*1048576;
	}
	private function to_mb($bytes)
	{
		return $bytes/1048576;
	}

	private function validate_size()
	{
		if ($this->allowed_size_bytes < $this->file_size_bytes) {
			
			$this->errors['file_size'] = "FILE SIZE EXCEEDS MAX FILE SIZE LIMIT";
			return false;
		}
		return true;
	}

	private function validate_extension()
	{
		if (!in_array($this->file_extension, $this->allowed_extensions)) {
			$this->errors['invalid_file_extension'] = "YOUR FILE EXTENSION DOSN'T MATCH ALLOWED EXTENSIONS";
			return false;
		}
		return true;
	}



	public function do_upload()
	{
		if (count($this->errors) == 0 and $this->validate_size() and $this->validate_extension()) {
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