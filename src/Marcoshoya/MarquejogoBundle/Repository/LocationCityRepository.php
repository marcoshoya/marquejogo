<?php

namespace Marcoshoya\MarquejogoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LocationCityRepository
 *
 * @author  Marcos Lazarin <marcoshoya at gmail dot com>
 */
class LocationCityRepository extends EntityRepository 
{
    public function findAllOrderedByName($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM MarcoshoyaMarquejogoBundle:LocationCity c
                  WHERE c.state = :id
                  ORDER BY c.name ASC'
            )
            ->setParameter('id', $id)
            ->getResult();
    }
}