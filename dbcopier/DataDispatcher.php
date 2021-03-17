<?php
include_once "../core/Database.php";

class DataDispatcher{

    private $db = null;
    
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Funzione InsertQuery che sovracarica la funzione query
     */
    private function insertQuery($querystr){
        $res = $this->db->query($querystr);
        return $res;
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

        //Modifico per rendere univoco il ModelID
        $mdp["modelID"] = !empty($mdp["modelID"]) ? "'$mdp[modelID]$mdp[noOfDoors]$mdp[bodyTypeID]'" : "NULL";//String
        
        $mdp["month"] = !empty($mdp["month"]) ? "'$zero$mdp[month]'" : "NULL";//String
        $mdp["year"] = !empty($mdp["year"]) ? "'$mdp[year]'" : "NULL";//String
        $mdp["noOfDoors"] = !empty($mdp["noOfDoors"]) ? $mdp["noOfDoors"] : "NULL";
        $mdp["bodyTypeID"] = !empty($mdp["bodyTypeID"]) ? "'$mdp[bodyTypeID]'" : "NULL";//String
        $mdp["makeID"] = !empty($mdp["makeID"]) ? $mdp["makeID"] : "NULL";

        $querystr = "INSERT INTO CarModel (idModel, makeID, noOfDoors,bodyTypeID) VALUES ($mdp[modelID], $mdp[makeID] , $mdp[noOfDoors], $mdp[bodyTypeID]);";
        $this->verifyInsert($this->insertQuery($querystr),$querystr);
        $querystr = "INSERT INTO Production (idModel, month, year) VALUES ($mdp[modelID], $mdp[month], $mdp[year]);";
        $this->verifyInsert($this->insertQuery($querystr),$querystr);

        foreach ($mdp["details"] as $detdata){
            $detail = $detdata["data"];
            
            $detail["_codall"] = !empty($detail["_codall"]) ? "'$detail[_codall]'" : "NULL";//String
            $detail["_buildPeriod"] = !empty( $detail["_buildPeriod"]) ? "'$detail[_buildPeriod]'" : "NULL";//String
            $detail["_version"] = !empty($detail["_version"]) ? "'$detail[_version]'" : "'Other'";//String
            $detail["_powerKW"] = !empty($detail["_powerKW"]) ? $detail["_powerKW"] : "NULL";
            $detail["_powerPS"] = !empty($detail["_powerPS"]) ? $detail["_powerPS"] : "NULL";
            $detail["_noOfSeats"] = !empty($detail["_noOfSeats"]) ? $detail["_noOfSeats"] : "NULL";
            $detail["_gears"] = !empty($detail["_gears"]) ? $detail["_gears"] : "NULL";
            $detail["_ccm"] = !empty($detail["_ccm"]) ? $detail["_ccm"] : "NULL";
            $detail["_cylinders"] = !empty($detail["_cylinders"]) ? $detail["_cylinders"] : "NULL";
            $detail["_weight"] = !empty($detail["_weight"]) ? $detail["_weight"] : "NULL";
            $detail["_consumptionMixed"] = !empty($detail["_consumptionMixed"]) ? $detail["_consumptionMixed"] : "NULL";
            $detail["_consumptionCity"] = !empty($detail["_consumptionCity"]) ? $detail["_consumptionCity"] : "NULL";
            $detail["_consumptionHighway"] = !empty($detail["_consumptionHighway"]) ? $detail["_consumptionHighway"] : "NULL";
            $detail["_co2EmissionMixed"] = !empty($detail["_co2EmissionMixed"]) ? $detail["_co2EmissionMixed"] : "NULL";
            $detail["_transm"] = !empty($detail["_trasm"]) ? $detail["_trasm"] : "NULL";
            $detail["_emClass"] = !empty($detail["_emClass"]) ? "'$detail[_emClass]'" : "NULL";//String
            $detail["_fuelTypeID"] = !empty($detail["_fuelTypeID"]) ? "'$detail[_fuelTypeID]'" : "O";//String OTHER
            $detail["_gearingTypeId"] = !empty($detail["_gearingTypeId"]) ? "'$detail[_gearingTypeId]'" : "NULL";//String
           

            //Automatic Quotess
            $querystr = "INSERT INTO CarDetail (codall, buildPeriod, version, powerKW, powerPS, noOfSeats, gears, ccm, cylinders, weight, consumptionMixed, consumptionCity, consumptionHighway, co2EmissionMixed, emClass, transm, idModel, fuelTypeID, gearingTypeID, month, year)
            VALUES ($detail[_codall], $detail[_buildPeriod], $detail[_version], $detail[_powerKW], $detail[_powerPS], $detail[_noOfSeats], $detail[_gears], $detail[_ccm], $detail[_cylinders], $detail[_weight], $detail[_consumptionMixed], $detail[_consumptionCity], $detail[_consumptionHighway], $detail[_co2EmissionMixed], $detail[_transm],$detail[_emClass], $mdp[modelID], $detail[_fuelTypeID], $detail[_gearingTypeId], $mdp[month], $mdp[year]);";
            $this->verifyInsert($this->insertQuery($querystr),$querystr);
        }
    }

    /**
     * 
     */
    private function verifyInsert($res,$querystr){
        if(!$res){
            print_r($querystr." | ");
            print_r("Error description: " . $this->db->getConn()->error);
            print_r("\n");
            header("HTTP/1.0 404 Not Found");
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