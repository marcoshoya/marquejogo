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
     * Dump a doctrine object
     * 
     * @param Object $obj
     * @return string
     */
    public static function dump($obj)
    {
        return \Doctrine\Common\Util\Debug::dump($obj);
    }

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
     * Gets position name
     * 
     * @param string $pos
     * @return string
     */
    public static function positionName($pos)
    {
        switch ($pos) {
            case 'goalkeeper': return 'goleiro';
            case 'defender': return 'defensor';
            case 'middle': return 'meio';
            case 'attacker': return 'atacante';
            default: return 'n/a';
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

    public static function sluggable($string)
    {
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array('&' => 'and');
        $string = mb_strtolower(trim($string), 'UTF-8');
        $string = str_replace(array_keys($special_cases), array_values($special_cases), $string);
        $string = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
        $string = preg_replace("/[^a-z0-9]/u", "-", $string);
        $string = preg_replace("/[-]+/u", "-", $string);

        return $string;
    }

}
