<?php
include_once("core/utility/file/FileExplorer.php");
class DOMExplorer extends FileExplorer{

    private $dom;
    public function __construct(string $filepath) {
        parent::__construct($filepath);

        $this->dom= new DOMDocument();
        $this->dom->validateOnParse = true; 
        $this->dom->load($this->filepath);
    }

    public function getDOM():DOMDocument{
        return $this->dom;
    }

    public function getText():string{
        return $this->dom->saveHTML();
    }

    public function appendValue(string $idParrent, string $value):bool{
        $node = $this->dom->getElementById($idParrent);
        if(($node != null)){
            $node->appendChild($this->dom->createTextNode($value));
            return true;
        }
        return false;
    }

    public function appendHTML(string $idParrent, string $html, string $nodetype="div"):bool{
        $node = $this->dom->getElementById($idParrent);
        if(($node != null)){
            $node->appendChild($this->dom->createElement($nodetype,$html));
            return true;
        }
        return false;
    }

    public function replaceValue(string $idParrent, string $value):bool{
        $node = $this->dom->getElementById($idParrent);
        if($node != null){
            $node->replaceChild($this->dom->createTextNode($value),$node->childNodes->item(0));
            return true;
        }
        return false;
    }

    public function replaceHTML(string $idParrent, string $html, string $nodetype="div"):bool{
        $node = $this->dom->getElementById($idParrent);
        if(($node != null)){
            $node->replaceChild($this->dom->createElement($nodetype,$html),$node->childNodes->item(0));
            return true;
        }
        return false;
    }

    public function getNodesByClass(string $classname):DOMNodeList{
        $finder = new DomXPath($this->dom);
        return $finder->query("//*[contains(@class, '$classname')]");
    }

    public function getValueById($id){
        return $this->dom->getElementById($id)->childNodes->item(0)->nodeValue;
    }
}
?>