<?php

namespace Marcoshoya\MarquejogoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BookRepository
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BookRepository extends EntityRepository
{
    public function findByPeriod(\DateTime $from, \DateTime $until)
    {
       $qb = $this->getEntityManager()->createQueryBuilder();
       
       $qb->select('b')
           ->from('MarcoshoyaMarquejogoBundle:Book', 'b')
           ->where($qb->expr()->between('b.created', ':from', ':until'))
           ->orderBy('b.created', 'desc')
           ->setParameters(array(
               'from' => $from->format('Y-m-d H:i:s'),
               'until' => $until->format('Y-m-d H:i:s')
           ));
       
       $query = $qb->getQuery();
       
       return $query->getResult();
    }
}
