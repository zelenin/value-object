<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar;

use DateTimeInterface;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Duration\Duration;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Point;

interface CalendarDate extends Point
{
    /**
     * @return DateTimeInterface
     */
    public function toDateTime();

    /**
     * @return string
     */
    public function toIso8601Format();

    /**
     * @param Duration $duration
     *
     * @return static
     */
    public function add(Duration $duration);

    /**
     * @param Duration $duration
     *
     * @return static
     */
    public function sub(Duration $duration);

    /**
     * @param CalendarDate $object
     *
     * @return bool
     */
    public function laterThan($object);

    /**
     * @param CalendarDate $object
     *
     * @return bool
     */
    public function earlierThan($object);
}
