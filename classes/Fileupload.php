<?php

  namespace classes;
  /**
   * Class FileUplaod
   * Created by: Deep Rahman
   * Arguments: Tmp Path of file uploaded via HTTP POST
   *            Boolean value for assigning unique name, default: FALSE
   *
   * methods:
   * 1.chkMime : for Checking MIME Type.
   * 2.moveFile : Moving file to a specified directory.
   */
  class FileUplaod{
    protected $temp_name;
    protected $base_name;
    protected $assign_uniq_name="0";

    // takes the $_FILES['file']
    public function __construct($uploaded_file,bool $uniq_name=false)
    {
      $this->temp_name = $uploaded_file['tmp_name'];
      $this->base_name = $uploaded_file['name'];
      if ($uniq_name){
        $file_extension = pathinfo($this->base_name,PATHINFO_EXTENSION);
        $this->assign_uniq_name = bin2hex(random_bytes(4)).".".$file_extension;
      }
    }

    //Check MIME type of a file
    public function chkMime():string
    {
      return mime_content_type($this->temp_name);
    }

    //Move to new directory only if uploaded via HTTP POST
    public function moveFile(string $dir_path):bool
    {
      if (!$this->assign_uniq_name){
        $target_location = $dir_path."/".$this->base_name;
        return move_uploaded_file($this->temp_name,$target_location);
      }
      $target_location = $dir_path."/".$this->assign_uniq_name;
      return move_uploaded_file($this->temp_name,$target_location);
    }
    public function getUnique():string{
      return $this->assign_uniq_name;
    }

    public function moveFileUpdate(string $dir_path, string $file_name):bool
    {
      if (!$this->assign_uniq_name){
        $target_location = $dir_path."/".$file_name;
        return move_uploaded_file($this->temp_name,$target_location);
      }
      $target_location = $dir_path."/".$this->assign_uniq_name;
      return move_uploaded_file($this->temp_name,$target_location);
    }


  }