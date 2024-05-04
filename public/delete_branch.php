<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/lib/request.php';
require_once __DIR__ . '/../src/lib/response.php';
require_once __DIR__ . '/../src/lib/database.php';
require_once __DIR__ . '/../src/lib/views.php';

function handleDeleteBranchForm(): void
{
    $branchId = (int)$_POST['branch_id'];
    $connection = connectDatabase();
    deleteAllEmployeeBranch($connection, $branchId);
    deleteBranchToDatabase($connection, $branchId);
    writeRedirectSeeOther('/');
}

try {
    if (isRequestHttpMethod(HTTP_METHOD_POST)) 
    {
        handleDeleteBranchForm();
    } else 
    {
        writeRedirectSeeOther('/');
    }
} catch (Throwable $ex) {
    error_log((string)$ex);
    writeInternalError();
}