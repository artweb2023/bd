<?php

function getBranchPageUrl(int $branchId): string
{
    return "/show_branch.php?branch_id=$branchId";
}
$counter = 1;
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Филиялы</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="container">
        <h1 class="text-center m-4">Список всех филиалов</h1>
        <div class="scrollme" style="height: 100hv !important; overflow-y: auto;">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Город</th>
                        <th scope="col">Адрес</th>
                        <th scope="col">Количество сотрудников</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach ($branches as $branch): ?>
                    <tr>
                        <td scope="row">
                            <a href="<?= getBranchPageUrl($branch['id']) ?>">
                                <?= htmlentities($counter) ?>
                            </a>
                        </td>
                        <td><?= htmlentities($branch['city']) ?></td>
                        <td><?= htmlentities($branch['address']) ?></td>
                        <td><?= htmlentities($branch['employees']) ?></td>
                        <td>
                            <form action="delete_branch.php" method="post">
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
        <form class="bd-example-snippet bd-code-snippet" action="index.php" method="post" enctype="multipart/form-data">
            <h1 class="text-center m-4">Добавить филиал</h1>
            <?php if ($errorMessage): ?>
            <div class="alert alert-danger" role="alert">
                <?= $errorMessage ?>
            </div>
            <?php endif; ?>
            <div class="mb-3">
                <label class="form-label mt-3" for="city">Город</label>
                <input type="text" class="form-control" id="city" name="city">
                <label class="form-label mt-3" for="address">Адрес</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Добавить</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>