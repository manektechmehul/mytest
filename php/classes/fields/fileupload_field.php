<?php

class fileupload_field extends abstract_field {

    private $titlepos;
    private $len;
    public $outputRightTemplate;
    private $filename;

    function __construct($fieldname, $name, $required = false, $validate = false, $outputTemplate = '', $emailTemplate = '', $outputRightTemplate = '') {
        parent::__construct($fieldname, $name, $required, $validate, $outputTemplate, $emailTemplate);
        $this->attachmentField = true;
        $this->titlepos = '';
        $this->len = 65;
        $this->outputRightTemplate = '';
        $this->validate = true;
    }

    function __set($var, $value) {
        if ($var == 'values')
            $this->values = $value;
    }

    function setOutputTemplate($outputTemplate) {
        if (!empty($outputTemplate))
            $this->outputTemplate = $outputTemplate;
        else
            $this->outputTemplate = "<tr valign=\"middle\">\n<td width=\"22%%\">\n<div align=\"right\">%s%s</div>\n</td>\n" .
                    "<td>\n<input type=\"file\" name=\"%s\" ></td></tr>\n\n";
    }

    function output() {
        if (isset($field['titlepos']) && ($field['titlepos'] == 'right'))
            $output = sprintf($this->outputRightTemplate, $this->name, $this->fieldname, $this->required);
        else
            $output = sprintf($this->outputTemplate, $this->name, $this->required, $this->fieldname);
        return $output;
    }

    function getEmailMessage() {
        return sprintf($this->emailTemplate, $this->name, $this->data);
    }

    function getData() {
        return $this->filename;
    }

    function isDataMissing() {
        return empty($this->data['name']);
    }

    function setData() {
        $this->data = (!empty($_FILES[$this->fieldname])) ? $_FILES[$this->fieldname] : "";
    }

    function isDataInvalid($key = '') {
        /*
         * will set $_SESSION['last_file_upload_location'] for use in the auto_form.php to write into your db 
         * if you set $_SESSION['form_file_upload_name'] before you arrive - then thats where the function will save the image 
         * the function will automatically make you a directory if it doesn't already exsist 
         * 
         * This function is almost identical to the fileupload version appart from the permitted_upload constants, and the for mentioned session names
         */
        umask(0);
        $file = $this->data;
        $valid_extensions = explode(',', PERMITTED_UPLOAD_FILE_TYPES);
        $upload_dir = 'UserFiles/Files/Uploads';

        /*      if(){
          $this->invalidDataMessage = "Invalid extension: $ext<br />Valid extensions are ".implode(', ', $valid_extensions) ;
          return true;
          }
         */
        // form item name - like photo or thumb
        $prefix = empty($this->values) ? $this->fieldname : $this->values;
        // let us pretend that passing in nothing is ok
        if ($this->data['size'] == '0') {
            return false; // for no error
            break;
        }
        // $key is pased in - its all the form values as as string 
        if ($key)
        //$keyname = strtolower(preg_replace('/[^A-Za-z0-9.]+/', '-', $prefix.'-'.$key));
         $keyname = strtolower(preg_replace('/[^A-Za-z0-9.]+/', '-', $prefix)) . md5(uniqid(rand(), true));
        $fileinfo = pathinfo($file['name']);
        $ext = strtolower($fileinfo['extension']);
        $i = 1;
        do {
            if ($key) {
                $dest = realpath('.') . '/' . $upload_dir . '/' . $keyname . '.' . $ext;
                $dest_folder = realpath('.') . '/' . $upload_dir;
                $web_relative_address = '/' . $upload_dir . '/' . $keyname . '.' . $ext;
                $_SESSION['last_file_upload_location'] = $web_relative_address;
            } else {
                $pre = "doc-{$i}_";
                $dest = realpath('.') . '/' . $upload_dir . '/' . $pre . $docname;
                // set at the start of the update function
            }
            $i++;
        } while (file_exists($dest));
        /* if you want to hard code a value - like a profile image
         * just set the session value in the form constructor.
         *
         * Then it will be there on post @-)
         */
        if ($_SESSION['form_image_upload_name'] != '') {
            // remove cached thumbnails
            $dest = realpath('.') . '/UserFiles/Image' . $_SESSION['form_file_upload_name'];
            $dest_folder = realpath('.') . '/UserFiles/Image' . $_SESSION['form_file_upload_cache_name'];

            // DELETE CACHE FOR FILE
            $cachefolder = realpath('.') . '/UserFiles/Thumbnails' . $_SESSION['form_file_upload_cache_name'];
            $this->unlinkRecursive($cachefolder, true);
        }


        if (!in_array($ext, $valid_extensions)) {
            $this->invalidDataMessage = "Invalid extension: $ext<br />Valid extensions are " . implode(', ', $valid_extensions);
            return true;
        } else {
            $uploadErrors = array('1' => 'File too large', '2' => 'File too large', '3' => 'Interupted file upload', '4' => 'No File',
                '6' => 'Contact Site Administrator', '7' => 'Contact Site Administrator', '8' => 'Invalid file type');

            //  need to explicitly make direcory for linux !! 
            if (!file_exists($dest_folder)) {
                umask(0);
                mkdir($dest_folder, 0777);
                //echo "The directory $dirname was successfully created.";
            } else {
                //echo "The directory $dest_folder exists.";
            }


            if (move_uploaded_file($file['tmp_name'], $dest)) {
                // might need to make the directory
                // mkdir("/path/to/my/dir", 0700);				
                $this->filename = $dest;
                umask(0);
                chmod($dest, 0777);
                return false;
            } else {
                $this->invalidDataMessage = $this->name . ': ' . $uploadErrors[$file['error']];
                return true;
            }
        }
    }

    /*
      function isDataInvalid($key = '')
      {
      umask(0);
      $file = $this->data;
      $valid_extensions = explode(',', PERMITTED_UPLOAD_FILE_TYPES);
      $upload_dir = 'UserFiles/Uploads/files';
      $prefix = empty($this->values) ? $this->name : $this->values;




      // let us pretend that passing in nothing is ok
      if($this->data['size'] == '0'){
      return false; // for no error
      break;
      }



      if ($key)
      $keyname = strtolower(preg_replace('/[^A-Za-z0-9.]+/', '-', $prefix.'-'.$key));

      $fileinfo = pathinfo($file['name']);
      $ext = strtolower($fileinfo['extension']);
      $i = 1;

      do {
      if ($key)
      {
      $dest = realpath('.').'/'.$upload_dir.'/'.$keyname.date('-dmy-His.').$ext;
      $web_relative_address = '/'.$upload_dir.'/'.$keyname.date('-dmy-His.').$ext;
      $_SESSION['last_image_upload_location'] = $web_relative_address;
      }
      else
      {
      $pre = "doc-{$i}_";
      $dest = realpath('.').'/'.$upload_dir.'/'.$pre.$docname;


      // set at the start of the update function

      }
      $i++;

      } while (file_exists($dest));

      // if you want to hard code a value - like a profile image
      // just set the session value in the form constructor.
      //
      // Then it will be there on post @-)

      if($_SESSION['form_image_upload_name'] != ''){
      // remove cached thumbnails
      $dest = realpath('.').'/UserFiles/Image'.$_SESSION['form_image_upload_name'];
      $dest_folder = realpath('.').'/UserFiles/Image'. $_SESSION['form_image_upload_cache_name'];

      // DELETE CACHE FOR FILE
      $cachefolder = realpath('.').'/UserFiles/Thumbnails' . $_SESSION['form_image_upload_cache_name'];
      $this->unlinkRecursive($cachefolder, true);
      }


      if (!in_array($ext, $valid_extensions))
      {
      $this->invalidDataMessage = "Invalid extension: $ext<br />Valid extensions are ".implode(', ', $valid_extensions) ;
      return true;
      }
      else
      {
      $uploadErrors = array('1' => 'File too large', '2' => 'File too large', '3' => 'Interupted file upload', '4' => 'No File',
      '6' => 'Contact Site Administrator', '7' => 'Contact Site Administrator', '8' => 'Invalid file type');

      //  need to explicitly make direcory for linux !!
      if (!file_exists($dest_folder)) {
      umask(0);
      mkdir($dest_folder, 0777);
      //echo "The directory $dirname was successfully created.";

      } else {
      //echo "The directory $dest_folder exists.";
      }


      if (move_uploaded_file($file['tmp_name'], $dest))
      {
      // might need to make the directory
      // mkdir("/path/to/my/dir", 0700);
      $this->filename = $dest;

      umask(0);
      chmod($dest, 0777);
      return false;
      }
      else
      {
      $this->invalidDataMessage = $this->name.': ' .$uploadErrors[$file['error']];
      return true;
      }
      }

      }
     */

    function processTemplate($message) {
        $message = str_replace("[FC:{$this->fieldname}]", ucwords($this->data), $message);
        $message = str_replace("[{$this->fieldname}]", $this->data, $message);
        return $message;
    }

    // recursive delete

    function unlinkRecursive($dir, $deleteRootToo) {
        if (!$dh = @opendir($dir)) {
            return;
        }
        while (false !== ($obj = readdir($dh))) {
            if ($obj == '.' || $obj == '..') {
                continue;
            }

            if (!@unlink($dir . '/' . $obj)) {
                unlinkRecursive($dir . '/' . $obj, true);
            }
        }

        closedir($dh);

        if ($deleteRootToo) {
            @rmdir($dir);
        }

        return;
    }

}