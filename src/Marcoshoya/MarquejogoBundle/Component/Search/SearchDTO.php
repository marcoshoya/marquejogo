<?php

namespace Marcoshoya\MarquejogoBundle\Component\Search;

use Marcoshoya\MarquejogoBundle\Entity\Autocomplete;

/**
 * SearchDTO carries data fro search
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class SearchDTO
{

    const session = 'search';

    /**
     * @var Marcoshoya\MarquejogoBundle\Entity\Autocomplete
     */
    private $autocomplete = null;

    /**
     * @var \DateTime
     */
    private $date = null;

    /**
     * Set autocomplete
     *
     * @param Autocomplete $autocomplete
     */
    public function setAutocomplete(Autocomplete $autocomplete)
    {
        $this->autocomplete = $autocomplete;
    }

    /**
     * Get autocomplete
     *
     * @return Autocomplete
     */
    public function getAutocomplete()
    {
        return $this->autocomplete;
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

}
