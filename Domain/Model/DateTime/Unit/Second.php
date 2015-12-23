<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Unit;

final class Second
{
    const MIN = 0;
    const MAX = 59;

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
