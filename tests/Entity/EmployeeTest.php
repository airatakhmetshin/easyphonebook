<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    public function testSetCellphoneNumbersOnlyDigits(): void
    {
        $employee = new Employee();
        $employee->setCellphoneNumbers(['8 (888) 123-12-11', '8 (111) 333-31-32']);

        $this->assertEmpty(array_diff(['88881231211', '81113333132'], $employee->getCellphoneNumbers()));
    }

    public function testSetCellphoneNumbersWithoutDigits(): void
    {
        $employee = new Employee();
        $employee->setCellphoneNumbers(['not digits', null]);

        $this->assertEquals([], $employee->getCellphoneNumbers());
    }

    public function testSetCellphoneNumbersMixedCharacters(): void
    {
        $employee = new Employee();
        $employee->setCellphoneNumbers([null, '8 (888) 123-12-11', '','8 (111) 333-31-32', 'test']);

        $this->assertEmpty(array_diff(['88881231211', '81113333132'], $employee->getCellphoneNumbers()));
    }
}
