<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

class PhoneTest extends TestCase
{
    public function testSetPhoneNumberOnlyDigits(): void
    {
        $phoneNumber = new Phone();
        $phoneNumber->setPhoneNumber('123');

        $this->assertEquals('123', $phoneNumber->getPhoneNumber());
    }

    public function testSetPhoneNumberWithoutDigits(): void
    {
        $phoneNumber = new Phone();
        $phoneNumber->setPhoneNumber('not digits');

        $this->assertNull($phoneNumber->getPhoneNumber());
    }

    public function testSetPhoneNumberMixedCharacters(): void
    {
        $phoneNumber = new Phone();
        $phoneNumber->setPhoneNumber('11-55-95');

        $this->assertEquals('115595', $phoneNumber->getPhoneNumber());
    }

    public function testSetAlternateNumberOnlyDigits(): void
    {
        $phoneNumber = new Phone();
        $phoneNumber->setAlternateNumber('123');

        $this->assertEquals('123', $phoneNumber->getAlternateNumber());
    }

    public function testSetAlternateNumberWithoutDigits(): void
    {
        $phoneNumber = new Phone();
        $phoneNumber->setAlternateNumber('not digits');

        $this->assertNull($phoneNumber->getAlternateNumber());
    }

    public function testSetAlternateNumberMixedCharacters(): void
    {
        $phoneNumber = new Phone();
        $phoneNumber->setAlternateNumber('11-55-95');

        $this->assertEquals('115595', $phoneNumber->getAlternateNumber());
    }
}
