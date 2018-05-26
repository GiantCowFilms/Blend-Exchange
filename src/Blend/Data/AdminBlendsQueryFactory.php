<?php declare(strict_types = 1);

namespace BlendExchange\Blend\Data;

use BlendExchange\Blend\Data\AdminBlendsQuery;
use Symfony\Component\HttpFoundation\Request;

final class AdminBlendsQueryFactory {
    public function __construct () {

    }

    public function createFromRequest(Request $request,BlendsQuery $blendsQuery) : AdminBlendsQuery
    {
        $query =  new AdminBlendsQuery(
            $blendsQuery,
            $request->query->get('flagged') === 'true',
            $request->query->get('deleted') === 'true'
        );

        $query->buildQuery();
        return $query;
    }
}