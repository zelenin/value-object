<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Interval;

use Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Duration\Duration;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\CalendarDate;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\DateTimeOfDay;

final class DateTimeOfDayInterval extends Interval
{
    /**
     * @param DateTimeOfDay $start
     * @param DateTimeOfDay $end
     */
    protected function __construct(CalendarDate $start, CalendarDate $end)
    {
        if (!$start instanceof DateTimeOfDay) {
            throw new NotMatchTypeException($start);
        }
        if (!$end instanceof DateTimeOfDay) {
            throw new NotMatchTypeException($end);
        }

        parent::__construct($start, $end);
    }

    /**
     * @param DateTimeOfDay $start
     * @param DateTimeOfDay $end
     *
     * @return static
     */
    public static function create(DateTimeOfDay $start, DateTimeOfDay $end)
    {
        return new static($start, $end);
    }

    /**
     * @param DateTimeOfDay $start
     * @param Duration $duration
     *
     * @return static
     */
    public static function createFromStartAndDuration(DateTimeOfDay $start, Duration $duration)
    {
        return static::create($start, $start->add($duration));
    }

    /**
     * @param DateTimeOfDay $end
     * @param Duration $duration
     *
     * @return static
     */
    public static function createFromEndAndDuration(DateTimeOfDay $end, Duration $duration)
    {
        return static::create($end->sub($duration), $end);
    }
}
