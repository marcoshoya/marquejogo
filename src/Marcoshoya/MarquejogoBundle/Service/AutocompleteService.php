<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;

/**
 * Description of AutocompleteService
 *
 * @author marcos
 */
class AutocompleteService implements \SplObserver
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * @var Symfony\Bridge\Monolog\Logger
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function update(\SplSubject $subject)
    {
        try {
            
            $this->logger->info("UPDATE AUTOCOMPLETE");
            
        } catch (\Exception $ex) {
            $this->logger->error("AutocompleteService error: " . $ex->getMessage());
        }
    }
}
