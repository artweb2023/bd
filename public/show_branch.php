<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/lib/request.php';
require_once __DIR__ . '/../src/lib/response.php';
require_once __DIR__ . '/../src/lib/database.php';
require_once __DIR__ . '/../src/lib/views.php';

const SHOW_BRANCH_URL = '/show_branch.php';
const BRANCH_ID_PARAM = 'branch_id';

function handleShowBranch(): void
{
    $branchId = $_GET['branch_id'] ?? null;
    if (!isset($branchId) || !is_numeric($branchId))
    {
        writeErrorNotFound();
        exit();
    }
    $connection = connectDatabase();
    $branchData = findBranchInDatabase($connection, (int)$branchId);
    if (!$branchData)
    {
        writeErrorNotFound();
        exit();
    }
    $employees = getEmployeesBranchInDatabase($connection, (int)$branchId);
    if (empty($employees)) {
        echo renderView('branch_page.php', [
            'branch' => [
                'id' => $branchId,
                'city' => $branchData['city'],
                'address' => $branchData['address'],
            ],
            'employees' => [],
        ]);
    } else {
        $employeesViews = [];
        foreach ($employees as $employeeData) {
            $employeesViews[] = [
                'id' => $employeeData['id'],
                'full_name' => $employeeData['full_name'],
                'position' => $employeeData['position'],
                'phone_number' => $employeeData['phone_number'],
                'email' => $employeeData['email'],
                'gender' => $employeeData['gender'],
                'date_of_birth' => $employeeData['date_of_birth'],
                'hire_date' => $employeeData['hire_date'],
                'comment' => $employeeData['comment'],
            ];
        }

        echo renderView('branch_page.php', [
            'branch' => [
                'id' => $branchId,
                'city' => $branchData['city'],
                'address' => $branchData['address'],
            ],
            'employees' => $employeesViews,
        ]);
    }
}

function handleChangeDataBranch(): void
{
    $branchId = (int)$_GET['branch_id'] ?? null;
    $branchCity = $_POST['city'] ?? null;
    $branchAddress = $_POST['address'] ?? null;
    $connection = connectDatabase();
    if($branchCity)
    {
        changeBranchCity($connection,[
            'branch_id' => $branchId,
            'city' => $branchCity,
        ]);
    }
    else if($branchAddress)
    {
        changeBranchAddress($connection,[
            'branch_id' => $branchId,
            'address' => $branchAddress,
        ]);
    }
    $branchUrl = SHOW_BRANCH_URL . '?' . http_build_query([BRANCH_ID_PARAM => $branchId]);
    writeRedirectSeeOther($branchUrl);
}

try
{
    if (isRequestHttpMethod(HTTP_METHOD_GET))
    {
        handleShowBranch();
    }
    elseif (isRequestHttpMethod(HTTP_METHOD_POST))
    {
        handleChangeDataBranch();
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