<?php
class FileExplorer{
    protected $myfile;
    protected $filepath;

    public function __construct(string $filepath) {
        $this->filepath = $filepath;
        $this->myfile = fopen($filepath, "r") or die("Unable to open file: $filepath");
    }

    public function __destruct(){
        fclose($this->myfile);
    }

    public function getText():string{
        return fread($this->myfile,filesize($this->filepath));
    }
}
?>