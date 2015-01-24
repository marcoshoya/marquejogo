<?php

namespace Marcoshoya\MarquejogoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Entity\ProviderPicture;

/**
 * ProviderPictureRepository
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ProviderPictureRepository extends EntityRepository
{
    /**
     * Set main picture to on
     * 
     * @param ProviderPicture $entity
     */
    public function mainpicture(ProviderPicture $entity)
    {
        $provider = $entity->getProvider();
        $this->mainpictureOff($provider);

        $em = $this->getEntityManager();
        $entity->setIsActive(true);
        $em->persist($entity);
        $em->flush();
    }

    /**
     * Set main picture to off
     * 
     * @param Provider $entity
     * @return boolean
     */
    public function mainpictureOff(Provider $entity)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $q = $qb->update("MarcoshoyaMarquejogoBundle:ProviderPicture", "pp")
            ->set("pp.isActive", ":active")
            ->where("pp.provider = :provider")
            ->setParameter("active", false)
            ->setParameter("provider", $entity->getId())
            ->getQuery();

        return $q->execute();
    }

}
