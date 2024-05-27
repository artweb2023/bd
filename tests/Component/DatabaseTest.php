<?php
declare(strict_types=1);

namespace App\Tests\Component;

use App\Tests\Common\AbstractDatabaseTestCase;

class DatabaseTest extends AbstractDatabaseTestCase
{
    public function testSaveBranchToDatabase(): void
    {
        $connection = \connectDatabase();
        $branchData = [
            'city' => 'Казань',
            'address' => 'Первомайская 100'
        ];

        $branchId = \saveBranchToDatabase($connection, $branchData);
        $this->assertIsInt($branchId);

        $savedBranch = \findBranchInDatabase($connection, $branchId);
        $this->assertNotNull($savedBranch);
        $this->assertEquals('Казань', $savedBranch['city']);
        $this->assertEquals('Первомайская 100', $savedBranch['address']);
         \deleteBranchToDatabase($connection, $branchId);
    }

    public function testDeleteBranchToDatabase(): void
    {
        $connection = \connectDatabase();
        $branchData = [
            'city' => 'Казань',
            'address' => 'Первомайская 100'
        ];

        $branchId = \saveBranchToDatabase($connection, $branchData);
        $this->assertIsInt($branchId);

        \deleteBranchToDatabase($connection, $branchId);

        $deletedBranch = \findBranchInDatabase($connection, $branchId);
        $this->assertNull($deletedBranch);
    }
    
    public function testFindNonУxistentBranchInDatabase(): void
    {
        $connection = \connectDatabase();
        $nonexistentBranchId = 9999;
        $nonexistentBranch = \findBranchInDatabase($connection, $nonexistentBranchId);
        $this->assertNull($nonexistentBranch);
    }

    public function testChangeBranchCity(): void
    {
        $connection = \connectDatabase();
        $branchData = [
            'city' => 'Казань',
            'address' => 'Первомайская 100'
        ];
        $branchId = \saveBranchToDatabase($connection, $branchData);
        $newCity = 'Москва';
        \changeBranchCity($connection, ['branch_id' => $branchId, 'city' => $newCity]);
        $updatedBranch = \findBranchInDatabase($connection, $branchId);
        $this->assertEquals($newCity, $updatedBranch['city']);
        \deleteBranchToDatabase($connection, $branchId);
    }
    
    public function testSaveEmployeeToDatabase(): void
    {
        $connection = \connectDatabase();
        $branchData = [
            'city' => 'Казань',
            'address' => 'Первомайская 100'
        ];

        $branchId = \saveBranchToDatabase($connection, $branchData);

        $employeeData = [
            'full_name' => 'John Doe',
            'position' => 'Manager',
            'phone_number' => '123-456-7890',
            'email' => 'john.doe@example.com',
            'gender' => 'M',
            'date_of_birth' => '1990-01-01',
            'hire_date' => '2020-01-01',
            'comment' => 'Good employee',
            'path_photo' => '/path/to/photo.jpg',
            'branch_id' => $branchId
        ];

        $employeeId = \saveEmployeeToDatabase($connection, $employeeData);
        $this->assertIsInt($employeeId);

        $savedEmployee = \findEmployeeInDatabase($connection, $employeeId);
        $this->assertNotNull($savedEmployee);
        $this->assertEquals('John Doe', $savedEmployee['full_name']);
        $this->assertEquals('Manager', $savedEmployee['position']);
         \deleteEmployeeFromBranch($connection, $employeeId);
         \deleteBranchToDatabase($connection, $branchId);
    }

    public function testDeleteEmployeeFromBranch(): void
    {
        $connection = \connectDatabase();
        $branchData = [
            'city' => 'Москва',
            'address' => 'Ленина 20'
        ];

        $branchId = \saveBranchToDatabase($connection, $branchData);

        $employeeData = [
            'full_name' => 'Jane Smith',
            'position' => 'Assistant',
            'phone_number' => '098-765-4321',
            'email' => 'jane.smith@example.com',
            'gender' => 'F',
            'date_of_birth' => '1985-05-05',
            'hire_date' => '2019-05-05',
            'comment' => 'Great employee',
            'path_photo' => '/path/to/photo2.jpg',
            'branch_id' => $branchId
        ];

        $employeeId = \saveEmployeeToDatabase($connection, $employeeData);

        \deleteEmployeeFromBranch($connection, $employeeId);

        $deletedEmployee = \findEmployeeInDatabase($connection, $employeeId);
        $this->assertNull($deletedEmployee);
        \deleteBranchToDatabase($connection, $branchId);
    }

    public function testFindNonexistentEmployeeInDatabase(): void
    {
        $connection = \connectDatabase();
        $nonexistentEmployeeId = 9999; 
        $nonexistentEmployee = \findEmployeeInDatabase($connection, $nonexistentEmployeeId);
        $this->assertNull($nonexistentEmployee);
    }

    public function testChangeFullNameEmployee(): void
    {
        $connection = \connectDatabase();
        $branchData = [
            'city' => 'Казань',
            'address' => 'Первомайская 100'
        ];
        $branchId = \saveBranchToDatabase($connection, $branchData);
        $employeeData = [
            'full_name' => 'John Doe',
            'position' => 'Manager',
            'phone_number' => '123-456-7890',
            'email' => 'john.doe@example.com',
            'gender' => 'M',
            'date_of_birth' => '1990-01-01',
            'hire_date' => '2020-01-01',
            'comment' => 'Good employee',
            'path_photo' => '/path/to/photo.jpg',
            'branch_id' => $branchId
        ];
        $employeeId = \saveEmployeeToDatabase($connection, $employeeData);

        $newFullName = 'John Smith';
        \changeFullNameEmployee($connection, ['employee_id' => $employeeId, 'full_name' => $newFullName, 'branch_id' => $branchId]);
        $updatedEmployee = \findEmployeeInDatabase($connection, $employeeId);
        $this->assertEquals($newFullName, $updatedEmployee['full_name']);
        \deleteEmployeeFromBranch($connection, $employeeId);
        \deleteBranchToDatabase($connection, $branchId);
    }
    public function testSQLInjectionAddBarnch(): void
    {
        $connection = \connectDatabase();
        $branchData = [
            'city' => 'Казань',
            'address' => "'Первомайская 100';DROP TABLE branch;--"
        ];
        $branchId = \saveBranchToDatabase($connection, $branchData);
        $this->assertTrue($this->isBranchTableExists($connection));
        \deleteBranchToDatabase($connection, $branchId);
    }
    public function testSQLInjectionInChangeCity(): void
    {
        $connection = \connectDatabase();
        $branchData = [
            'city' => "Казань'; DROP TABLE branch;--",
            'address' => 'Первомайская 100'
        ];
        $branchId = \saveBranchToDatabase($connection, $branchData);
        $this->assertTrue($this->isBranchTableExists($connection));
        \deleteBranchToDatabase($connection, $branchId);
    }
    public function testSQLInjectionInAddEmploye(): void
    {
        $connection = \connectDatabase();
        $branchData = [
            'city' => 'Казань',
            'address' => 'Первомайская 100'
        ];
        $branchId = \saveBranchToDatabase($connection, $branchData);
        $employeeData = [
            'full_name' => 'John Doe',
            'position' => "'Manager';DROP TABLE employee;--",
            'phone_number' => '123-456-7890',
            'email' => 'john.doe@example.com',
            'gender' => 'M',
            'date_of_birth' => '1990-01-01',
            'hire_date' => '2020-01-01',
            'comment' => 'Good employee',
            'path_photo' => '/path/to/photo.jpg',
            'branch_id' => $branchId
        ];
        $employeeId = \saveEmployeeToDatabase($connection, $employeeData);
        $this->assertTrue($this->isEmployeeTableExists($connection));
        \deleteEmployeeFromBranch($connection, $employeeId);
        \deleteBranchToDatabase($connection, $branchId);
    }

    public function testSQLInjectionInChangeFullName(): void
    {
        $connection = \connectDatabase();
        $branchData = [
            'city' => 'Казань',
            'address' => 'Первомайская 100'
        ];
        $branchId = \saveBranchToDatabase($connection, $branchData);
        $employeeData = [
            'full_name' => "John Doe'; DROP TABLE employee;--",
            'position' => 'Manager',
            'phone_number' => '123-456-7890',
            'email' => 'john.doe@example.com',
            'gender' => 'M',
            'date_of_birth' => '1990-01-01',
            'hire_date' => '2020-01-01',
            'comment' => 'Good employee',
            'path_photo' => '/path/to/photo.jpg',
            'branch_id' => $branchId
        ];
        $employeeId = \saveEmployeeToDatabase($connection, $employeeData);
        $this->assertTrue($this->isEmployeeTableExists($connection));
        \deleteEmployeeFromBranch($connection, $employeeId);
        \deleteBranchToDatabase($connection, $branchId);
    }

    public function isBranchTableExists($connection): bool
    {
        $tableName = 'branch';
        $statement = $connection->prepare("SELECT 1 FROM $tableName LIMIT 1");
        $statement->execute();
        return $statement->rowCount() !== false;
    }

    public function isEmployeeTableExists($connection): bool
    {
        $tableName = 'employee';
        $statement = $connection->prepare("SELECT 1 FROM $tableName LIMIT 1");
        $statement->execute();
        return $statement->rowCount() !== false;
    }
}