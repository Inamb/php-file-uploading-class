# php-file-uploading-class
validate and upload files more easily using php
All you need to do is include this class or autoload it in your files.
then add below code in your form handling code.

```
$a = new Upload("input_field_name","upload_dir_name/");

if ($a->do_upload()) {
  
  // store in database or do whatever you want to do.
  
  $file_name = $a->file_name();
  
  $file_url = $a->file_url();

}else{
 
 //errors detected
 
 die(var_dump($a->errors()));

}
```
