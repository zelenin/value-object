<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Fixed;

use DateTimeImmutable;
use DateTimeInterface;
use Zelenin\Ddd\Core\Domain\Exception\InvalidArgumentException;
use Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException;
use Zelenin\Ddd\Core\Domain\Model\DefaultValueObject;
use Zelenin\Ddd\Core\Domain\Object\Object;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Unit\Hour;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Unit\Minute;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Unit\Second;

final class TimeOfDay extends DefaultValueObject implements FixedPoint
{
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
     */
    private function __construct($hour, $minute, $second)
    {
        if (!is_int($hour)) {
            throw new InvalidArgumentException('$hour is not integer');
        }
        if (!is_int($minute)) {
            throw new InvalidArgumentException('$minute is not integer');
        }
        if (!is_int($second)) {
            throw new InvalidArgumentException('$second is not integer');
        }

        if ($hour < Hour::min() || $hour > Hour::max()) {
            throw new InvalidArgumentException(sprintf('%s should be in range %d-%d', '$hour', Hour::min(), Hour::max()));
        }
        if ($minute < Minute::min() || $minute > Minute::max()) {
            throw new InvalidArgumentException(sprintf('%s should be in range %d-%d', '$minute', Minute::min(), Minute::max()));
        }
        if ($second < Second::min() || $second > Second::max()) {
            throw new InvalidArgumentException(sprintf('%s should be in range %d-%d', '$second', Second::min(), Second::max()));
        }

        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    /**
     * @param $hour
     * @param $minute
     * @param $second
     *
     * @return self
     */
    public static function create($hour, $minute, $second)
    {
        return new static($hour, $minute, $second);
    }

    /**
     * @param DateTimeInterface $dateTime
     *
     * @return self
     */
    public static function createFromDateTime(DateTimeInterface $dateTime)
    {
        return static::create((int)$dateTime->format('H'), (int)$dateTime->format('i'), (int)$dateTime->format('s'));
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
        return sprintf('%02d:%02d:%02d', $this->hour(), $this->minute(), $this->second());
    }

    /**
     * @param TimeOfDay $object
     *
     * @return bool
     */
    public function equalsTo(Object $object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return
            $this->hour() === $object->hour() &&
            $this->minute() === $object->minute() &&
            $this->second() === $object->second();
    }

    /**
     * @param TimeOfDay $object
     *
     * @return bool
     */
    public function laterThan($object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return ($this->hour() > $object->hour()) ||
        ($this->hour() >= $object->hour() && $this->minute() > $object->minute()) ||
        ($this->hour() >= $object->hour() && $this->minute() >= $object->minute() && $this->second() > $object->second());
    }

    /**
     * @param TimeOfDay $object
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
