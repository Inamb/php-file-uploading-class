# PHP File Uploading Class

Validate and upload files on server more easily using PHP.

## Getting Started

All you need to get started with this class is, include or autoload this class into you project/file.

## Simple uploading

We can start using this class by adding this simple code into your form submiting code block. [ file(containing form) dirctory is default upload directory ]

```
$a = new Upload("input_field_name");
$a->do_upload();
```

## Set upload directory (optional)
We can pass second parameter(string) for defining upload directory. [ Don't forgot the ending slash in directory name ]

```
$a = new Upload("input_field_name","uploaded_files/");
$a->do_upload();
```

## Maximum file size validation (optional)

We can add maximum file size(Megabytes) parameter in order to validate file size. [ default=5MB ]

```
$a = new Upload("input_field_name","directory_to_upload/",10);
$a->do_upload();
```

## File extension validation (optional) 

We can pass an array of allowed extensions to validate file extension. [ default = ["jpg","png","gif"] ]

```
$a = new Upload("input_field_name","directory_to_upload/",10,["png","jpg"]);
$a->do_upload();
```
## Checking for errors and warnings

we can use `errors()` method to get array of errors and warnings.
Remember `do_upload()` will return false if there will be any error or warning. 

```
$a = new Upload("input_field_name","directory_to_upload/",10,["png","jpg"]);
if($a->do_upload()){
  $a->file_name(); // will return file name
  $a->file_url(); // will return file url(current) 
  // file is uploaded do whatever you want to do
}else{
  foreach ($a->errors() as $error) {
    echo $error."<br>";
  }
  die();
}
```
check test.php for complete working example.

## Authors

* **Inambe** - *Developer* - [Inambe](https://facebook.com/inambe.io)
* **kamaltech** *Created for* [Kamaltech](http://kamaltech.io)
