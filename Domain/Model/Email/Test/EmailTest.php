<?php

namespace Zelenin\Ddd\ValueObject\Domain\Model\Email\Test;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Zelenin\Ddd\ValueObject\Domain\Model\Email\Email;

class EmailTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validEmails
     */
    public function testValidEmails($email, $name, $domain)
    {
        static::assertTrue(Email::create($email)->email() === $email);
    }

    /**
     * @dataProvider validEmails
     */
    public function testPartsOfEmails($email, $name, $domain)
    {
        $object = Email::create($email);
        static::assertEquals($object->name(), $name);
        static::assertEquals($object->domain(), $domain);
    }

    /**
     * @dataProvider notValidEmails
     * @expectedException InvalidArgumentException
     */
    public function testNotValidEmails($email)
    {
        Email::create($email);
    }

    /**
     * @return array
     */
    public static function validEmails()
    {
        return [
            ['name@domain.com', 'name', 'domain.com'],
            ['name@domain', 'name', 'domain'],
            ['имя@домен', 'имя', 'домен'],
            ['site+site@192.168.1.2', 'site+site', '192.168.1.2'],
            ['name@local', 'name', 'local']
        ];
    }

    /**
     * @return array
     */
    public static function notValidEmails()
    {
        return [
            ['name(at)domain.com'],
            ['namedomain'],
            ['имядомен']
        ];
    }
}
