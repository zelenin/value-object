<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Unit;

final class Day
{
    const MIN = 1;
    const MAX = 31;

    /**
     * @return int
     */
    public static function min()
    {
        return self::MIN;
    }

    /**
     * @return int
     */
    public static function max()
    {
        return self::MAX;
    }
}
