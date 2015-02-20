<?php

namespace Marcoshoya\MarquejogoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * BookRepository
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class BookRepository extends EntityRepository
{
    /**
     * Find all books by period
     * 
     * @param \DateTime $from
     * @param \DateTime $until
     * 
     * @return array
     */
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
    
    public function countBookByMonth(\DateTime $date)
    {
        $sql = "SELECT EXTRACT(month from created_at) as month, count(id) as quantity
                    FROM book
                    WHERE EXTRACT(year from created_at) = :year 
                GROUP BY EXTRACT(month from created_at)";
        
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('quantity', 'quantity');
        $rsm->addScalarResult('month', 'month');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('year', $date->format('Y'));
        
        return $query->getArrayResult();
    }
}
