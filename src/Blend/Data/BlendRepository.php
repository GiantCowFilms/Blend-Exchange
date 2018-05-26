<?php declare(strict_types=1);

namespace BlendExchange\Blend\Data;

use BlendExchange\Blend\Model\BlendFile;

final class BlendRepository
{
    public function __construct () 
    {

    }

    private function getBaseBlendQuery () {
        return BlendFile::where('fileGoogleId','!=',null);
    }

    private function locateBlendById ($id,$query) {
        $blend = (clone $query)->find($id);

        if ($blend === null) {
            $blend = (clone $query)->where('legacy_id', $id)->first();
        }
    
        return $blend;
    }

    public function findIncompleteBlendById($id) {
        return $this->locateBlendById($id,BlendFile::where('fileGoogleId','=',null));
    }

    public function findBlendById($id) {
        $baseQuery = $this->getBaseBlendQuery()->withCount('favorites')->withUniqueCount('views','ip')->withUniqueCount('downloads','ip');
        //->withCount('favorites')->with('flags')
        return $this->locateBlendById($id,$baseQuery);
    }

    public function remove(BlendFile $blend) {
        $blend->delete();
    }
}