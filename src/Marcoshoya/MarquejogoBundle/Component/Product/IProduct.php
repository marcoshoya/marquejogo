<?php

namespace Marcoshoya\MarquejogoBundle\Component\Product;

use Marcoshoya\MarquejogoBundle\Entity\Provider;

/**
 * IProduct
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
interface IProduct
{

    /**
     * Get all product to sell from provider
     * 
     * @param Provider $provider
     */
    public function getallProduct(Provider $provider);
}
