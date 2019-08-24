<?php declare(strict_types=1);

namespace BlendExchange\Ads;

use BlendExchange\Blend\Data\VisibleBlendsQuery;

final class FileCountAd extends BaseAd {
    public $cords = [0,368];
    public $imageName = 'FileCountAd';
    public $align = 'center'; // left|center|right
    public $font = 'RalewayBold.ttf';
    public $fontSize = 36;
    public $fontColor = [141,141,141];

    public function text () : string
    {
        $query = new VisibleBlendsQuery();
        $query->buildQuery();
        return (string)($query->getQuery()->count());
    }
}