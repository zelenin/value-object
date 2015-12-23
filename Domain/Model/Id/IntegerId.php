<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\Id;

use Zelenin\Ddd\Core\Domain\Exception\InvalidArgumentException;

/**
 * @method static create($id)
 */
abstract class IntegerId extends DefaultId
{
    /**
     * @param int $id
     */
    protected function __construct($id)
    {
        if (!is_int($id)) {
            throw new InvalidArgumentException('Id must be an integer');
        }
        parent::__construct($id);
    }
}
