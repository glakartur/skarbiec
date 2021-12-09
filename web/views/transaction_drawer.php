<?php

include_once("domain/permissions.php");

class TransactionDrawer
{
    private $bookings;
    private $knownFunds;
    private $knownPersons;
    private $knownExpenses;
    private $knownAccounts;
    private $auth;

    public function __construct($bookings, $knownFunds, $knownExpenses, $knownPersons, $knownAccounts, $auth)
    {
        $this->bookings = $bookings;
        $this->knownFunds = $knownFunds;
        $this->knownExpenses = $knownExpenses;
        $this->knownPersons = $knownPersons;
        $this->knownAccounts = $knownAccounts;
        $this->auth = $auth;
    }

    public function Draw($transaction, $editMode)
    {
        $showable = $editMode || !empty($this->bookings->ListExpenses($transaction->id));
        if (!$showable)
        {
            foreach ($this->bookings->ListFunds($transaction->id) as $book)
            {
                if ($this->auth->HasPermission(Permission::AllStudents) 
                    || $this->auth->HasPermission(Permission::StudentPrefix . $book["personId"]) )
                {
                    $showable = true;
                    break;
                }
            }    
        }

        if (!$showable)
            return;

        $bookedMoney = $this->bookings->SumarizeTransactionBookings($transaction->id);
        $unbookedValue = $transaction->money - $bookedMoney;

        echo '<div class="list-group">';
        echo '<div class="list-group-item list-group-item-action">';
        echo '<div class="row">';
            echo '<div class="col-sm-6">';
                echo '<div>';
                    echo "<span class='tran-date'>" . $transaction->date . "</span>";
                    echo "<span class='tran-from'>" . $transaction->sender . "</span> --&gt;";
                    echo "<span class='tran-to'>" . $transaction->receiver . "</span>";
                echo "</div>";
                echo "<div>";
                    echo "<span class='tran-descr-label'>Opis:</span>";
                    echo "<span class='tran-descr'>" . $transaction->description . "</span>";
                echo "</div>";
            echo "</div>";

            echo '<div class="col-sm-2 text-center">';
                echo '<div class="text-center">' . $transaction->money . ' zł</div>';
                echo '<div class="text-center"><span>' . $transaction->saleMoney . ' ' . $transaction->saleCurrency . '</span></div>';
            echo '</div>';

        if ($editMode)
        {
            echo '<div class="col-sm-1 text-center">' . $unbookedValue . ' zł</div>';
    
            if (! $this->bookings->IsConfirmed($transaction->id))
            {
                echo '<div class="col-sm-1 text-center"><button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#incomeBookForm" ' .
                    'data-bs-descr="' . $transaction->description . '" ' .
                    'data-bs-sender="' . $transaction->sender . '" ' .
                    'data-bs-money="' . $unbookedValue . '" ' .
                    'data-bs-transaction="' . $transaction->id . '">' .
                    'przypisz zbiórkę</button></div>';

                echo '<div class="col-sm-1 text-center"><button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#expenseBookForm" ' .
                    'data-bs-descr="' . $transaction->description . '" ' .
                    'data-bs-sender="' . $transaction->sender . '" ' .
                    'data-bs-money="' . $unbookedValue . '" ' .
                    'data-bs-transaction="' . $transaction->id . '">' .
                    'przypisz wydatek</button></div>';
            }

            if ($unbookedValue == 0)
            {
                echo '<div class="col-sm-1 text-center"><button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#confirm-transaction-form" ' .
                    'data-bs-transaction="' . $transaction->id . '" ' .
                    '>zatwierdź</button></div>';
            }
            //echo '</div>';
        }

        echo '<div class="border-top pt-2">';
        // echo "<h1>" . Permission::StudentPrefix . "3" . "</h1>";
        // echo "<h1>" . $this->auth->HasPermission(Permission::StudentPrefix . "3") . "</h1>";
        


        foreach ($this->bookings->ListFunds($transaction->id) as $book)
        {
            if ($this->auth->HasPermission(Permission::AllStudents) 
                || $this->auth->HasPermission(Permission::StudentPrefix . $book["personId"]) )
            {
                echo '<div class="pt-2 row">';

                echo '<div class="col-sm-12">';
                echo '<span class="bg-success rounded p-1 m-2">';
                echo '<span class="badge bg-success text-white fw-normal font-size-lg">';
                echo $book["money"] . " zł";
                echo "</span>";
                echo '<span class="badge bg-warning text-dark fw-normal font-size-lg">';
                echo $this->knownPersons->GetPerson($book["personId"])->name;
                echo "</span>";
                echo '<span class="badge bg-secondary text-light fw-normal font-size-lg">';
                echo $this->knownFunds->GetFund($book["fundId"])->name;
                echo "</span>";
                echo "</span>";
                echo "</div>";

                echo "</div>";
            }
        }

        foreach ($this->bookings->ListExpenses($transaction->id) as $book)
        {
            echo '<div class="pt-2 row">';

            echo '<div class="col-sm-12">';
            echo '<span class="bg-danger rounded p-1 m-2">';
            echo '<span class="badge bg-danger text-white fw-normal font-size-lg">';
            echo $book["money"] . " zł";
            echo "</span>";
            echo '<span class="badge bg-warning text-dark fw-normal font-size-lg">';
            echo $this->knownAccounts->Get($book["accountId"])->name;
            echo "</span>";
            echo '<span class="badge bg-danger text-light fw-normal font-size-lg">';
            echo $book["description"];
            echo "</span>";
            echo "</span>";
            echo "</div>";

            echo "</div>";
        }

        echo "</div>";
        echo "</div>";

        echo '</div>';
        echo "</div>";
    }
}

?>