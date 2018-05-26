<?php declare(strict_types=1);

namespace BlendExchange\Queries;

use BlendExchange\Blend\Model\BlendFile;

final class BlendQuery
{
    public function execute($id) : ?BlendFile
    {
        $base_query = BlendFile::with('flags')->withCount('views')->withCount('downloads');
        $blend = (clone $base_query)->find($id);

        if ($blend === null) {
            $blend = (clone $base_query)->where('legacy_id', $id)->first();
        }

        return $blend;
    }
}
