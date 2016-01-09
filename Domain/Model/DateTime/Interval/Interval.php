<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Interval;

use Zelenin\Ddd\Core\Domain\Contract\HasClassName;
use Zelenin\Ddd\Core\Domain\Exception\InvalidArgumentException;
use Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException;
use Zelenin\Ddd\Core\Domain\Model\DefaultValueObject;
use Zelenin\Ddd\Core\Domain\Object\Object;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Duration\Duration;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\CalendarDate;

abstract class Interval extends DefaultValueObject
{
    /**
     * @var CalendarDate
     */
    protected $start;

    /**
     * @var CalendarDate
     */
    protected $end;

    /**
     * @param CalendarDate|Object $start
     * @param CalendarDate|HasClassName $end
     */
    protected function __construct(CalendarDate $start, CalendarDate $end)
    {
        if (!$end->sameTypeAs($start)) {
            throw new NotMatchTypeException($end);
        }

        if ($end->earlierThan($start)) {
            throw new InvalidArgumentException(sprintf('%s must be later then %s', '$end', '$start'));
        }

        $this->start = $start;
        $this->end = $end;
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
        return Duration::createFromDateInterval($this->start()->toDateTime()->diff($this->end()->toDateTime()));
    }

    /**
     * @return string
     */
    public function toIso8601Format()
    {
        return $this->start()->toIso8601Format() . '/' . $this->end()->toIso8601Format();
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

        return $this->start()->equalsTo($object->start()) && $this->end()->equalsTo($object->end());
    }
}
