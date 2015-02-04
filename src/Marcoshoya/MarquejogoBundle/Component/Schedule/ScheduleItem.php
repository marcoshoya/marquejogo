<?php

namespace Marcoshoya\MarquejogoBundle\Component\Schedule;

use Marcoshoya\MarquejogoBundle\Component\Schedule\ScheduleComponent;

/**
 * ScheduleItem
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ScheduleItem extends ScheduleComponent
{
    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var array
     */
    private $productList = array();
    
    /**
     * @var array
     */
    private $bookList = array();

    /**
     * @inheritDoc
     */
    public function show()
    {
        return $this->date->format('H:i');
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Add a product on item
     *
     * @param Entity $product
     * @param integer|string $idx
     */
    public function addProduct($product, $time, $idx)
    {
        unset($this->productList[$time][$idx]);

        $this->productList[$time][$idx] = $product;
    }

    /**
     * Get a all products from item
     *
     * @return array
     */
    public function allProduct($time)
    {
        return $this->productList[$time];
    }
    
    /**
     * Add a book on item
     *
     * @param Entity $book
     * @param integer|string $idx
     */
    public function addBook($book, $time, $idx)
    {
        unset($this->bookList[$time][$idx]);

        $this->bookList[$time][$idx] = $book;
    }
    
    public function getBook($time, $idx)
    {
        return isset($this->bookList[$time][$idx]) ? $this->bookList[$time][$idx] : null;
    }
}
