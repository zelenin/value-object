<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Test\Point\Fixed;

use DateTime;
use PHPUnit_Framework_TestCase;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Fixed\TimeOfDay;

class TimeOfDayTest extends PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $object = TimeOfDay::create(10, 2, 5);

        static::assertEquals(10, $object->hour());
        static::assertEquals(2, $object->minute());
        static::assertEquals(5, $object->second());

        $object = TimeOfDay::createFromDateTime(new DateTime('10:05:25'));

        static::assertEquals(10, $object->hour());
        static::assertEquals(5, $object->minute());
        static::assertEquals(25, $object->second());

        $object = TimeOfDay::createFromString('10:05:25');

        static::assertEquals(10, $object->hour());
        static::assertEquals(5, $object->minute());
        static::assertEquals(25, $object->second());
    }

    public function testEqualsTo()
    {
        $object1 = TimeOfDay::create(10, 2, 5);
        $object2 = TimeOfDay::createFromDateTime(new DateTime('10:02:05'));

        static::assertTrue($object1->equalsTo($object2));

        $object1 = TimeOfDay::create(10, 2, 4);
        $object2 = TimeOfDay::createFromDateTime(new DateTime('10:02:05'));

        static::assertFalse($object1->equalsTo($object2));
    }

    public function testLaterThan()
    {
        $object1 = TimeOfDay::create(10, 2, 6);
        $object2 = TimeOfDay::createFromDateTime(new DateTime('10:02:05'));

        static::assertTrue($object1->laterThan($object2));

        $object1 = TimeOfDay::create(10, 2, 5);
        $object2 = TimeOfDay::createFromDateTime(new DateTime('10:02:05'));

        static::assertFalse($object1->laterThan($object2));

        $object1 = TimeOfDay::create(10, 2, 4);
        $object2 = TimeOfDay::createFromDateTime(new DateTime('10:02:05'));

        static::assertFalse($object1->laterThan($object2));
    }

    public function testEarlierThan()
    {
        $object1 = TimeOfDay::create(10, 2, 6);
        $object2 = TimeOfDay::createFromDateTime(new DateTime('10:02:05'));

        static::assertFalse($object1->earlierThan($object2));

        $object1 = TimeOfDay::create(10, 2, 5);
        $object2 = TimeOfDay::createFromDateTime(new DateTime('10:02:05'));

        static::assertFalse($object1->earlierThan($object2));

        $object1 = TimeOfDay::create(10, 2, 4);
        $object2 = TimeOfDay::createFromDateTime(new DateTime('10:02:05'));

        static::assertTrue($object1->earlierThan($object2));
    }
}
