<?php

/**
 * DynamicAd short summary.
 *
 * DynamicAd description.
 *
 * @version 1.0
 * @author GiantCowFilms
 */
class DynamicAd {
    
    public $textSettings;
    public $getText;
    public $imageName;
    public $x;
    public $y;
    public $align;
    //Read from disk
    public $image;
    
    public function __construct($x,$y, $align = 'left', array $textSettings, $imageName, $getText, $cacheText = true)
    {
        $this->x = $x;
        $this->y = $y;
        $this->textSettings = $textSettings;
        $this->imageName = $imageName;
        $this->getText = $getText;
        $this->align = $align;
    }
    
    
    public function getString( )
    {
        //$this->getText(); *should* work but doesn't D:
	    return call_user_func($this->getText);
    }
    
    public function drawAdd( )
    {
        if($this->align = 'center'){
            $bounds = imagettfbbox($this->textSettings["size"], 0, $this->getResources( )["font"], $this->getString());
            
            $this->x = ceil((imagesx($this->getResources( )["image"]) - $bounds[2]) / 2);
        }
        
        $resources = $this->getResources();
        $this->image = $this->getResources( )["image"];
	    imagettftext(
                $this->image,
                $this->textSettings["size"],
                0,
                $this->x,
                $this->y,
                imagecolorallocate($this->image,
                    $this->textSettings["color"]["red"],
                    $this->textSettings["color"]["blue"],
                    $this->textSettings["color"]["green"]
                ),
                $this->getResources( )["font"],
                $this->getString()
        );
        return $this->image;
    }
    
    public function getResources( )
    {
        if(!isset($this->image)){
            $this->image = imagecreatefrompng("./img/".$this->imageName.".png");
        }
	    return [
            'image' => $this->image,
            'font' => "./fonts/"."RalewayBold.ttf"
        ];
    }
}