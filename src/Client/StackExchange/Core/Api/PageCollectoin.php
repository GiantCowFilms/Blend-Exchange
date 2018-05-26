<?php declare(strict_types=1);

final class PageCollection implements Iterator
{
    private $page;
    private $pageIndex = 0;

    public function __construct(Query $query,Endpoint $endpoint)
    {
        $this->page = $this->first();
    }

    /**
     * Returns first result in the collection
     *
     * @return
     */
    public function first() 
    {
        return $this->getPage(0);
    }

    public function getPage(int $page) {
        return $this->endpoint->execute($this->query,$page);
    }

    public function current() {
        return $this->page;
    }

    public function key() {
        return $this->pageIndex;
    }

    public function next() {
        if($this->page->hasMore()) {
            $this->pageIndex++;
            $this->page = $this->getPage($this->pageIndex);
        } else {
            $this->page = null;
        }
    }

    public function rewind() {
        $this->pageIndex = 0;
        $this->page = $this->getPage($this->pageIndex);
    }

    public function valid() {
        $this->page !== null;
    }
}