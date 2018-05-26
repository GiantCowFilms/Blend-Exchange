<?php declare(strict_types=1);

namespace BlendExchange\Blend\Data;

final class AdminBlendsQuery
{
    private $query;
    private $errors;
    private $page;
    public function __construct(BlendsQuery $blendsQuery, bool $flagged, bool $deleted)
    {
        $this->query = $blendsQuery->getQuery();
        $this->errors = $blendsQuery->getErrors();
        $this->page = $blendsQuery->getPage();
        $this->flagged = $flagged;
        $this->deleted = $deleted;
    }

    public function buildQuery()
    {
        if ($this->flagged) {
            $this->query = $this->query->withCount('flags')->where('flag_count', '>', 0);
        }
        if ($this->deleted) {
            $this->query = $this->query->withTrashed();
        }
    }

    public function execute()
    {
        return $this->query->paginate(25,['*'], 'page', $this->page);
    }
}
