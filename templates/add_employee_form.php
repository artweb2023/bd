<?php
$branchId = $_GET['branch_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Добавить сотрудника</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
</head>

<body class="bg-light">
    <div class="container">
        <h1 class="text-center m-4">Добавить сотрудника</h1>
        <?php if ($errorMessage): ?>
        <div class="alert alert-danger" role="alert">
            <?= $errorMessage ?>
        </div>
        <?php endif; ?>
        <form class="bd-example-snippet bd-code-snippet" action="add_employee.php?branch_id=<?= $branchId ?>"
            method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 border ms-3 d-flex justify-content-center align-items-center">
                    <div class="mb-3">
                        <input class="form-control" type="file" id="formFile" name="image">
                    </div>
                </div>
                <div class="col-md-5 ms-5">
                    <input type="text" class="form-control mb-2" placeholder="ФИО" name="full_name">
                    <input type="text" class="form-control mb-2" placeholder="Должность" name="position">
                    <input type="text" class="form-control mb-2" placeholder="Телефон" name="phone_number">
                    <input type="text" class="form-control mb-2" placeholder="Email" name="email">
                    <select class="form-select mb-2" name="gender">
                        <option selected>Откройте это меню выбора</option>
                        <option value="M">Мужчина</option>
                        <option value="F">Женщина</option>
                    </select>
                    <div id="datepicker1" class="input-group date mb-2" data-date-format="mm-dd-yyyy">
                        <input class="form-control" type="text" name="date_of_birth" readonly
                            placeholder="Дата рождения" />
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                    </div>
                    <div id="datepicker2" class="input-group date mb-2" data-date-format="mm-dd-yyyy">
                        <input class="form-control" type="text" name="hire_date" readonly placeholder="Дата найма" />
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label mt-3">Коментарий</label>
                <textarea class="form-control" name="coment"></textarea>
            </div>
            <div class="col-auto mt-3">
                <button type="submit" class="btn btn-primary mb-3">Сохранить</button>
            </div>
        </form>
    </div>
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