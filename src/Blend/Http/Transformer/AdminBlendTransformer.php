<?php declare(strict_types=1);

namespace BlendExchange\Blend\Http\Transformer;

use League\Fractal\TransformerAbstract;
use BlendExchange\Blend\Model\BlendFile;
use BlendExchange\Flag\Http\Transformer\AdminFlagTransformer;

final class AdminBlendTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'flags'
    ];

    public function transform(BlendFile $blend) 
    {
        //TODO - make an actual transformer.
        return [
            'id' => $blend->id,
            'fileName' => $blend->fileName,
            'questionLink' => $blend->questionLink, 
            'fileSize' => $blend->fileSize,
            'adminComment' => $blend->adminComment,
            'views_count' => $blend->views_count,
            'downloads_count' => $blend->downloads_count,
            'favorites_count' => $blend->favorites_count,
            //Admin
            'owner' => $blend->owner,
            'date' => $blend->date
        ];
    }

    public function includeFlags(BlendFile $blend)
    {
        $flags = $blend->flags;

        return $this->collection($flags, new AdminFlagTransformer);
    }
}