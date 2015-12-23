<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\Id;

use Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException;
use Zelenin\Ddd\Core\Domain\Model\DefaultValueObject;
use Zelenin\Ddd\Core\Domain\Model\Id\Id;
use Zelenin\Ddd\Core\Domain\Object\Object;

abstract class DefaultId extends DefaultValueObject implements Id
{
    /**
     * @var mixed
     */
    private $id;

    /**
     * @param mixed $id
     */
    protected function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $id
     *
     * @return static
     */
    public static function create($id)
    {
        return new static($id);
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->id();
    }

    /**
     * @param DefaultId $object
     *
     * @return bool
     */
    public function equalsTo(Object $object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return $this->id() === $object->id();
    }
}
