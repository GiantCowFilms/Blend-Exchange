<?php declare(strict_types = 1);

namespace BlendExchange\Blend\Data;

use Symfony\Component\HttpFoundation\Request;

final class BlendsQueryFactory {
    public function __construct () {

    }

    public function createFromRequest(Request $request) : BlendsQuery
    {
        $query =  new BlendsQuery(
            (string)$request->query->get('sort'),
            (int)($request->query->get('page')?? 1)
        );
        $query->setOwner($request->query->get('owner'));

        $query->buildQuery();
        return $query;
    }
}