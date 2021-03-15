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


    public function getModelsForMake(){
        $data = $this->makeHTTPRequest("https://www.autoscout24.it/offerb2c/data/NewDecision/Taxonomy/GetVehicleModelLineDataIT?countryISOCode=IT&make=$_GET[make]&year=$_GET[year]$_GET[month]");
        print_r($data);
    }

    public function getDetailForModel(){
        $data = $this->makeHTTPRequest("https://www.autoscout24.it/offerb2c/data/NewDecision/Taxonomy/GetVehicleIdentificationDataIT?NumberOfDoors=$_GET[nDoors]&bodyTypeId=$_GET[bodytype]&countryISOCode=IT&make=$_GET[make]&modelID=$_GET[model]&year=$_GET[year]$_GET[month]");
        print_r($data);
    }


}

$dd= new DataDispatcher();

if(isset($_GET["ModelsForMake"])){
    $dd->getModelsForMake();
}elseif(isset($_GET["staticDataJS"])){
    $dd->getStaticDataJs();
}elseif(isset($_GET["detailForModel"])){
    $dd->getDetailForModel();
}elseif(isset($_GET["postMakes"])){
    print_r(json_encode($_POST));
}
?>