<?php

namespace Marcoshoya\MarquejogoBundle\Component\Schedule;

use Marcoshoya\MarquejogoBundle\Component\Schedule\ScheduleComponent;

/**
 * ScheduleComposite
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ScheduleComposite extends ScheduleComponent
{
    /**
     * @inheritDoc
     */
    public function get($idx)
    {
        return isset($this->itemList[$idx]) ? $this->itemList[$idx] : null;
    }

    /**
     * @inheritDoc
     */
    public function add(ScheduleComponent $item, $idx)
    {
        unset($this->itemList[$idx]);

        $this->itemList[$idx] = $item;
    }

    /**
     * @inheritDoc
     */
    public function remove(ScheduleComponent $item)
    {
        $this->itemList = array_diff($this->itemList, array($item));
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return $this->itemList;
    }

    /**
     * @inheritDoc
     */
    public function show()
    {

    }

}
