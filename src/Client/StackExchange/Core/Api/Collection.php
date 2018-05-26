<?php declare(strict_types=1);



final class Collection implements Iterator
{
    private $page;
    private $pageCollection;
    private $position;
    
    public function __construct(PageCollection $pages) {
        $this->pageCollection = $pages;
    }

    /**
     * Returns first result in the collection
     *
     * @return
     */
    public function first() 
    {
        return $this->pageCollection->getPage(0)->first();
    }

    public function current() {
        $this->page->current();
    }

    public function key() {
        return $this->position;
    }


    public function next() {
        $this->position++;
        $this->page->next();
        if(!$this->page->valid()) {
            $this->pageCollection->next();
        }
        $this->page = $this->pageCollection->current();
    }

    public function rewind() {
        $this->position = 0;
        $this->pageCollection->rewind();$this->pageCollection->current()->rewind();
    }

    public function valid() {
        return $this->pageCollection->valid();
    }
}