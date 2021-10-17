<?php
    header("Content-type: application/json");

    $operation = $_GET['op'];
    $transactionId = $_GET['tran'];

    include_once("../domain/bookings.php");
    include_once("../domain/transfers.php");
    include_once("../domain/funds.php");
    include_once("../domain/persons.php");
    include_once("../domain/expenses.php");
    require_once('../config.php');
    $cfg = new Config('../');

    $bookings = new Bookings($cfg);
    $bookings->Load();

    $transfers = new Transfers($cfg);
    $transfers->Load();

    $funds = new Funds($cfg);
    $funds->Load();

    $persons = new Persons($cfg);
    $persons->Load();

    $expenses = new Expenses($cfg);
    $expenses->Load();

    switch  ($operation)
    {
        case "setPerson":
            $personId = $_GET['sender'];
            $bookings->SetPerson($transactionId, $personId);
            break;
        case "assignFund":
            $personId = (int)$_GET['person'];
            $fundId = (int)$_GET['fund'];
            $money = (float) str_replace(",",".",$_GET['money']);
            $fund = $funds->GetFund($fundId);
            $person = $persons->GetPerson($personId);
            $incomeGuid = uniqid($fund->accountId . 'f' . $fundId . 'm' . $money, $more_entropy = true);
            $bookings->AddFund($transactionId, $personId, $fundId, $fund->accountId, $money, $incomeGuid);
            $transfers->AddFundTransfer($fund->accountId, $fund->id, $money, $fund->name . ", " . $person->name , $incomeGuid);
            $transfers->Save();
            $bookings->Save();
            break;
        case "assignExpense":
            $expenseId = (int)$_GET['expense'];
            $description = $_GET['descr'];
            $money = (float) str_replace(",",".",$_GET['money']);
            $expense = $expenses->Get($expenseId);
            $expenseGuid = uniqid($expense->accountId . 'e' . $expenseId . 'm' . $money, $more_entropy = true);
            $bookings->AddExpense($transactionId, $expenseId, $expense->accountId, $money, $description, $expenseGuid);
            $transfers->AddExpenseTransfer($expense->accountId, $expenseId, $money, $description, $expenseGuid);
            $transfers->Save();
            $bookings->Save();
            break;
        case "finish":
            $accountId = (int)$_GET['account'];
            $bookings->Confirm($transactionId);
            $bookings->Save();
            break;
        default:
            die("Bad request");
    }
?>
