<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Test\RecurringInterval;

use PHPUnit_Framework_TestCase;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Duration\Duration;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\Date;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\RecurringInterval\RecurringInterval;

class RecurringIntervalTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validProvider
     */
    public function testValid($start, $duration, $recurrence, $iso8601)
    {
        $object = RecurringInterval::createFromStartDuration($start, $duration, $recurrence);

        static::assertEquals($object->toIso8601Format(), $iso8601);
    }

    /**
     * @return array
     */
    public function validProvider()
    {
        return [
            [Date::create(2015, 6, 15), Duration::create(0, 0, 2, 0, 0, 0), 5, 'R5/2015-06-15T00:00:00Z/P2D']
        ];
    }
}
