<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <title>PHP Test</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="/static/style.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="/static/restapi.js"></script>
    <script src="/static/forms.js"></script>
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="header">
        <div class="jumbotron bg-dark p-2 d-flex align-items-center">
            <img src="/static/wallet.svg" class="ms-2 me-2" height="50px" />
            <div class="text-white text-left d-flex align-items-baseline">
                <h1>Skarbiec</h1>
                <h5 class="ms-4">Klasa 8e</h5>
            </div>
        </div>
    </div>

    <div class="container">

        <?php
        $showBankAccountAndMenu = true;
        switch ($page) {
            case "reg":
                $showBankAccountAndMenu = false;
                break;
            case "registered":
                $showBankAccountAndMenu = false;
                break;
            case "login":
                $showBankAccountAndMenu = false;
                break;
        }


        if ($showBankAccountAndMenu)
            require('views/menu.php');

        if (!empty($event)) {
            echo "<div class='alert alert-danger mt-3 mb-3' role='alert'>{$event}</div>";
        }

        switch ($page) {
            case "registered":
                include_once('views/registered.php');
                break;
            case "reg":
                include_once('views/register.php');
                break;
            case "login":
                include('views/login.php');
                break;
            case "regac":
                include('views/register_account.php');
                break;
            case "ac":
                include('views/accounts.php');
                break;
            case "st":
                include('views/funds_state.php');
                break;
            case "in":
                include('views/incomes.php');
                break;
            case "tr":
                include('views/transactions.php');
                break;
            case "ex":
                include('views/expenses.php');
                break;
        }

        ?>
    </div>

    <?php
    if ($showBankAccountAndMenu)
        echo '<footer class="bg-dark text-light text-center footer mt-auto py-3">Nr konta do wpłat: 02 2490 1044 0000 4200 9007 6619</footer>';
    ?>

    <script>
        feather.replace()
    </script>
</body>

</html>