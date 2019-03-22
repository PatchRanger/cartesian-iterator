<?php

namespace PatchRanger;

class CartesianIterator extends \MultipleIterator
{
    /** @var \Iterator[] */
    protected $iterators;

    /** @var int */
    protected $key = 0;

    public function __construct($flags = self::MIT_NEED_ANY|self::MIT_KEYS_NUMERIC)
    {
        parent::__construct($flags);
        $this->setFlags($flags|static::MIT_NEED_ALL);
    }

    public function attachIterator(\Iterator $iterator, $infos = null): void
    {
        parent::attachIterator($iterator, $infos);
        $this->iterators[] = $iterator;
    }

    public function detachIterator(\Iterator $iterator): void
    {
        parent::detachIterator($iterator);
        foreach ($this->iterators as $index => $iteratorAttached) {
            if ($iterator === $iteratorAttached) {
                unset($this->iterators[$index]);
                break;
            }
        }
    }

    public function key(): int
    {
        return $this->key;
    }

    public function next(): void
    {
        $this->applyNext();
        $this->key += 1;
    }

    public function rewind(): void
    {
        parent::rewind();
        $this->key = 0;
    }

    private function applyNext(int $index = 0): void
    {
        $iterator = $this->iterators[$index];
        $iterator->next();
        if (!$iterator->valid() && $index < $this->countIterators() - 1) {
            $iterator->rewind();
            $this->applyNext($index + 1);
        }
    }
}
