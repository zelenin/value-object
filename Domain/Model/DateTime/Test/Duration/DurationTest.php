<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Test\Duration;

use DateInterval;
use PHPUnit_Framework_TestCase;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Duration\Duration;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\Date;

class DurationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validProvider
     */
    public function testValid($year, $month, $day, $hour, $minute, $second, $iso8601format)
    {
        $object = Duration::create($year, $month, $day, $hour, $minute, $second);

        static::assertEquals($year, $object->year());
        static::assertEquals($month, $object->month());
        static::assertEquals($day, $object->day());
        static::assertEquals($hour, $object->hour());
        static::assertEquals($minute, $object->minute());
        static::assertEquals($second, $object->second());

        static::assertInstanceOf(DateInterval::class, $object->toDateInterval());
        static::assertEquals($iso8601format, $object->toIso8601Format());

        Duration::createFromDateInterval($object->toDateInterval());
        Duration::createFromString($object->toIso8601Format());
    }

    /**
     * @dataProvider notValidProvider
     * @expectedException \Zelenin\Ddd\Core\Domain\Exception\InvalidArgumentException
     */
    public function testNotValid($year, $month, $day, $hour, $minute, $second, $iso8601format)
    {
        Duration::create($year, $month, $day, $hour, $minute, $second);
    }

    /**
     * @dataProvider validProvider
     */
    public function testEquals($year, $month, $day, $hour, $minute, $second, $iso8601format)
    {
        $object1 = Duration::create($year, $month, $day, $hour, $minute, $second);
        $object2 = Duration::create($year, $month, $day, $hour, $minute, $second);

        static::assertTrue($object1->equalsTo($object2));
    }

    /**
     * @dataProvider validProvider
     */
    public function testNotEquals($year, $month, $day, $hour, $minute, $second, $iso8601format)
    {
        $object1 = Duration::create($year, $month, $day, $hour, $minute, $second);
        $object2 = Duration::create($year, $month, $day, $hour, $minute, $second + 1);

        static::assertFalse($object1->equalsTo($object2));
    }

    /**
     * @dataProvider validProvider
     * @expectedException \Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException
     */
    public function testEqualsNotSameType($year, $month, $day, $hour, $minute, $second, $iso8601format)
    {
        $object1 = Duration::create($year, $month, $day, $hour, $minute, $second);
        $object2 = Date::create(1, 1, 1);

        $object1->equalsTo($object2);
    }

    public function validProvider()
    {
        return [
            [1, 2, 3, 4, 5, 6, 'P1Y2M3DT4H5M6S'],
            [1, 0, 0, 4, 5, 6, 'P1YT4H5M6S'],
            [0, 0, 0, 0, 0, 6, 'PT6S'],
            [0, 1, 0, 0, 0, 0, 'P1M']
        ];
    }

    public function notValidProvider()
    {
        return [
            ['1', 2, 3, 4, 5, 6, 'P1Y2M3DT4H5M6S'],
            [1, '2', 3, 4, 5, 6, 'P1Y2M3DT4H5M6S'],
            [1, 2, '3', 4, 5, 6, 'P1Y2M3DT4H5M6S'],
            [1, 2, 3, '4', 5, 6, 'P1Y2M3DT4H5M6S'],
            [1, 2, 3, 4, '5', 6, 'P1Y2M3DT4H5M6S'],
            [1, 2, 3, 4, 5, '6', 'P1Y2M3DT4H5M6S'],
        ];
    }
}
