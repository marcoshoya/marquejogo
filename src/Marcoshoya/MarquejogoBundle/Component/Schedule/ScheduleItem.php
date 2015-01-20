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
    public function addProduct($product, $idx)
    {
        unset($this->productList[$idx]);

        $this->productList[$idx] = $product;
    }

    /**
     * Get a all products from item
     *
     * @return array
     */
    public function allProduct()
    {
        return $this->productList;
    }
}
