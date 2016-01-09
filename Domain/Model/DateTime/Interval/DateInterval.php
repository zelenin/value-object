<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Interval;

use Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Duration\Duration;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\CalendarDate;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\Date;

final class DateInterval extends Interval
{
    /**
     * @param Date $start
     * @param Date $end
     */
    protected function __construct(CalendarDate $start, CalendarDate $end)
    {
        if (!$start instanceof Date) {
            throw new NotMatchTypeException($start);
        }
        if (!$end instanceof Date) {
            throw new NotMatchTypeException($end);
        }

        parent::__construct($start, $end);
    }

    /**
     * @param Date $start
     * @param Date $end
     *
     * @return static
     */
    public static function create(Date $start, Date $end)
    {
        return new static($start, $end);
    }

    /**
     * @param Date $start
     * @param Duration $duration
     *
     * @return static
     */
    public static function createFromStartAndDuration(Date $start, Duration $duration)
    {
        return static::create($start, $start->add($duration));
    }

    /**
     * @param Date $end
     * @param Duration $duration
     *
     * @return static
     */
    public static function createFromEndAndDuration(Date $end, Duration $duration)
    {
        return static::create($end->sub($duration), $end);
    }
}
