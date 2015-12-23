<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Zelenin\Ddd\Core\Domain\Exception\InvalidArgumentException;
use Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException;
use Zelenin\Ddd\Core\Domain\Model\DefaultValueObject;
use Zelenin\Ddd\Core\Domain\Object\Object;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Duration\Duration;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Unit\Day;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Unit\Month;

final class Date extends DefaultValueObject implements CalendarDate
{
    /**
     * @var int
     */
    private $year;

    /**
     * @var int
     */
    private $month;

    /**
     * @var int
     */
    private $day;

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     */
    private function __construct($year, $month, $day)
    {
        if (!is_int($year)) {
            throw new InvalidArgumentException(sprintf('%s is not integer', '$year'));
        }
        if (!is_int($month)) {
            throw new InvalidArgumentException(sprintf('%s is not integer', '$month'));
        }
        if (!is_int($day)) {
            throw new InvalidArgumentException(sprintf('%s is not integer', '$day'));
        }

        if ($month < Month::min() || $month > Month::max()) {
            throw new InvalidArgumentException(sprintf('%s should be in range %d-%d', '$month', Month::min(), Month::max()));
        }
        /**
         * @todo 02-31
         */
        if ($day < Day::min() || $day > Day::max()) {
            throw new InvalidArgumentException(sprintf('%s should be in range %d-%d', '$day', Day::min(), Day::max()));
        }

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return static
     */
    public static function create($year, $month, $day)
    {
        return new static($year, $month, $day);
    }

    /**
     * @param DateTimeInterface $dateTime
     *
     * @return static
     */
    public static function createFromDateTime(DateTimeInterface $dateTime)
    {
        return static::create((int)$dateTime->format('Y'), (int)$dateTime->format('m'), (int)$dateTime->format('d'));
    }

    /**
     * @param string $date
     *
     * @return static
     */
    public static function createFromString($date)
    {
        return static::createFromDateTime(new DateTimeImmutable($date));
    }

    /**
     * @return int
     */
    public function year()
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function month()
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function day()
    {
        return $this->day;
    }

    /**
     * @return DateTime
     */
    public function toDateTime()
    {
        $dateTime = new DateTime();
        $dateTime->setDate($this->year(), $this->month(), $this->day());
        $dateTime->setTime(0, 0, 0);

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
     * @param Date $object
     *
     * @return bool
     */
    public function equalsTo(Object $object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return
            $this->year() === $object->year() &&
            $this->month() === $object->month() &&
            $this->day() === $object->day();
    }

    /**
     * @param Date $object
     *
     * @return bool
     */
    public function laterThan($object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return ($this->year() > $object->year()) ||
        ($this->year() >= $object->year() && $this->month() > $object->month()) ||
        ($this->year() >= $object->year() && $this->month() >= $object->month() && $this->day() > $object->day());
    }

    /**
     * @param Date $object
     *
     * @return bool
     */
    public function earlierThan($object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return !$this->equalsTo($object) && !$this->laterThan($object);
    }

    /**
     * @param Duration $duration
     *
     * @return static
     */
    public function add(Duration $duration)
    {
        return static::createFromDateTime($this->toDateTime()->add($duration->toDateInterval()));
    }

    /**
     * @param Duration $duration
     *
     * @return static
     */
    public function sub(Duration $duration)
    {
        return static::createFromDateTime($this->toDateTime()->sub($duration->toDateInterval()));
    }
}
