<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\Id;

use Zelenin\Ddd\Core\Domain\Exception\InvalidArgumentException;

/**
 * @method static create($id)
 */
abstract class UuidId extends DefaultId
{
    /**
     * @param string $id
     */
    protected function __construct($id)
    {
        if (!preg_match('/^[[:xdigit:]]{8}-[[:xdigit:]]{4}-[[:xdigit:]]{4}-[[:xdigit:]]{4}-[[:xdigit:]]{12}$/i', $id)) {
            throw new InvalidArgumentException('Id must be an UUID');
        }
        parent::__construct($id);
    }
}
