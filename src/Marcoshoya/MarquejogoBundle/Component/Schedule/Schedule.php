<?php

namespace Marcoshoya\MarquejogoBundle\Component\Schedule;

use Marcoshoya\MarquejogoBundle\Component\Schedule\ScheduleComponent;

/**
 * Description of DayComposite
 *
 * @author marcos
 */
class Schedule extends ScheduleComponent
{   
    public function get($idx)
    {
        return isset($this->itemList[$idx]) ? $this->itemList[$idx] : null;
    }

    public function add(ScheduleComponent $item, $idx)
    {
        unset($this->itemList[$item]);

        $this->itemList[$idx] = $item;
    }

    public function remove(ScheduleComponent $item)
    {
        $this->itemList = array_diff($this->itemList, array($item));
    }

    public function all()
    {
        return $this->itemList;
    }

    public function show()
    {
        
    }

}
