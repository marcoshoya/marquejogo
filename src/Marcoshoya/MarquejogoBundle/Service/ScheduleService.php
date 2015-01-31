<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Component\Schedule\ISchedule;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;
use Marcoshoya\MarquejogoBundle\Entity\Provider;

/**
 * ProductService
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ScheduleService extends BaseService implements ISchedule
{

    public function getallProduct(Provider $provider)
    {
        try {

            return $this->getEm()
                    ->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')
                    ->findBy(array('provider' => $provider));
        } catch (\Exception $ex) {
            $this->getLogger()->error("ScheduleService error: " . $ex->getMessage());
        }
    }

    public function getallProductBySearch(Provider $provider, SearchDTO $search)
    {
        try {

            $qb = $this->getEm()->createQueryBuilder()
                ->from('MarcoshoyaMarquejogoBundle:Schedule', 's')
                ->innerJoin('s.scheduleItem', 'si')
                ->where('s.provider = :provider')
                ->setParameter('provider', $provider);
            
            $query = $qb->getQuery();
            
            return $query->getResult();


        } catch (\Exception $ex) {
            $this->getLogger()->error("ScheduleService error: " . $ex->getMessage());
        }
    }

}
