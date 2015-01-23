<?php

namespace Marcoshoya\MarquejogoBundle\Component\Search;

use Marcoshoya\MarquejogoBundle\Entity\Provider;

/**
 * SearchCollection
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class SearchCollection implements \IteratorAggregate
{
    /**
     * @var Provider[]
     */
    private $itemList = array();
    
    /**
     * It implements \IteratorAggregate.
     *
     * @see all()
     *
     * @return \ArrayIterator An \ArrayIterator object for iterating over itens
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->itemList);
    }
    
    /**
     * Add a item
     * 
     * @param Provider $item
     * @param integer|string $idx
     */
    public function add(Provider $item, $idx)
    {
        unset($this->itemList[$idx]);

        $this->itemList[$idx] = $item;
    }

    /**
     * Remove item
     * 
     * @param Provider $item
     */
    public function remove(Provider $item)
    {
        $this->itemList = array_diff($this->itemList, array($item));
    }
    
    /**
     * Get item by index
     * 
     * @param integer|string $idx
     */
    public function get($idx)
    {
        return isset($this->itemList[$idx]) ? $this->itemList[$idx] : null;
    }
    
    /**
     * Get all itens
     * 
     * @return Provider[] An array of itens
     */
    public function all()
    {
        return $this->itemList;
    }
    
    /**
     * Number of itens on collection
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->itemList);
    }

}
