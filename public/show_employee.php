<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/lib/request.php';
require_once __DIR__ . '/../src/lib/response.php';
require_once __DIR__ . '/../src/lib/database.php';
require_once __DIR__ . '/../src/lib/views.php';


const SHOW_EMPLOYEE_URL = '/show_employee.php';
const EMPLOYEE_ID_PARAM = 'employee_id';

function handleShowEmployee(?string $errorMessage = null): void
{
    $employeeId = $_GET['employee_id'] ?? null;
    if (!isset($employeeId) || !is_numeric($employeeId))
    {
        writeErrorNotFound();
        exit();
    }
    $connection = connectDatabase();
    $employeeData = findEmployeeInDatabase($connection, (int)$employeeId);
    if (!$employeeData)
    {
        writeErrorNotFound();
        exit();
    }
    $branch = findBranchInDatabase($connection, $employeeData['branch_id']);
    $params = ['employee' => $employeeData, 'branch' => $branch, 'errorMessage' => $errorMessage,];
    echo renderView('employee_page.php', $params);
}

function handleChangeEmployeeForm(): void
{
    $employeeId = (int)$_GET['employee_id'] ?? null;
    $fullName = $_POST['full_name'] ?? null;
    $position = $_POST['position'] ?? null;
    $phoneNumber = $_POST['phone_number'] ?? null;
    $email = $_POST['email'] ?? null;
    $gender = $_POST['gender'] ?? null; 
    $dateOfBirth = $_POST['date_of_birth'] ?? null;
    $hireDate = $_POST['hire_date'] ?? null;
    $comment = $_POST['comment'] ?? null; 
    $dateOfBirth = $dateOfBirth !== null ? date('Y-m-d', strtotime($dateOfBirth)) : null;
    $hireDate = $hireDate !== null ? date('Y-m-d', strtotime($hireDate)) : null;
    $connection = connectDatabase();
    if($fullName) {
        changeFullNameEmployee($connection,[
            'employee_id' => $employeeId,
            'full_name' => $fullName,
        ]);
    } else if($position) {
        changePositionEmployee($connection,[
            'employee_id' => $employeeId,
            'position' => $position,
        ]);
    } else if($phoneNumber) {
        changePhoneNumberEmployee($connection,[
            'employee_id' => $employeeId,
            'phone_number' => $phoneNumber,
        ]);
    } else if($email) {
        changeEmailEmployee($connection,[
            'employee_id' => $employeeId,
            'email' => $email,
        ]);     
    } else if($gender) {
        changeGenderEmployee($connection,[
            'employee_id' => $employeeId,
            'gender' => $gender,
        ]);  
    } else if($dateOfBirth) {
        changeDateOfBirthEmployee($connection,[
            'employee_id' => $employeeId,
            'date_of_birth' => $dateOfBirth,
        ]);  
    } else if($hireDate) {
        changeHireDateEmployee($connection,[
            'employee_id' => $employeeId,
            'hire_date' => $hireDate,
        ]);  
    } else if($comment) {
        changeCommentEmployee($connection,[
            'employee_id' => $employeeId,
            'comment' => $comment,
        ]);  
    } else {
        handleShowEmployee('Поле обязательны для заполнения');
    }
    $employeeUrl = SHOW_EMPLOYEE_URL . '?' . EMPLOYEE_ID_PARAM . '=' . $employeeId;
    writeRedirectSeeOther($employeeUrl);
}

try
{
    if (isRequestHttpMethod(HTTP_METHOD_GET))
    {
        handleShowEmployee();
    }
    elseif (isRequestHttpMethod(HTTP_METHOD_POST))
    {
        handleChangeEmployeeForm();
    }
    else
    {
        writeRedirectSeeOther($_SERVER['REQUEST_URI']);
    }
}
catch (Throwable $ex)
{
    error_log((string)$ex);
    writeInternalError();
}