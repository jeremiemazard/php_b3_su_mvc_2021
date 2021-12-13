<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="employees")
 */
class Employee extends User
{

    /**
     * @ORM\Column(type="integer")
     */
    private $employee_num;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $salary;

    public function getEmployeeNum(): ?int
    {
        return $this->employee_num;
    }

    public function setEmployeeNum(int $employee_num): self
    {
        $this->employee_num = $employee_num;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(?float $salary): self
    {
        $this->salary = $salary;

        return $this;
    }
}