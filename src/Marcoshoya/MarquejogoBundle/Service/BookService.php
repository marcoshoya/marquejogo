<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Service\BaseService;
use Marcoshoya\MarquejogoBundle\Component\Book\IBook;
use Marcoshoya\MarquejogoBundle\Component\Book\BookDTO;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;
use Marcoshoya\MarquejogoBundle\Entity\Schedule;
use Marcoshoya\MarquejogoBundle\Entity\Provider;

/**
 * BookingService
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BookService extends BaseService implements IBook
{

    /**
     * @inheritDoc
     */
    public function doBook(BookDTO $book)
    {
        try {
            
            $book->getProvider();
            
        } catch (\Exception $ex) {
            $this->getLogger()->error("BookService error: " . $ex->getMessage());
            throw new \RuntimeException("Error to create book");
        }
    }

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

}
