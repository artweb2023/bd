<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/lib/request.php';
require_once __DIR__ . '/../src/lib/response.php';
require_once __DIR__ . '/../src/lib/database.php';
require_once __DIR__ . '/../src/lib/views.php';

function handleShowBranches(?string $errorMessage = null): void
{
    $connection = connectDatabase();
    $branches = getAllBranchInDatabase($connection);
    $branchesWithEmployeeCount = getAllEmployeesBranchInDatabase($connection);
    $branchesViews = [];
    if($branches !== null)
    {
        foreach ($branches as $branch) {
            $employeeCount = 0;
            foreach ($branchesWithEmployeeCount as $row) {
                if ($row['branch_id'] === $branch['id']) {
                    $employeeCount = $row['employee_count'];
                    break;
                }
            }
                $branchesViews[] = [
                'id' => $branch['id'],
                'city' => $branch['city'],
                'address' => $branch['address'],
                'employees' => $employeeCount
            ];
        }
    }
    echo renderView('branches_feed_page.php', [
        'branches' => $branchesViews,
        'errorMessage' => $errorMessage,
    ]);
}

function handleAddBranchForm(): void
{
    $city = $_POST['city'] ?? null;
    $address = $_POST['address'] ?? null;
    if (!$city || !$address)
    {
        handleShowBranches('Все поля обязательны для заполнения');
        http_response_code(HTTP_STATUS_400_BAD_REQUEST);
        return;
    }
    $connection = connectDatabase();
    saveBranchToDatabase($connection, [
        'city' => $city,
        'address' => $address
    ]);
    writeRedirectSeeOther('/');
}


try {
    if (isRequestHttpMethod(HTTP_METHOD_GET)) 
    {
        handleShowBranches();
    } elseif (isRequestHttpMethod(HTTP_METHOD_POST)) 
    {
        handleAddBranchForm();
    } else 
    {
        writeRedirectSeeOther($_SERVER['REQUEST_URI']);
    }
} catch (Throwable $ex) {
    error_log((string)$ex);
    writeInternalError();
}