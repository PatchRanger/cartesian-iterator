<?php

namespace PatchRanger;

class CartesianIterator extends \MultipleIterator
{
    /** @var \Iterator[] */
    protected $iterators = [];

    /** @var int */
    protected $key = 0;

    /** @var string[] */
    protected $infosHashMap = [];

    public function __construct()
    {
        parent::__construct(static::MIT_NEED_ALL|static::MIT_KEYS_ASSOC);
    }

    public function attachIterator(\Iterator $iterator, $infos = null): void
    {
        $this->iterators[] = $iterator;
        if ($infos === null) {
            $infos = count($this->iterators) - 1;
        }
        if (isset($this->infosHashMap[$infos])) {
            throw new \InvalidArgumentException("Iterator with the same key has been already added: {$infos}");
        }
        $this->infosHashMap[$infos] = spl_object_hash($iterator);
        parent::attachIterator($iterator, $infos);
    }

    public function detachIterator(\Iterator $iterator): void
    {
        if (!$this->containsIterator($iterator)) {
            return;
        }
        parent::detachIterator($iterator);
        $iteratorHash = spl_object_hash($iterator);
        foreach ($this->iterators as $index => $iteratorAttached) {
            if ($iteratorHash === spl_object_hash($iteratorAttached)) {
                unset($this->iterators[$index]);
                break;
            }
        }
        $infos = array_flip($this->infosHashMap)[spl_object_hash($iterator)];
        unset($this->infosHashMap[$infos]);
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
