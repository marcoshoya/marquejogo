<?php

namespace Marcoshoya\MarquejogoBundle\Helper;

/**
 * BundleHelper implements help functions used on application
 *
 * @author  Marcos Joia <marcoshoya at gmail dot com>
 */
class BundleHelper
{
    /**
     * Returns product category name
     * 
     * @param string $category
     * @return string
     */
    public static function productCategory($category)
    {
        switch ($category) {
            case 'open': return 'Aberto';
            case 'close': return 'Fechado';
            default: return $category;
        }
    }
    
    /**
     * Returns product type name
     * 
     * @param string $type
     * @return string
     */
    public static function productType($type)
    {
        switch ($type) {
            case 'soccer': return 'Futebol';
            case 'voley': return 'Vôleibol';
            case 'swiss': return 'Futebol suiço';
            default: return $type;
        }
    }
}
