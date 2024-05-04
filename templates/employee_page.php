<?php
function generateEditModal(string $modalId, string $title, string $fieldName, string $fieldValue, ?int $employeeId): string {
    $inputField = '';
    switch ($fieldName) {
        case 'gender':
            $inputField = <<<HTML
            <select class="form-select mb-2" name="gender">
                <option value="M">Мужчина</option>
                <option value="F">Женщина</option>
            </select>
            HTML;
            break;
        case 'date_of_birth':
            $inputField = <<<HTML
                <div id="datepicker1" class="input-group date mb-2" data-date-format="mm-dd-yyyy">
                    <input class="form-control" type="text" name="date_of_birth" readonly
                        value="$fieldValue"/>
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-calendar"></i>
                    </span>
                </div>
            HTML;
            break;
        case 'hire_date':
            $inputField = <<<HTML
                <div id="datepicker2" class="input-group date mb-2" data-date-format="mm-dd-yyyy">
                    <input class="form-control" type="text" name="hire_date" readonly
                        value="$fieldValue" />
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-calendar"></i>
                    </span>
                </div>
            HTML;
            break;
        default:
            $inputField = <<<HTML
            <input class="form-control" name="$fieldName" value="$fieldValue">
            HTML;
            break;
    }
    return <<<HTML
    <div class="modal fade" id="$modalId" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="show_employee.php?employee_id=$employeeId" method="post">
                <input type="hidden" name="type" value="$fieldName">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        $title
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    $inputField
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
    <title>Филиал</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
</head>

<body class="bg-light">
    <header class="container mt-5">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Фильялы</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="/show_branch.php?branch_id=<?= htmlentities($branch['id']) ?> ">
                        <?= htmlentities($branch['city']) ?>,
                        <?= htmlentities($branch['address']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= htmlentities($employee['full_name']) ?>
                </li>
            </ol>
        </nav>
    </header>
    <h1 class="text-center m-5">Карточка сотрудника</h1>
    <?php if ($errorMessage): ?>
    <div class="alert alert-danger" role="alert">
        <?= $errorMessage ?>
    </div>
    <?php endif; ?>
    <div class="container mb-5">
        <div class="row">
            <div class="col">
                <img src="<?= htmlentities($employee['path_photo']) ?>" class="img-fluid img-thumbnail" alt="photo">
            </div>
            <div class="col">
                <div class="container mb-3">
                    <div class="row">
                        <div class="col d-flex align-items-center">
                            ФИО: <?= htmlentities($employee['full_name']) ?>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editNameModal">
                                Редактировать
                            </button>
                        </div>
                    </div>
                </div>
                <div class="container mb-3">
                    <div class="col">
                        <div class="row">
                            <div class="col d-flex align-items-center">
                                Позиция: <?= htmlentities($employee['position']) ?>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editPositionModal">
                                    Редактировать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mb-3">
                    <div class="col">
                        <div class="row">
                            <div class="col d-flex align-items-center">
                                Номер телефона: <?= htmlentities($employee['phone_number']) ?>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editPhoneNumberModal">
                                    Редактировать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mb-3">
                    <div class="col">
                        <div class="row">
                            <div class="col d-flex align-items-center">
                                Email: <?= htmlentities($employee['email']) ?>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editEmailModal">
                                    Редактировать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mb-3">
                    <div class="col">
                        <div class="row">
                            <div class="col d-flex align-items-center">
                                Пол: <?= htmlentities($employee['gender']) ?>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editGenderModal">
                                    Редактировать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mb-3">
                    <div class="col">
                        <div class="row">
                            <div class="col d-flex align-items-center">
                                Дата рождения: <?= htmlentities($employee['date_of_birth']) ?>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editDateOfBirth">
                                    Редактировать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mb-3">
                    <div class="col">
                        <div class="row">
                            <div class="col d-flex align-items-center">
                                Дата найма: <?= htmlentities($employee['hire_date']) ?>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editHireDate">
                                    Редактировать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mb-3">
        <p class="container mb-2 ps-0">Коментарий:</p>
        <div class="col d-flex align-items-center pb-5 border ">
            <p class="ms-2 mt-2"><?= htmlentities($employee['comment']) ?></p>
        </div>
        <div class="col">
            <button type="button" class="btn btn-primary mt-5" data-bs-toggle="modal" data-bs-target="#editComent">
                Редактировать
            </button>
        </div>
    </div>
    <?= generateEditModal("editNameModal", "Редактировать имя", "full_name", $employee['full_name'], $employee['id']) ?>
    <?= generateEditModal("editPositionModal", "Редактировать должность", "position", $employee['position'], $employee['id']) ?>
    <?= generateEditModal("editPhoneNumberModal", "Редактировать телефон", "phone_number", $employee['phone_number'], $employee['id']) ?>
    <?= generateEditModal("editEmailModal", "Редактировать email", "email", $employee['email'], $employee['id']) ?>
    <?= generateEditModal("editGenderModal", "Редактировать пол", "gender", $employee['gender'], $employee['id']) ?>
    <?= generateEditModal("editDateOfBirth", "Редактировать дату рождения", "date_of_birth", $employee['date_of_birth'], $employee['id']) ?>
    <?= generateEditModal("editHireDate", "Редактировать дату найма", "hire_date", $employee['hire_date'], $employee['id']) ?>
    <?= generateEditModal("editComent", "Редактировать коментарий", "comment", $employee['comment'], $employee['id']) ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js">
    </script>
    <script>
    $(document).ready(function() {
        $('#datepicker1').datepicker();
        $('#datepicker2').datepicker();
    });
    </script>
</body>

</html>