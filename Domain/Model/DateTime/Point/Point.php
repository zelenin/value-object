<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point;

use DateTimeInterface;
use Zelenin\Ddd\Core\Domain\Contract\HasEqualsTo;
use Zelenin\Ddd\Core\Domain\Contract\HasSameTypeAs;

interface Point extends HasEqualsTo, HasSameTypeAs
{
    /**
     * @param static $object
     *
     * @return bool
     */
    public function laterThan($object);

    /**
     * @param static $object
     *
     * @return bool
     */
    public function earlierThan($object);

    /**
     * @param DateTimeInterface $dateTime
     *
     * @return static
     */
    public static function createFromDateTime(DateTimeInterface $dateTime);

    /**
     * @param string $string
     *
     * @return static
     */
    public static function createFromString($string);
}
