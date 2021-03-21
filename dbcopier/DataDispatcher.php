<?php

class DataDispatcher{
    private function makeHTTPRequest($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function getStaticDataJs(){
        $data = $this->makeHTTPRequest("https://www.autoscout24.it/offerb2c/data/Mdw/StaticData/StaticDataJs");
        $data = str_replace("var staticData=","",$data);
        print_r($data);
    }

    public function saveImage(){
        file_put_contents("./images/image.jpeg" ,$_POST['image']);
        return print_r($_POST['image']);
    }


}

$dd= new DataDispatcher();

if(isset($_GET["staticDataJS"])){
    $dd->getStaticDataJs();
}

if(isset($_GET["saveImage"])){
    $dd->saveImage();
}
?>