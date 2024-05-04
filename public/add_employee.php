<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/lib/request.php';
require_once __DIR__ . '/../src/lib/response.php';
require_once __DIR__ . '/../src/lib/database.php';
require_once __DIR__ . '/../src/lib/uploads.php';
require_once __DIR__ . '/../src/lib/views.php';

const SHOW_BRANCH_URL = '/show_branch.php';
const BRANCH_ID_PARAM = 'branch_id';

function showAddEmployeeForm(?string $errorMessage = null, ?int $branchId = null): void
{
    $params = ['errorMessage' => $errorMessage, 'branchId' => $branchId];
    echo renderView('add_employee_form.php', $params);
}

function handleAddEmployeeForm(): void
{
    $fileInfo = $_FILES['image'] ?? null;
    $branchId = (int)$_GET['branch_id'];
    $fullName = $_POST['full_name'] ?? null;
    $position = $_POST['position'] ?? null;
    $phoneNumber = $_POST['phone_number'] ?? null;
    $email = $_POST['email'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $dateOfBirth = false !== ($dobTimestamp = strtotime($_POST['date_of_birth'])) 
                              ? date('Y-m-d', $dobTimestamp) : null;
    $hireDate = false !== ($hireTimestamp = strtotime($_POST['hire_date'])) 
                              ? date('Y-m-d', $hireTimestamp) : null;
    $comment = $_POST['coment'] ?? null;
    if (!$fileInfo || !$fullName || !$position || !$phoneNumber || !$email || !$gender 
        || !$dateOfBirth || !$hireDate || !$comment) 
    {
        showAddEmployeeForm('Все поля обязательны для заполнения', $branchId);
        return;
    }
    try
    {
        $imageInfo = uploadImageFile($fileInfo);
    }
    catch (InvalidArgumentException $exception)
    {
        http_response_code(HTTP_STATUS_400_BAD_REQUEST);
        showAddEmployeeForm(errorMessage: $exception->getMessage());
        return;
    }
    $imageUrlPath = getUploadUrlPath($imageInfo['path']);
    $connection = connectDatabase();
    $employeeData = saveEmployeeToDatabase($connection, [
        'full_name' => $fullName,
        'position' => $position,
        'phone_number' => $phoneNumber,
        'email' => $email,
        'gender' => $gender,
        'date_of_birth' => $dateOfBirth,
        'hire_date' => $hireDate,
        'comment' => $comment,
        'path_photo' => $imageUrlPath,
        'branch_id' => $branchId,
    ]);

    $branchUrl = SHOW_BRANCH_URL . '?' . http_build_query([BRANCH_ID_PARAM => $branchId]);
    writeRedirectSeeOther($branchUrl);
}

try
{
    if (isRequestHttpMethod(HTTP_METHOD_GET))
    {
        showAddEmployeeForm();
    }
    elseif (isRequestHttpMethod(HTTP_METHOD_POST))
    {
        handleAddEmployeeForm();
    }
    else
    {
        writeRedirectSeeOther('add_employee.php');
    }
}
catch (Throwable $ex)
{
    error_log((string)$ex);
    writeInternalError();
}