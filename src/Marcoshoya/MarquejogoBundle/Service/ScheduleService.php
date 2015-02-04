<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Component\Schedule\ISchedule;
use Marcoshoya\MarquejogoBundle\Component\Search\SearchDTO;
use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Entity\Schedule;
use Marcoshoya\MarquejogoBundle\Entity\ProviderProduct;
use Marcoshoya\MarquejogoBundle\Helper\BundleHelper;

/**
 * ScheduleService
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ScheduleService extends BaseService implements ISchedule
{

    /**
     * Gets now date
     * 
     * @return array
     */
    public function getNow()
    {
        $current = new \DateTime();
        $now['day'] = $current->format('j');
        $now['month'] = BundleHelper::monthTranslate($current->format('F'));
        $now['year'] = $current->format('Y');

        return $now;
    }

    /**
     * Gets the first day of month
     * 
     * @param integer $year
     * @param integer $month
     * 
     * @return \DateTime
     */
    public function getFirstdayMonth($year, $month)
    {
        return new \DateTime(date(sprintf('%d-%d-01', $year, $month)));
    }

    /**
     * Gets the last day of month based on first day
     * 
     * @param \DateTime $firstDay
     * 
     * @return \DateTime
     */
    public function getLastdayMonth(\DateTime $firstDay)
    {
        $lastDay = clone $firstDay;
        $lastDay->modify('+1 month');
        $lastDay->modify('-1 day'); // bug with february

        return $lastDay;
    }

    /**
     * Creates the month calendar
     * 
     * @param \DateTime $firstDay
     * @param Provider $provider    Optional to check books on day
     * 
     * @return array
     */
    public function createMonthCalendar(\DateTime $firstDay, Provider $provider = null)
    {
        $schedule = $navbar = array();

        // last day of month
        $lastDay = $this->getLastdayMonth($firstDay);

        // check if fist day is not sunday
        if ($firstDay->format('w') != 0) {
            $firstDay->modify('last Sunday');
        }

        // get next 6 weeks
        $d1 = clone $firstDay;
        $d2 = clone $d1;
        $d2->modify('+6 weeks');

        $week = $day = 1;

        // get day by week
        for ($aux = $d1->getTimestamp(); $aux < $d2->getTimestamp(); $aux = strtotime('+1 day', $aux)) {
            $datetime = new \DateTime(date('Y-m-d', $aux));
            $schedule[$week][$day]['date'] = $datetime;

            $hasBook = false;
            if (null !== $provider) {
                $hasBook = $this->getBookByDate($provider, $datetime);
                $schedule[$week][$day]['book'] = count($hasBook) ? true : false;
            }

            // check if date from loop is the last day of the month and if it is not saturday
            if ($datetime->format('Y-m-d') === $lastDay->format('Y-m-d') && $day === 7) {
                break;
            }

            // adjusts to print 5 weeks
            if ($datetime->format('Y-m-d') > $lastDay->format('Y-m-d') && $day === 1) {
                unset($schedule[$week]);
                break;
            }

            // controls push on array of week, to organize it
            $day++;
            if ($day > 7) {
                $day = 1;
                $week++;
            }
        }

        return $schedule;
    }

    /**
     * Creates the navbar on top calendar
     * 
     * @param \DateTime $firstDay
     * @param string $modify
     * 
     * @return array
     */
    public function createNavbar(\DateTime $firstDay, $modify = 'month')
    {
        // nav bar above calendar
        $navbar = array();

        $currMonth = clone $firstDay;
        $prevMonth = clone $firstDay;
        $prevMonth->modify("-1 {$modify}");
        $nextMonth = clone $firstDay;
        $nextMonth->modify("+1 {$modify}");

        $navbar['curr']['title']['index'] = sprintf(
            '%s de %d', // template
            BundleHelper::monthTranslate($currMonth->format('F')), // month
            $currMonth->format('Y') // year
        );
        $navbar['curr']['title']['edit'] = sprintf(
            '%s %d %s, %d', // title format
            BundleHelper::weekdayTranslate($currMonth->format('w')), // day of week
            $currMonth->format('d'), // day number
            BundleHelper::monthTranslate($currMonth->format('F')), // name of month
            $currMonth->format('Y')
        );
        $navbar['curr']['title']['show'] = sprintf(
            '%s, %d de %s de %d', // title format
            BundleHelper::weekdayTranslate($currMonth->format('w')), // day of week
            $currMonth->format('d'), // day number
            BundleHelper::monthTranslate($currMonth->format('F')), // name of month
            $currMonth->format('Y')
        );
        $navbar['curr']['date'] = $currMonth;
        $navbar['curr']['month'] = $currMonth->format('m');
        $navbar['curr']['year'] = $currMonth->format('Y');
        $navbar['curr']['day'] = $currMonth->format('d');
        $navbar['prev']['month'] = $prevMonth->format('m');
        $navbar['prev']['year'] = $prevMonth->format('Y');
        $navbar['prev']['day'] = $prevMonth->format('d');
        $navbar['next']['month'] = $nextMonth->format('m');
        $navbar['next']['year'] = $nextMonth->format('Y');
        $navbar['next']['day'] = $nextMonth->format('d');

        return $navbar;
    }
    
    /**
     * 
     * @param Provider $provider
     * @param \DateTime $datetime
     * @return type
     */
    public function getBookByDate(Provider $provider, \DateTime $datetime)
    {
        try {

            $qb = $this->getEm()->createQueryBuilder();
            $qb->select('b')
                ->from('MarcoshoyaMarquejogoBundle:Book', 'b')
                ->where('b.provider = :provider')
                ->andWhere($qb->expr()->between('b.date', ':fromDate', ':toDate'))
                ->setParameters(array(
                    'provider' => $provider,
                    'fromDate' => $datetime->format('Y-m-d 00:00:00'),
                    'toDate' => $datetime->format('Y-m-d 23:59:59'),
            ));

            $query = $qb->getQuery();

            return $query->execute();
        } catch (\Exception $ex) {
            $this->getLogger()->error("ScheduleService error: " . $ex->getMessage());
        }
    }

    public function getallProduct(Provider $provider)
    {
        try {

            return $this->getEm()
                    ->getRepository('MarcoshoyaMarquejogoBundle:ProviderProduct')
                    ->findBy(array('provider' => $provider));
        } catch (\Exception $ex) {
            $this->getLogger()->error("ScheduleService error: " . $ex->getMessage());
        }
    }

    public function getallProductBySearch(Schedule $schedule, SearchDTO $search)
    {
        try {

            $qb = $this->getEm()->createQueryBuilder()
                ->select('s')
                ->from('MarcoshoyaMarquejogoBundle:ScheduleItem', 's')
                ->where('s.schedule = :schedule')
                ->andWhere('s.date = :date')
                ->setParameters(array(
                'schedule' => $schedule,
                'date' => $search->getDate(),
            ));

            $query = $qb->getQuery();

            return $query->execute();
        } catch (\Exception $ex) {
            $this->getLogger()->error("ScheduleService error: " . $ex->getMessage());
        }
    }

    public function getItemByProductAndDate(Schedule $schedule, ProviderProduct $product, \DateTime $date)
    {
        try {

            $qb = $this->getEm()->createQueryBuilder();
            $qb
                ->select('s')
                ->from('MarcoshoyaMarquejogoBundle:ScheduleItem', 's')
                ->where('s.schedule = :schedule')
                ->andWhere('s.providerProduct = :providerProduct')
                ->andWhere($qb->expr()->eq('s.date', ':date'))
                ->setParameters(array(
                    'schedule' => $schedule,
                    'providerProduct' => $product,
                    'date' => $date,
                ))
                ->setMaxResults(1);

            $query = $qb->getQuery();

            return $query->getOneOrNullResult();
        } catch (\Exception $ex) {
            $this->getLogger()->error("ScheduleService error: " . $ex->getMessage());
        }
    }

}
