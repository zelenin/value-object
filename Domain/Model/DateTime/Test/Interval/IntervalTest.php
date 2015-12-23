<?php

namespace Zelenin\Ddd\Domain\ValueObject\DateTime\Test\Interval;

use PHPUnit_Framework_TestCase;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Duration\Duration;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Interval\Interval;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\CalendarDate;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\Date;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\DateTimeOfDay;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Fixed\TimeOfDay;

class IntervalTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validProvider
     */
    public function testValid($argument1, $argument2, Duration $duration, $iso8601 = null)
    {
        $object = null;
        if ($argument1 instanceof CalendarDate) {
            if ($argument2 instanceof CalendarDate) {
                $object = Interval::create($argument1, $argument2);
            }
            if ($argument2 instanceof Duration) {
                $object = Interval::createFromStartAndDuration($argument1, $argument2);
            }
        }
        if ($argument1 instanceof Duration) {
            if ($argument2 instanceof CalendarDate) {
                $object = Interval::createFromEndAndDuration($argument2, $argument1);
            }
        }

        static::assertEquals($object->duration()->year(), $duration->year());
        static::assertEquals($object->duration()->month(), $duration->month());
        static::assertEquals($object->duration()->day(), $duration->day());
        static::assertEquals($object->duration()->hour(), $duration->hour());
        static::assertEquals($object->duration()->minute(), $duration->minute());
        static::assertEquals($object->duration()->second(), $duration->second());

        if ($iso8601) {
            static::assertEquals($object->toIso8601Format(), $iso8601);
        }
    }

    public function validProvider()
    {
        return [
            [Date::create(2015, 6, 15), Date::create(2015, 6, 17), Duration::create(0, 0, 2, 0, 0, 0), '2015-06-15T00:00:00/2015-06-17T00:00:00'],
            [DateTimeOfDay::create(Date::create(2012, 6, 17), TimeOfDay::create(23, 1, 0)), DateTimeOfDay::create(Date::create(2015, 6, 17), TimeOfDay::create(23, 2, 0)), Duration::create(3, 0, 0, 0, 1, 0)],
            [Date::create(2015, 6, 15), Duration::create(0, 1, 5, 0, 0, 0), Duration::create(0, 1, 5, 0, 0, 0)],
            [Duration::create(0, 5, 12, 0, 0, 0), Date::create(2015, 6, 17), Duration::create(0, 5, 12, 0, 0, 0), '2015-01-05T00:00:00/2015-06-17T00:00:00']
        ];
    }
}
