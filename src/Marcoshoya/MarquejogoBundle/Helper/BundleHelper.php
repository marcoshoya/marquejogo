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

    public static function monthTranslate($month)
    {
        switch ($month) {
            case "January": return "Janeiro";
            case "February": return "Fevereiro";
            case "March": return "Março";
            case "April": return "Abril";
            case "May": return "Maio";
            case "June": return "Junho";
            case "July": return "Julho";
            case "August": return "Agosto";
            case "September": return "Setembro";
            case "October": return "Outubro";
            case "November": return "Novembro";
            case "December": return "Dezembro";
            default: return "Unknown";
        }
    }

}
