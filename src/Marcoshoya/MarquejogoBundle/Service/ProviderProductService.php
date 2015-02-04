<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Service\BaseService;
use Marcoshoya\MarquejogoBundle\Entity\Provider;

/**
 * ProviderProductService
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ProviderProductService extends BaseService
{
    /**
     * Gets all active product
     * 
     * @param Provider $provider
     * @return array
     */
    public function getallProductActive(Provider $provider)
    {
        try {

            return $this->getEm()
                    ->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')
                    ->findBy(array('provider' => $provider, 'active' => true));
            
        } catch (\Exception $ex) {
            $this->getLogger()->error("ProviderProductService error: " . $ex->getMessage());
        }
    }
}
