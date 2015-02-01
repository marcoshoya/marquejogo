<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Service\BaseService;
use Marcoshoya\MarquejogoBundle\Component\Book\IBook;
use Marcoshoya\MarquejogoBundle\Component\Book\BookDTO;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;
use Marcoshoya\MarquejogoBundle\Entity\Schedule;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Entity\Book;

/**
 * BookingService
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BookService extends BaseService implements IBook
{

    /**
     * Persist the object book on session
     *
     * @param Schedule $schedule
     */
    public function createBookSession(Schedule $schedule, SearchDTO $searchDTO)
    {
        $provider = $schedule->getProvider();
        $date = $searchDTO->getDate();

        // create book object
        $bookDTO = new BookDTO($provider);
        $bookDTO->setDate($date);

        // picture
        $picture = $this->getPersonDelegate()->getBusinessService($provider)->getPicture();
        if (null !== $picture) {
            $bookDTO->setPicture($picture);
        }

        foreach ($schedule->getScheduleItem() as $idx => $item) {
            if ($item->getAlocated()) {
                $bookDTO->addItem($item, $idx);
                $bookDTO->updatePrice($item->getPrice());
            }
        }

        $this->setBookSession($provider, $bookDTO);

        return $bookDTO;
    }

    /**
     * Set book on session
     * 
     * @param Provider $provider
     * @param BookDTO $book
     */
    public function setBookSession(Provider $provider, BookDTO $book)
    {
        $bookDTO = new BookDTO($provider);

        // session name
        $key = $bookDTO->getSessionKey();

        // if already exists, clear
        if ($this->getSession()->has($key)) {
            $this->getSession()->remove($key);
        }

        $this->getSession()->set($key, serialize($book));

        return $book;
    }

    /**
     * Get object book from session
     *
     * @param \Marcoshoya\MarquejogoBundle\Service\Provider $provider
     * @return BookDTO|null
     */
    public function getBookSession(Provider $provider)
    {
        $bookDTO = new BookDTO($provider);
        // session name
        $key = $bookDTO->getSessionKey();
        if ($this->getSession()->has($key)) {

            return unserialize($this->getSession()->get($key));
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function doBook(BookDTO $bookDTO)
    {
        try {

            $this->getEm()->getConnection()->beginTransaction();

            $book = $this->createBook($bookDTO);

            $this->getEm()->getConnection()->commit();

            return $book;
        } catch (\Exception $ex) {
            $this->getLogger()->error("BookService error: " . $ex->getMessage());
            $this->getEm()->getConnection()->rollback();
            throw new \RuntimeException("Error to create book");
        }
    }

    /**
     * Create book process
     * 
     * @param BookDTO $bookDTO
     * @return Book
     */
    private function createBook(BookDTO $bookDTO)
    {
        $total = 0.00;
        $bookObject = $this->persistBook();
        $customer = $this->persistCustomer($bookDTO);

        $bookObject->setCustomer($customer);
        $bookObject->setDate($bookDTO->getDate());

        foreach ($bookDTO->getAllItem() as $item) {
            $bookItem = new \Marcoshoya\MarquejogoBundle\Entity\BookItem();
            $bookItem->setBook($bookObject);
            $bookItem->setProduct($item->getProviderProduct()->getId());
            $bookItem->setName($item->getProviderProduct()->getName());
            $bookItem->setPrice($item->getPrice());

            $this->getEm()->persist($bookItem);

            $total = bcadd($total, $item->getPrice());
        }

        $bookObject->setTotalPrice($total);

        $this->getEm()->persist($bookObject);
        $this->getEm()->flush();

        return $bookObject;
    }

    /**
     * Persist book
     * 
     * @return Book
     */
    private function persistBook()
    {
        $bookObject = new Book();
        $bookObject->setStatus('new');
        $bookObject->setTotalPrice(0.00);


        $this->getEm()->persist($bookObject);
        $this->getEm()->flush();

        return $bookObject;
    }
    
    /**
     * Persist customer
     * 
     * @param BookDTO $bookDTO
     * @return Customer
     */
    private function persistCustomer(BookDTO $bookDTO)
    {
        $customer = $bookDTO->getCustomer();
        $this->getEm()->persist($customer);
        $this->getEm()->flush();

        foreach ($customer->getTeam() as $team) {
            $team->setOwner($customer);
            $this->getEm()->persist($team);
        }
        
        $this->getEm()->flush();

        return $customer;
    }

}
