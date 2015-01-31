<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Service\BaseService;
use Marcoshoya\MarquejogoBundle\Component\Book\IBook;
use Marcoshoya\MarquejogoBundle\Component\Book\BookDTO;
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
        $book->getProvider();
    }

    /**
     * Persist the object book on session
     *
     * @param Schedule $schedule
     */
    public function setBookSession(Schedule $schedule)
    {
        $provider = $schedule->getProvider();

        // create book object
        $bookDTO = new BookDTO($provider);
        // session name
        $key = $bookDTO->getSessionKey();

        // if already exists, clear
        if ($this->getSession()->has($key)) {
            $this->getSession()->remove($key);
        }

        foreach ($schedule->getScheduleItem() as $idx => $item) {
            $bookDTO->addItem($item, $idx);
        }

        $this->getSession()->set($key, serialize($bookDTO));
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
