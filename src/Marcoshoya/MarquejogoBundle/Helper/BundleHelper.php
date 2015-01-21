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

    /**
     * Returns the month name in portuguese
     *
     * @param string $month
     * @return string
     */
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

    /**
     * Returns the weekday name in portuguese
     *
     * @param integer $weekday
     * @return string
     */
    public static function weekdayTranslate($weekday)
    {
        switch ($weekday) {
            case '0' : return "Domingo";
            case '1' : return "Segunda-feira";
            case '2' : return "Terça-feira";
            case '3' : return "Quarta-feira";
            case '4' : return "Quinta-feira";
            case '5' : return "Sexta-feira";
            case '6' : return "Sábado";
            default: return "Unknown";
        }
    }

    public static function sluggable($str)
    {
        $before = array(
            'àáâãäåòóôõöøèéêëðçìíîïùúûüñšž',
            '/[^a-z0-9\s]/',
            array('/\s/', '/--+/', '/---+/')
        );

        $after = array(
            'aaaaaaooooooeeeeeciiiiuuuunsz',
            '',
            '-'
        );

        $str = strtolower($str);
        $str = strtr($str, $before[0], $after[0]);
        $str = preg_replace($before[1], $after[1], $str);
        $str = trim($str);
        $str = preg_replace($before[2], $after[2], $str);

        return $str;
    }

}
