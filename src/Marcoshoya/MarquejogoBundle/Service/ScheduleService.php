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
    
    public function getallProductBySearch(SearchDTO $search)
    {
        try {
            
            /**
            return $this->getEm()
                ->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')
                ->findBy(array('provider' => $provider));
             * 
             */
            
        } catch (\Exception $ex) {
            $this->getLogger()->error("ScheduleService error: " . $ex->getMessage());
        }
        
        
    }
}
