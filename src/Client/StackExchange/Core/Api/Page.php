<?php declare(strict_types=1);

final class Page implements Iterator
{
    private $hasMore;
    private $items;
    private $itemsIterator;
    public function __construct($items, $hasMore)
    {
        $this->items = $items;
        $this->hasMore = $hasMore;
        $this->itemsIterator = new ArrayIterator($items);
    }

    public function next()
    {
        return $this->itemsIterator->next();
    }

    public function current()
    {
        return $this->itemsIterator->current();
    }

    public function rewind()
    {
        return $this->itemsIterator->rewind();
    }

    public function valid()
    {
        return $this->itemsIterator->valid();
    }

    public function key()
    {
        return $this->itemsIterator->key();
    }

    public function hasMore() : bool
    {
        return $this->hasMore;
    }
}
