<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Duration;

use DateInterval;
use Zelenin\Ddd\Core\Domain\Exception\InvalidArgumentException;
use Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException;
use Zelenin\Ddd\Core\Domain\Model\DefaultValueObject;
use Zelenin\Ddd\Core\Domain\Object\Object;

final class Duration extends DefaultValueObject
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
     * @var int
     */
    private $hour;

    /**
     * @var int
     */
    private $minute;

    /**
     * @var int
     */
    private $second;

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @param int $second
     */
    private function __construct($year, $month, $day, $hour, $minute, $second)
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
        if (!is_int($hour)) {
            throw new InvalidArgumentException(sprintf('%s is not integer', '$hour'));
        }
        if (!is_int($minute)) {
            throw new InvalidArgumentException(sprintf('%s is not integer', '$minute'));
        }
        if (!is_int($second)) {
            throw new InvalidArgumentException(sprintf('%s is not integer', '$second'));
        }

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @param int $second
     *
     * @return static
     */
    public static function create($year, $month, $day, $hour, $minute, $second)
    {
        return new static($year, $month, $day, $hour, $minute, $second);
    }

    /**
     * @param DateInterval $dateInterval
     *
     * @return static
     */
    public static function createFromDateInterval(DateInterval $dateInterval)
    {
        return static::create((int)$dateInterval->format('%y'), (int)$dateInterval->format('%m'), (int)$dateInterval->format('%d'), (int)$dateInterval->format('%h'), (int)$dateInterval->format('%i'), (int)$dateInterval->format('%s'));
    }

    /**
     * @param $string
     *
     * @return static
     */
    public static function createFromString($string)
    {
        return static::createFromDateInterval(new DateInterval($string));
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
     * @return int
     */
    public function hour()
    {
        return $this->hour;
    }

    /**
     * @return int
     */
    public function minute()
    {
        return $this->minute;
    }

    /**
     * @return int
     */
    public function second()
    {
        return $this->second;
    }

    /**
     * @return string
     */
    public function toIso8601Format()
    {
        $date = [
            'Y' => $this->year(),
            'M' => $this->month(),
            'D' => $this->day()
        ];
        $time = [
            'H' => $this->hour(),
            'M' => $this->minute(),
            'S' => $this->second()
        ];

        $date = array_filter(array_map(function ($value, $key) {
            return $value ? $value . $key : null;
        }, array_values($date), array_keys($date)));

        $time = array_filter(array_map(function ($value, $key) {
            return $value ? $value . $key : null;
        }, array_values($time), array_keys($time)));

        return 'P' . implode('', $date) . ($time ? 'T' . implode('', $time) : '');
    }

    /**
     * @param Duration $object
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
            $this->day() === $object->day() &&
            $this->hour() === $object->hour() &&
            $this->minute() === $object->minute() &&
            $this->second() === $object->second();
    }

    /**
     * @return DateInterval
     */
    public function toDateInterval()
    {
        return new DateInterval($this->toIso8601Format());
    }
}
