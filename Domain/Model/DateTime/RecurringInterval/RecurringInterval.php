<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\RecurringInterval;

use Iterator;
use Zelenin\Ddd\Core\Domain\Exception\InvalidArgumentException;
use Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException;
use Zelenin\Ddd\Core\Domain\Model\DefaultValueObject;
use Zelenin\Ddd\Core\Domain\Object\Object;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Duration\Duration;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\CalendarDate;

final class RecurringInterval extends DefaultValueObject implements Iterator
{
    /**
     * @var CalendarDate
     */
    private $start;

    /**
     * @var CalendarDate
     */
    private $end;

    /**
     * @var CalendarDate
     */
    private $current;

    /**
     * @var Duration
     */
    private $duration;

    /**
     * @var int|null
     */
    private $recurrence = null;

    /**
     * @var int
     */
    private $currentRecurrence = 0;

    /**
     * @param CalendarDate $start
     * @param CalendarDate|null $end
     * @param Duration $duration
     * @param int|null $recurrence
     *
     * @throws InvalidArgumentException
     */
    private function __construct(CalendarDate $start, CalendarDate $end = null, Duration $duration, $recurrence = null)
    {
        if ($recurrence !== null && !is_int($recurrence)) {
            throw new InvalidArgumentException(sprintf('%s is not integer', '$recurrence'));
        }
        if ($end && $end->earlierThan($start)) {
            throw new InvalidArgumentException(sprintf('%s must be later then %s', '$end', '$start'));
        }

        $this->start = $start;
        $this->end = $end;
        $this->duration = $duration;
        $this->recurrence = $recurrence;
    }

    /**
     * @param CalendarDate $start
     * @param Duration $duration
     * @param int|null $recurrence
     *
     * @return static
     */
    public static function create(CalendarDate $start, CalendarDate $end = null, Duration $duration, $recurrence = null)
    {
        return new static($start, $end, $duration, $recurrence);
    }

    /**
     * @param CalendarDate $start
     * @param CalendarDate $end
     * @param int|null $recurrence
     *
     * @return static
     */
    public static function createFromStartEnd(CalendarDate $start, CalendarDate $end, $recurrence = null)
    {
        return static::create($start, null, Interval::create($start, $end)->duration(), $recurrence);
    }

    /**
     * @param CalendarDate $start
     * @param CalendarDate $end
     * @param Duration $duration
     * @param null $recurrence
     *
     * @return static
     */
    public static function createFromStartEndDuration(CalendarDate $start, CalendarDate $end, Duration $duration)
    {
        return static::create($start, $end, $duration, null);
    }

    /**
     * @param CalendarDate $start
     * @param Duration $duration
     * @param int|null $recurrence
     *
     * @return static
     */
    public static function createFromStartDuration(CalendarDate $start, Duration $duration, $recurrence = null)
    {
        return static::create($start, null, $duration, $recurrence);
    }

    /**
     * @param CalendarDate $end
     * @param Duration $duration
     * @param int|null $recurrence
     *
     * @return static
     */
    public static function createFromEndDuration(CalendarDate $end, Duration $duration, $recurrence = null)
    {
        return static::create($end->sub($duration), null, $duration, $recurrence);
    }

    /**
     * @return CalendarDate
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * @return CalendarDate
     */
    public function end()
    {
        return $this->end;
    }

    /**
     * @return Duration
     */
    public function duration()
    {
        return $this->duration;
    }

    /**
     * @return int|null
     */
    public function recurrence()
    {
        return $this->recurrence;
    }

    /**
     * @return string
     */
    public function toIso8601Format()
    {
        $data = [
            'R' . ($this->recurrence() ?: ''),
            $this->start()->toIso8601Format() . 'Z',
            $this->duration()->toIso8601Format(),
            $this->end() ? $this->end()->toIso8601Format() . 'Z' : ''
        ];
        return implode('/', array_filter($data));
    }

    /**
     * @param static $object
     *
     * @return bool
     */
    public function equalsTo(Object $object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return $this->start()->equalsTo($object->start()) && $this->duration()->equalsTo($object->duration()) && $this->recurrence() === $object->recurrence();
    }

    /**
     * @return CalendarDate
     */
    public function current()
    {
        if ($this->current === null) {
            $this->current = $this->start();
        }
        return $this->current;
    }

    public function next()
    {
        $this->current = $this->current()->add($this->duration());
        $this->currentRecurrence++;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->currentRecurrence;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return !(($this->end() && $this->current()->laterThan($this->end())) || ($this->recurrence() && $this->currentRecurrence > $this->recurrence()));
    }

    public function rewind()
    {
        $this->current = $this->start();
        $this->currentRecurrence = 0;
    }

    /**
     * @return CalendarDate[]
     */
    public function toArray()
    {
        return iterator_to_array($this);
    }
}
