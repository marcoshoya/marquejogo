<?php

namespace Marcoshoya\MarquejogoBundle\Component\Schedule;

/**
 * ScheduleComponent provides the interface to use on schedule itens
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
abstract class ScheduleComponent implements \IteratorAggregate
{

    /**
     * @var ScheduleComponent[]
     */
    private $itemList = array();

    /**
     * Prints item data
     */
    public abstract function show();

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
     * @param \Marcoshoya\MarquejogoBundle\Component\Schedule\ScheduleComponent $item
     * @param integer|string $idx
     */
    public function add(ScheduleComponent $item, $idx)
    {
        throw new \BadMethodCallException();
    }

    /**
     * Remove item
     * 
     * @param \Marcoshoya\MarquejogoBundle\Component\Schedule\ScheduleComponent $item
     */
    public function remove(ScheduleComponent $item)
    {
        throw new \BadMethodCallException();
    }

    /**
     * Get all itens
     * 
     * @return ScheduleComponent[] An array of itens
     */
    public function all()
    {
        throw new \BadMethodCallException();
    }

    /**
     * Get item by index
     * 
     * @param integer|string $idx
     */
    public function get($idx)
    {
        throw new \BadMethodCallException();
    }

}
