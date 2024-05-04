<?php

function getEmployeePageUrl(int $employeeId): string
{
    return "/show_employee.php?employee_id=$employeeId";
}

function generateEditModal(string $modalId, string $title, string $fieldName, string $fieldValue, int $branchId):string{
     return 
    <<<HTML
        <div class="modal fade" id="$modalId" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="show_branch.php?branch_id=$branchId" method="post">
                    <input type="hidden" name="type" value="$fieldName">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                            $title
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" name="$fieldName" value="$fieldValue">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    HTML;
}
$counter = 1;
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Филиял</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light">
    <header class="container mt-5">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Фильялы</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= htmlentities($branch['city']) ?> :
                    <?= htmlentities($branch['address']) ?>
                </li>
            </ol>
        </nav>
    </header>
    <h1 class="text-center m-5">Филиял</h1>
    <div class="container">
        <div class="d-flex gap-2 justify-content-start py-2">
            <h3 class="text-secondary me-5">Город : <?= htmlentities($branch['city']) ?></h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCity">
                Редактировать
            </button>
        </div>
        <div class="d-flex gap-2 justify-content-start py-2">
            <h3 class="text-secondary me-2">Адрес : <?= htmlentities($branch['address']) ?></h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAddress">
                Редактировать
            </button>
        </div>
        <h1 class="text-center m-4">Список всех работников</h1>
        <div class="scrollme" style="height: 100hv !important; overflow-y: auto;">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ФИО</th>
                        <th scope="col">Должность</th>
                        <th scope="col">Номер телефона</th>
                        <th scope="col">Email</th>
                        <th scope="col">Пол</th>
                        <th scope="col">Дата Рождения</th>
                        <th scope="col">Дата Найма</th>
                        <th scope="col">Коментарий</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td scope="row">
                            <a href="<?= getEmployeePageUrl($employee['id']) ?>">
                                <?= htmlentities($counter) ?>
                            </a>
                        </td>
                        <td><?= htmlentities($employee['full_name']) ?></td>
                        <td><?= htmlentities($employee['position']) ?></td>
                        <td><?= htmlentities($employee['phone_number']) ?></td>
                        <td><?= htmlentities($employee['email']) ?></td>
                        <td><?= htmlentities($employee['gender']) ?></td>
                        <td><?= htmlentities($employee['date_of_birth']) ?></td>
                        <td><?= htmlentities($employee['hire_date']) ?></td>
                        <td><?= htmlentities($employee['comment']) ?></td>
                        <td>
                            <form action="delete_employee.php" method="post">
                                <input type="hidden" name="employee_id" value="<?= $employee['id']?>">
                                <input type="hidden" name="branch_id" value="<?= $branch['id']?>">
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                    <?php $counter++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button id="addEmployeeBtn" data-branch-id="<?= $branch['id'] ?>" class="btn btn-primary my-4 ">Добавить
            сотрудника</button>
    </div>
    <?= generateEditModal("editCity", "Редактировать город", "city", $branch['city'], $branch['id']) ?>
    <?= generateEditModal("editAddress", "Редактировать адрес", "address", $branch['address'], $branch['id']) ?>
    <script>
    document.getElementById("addEmployeeBtn").addEventListener("click", function() {
        var branchId = this.getAttribute("data-branch-id");
        window.location.href = "add_employee.php?branch_id=" + branchId;
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>