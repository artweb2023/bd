<?php
declare(strict_types=1);

const DATABASE_CONFIG_NAME = 'org.db.ini';

function connectDatabase(): PDO
{
    $configPath = getConfigPath(DATABASE_CONFIG_NAME);
    if (!file_exists($configPath)) {
        throw new RuntimeException("Could not find database configuration at '$configPath'");
    }
    $config = parse_ini_file($configPath);
    if (!$config) {
        throw new RuntimeException("Failed to parse database configuration from '$configPath'");
    }

    $expectedKeys = ['dsn', 'user', 'password'];
    $missingKeys = array_diff($expectedKeys, array_keys($config));
    if ($missingKeys) {
        throw new RuntimeException('Wrong database configuration: missing options ' . implode(' ', $missingKeys));
    }

    return new PDO($config['dsn'], $config['user'], $config['password']);
}

function getAllBranchInDatabase(PDO $connection): ?array
{
    $query = <<<SQL
        SELECT
            id,
            city,
            address
        FROM branch
        SQL;

    $statement = $connection->query($query);
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $rows ?: null;
}

function getAllEmployeesBranchInDatabase(PDO $connection): array
{
    $query = <<<SQL
        SELECT
            branch_id,
            COUNT(id) as employee_count
        FROM employee
        GROUP BY branch_id
        SQL;

    $statement = $connection->query($query);
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $rows ?: [];
}

function saveBranchToDatabase(PDO $connection, array $branchData): int
{
    $query = <<<SQL
        INSERT INTO branch (city, address)
        VALUES (:city, :address)
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':city' => $branchData['city'],
        ':address' => $branchData['address']
    ]);

    return (int)$connection->lastInsertId();
}

function deleteBranchToDatabase(PDO $connection, int $id): void
{
    $query = <<<SQL
        DELETE 
        FROM branch
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute(['id' => $id]);
}

function deleteAllEmployeeBranch(PDO $connection, int $id): void
{
    $query = <<<SQL
        DELETE 
        FROM employee
        WHERE branch_id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute(['id' => $id]);
}

function findBranchInDatabase(PDO $connection, int $id): ?array
{
    $query = <<<SQL
        SELECT
            id,
            city,
            address
        FROM branch
        WHERE id = $id
        SQL;

    $statement = $connection->query($query);
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

function getEmployeesBranchInDatabase(PDO $connection, int $id): ?array
{
    $query = <<<SQL
        SELECT
            id,
            full_name,
            position,
            phone_number,
            email,
            gender,
            date_of_birth,
            hire_date,
            comment
        FROM employee
        WHERE branch_id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([':id' => $id]);
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $rows ?: null;
}
function saveEmployeeToDatabase(PDO $connection, array $employeeData): int
{
    $query = <<<SQL
        INSERT INTO employee (full_name, position, phone_number, email,
                              gender, date_of_birth, hire_date, comment, path_photo, branch_id)
        VALUES (:full_name, :position, :phone_number, :email, :gender, :date_of_birth, :hire_date,
                :comment, :path_photo, :branch_id)
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':full_name' => $employeeData['full_name'],
        ':position' => $employeeData['position'],
        ':phone_number' => $employeeData['phone_number'],
        ':email' => $employeeData['email'],
        ':gender' => $employeeData['gender'],
        ':date_of_birth' => $employeeData['date_of_birth'],
        ':hire_date' => $employeeData['hire_date'],
        ':comment' => $employeeData['comment'],
        ':path_photo' => $employeeData['path_photo'],
        ':branch_id' => $employeeData['branch_id']
    ]);

    return (int)$connection->lastInsertId();
}

function findEmployeeInDatabase(PDO $connection, int $id)
{
    $query = <<<SQL
        SELECT
            id,
            full_name,
            position,
            phone_number,
            email,
            gender,
            date_of_birth,
            hire_date,
            comment,
            path_photo,
            branch_id
        FROM employee
        WHERE id = $id
        SQL;

    $statement = $connection->query($query);
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

function deleteEmployeeFromBranch(PDO $connection, int $id): void
{
    $query = <<<SQL
        DELETE 
        FROM employee
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute(['id' => $id]);
}

function changeFullNameEmployee(PDO $connection, array $employeeData): int
{
    $query = <<<SQL
        UPDATE employee
        SET full_name = :full_name
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':id' => $employeeData['employee_id'],
        ':full_name' => $employeeData['full_name']
    ]);

    return $statement->rowCount();
}

function changePositionEmployee(PDO $connection, array $employeeData): int
{
    $query = <<<SQL
        UPDATE employee
        SET position = :position
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':id' => $employeeData['employee_id'],
        ':position' => $employeeData['position']
    ]);

    return $statement->rowCount();
}

function changePhoneNumberEmployee(PDO $connection, array $employeeData): int
{
    $query = <<<SQL
        UPDATE employee
        SET phone_number = :phone_number
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':id' => $employeeData['employee_id'],
        ':phone_number' => $employeeData['phone_number']
    ]);

    return $statement->rowCount();
}

function changeEmailEmployee(PDO $connection, array $employeeData): int
{
    $query = <<<SQL
        UPDATE employee
        SET email = :email
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':id' => $employeeData['employee_id'],
        ':email' => $employeeData['email']
    ]);

    return $statement->rowCount();
}

function changeGenderEmployee(PDO $connection, array $employeeData): int
{
    $query = <<<SQL
        UPDATE employee
        SET gender = :gender
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':id' => $employeeData['employee_id'],
        ':gender' => $employeeData['gender']
    ]);

    return $statement->rowCount();
}

function changeDateOfBirthEmployee(PDO $connection, array $employeeData): int
{
    $query = <<<SQL
        UPDATE employee
        SET date_of_birth = :date_of_birth
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':id' => $employeeData['employee_id'],
        ':date_of_birth' => $employeeData['date_of_birth']
    ]);

    return $statement->rowCount();
}

function changeHireDateEmployee(PDO $connection, array $employeeData): int
{
    $query = <<<SQL
        UPDATE employee
        SET hire_date = :hire_date
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':id' => $employeeData['employee_id'],
        ':hire_date' => $employeeData['hire_date']
    ]);

    return $statement->rowCount();
}

function changeCommentEmployee(PDO $connection, array $employeeData): int
{
    $query = <<<SQL
        UPDATE employee
        SET comment = :comment
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':id' => $employeeData['employee_id'],
        ':comment' => $employeeData['comment']
    ]);

    return $statement->rowCount();
}

function changeBranchCity(PDO $connection, array $branchData): int
{
    $query = <<<SQL
        UPDATE branch
        SET city = :city
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':id' => $branchData['branch_id'],
        ':city' => $branchData['city']
    ]);

    return $statement->rowCount();
}

function changeBranchAddress(PDO $connection, array $branchData): int
{
    $query = <<<SQL
        UPDATE branch
        SET address = :address
        WHERE id = :id
        SQL;

    $statement = $connection->prepare($query);
    $statement->execute([
        ':id' => $branchData['branch_id'],
        ':address' => $branchData['address']
    ]);

    return $statement->rowCount();
}