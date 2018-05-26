<?php declare(strict_types=1);

namespace BlendExchange\Ads;
use BlendExchange\Blend\Model\BlendFile;
final class FileCountAd extends BaseAd {
    public $cords = [0,368];
    public $imageName = 'FileCountAd';
    public $align = 'center'; // left|center|right
    public $font = 'RalewayBold.ttf';
    public $fontSize = 36;
    public $fontColor = [141,141,141];

    public function text () : string
    {
        return (string)BlendFile::count();
    }
}