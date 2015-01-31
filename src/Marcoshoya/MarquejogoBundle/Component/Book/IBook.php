<?php

namespace Marcoshoya\MarquejogoBundle\Component\Book;

use Marcoshoya\MarquejogoBundle\Component\Book\BookDTO;

/**
 * IBook interface
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
interface IBook
{
    /**
     * Process the book
     * 
     * @param BookDTO $book
     */
    public function doBook(BookDTO $book);
}
