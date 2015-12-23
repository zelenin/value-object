<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\Email;

use InvalidArgumentException;
use Zelenin\Ddd\Core\Domain\Exception\NotMatchTypeException;
use Zelenin\Ddd\Core\Domain\Model\DefaultValueObject;
use Zelenin\Ddd\Core\Domain\Object\Object;

final class Email extends DefaultValueObject
{
    /**
     * @var string
     */
    private $email;

    /**
     * @param string $email
     */
    private function __construct($email)
    {
        if (strpos($email, '@') === false) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid email', $email));
        }

        $this->email = $email;
    }

    /**
     * @param string $email
     *
     * @return static
     */
    public static function create($email)
    {
        return new static($email);
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function name()
    {
        $parts = explode('@', $this->email());
        array_pop($parts);
        return implode('@', $parts);
    }

    /**
     * @return string
     */
    public function domain()
    {
        $parts = explode('@', $this->email());
        return array_pop($parts);
    }

    /**
     * @param static $object
     *
     * @return bool
     */
    public function equalsTo(Object $object)
    {
        if (!$this->sameTypeAs($object)) {
            throw new NotMatchTypeException($this);
        }

        return $this->email() === $object->email();
    }
}
