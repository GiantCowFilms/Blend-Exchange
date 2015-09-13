<?php

//Step one: get data

class DynamicAd {
    
    public $textSettings;
    public $getText;
    public $imageName;
    public $x;
    public $y;
    
    
    public function __construct($x,$y,array $textSettings, $imageName, $getText, $cacheText = true)
     {
        $this->x = $x;
        $this->y = $y;
        $this->textSettings = $text;
        $this->imageName = $imageName;
        $this->getText = $getText;
        
     }
     
    
    public function getString( )
    {
	    return $getText();
    }
    
    public function drawAdd( )
    {
        $resources = $this->getResources();
	    imagettftext(
                $this->getResources( )["image"],
                $textSettings["size"],
                0,
                $this->x,
                $this->y,
                $textSettings["color"],
                $this->getResources( )["font"],
                $this->getString()
        );
    }
    
    public function getResources( )
    {
	    return [
            'image' => imagecreatefrompng("img/".$this->imageName.".png"),
            'font' => "fonts/".""
        ];
    }
}

DynamicAd::drawAdd();

?>