<?php
//Make HEAD
    //aggiungere link e script e boostrap
//Make Body
    //Prendere HTML Body
    //Aggiungerci dati statici
        //Prendere file JSON dati statici
        //aggiungere ad HTML Body i dati statici
        include_once("core/utility/file/DOMExplorer.php");
        $viewHTMLDOM = new DOMExplorer("core/view/login/login.html");
        $staticString = new DOMExplorer("core/view/staticString.xml");


        $nodes = [];
        $nodes=$viewHTMLDOM->getNodesByClass("dp");
        foreach($nodes as $node){
            $id = $node->getAttribute('xml:id');
            $value = $staticString->getValueById($id);
            $viewHTMLDOM->appendValue($id,$value);
        }

        print_r($viewHTMLDOM->getText());

?>