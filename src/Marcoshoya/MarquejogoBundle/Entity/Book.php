<?php

namespace Marcoshoya\MarquejogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Book
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="Marcoshoya\MarquejogoBundle\Repository\BookRepository")
 */
class Book
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Provider", inversedBy="book")
     * @ORM\JoinColumn(name="provider_id", referencedColumnName="id")
     */
    private $provider;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="book", cascade={"persist"})
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;

    /**
     * @ORM\Column(type="string", nullable=false, columnDefinition="ENUM('new', 'booked', 'closed', 'cancelled')")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(name="total_price", type="decimal", precision=10, scale=2)
     */
    private $totalPrice;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="BookItem", mappedBy="book")
     * */
    private $bookItem;


    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->bookItem = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->id;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Book
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Book
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
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
     * Set totalPrice
     *
     * @param string $totalPrice
     * @return Book
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return string
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set customer
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Customer $customer
     * @return Book
     */
    public function setCustomer(\Marcoshoya\MarquejogoBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Marcoshoya\MarquejogoBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add bookItem
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\BookItem $bookItem
     * @return Book
     */
    public function addBookItem(\Marcoshoya\MarquejogoBundle\Entity\BookItem $bookItem)
    {
        $this->bookItem[] = $bookItem;

        return $this;
    }

    /**
     * Remove bookItem
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\BookItem $bookItem
     */
    public function removeBookItem(\Marcoshoya\MarquejogoBundle\Entity\BookItem $bookItem)
    {
        $this->bookItem->removeElement($bookItem);
    }

    /**
     * Get bookItem
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookItem()
    {
        return $this->bookItem;
    }

    /**
     * Set provider
     *
     * @param \Marcoshoya\MarquejogoBundle\Entity\Provider $provider
     * @return Book
     */
    public function setProvider(\Marcoshoya\MarquejogoBundle\Entity\Provider $provider = null)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return \Marcoshoya\MarquejogoBundle\Entity\Provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Book
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Book
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Gets status name translated
     *
     * @return string
     */
    public function getStatusName()
    {
        return \Marcoshoya\MarquejogoBundle\Helper\BundleHelper::bookStatusName($this->status);
    }

    /**
     * Allows cancel the book
     *
     * @return boolean
     */
    public function allowCancel()
    {
        if ($this->status === 'new' ||
            $this->status === 'booked') {

            return true;
        }

        return false;
    }

    /**
     * Allows confirm the book
     *
     * @return boolean
     */
    public function allowConfirm()
    {
        if ($this->status === 'new') {

            return true;
        }

        return false;
    }
}
