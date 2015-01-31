<?php

namespace Marcoshoya\MarquejogoBundle\Component\Book;

use Marcoshoya\MarquejogoBundle\Entity\ScheduleItem;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Entity\Customer;

/**
 * BookDTO
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BookDTO
{
    /**
     * @var string
     */
    private $sessionKey;

    /**
     * @var Provider
     */
    private $provider;
    
    /**
     * @var Customer
     */
    private $customer = null;

    /**
     * @var DateTime
     */
    private $date = null;
    
    /**
     * @var ScheduleItem[]
     */
    private $itemList = array();
    
    /**
     * @var string[]
     */
    private $itemName = array();
    
    /**
     * @var decimal
     */
    private $price = 0.00;
    
    /**
     *
     * @var type 
     */
    private $picture = null;
    
    /**
     * Constructor
     * 
     * @param Provider $provider
     */
    public function __construct(Provider $provider)
    {
        $this->sessionKey = md5(sprintf('book#%s', $provider->getId()));
        $this->setProvider($provider);
    }
    
    /**
     * Update book price
     * 
     * @param decimal $price
     */
    public function updatePrice($price)
    {
        $this->price = bcadd($this->price, $price);
    }
    
    /**
     * Get book price
     * 
     * @return decimal
     */
    public function getPrice()
    {
        return number_format($this->price, 2, ',', '');
    }


    /**
     * Get session id
     * 
     * @return string
     */
    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    /**
     * Set provider
     *
     * @param Provider $provider
     */
    public function setProvider(Provider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get provider
     *
     * @return Provider
     */
    public function getProvider()
    {
        return $this->provider;
    }
    
    /**
     * Set customer
     *
     * @param Provider $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Get customer
     *
     * @return Provider
     */
    public function getCustomer()
    {
        return $this->customer;
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
     * Add item
     *
     * @param ScheduleItem $item
     * @param integer $idx
     */
    public function addItem(ScheduleItem $item, $idx)
    {
        unset($this->itemList[$idx]);

        $this->itemList[$idx] = $item;
        $this->itemName[$idx] = $item->getProviderProduct()->getName();
        
        
    }

    /**
     * Remove item
     *
     * @param ScheduleItem $item
     */
    public function remove(ScheduleItem $item)
    {
        $this->itemList = array_diff($this->itemList, array($item));
    }

    /**
     * Get item
     *
     * @param integer $idx
     * @return ScheduleItem|null
     */
    public function getItem($idx)
    {
        return isset($this->itemList[$idx]) ? $this->itemList[$idx] : null;
    }
    
    /**
     * Get item name
     *
     * @param integer $idx
     * @return string|null
     */
    public function getItemName($idx)
    {
        return isset($this->itemName[$idx]) ? $this->itemName[$idx] : null;
    }

    /**
     * Get all item
     *
     * @return ScheduleItem[]
     */
    public function getAllItem()
    {
        return $this->itemList;
    }

    /**
     * Number of itens on collection
     *
     * @return integer
     */
    public function countItem()
    {
        return count($this->itemList);
    }

    /**
     * Set picture
     * 
     * @param \Marcoshoya\MarquejogoBundle\Entity\ProviderPicture $picture
     */
    public function setPicture(\Marcoshoya\MarquejogoBundle\Entity\ProviderPicture $picture)
    {
        $this->picture = $picture;
    }
    
    /**
     * Get picture
     * 
     * @return type
     */
    public function getPicture()
    {
        return $this->picture;
    }
}
