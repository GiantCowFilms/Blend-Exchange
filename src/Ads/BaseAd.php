<?php declare(strict_types=1);

namespace BlendExchange\Ads;

abstract class BaseAd {
    public $cords = [0,0];
    public $imageName = 'AdNotFound';
    public $align = 'left'; // left|center|right
    public $font = 'RalewayBold.ttf';
    public $useCache = false;
    public $fontSize = 30;
    public $fontColor = [0,0,0];
    public $resourceDir = '/../../resources';
    private $image = null;
    public $cache;

    function __construct (\Stash\Pool $cache = null) {
        $this->cache = $cache;
    }

    public function getPng () {
        if ($this->image === null) {
            $path = realpath(dirname(__FILE__)  . $this->resourceDir.'/Ads/'.$this->imageName.".png");
            //Will throw an exception if a resource is not found
            $this->image = imagecreatefrompng($path );
        }
        return $this->image;
    }

    public function getFontPath() {
        return dirname(__FILE__)  . $this->resourceDir . '/Fonts/' . $this->font;
    }

    public function render () {
        if($this->align = 'center'){
            $bounds = imagettfbbox($this->fontSize, 0, $this->getFontPath( ), $this->getText());

            $this->cords[0] = ceil((imagesx($this->getPng( )) - $bounds[2]) / 2);
        }

        $image = $this->getPng( );
	    imagettftext(
                $image,
                $this->fontSize,
                0,
                (int)$this->cords[0],
                (int)$this->cords[1],
                imagecolorallocate($image,
                    $this->fontColor[0],
                    $this->fontColor[1],
                    $this->fontColor[2]
                ),
                $this->getFontPath( ),
                $this->getText()
        );
        return $image;
    }

    public function getText () {
        if($this->useCache) {
            $item = $this->cache->getItem('Ads/'.$this->imageName);
            if ($item->isMiss()) {
                $text = $this->text();
                $item->set($text);
                $item->expiresAfter(3600 * 24); //Expires after one day
                $this->cache->save($item);
            } else {
                $text = $item->get();
            }
        } else {
            $text = $this->text();
        }
        return $text;
    }

    abstract public function text () : string;
}