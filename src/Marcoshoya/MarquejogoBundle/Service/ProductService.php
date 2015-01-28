<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Component\Product\IProduct;
use Marcoshoya\MarquejogoBundle\Entity\Provider;

/**
 * ProductService
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ProductService extends BaseService implements IProduct
{
    public function getallProduct(Provider $provider)
    {
        try {
            
            return $this->getEm()
                ->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')
                ->findBy(array('provider' => $provider));
            
        } catch (\Exception $ex) {
            $this->getLogger()->error("ProductService error: " . $ex->getMessage());
        }
        
        
    }
}
