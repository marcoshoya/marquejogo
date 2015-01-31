<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Service\BaseService;
use Marcoshoya\MarquejogoBundle\Component\Book\IBook;
use Marcoshoya\MarquejogoBundle\Component\Book\BookDTO;

/**
 * BookingService
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BookService extends BaseService implements IBook
{
    public function doBook(BookDTO $book)
    {
        $book->getProvider();
    }

}
