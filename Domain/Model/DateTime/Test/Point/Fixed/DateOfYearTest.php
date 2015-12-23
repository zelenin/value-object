<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Test\Point\Fixed;

use DateTime;
use PHPUnit_Framework_TestCase;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Fixed\DateOfYear;

class DateOfYearTest extends PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $object = DateOfYear::create(10, 2);

        static::assertEquals(10, $object->month());
        static::assertEquals(2, $object->day());

        $object = DateOfYear::createFromDateTime(new DateTime('1 february'));

        static::assertEquals(2, $object->month());
        static::assertEquals(1, $object->day());

        $object = DateOfYear::createFromString('1 march');

        static::assertEquals(3, $object->month());
        static::assertEquals(1, $object->day());
    }

    public function testEqualsTo()
    {
        $object1 = DateOfYear::create(2, 1);
        $object2 = DateOfYear::createFromDateTime(new DateTime('1 february'));

        static::assertTrue($object1->equalsTo($object2));

        $object1 = DateOfYear::create(2, 1);
        $object2 = DateOfYear::createFromDateTime(new DateTime('1 march'));

        static::assertFalse($object1->equalsTo($object2));
    }

    public function testLaterThan()
    {
        $object1 = DateOfYear::create(3, 1);
        $object2 = DateOfYear::createFromDateTime(new DateTime('1 february'));

        static::assertTrue($object1->laterThan($object2));

        $object1 = DateOfYear::create(2, 1);
        $object2 = DateOfYear::createFromDateTime(new DateTime('1 february'));

        static::assertFalse($object1->laterThan($object2));

        $object1 = DateOfYear::create(1, 1);
        $object2 = DateOfYear::createFromDateTime(new DateTime('1 february'));

        static::assertFalse($object1->laterThan($object2));
    }

    public function testEarlierThan()
    {
        $object1 = DateOfYear::create(3, 1);
        $object2 = DateOfYear::createFromDateTime(new DateTime('1 february'));

        static::assertFalse($object1->earlierThan($object2));

        $object1 = DateOfYear::create(2, 1);
        $object2 = DateOfYear::createFromDateTime(new DateTime('1 february'));

        static::assertFalse($object1->earlierThan($object2));

        $object1 = DateOfYear::create(1, 1);
        $object2 = DateOfYear::createFromDateTime(new DateTime('1 february'));

        static::assertTrue($object1->earlierThan($object2));
    }
}
