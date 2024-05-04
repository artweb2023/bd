<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/lib/request.php';
require_once __DIR__ . '/../src/lib/response.php';
require_once __DIR__ . '/../src/lib/database.php';
require_once __DIR__ . '/../src/lib/views.php';

const SHOW_BRANCH_URL = '/show_branch.php';
const BRANCH_ID_PARAM = 'branch_id';


function handleDeleteEmployeeFormBranch(): void
{
    $branchId = (int)$_POST['branch_id'];
    $employeeId = (int)$_POST['employee_id'];
    $connection = connectDatabase();
    deleteEmployeeFromBranch($connection, $employeeId);
    $branchUrl = SHOW_BRANCH_URL . '?' . http_build_query([BRANCH_ID_PARAM => $branchId]);
    writeRedirectSeeOther($branchUrl);
}

try {
    if (isRequestHttpMethod(HTTP_METHOD_POST)) 
    {
        handleDeleteEmployeeFormBranch();
    } else 
    {
        writeRedirectSeeOther('add_employee.php');
    }
} catch (Throwable $ex) {
    error_log((string)$ex);
    writeInternalError();
}