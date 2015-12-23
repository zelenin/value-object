<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Test\Point\Calendar;

use DateTime;
use PHPUnit_Framework_TestCase;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\Date;

class DateOfYearTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validProvider
     */
    public function testValid($year, $month, $day)
    {
        $object = Date::create($year, $month, $day);

        static::assertEquals($year, $object->year());
        static::assertEquals($month, $object->month());
        static::assertEquals($day, $object->day());

        static::assertInstanceOf(DateTime::class, $object->toDateTime());
    }

    /**
     * @dataProvider notValidProvider
     * @expectedException \Zelenin\Ddd\Core\Domain\Exception\InvalidArgumentException
     */
    public function testNotValid($year, $month, $day)
    {
        Date::create($year, $month, $day);
    }

    public function testEqualsTo()
    {
        $object1 = Date::create(2005, 2, 11);
        $object2 = Date::createFromDateTime(new DateTime('2005-02-11'));

        static::assertTrue($object1->equalsTo($object2));

        $object1 = Date::create(2005, 2, 11);
        $object2 = Date::createFromDateTime(new DateTime('2005-03-11'));

        static::assertFalse($object1->equalsTo($object2));
    }

    public function testLaterThan()
    {
        $object1 = Date::create(2005, 3, 11);
        $object2 = Date::createFromDateTime(new DateTime('2005-02-11'));

        static::assertTrue($object1->laterThan($object2));

        $object1 = Date::create(2005, 2, 11);
        $object2 = Date::createFromDateTime(new DateTime('2005-02-11'));

        static::assertFalse($object1->laterThan($object2));

        $object1 = Date::create(2005, 1, 11);
        $object2 = Date::createFromDateTime(new DateTime('2005-02-11'));

        static::assertFalse($object1->laterThan($object2));
    }

    public function testEarlierThan()
    {
        $object1 = Date::create(2005, 3, 11);
        $object2 = Date::createFromDateTime(new DateTime('2005-02-11'));

        static::assertFalse($object1->earlierThan($object2));

        $object1 = Date::create(2005, 2, 11);
        $object2 = Date::createFromDateTime(new DateTime('2005-02-11'));

        static::assertFalse($object1->earlierThan($object2));

        $object1 = Date::create(2005, 1, 11);
        $object2 = Date::createFromDateTime(new DateTime('2005-02-11'));

        static::assertTrue($object1->earlierThan($object2));
    }

    public function validProvider()
    {
        return [
            [2005, 1, 1],
            [2005, 6, 24],
            [2011, 12, 1],
            [-56, 2, 15]
        ];
    }

    public function notValidProvider()
    {
        return [
            [2005, 13, 10],
            [2005, 0, 11],
            [2005, 5, 35],
            [2005, 5, 0],
            [2005, 2, 32],
            ['2005', 2, 1],
            [2005, '2', 1],
            [2005, 2, '1'],
        ];
    }
}
