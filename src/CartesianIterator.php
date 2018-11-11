<?php

namespace PatchRanger;

class CartesianIterator extends \MultipleIterator
{
    /** @var \Iterator[] */
    private $iterators;

    public function __construct($flags)
    {
        parent::__construct($flags);
        $this->setFlags($flags|static::MIT_NEED_ALL);
    }

    public function attachIterator(\Iterator $iterator, $infos = null)
    {
        parent::attachIterator($iterator, $infos);
        $this->iterators[] = $iterator;
    }

    public function detachIterator(\Iterator $iterator)
    {
        parent::detachIterator($iterator);
        foreach ($this->iterators as $index => $iteratorAttached) {
            if ($iterator === $iteratorAttached) {
                unset($this->iterators[$index]);
                break;
            }
        }
    }

    public function key()
    {
        return array_sum(parent::key());
    }

    public function next()
    {
        $this->applyNext();
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
