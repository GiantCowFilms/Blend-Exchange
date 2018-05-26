<?php declare(strict_types=1);

namespace BlendExchange\Ads;

use BlendExchange\Blend\Model\BlendFile;

final class AnimationNodesAd extends BaseAd
{
    public $cords = [0,436.7];
    public $imageName = 'AnimationNodesAd';
    public $align = 'center'; // left|center|right
    public $font = 'RobotoCondensed-Light.ttf';
    public $fontColor = [255,255,255];
    public $fontSize = 32;
    public $useCache = true;

    public function text() : string
    {
        $json = file_get_contents("https://api.stackexchange.com/2.2/tags/animation-nodes/info?order=desc&sort=popular&site=blender");
        $json = gzinflate(substr($json, 10, -8));
        $count = json_decode($json)->items[0]->count;

        return $count . " Questions Asked";
    }
}
