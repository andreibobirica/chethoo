<?php
include_once "../core/Database.php";

class DataDispatcher{

    private $db = null;
    
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Funzione InsertQuery che sovracarica la funzione query di Database ed in più fa un controllo del errore
     * Nel caso trovi un errore nel inserimento della query, stampa la query stessa abbianata al errore
     */
    private function insertQuery($querystr){
        $res = $this->db->query($querystr);
        if(!$res){
            print_r($querystr." | ");
            print_r("Error description: " . $this->db->getConn()->error);
            print_r("\n");
            header("HTTP/1.0 404 Not Found");
        }
    }

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

    public function postMakes($makes){
        foreach ($makes as $make) {
            $querystr = "INSERT INTO CarMake (makeID, makeName) VALUES ('$make[Id]', '$make[MakeName]');";
            $this->insertQuery($querystr);
        }
    }

    public function postGearingTypes($gearingTypes){
        foreach ($gearingTypes as $gtid => $gt) {
            $querystr = "INSERT INTO GearingType (gearingTypeID, gearingTypeName) VALUES ('$gtid', '$gt');";
            $this->insertQuery($querystr);
        }
    }

    public function postFuel($fuel){
        foreach ($fuel as $fid => $f) {
            $querystr = "INSERT INTO Fuel (fuelTypeID, fuelTypeName) VALUES ('$fid', '$f');";
            $this->insertQuery($querystr);
        }
    }

    public function postBodyTypes($bodyTypes){
        foreach ($bodyTypes as $btid => $bt) {
            $querystr = "INSERT INTO BodyType (bodyTypeID, bodyTypeName) VALUES ('$btid', '$bt');";
            $this->insertQuery($querystr);
        }
    }


    public function postModelsDetailsProductions($mdp){
        //zero for month
        $zero="";
        if($mdp["month"]<10)
        $zero="0";

        $querystr = "INSERT INTO CarModel (idModel, modelName, noOfDoors, makeID) VALUES ('$mdp[modelID]', '', $mdp[noOfDoors], '$mdp[makeID]');";
        $this->insertQuery($querystr);
        $querystr = "INSERT INTO Production (idModel, month, year) VALUES ('$mdp[modelID]', '$zero$mdp[month]', '$mdp[year]');";
        $this->insertQuery($querystr);
        foreach ($mdp["details"] as $detdata){
            $detail = $detdata["data"];
            $querystr = "INSERT INTO CarDetail (codall, buildPeriod, version, powerKW, powerPS, noOfSeats, gears, ccm, cylinders, weight, consumptionMixed, consumptionCity, consumptionHighway, co2EmissionMixed, emClass, idModel, fuelTypeID, gearingTypeID, month, year) VALUES ('$detail[_codall]', '$detail[_buildPeriod]', '$detail[_version]', $detail[_powerKW], $detail[_powerPS], $detail[_noOfSeats], $detail[_gears], $detail[_ccm], $detail[_cylinders], $detail[_weight], $detail[_consumptionmixed], $detail[_consumptioncity], $detail[_consumptionhighway], $detail[_co2emissionmixed], '$detail[_emclass]', '$mdp[modelID]', '$detail[_FuelTypeID]', '$detail[_gearingTypeId]', '$zero$mdp[month]', '$mdp[year]');";
            $this->insertQuery($querystr);
        }
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
    $dd->postMakes($_POST["makes"]);
}elseif(isset($_GET["postGearingTypes"])){
    $dd->postGearingTypes($_POST["gearingTypes"]);
}elseif(isset($_GET["postFuel"])){
    $dd->postFuel($_POST["fuel"]);
}elseif(isset($_GET["postBodyTypes"])){
    $dd->postBodyTypes($_POST["bodyTypes"]);
}elseif(isset($_GET["postModelsDetailsProductions"])){
    $dd->postModelsDetailsProductions($_POST["mdp"]);
}
?>