<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\Id;

use Zelenin\Ddd\Core\Domain\Exception\InvalidArgumentException;

/**
 * @method static create($id)
 */
abstract class StringId extends DefaultId
{
    /**
     * @param string $id
     */
    protected function __construct($id)
    {
        if (!is_string($id)) {
            throw new InvalidArgumentException('Id must be a string');
        }
        parent::__construct($id);
    }
}
