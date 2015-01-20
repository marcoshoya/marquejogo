<?php

namespace Marcoshoya\MarquejogoBundle\Component\Schedule;

use Marcoshoya\MarquejogoBundle\Component\Schedule\ScheduleComponent;

/**
 * Description of HourLeaf
 *
 * @author marcos
 */
class ScheduleItem extends ScheduleComponent
{
    /**
     * @var \DateTime
     */
    private $date;
    
    /**
     * @var decimal
     */
    private $price;
    
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    public function setPrice($price = 0.00)
    {
        $this->price = $price;
    }
    
    public function getPrice()
    {
        return $this->price;
    }
    
    public function isAvailable()
    {
        
    }

    public function show()
    {
        
    }

}
