<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Component\Schedule\ISchedule;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Entity\Schedule;

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

    public function getallProductBySearch(Schedule $schedule, SearchDTO $search)
    {
        try {

            $qb = $this->getEm()->createQueryBuilder()
                ->select('s')
                ->from('MarcoshoyaMarquejogoBundle:ScheduleItem', 's')
                ->where('s.schedule = :schedule')
                ->andWhere('s.date = :date')
                ->setParameters(array(
                    'schedule' => $schedule,
                    'date' => $search->getDate(),
                ));
            
            $query = $qb->getQuery();

            return $query->execute();

        } catch (\Exception $ex) {
            $this->getLogger()->error("ScheduleService error: " . $ex->getMessage());
        }
    }

}
