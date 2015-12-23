<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Test\Point\Calendar;

use DateTime;
use PHPUnit_Framework_TestCase;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\Date;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\DateTimeOfDay;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Fixed\TimeOfDay;

class DateTimeOfDayTest extends PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $object = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));

        static::assertEquals(2005, $object->year());
        static::assertEquals(10, $object->month());
        static::assertEquals(11, $object->day());
        static::assertEquals(15, $object->hour());
        static::assertEquals(12, $object->minute());
        static::assertEquals(13, $object->second());

        static::assertInstanceOf(DateTime::class, $object->toDateTime());
    }

    public function testEqualsTo()
    {
        $object1 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));
        $object2 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));

        static::assertTrue($object1->equalsTo($object2));

        $object1 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 11, 13));
        $object2 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));

        static::assertFalse($object1->equalsTo($object2));
    }

    public function testLaterThan()
    {
        $object1 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 13, 13));
        $object2 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));

        static::assertTrue($object1->laterThan($object2));

        $object1 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));
        $object2 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));

        static::assertFalse($object1->laterThan($object2));

        $object1 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 11, 13));
        $object2 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));

        static::assertFalse($object1->laterThan($object2));
    }

    public function testEarlierThan()
    {
        $object1 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 13, 13));
        $object2 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));

        static::assertFalse($object1->earlierThan($object2));

        $object1 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));
        $object2 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));

        static::assertFalse($object1->earlierThan($object2));

        $object1 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 11, 13));
        $object2 = DateTimeOfDay::create(Date::create(2005, 10, 11), TimeOfDay::create(15, 12, 13));

        static::assertTrue($object1->earlierThan($object2));
    }
}
