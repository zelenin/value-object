<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException;
use Zelenin\Ddd\Core\Domain\Model\DefaultValueObject;
use Zelenin\Ddd\Core\Domain\Object\Object;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Duration\Duration;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Fixed\TimeOfDay;

final class DateTimeOfDay extends DefaultValueObject implements CalendarDate
{
    /**
     * @var Date
     */
    private $date;

    /**
     * @var TimeOfDay
     */
    private $time;

    /**
     * @param Date $date
     * @param TimeOfDay $time
     */
    private function __construct(Date $date, TimeOfDay $time)
    {
        $this->date = $date;
        $this->time = $time;
    }

    /**
     * @param Date $date
     * @param TimeOfDay $time
     *
     * @return self
     */
    public static function create(Date $date, TimeOfDay $time)
    {
        return new static($date, $time);
    }

    /**
     * @param DateTimeInterface $dateTime
     *
     * @return self
     */
    public static function createFromDateTime(DateTimeInterface $dateTime)
    {
        return static::create(Date::createFromDateTime($dateTime), TimeOfDay::createFromDateTime($dateTime));
    }

    /**
     * @param string $date
     *
     * @return self
     */
    public static function createFromString($date)
    {
        return static::createFromDateTime(new DateTimeImmutable($date));
    }

    /**
     * @return self
     */
    public static function now()
    {
        return static::createFromDateTime(new DateTimeImmutable());
    }

    /**
     * @return Date
     */
    public function date()
    {
        return $this->date;
    }

    /**
     * @return TimeOfDay
     */
    public function time()
    {
        return $this->time;
    }

    /**
     * @return int
     */
    public function year()
    {
        return $this->date()->year();
    }

    /**
     * @return int
     */
    public function month()
    {
        return $this->date()->month();
    }

    /**
     * @return int
     */
    public function day()
    {
        return $this->date()->day();
    }

    /**
     * @return int
     */
    public function hour()
    {
        return $this->time()->hour();
    }

    /**
     * @return int
     */
    public function minute()
    {
        return $this->time()->minute();
    }

    /**
     * @return int
     */
    public function second()
    {
        return $this->time()->second();
    }

    /**
     * @return DateTime
     */
    public function toDateTime()
    {
        $dateTime = new DateTime();
        $dateTime->setDate($this->year(), $this->month(), $this->day());
        $dateTime->setTime($this->hour(), $this->minute(), $this->second());

        return $dateTime;
    }

    /**
     * @return string
     */
    public function toIso8601Format()
    {
        return $this->toDateTime()->format('Y-m-d\TH:i:s');
    }

    /**
     * @param DateTimeOfDay $object
     *
     * @return bool
     */
    public function equalsTo(Object $object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return $this->date()->equalsTo($object->date()) && $this->time()->equalsTo($object->time());
    }

    /**
     * @param DateTimeOfDay $object
     *
     * @return bool
     */
    public function earlierThan($object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return $this->date()->earlierThan($object->date()) || ($this->date()->equalsTo($object->date()) && $this->time()->earlierThan($object->time()));
    }

    /**
     * @param DateTimeOfDay $object
     *
     * @return bool
     */
    public function laterThan($object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return $this->date()->laterThan($object->date()) || ($this->date()->equalsTo($object->date()) && $this->time()->laterThan($object->time()));
    }

    /**
     * @param Duration $duration
     *
     * @return self
     */
    public function add(Duration $duration)
    {
        return static::createFromDateTime($this->toDateTime()->add($duration->toDateInterval()));
    }

    /**
     * @param Duration $duration
     *
     * @return self
     */
    public function sub(Duration $duration)
    {
        return static::createFromDateTime($this->toDateTime()->sub($duration->toDateInterval()));
    }
}
