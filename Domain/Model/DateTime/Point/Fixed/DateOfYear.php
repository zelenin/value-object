<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Fixed;

use DateTimeImmutable;
use DateTimeInterface;
use Zelenin\Ddd\Core\Domain\Exception\InvalidArgumentException;
use Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException;
use Zelenin\Ddd\Core\Domain\Model\DefaultValueObject;
use Zelenin\Ddd\Core\Domain\Object\Object;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Unit\Day;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Unit\Month;

final class DateOfYear extends DefaultValueObject implements FixedPoint
{
    /**
     * @var int
     */
    private $month;

    /**
     * @var int
     */
    private $day;

    /**
     * @param int $month
     * @param int $day
     */
    private function __construct($month, $day)
    {
        if (!is_int($month)) {
            throw new InvalidArgumentException('$month is not integer');
        }
        if (!is_int($day)) {
            throw new InvalidArgumentException('$day is not integer');
        }

        if ($month < Month::min() || $month > Month::max()) {
            throw new InvalidArgumentException(sprintf('%s should be in range %d-%d', '$month', Month::min(), Month::max()));
        }
        if ($day < Day::min() || $day > Day::max()) {
            throw new InvalidArgumentException(sprintf('%s should be in range %d-%d', '$day', Day::min(), Day::max()));
        }

        $this->month = $month;
        $this->day = $day;
    }

    /**
     * @param int $month
     * @param int $day
     *
     * @return static
     */
    public static function create($month, $day)
    {
        return new static($month, $day);
    }

    /**
     * @param DateTimeInterface $dateTime
     *
     * @return static
     */
    public static function createFromDateTime(DateTimeInterface $dateTime)
    {
        return static::create((int)$dateTime->format('m'), (int)$dateTime->format('d'));
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
     * @param DateOfYear $object
     *
     * @return bool
     */
    public function equalsTo(Object $object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return $this->month() === $object->month() && $this->day() === $object->day();
    }

    /**
     * @param DateOfYear $object
     *
     * @return bool
     */
    public function laterThan($object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return
            ($this->month() > $object->month()) ||
            ($this->month() >= $object->month() && $this->day() > $object->day());
    }

    /**
     * @param DateOfYear $object
     *
     * @return bool
     */
    public function earlierThan($object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return $this->equalsTo($object) === false && $this->laterThan($object) === false;
    }
}
