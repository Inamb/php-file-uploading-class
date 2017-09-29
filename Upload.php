<?php

/**
* PHP File Uploading Class
* Easily validate and upload files on server
* version 1.1
* Class size : 5kb
* Dependency : none
* Tested : true
* Written by : Inambe - fb.com/inambe.io,fiverr.com/inambutt
* Support : KamalTech - kamaltech.io 
**/
class Upload
{

	// strings
	public $strings = [
		"field_error"	=>	"Invalid input field name",
		"upload_dir_error"	=>	"Invalid directory to upload",
		"input_size_error"	=>	"Invalid defined max_upload size",
		"file_not_selected_war"	=>	"No file selected",
		"max_size_war"	=>	"File size exceeds max file size limit",
		"invalid_file_extension"	=>	"Invalid file extension",
		"unknown_problem"	=>	"Unknown problem"
	];

	// protocol http / https
	private $protocol;
	// upload status
	private $uploaded = false;

	// this file specs
	private $file_extension;
	private $field_name;
	private $file_name;
	private $file_tmp_name;
	private $file_size_bytes;


	// upload directory
	private $dir;

	// errors/warnings array
	private $errors = [];
	private $warnings = [];

	// default allowed extentions
	private $allowed_extensions = ["jpg","png","gif"];
	
	// allowed file size in Bytes
	private $allowed_size_bytes;

	function __construct( $field_name = null,$dir = "./",$max_size_mb = 5,$allowed_extensions = null )
	{
		if ( $field_name != "" and $field_name != null and isset($_FILES[$field_name]) ) {
			$this->set_file_specs($field_name);
		}else{
			array_push($this->errors, $this->strings['field_error']);
		}

		if ( $dir != "" and is_dir($dir) ) {
			$this->dir = $dir;
		}else{
			array_push($this->errors, $this->strings['upload_dir_error']);
		}

		if ( $max_size_mb < 1000 and $max_size_mb != null and $max_size_mb != "" ) {
			$this->allowed_size_bytes = $this->to_byte($max_size_mb);	
		}else{
			array_push($this->errors, $this->strings['input_size_error']);
		}

		if ( is_array($allowed_extensions) and $allowed_extensions != "" and $allowed_extensions != null ) {
			$this->allowed_extensions = $allowed_extensions;
		}
		
	}

	private function set_file_specs ( $field_name )
	{
		if (!empty($_FILES[$field_name]['name'])) {
			$this_file_ref	=	$_FILES[$field_name];
			$this->field_name = $field_name;
			$this->file_tmp_name = $this_file_ref['tmp_name'];
			$this->file_name = 	$this_file_ref['name'];
			$this->file_size_bytes = $this_file_ref['size'];
			$this->file_extension = pathinfo($this->file_name)['extension'];	
		}else{
			array_push($this->warnings, $this->strings['file_not_selected_war']);
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
			array_push($this->warnings, $this->strings['max_size_war']);
			return false;
		}
		return true;
	}

	private function validate_extension()
	{
		if (!in_array($this->file_extension, $this->allowed_extensions)) {
			array_push($this->warnings, $this->strings['invalid_file_extension']);
			return false;
		}
		return true;
	}



	public function do_upload()
	{
		if (empty($this->errors) and empty($this->warnings) and $this->validate_size() and $this->validate_extension()) {
			$this->uploaded = move_uploaded_file($this->file_tmp_name, $this->dir . $this->file_name);
			if($this->uploaded){
				return true;
			}
			array_push(array_push($this->errors, $this->strings['unknown_problem']));
			return false;
		}
		return false;
	}
	public function file_name()
	{
		if ($this->uploaded) {
			return $this->file_name;
		}
		return false;
	}

	public function file_url()
	{
		if ($this->uploaded) {
			if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
			    $this->protocol .= "https";
			}else{
			    $this->protocol .= "http";
			}
	    	return $this->protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$this->dir.$this->file_name;
    	}
    	return false;
	}

	public function errors()
	{
		if (empty($this->errors) and empty($this->warnings)) {
			return false;
		}
		return array_merge($this->errors,$this->warnings);
	}

}