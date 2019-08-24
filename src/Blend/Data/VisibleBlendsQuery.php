<?php declare(strict_types=1);

namespace BlendExchange\Blend\Data;

use BlendExchange\Blend\Model\BlendFile;

class VisibleBlendsQuery {
    private $query;

    public function __construct()
    {
        $this->query = BlendFile::query();
    }

    public function buildQuery() {
        $this->query = $this->query->where('fileGoogleId','!=',null);
    }

    public function getQuery()
    {
        return $this->query;
    }
}