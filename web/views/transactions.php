<?php
    include_once("config.php");
    include_once("domain/persons.php");
    include_once("domain/bookings.php");
    include_once("domain/funds.php");
    include_once("domain/expenses.php");
    include_once("domain/accounts.php");
    include_once("transaction_drawer.php");
    include_once("domain/permissions.php");

    $knownPersons = new Persons($cfg);
    $knownPersons->Load();
    
    $knownAccounts = new Accounts($cfg);
    $knownAccounts->Load();

    $knownFunds = new Funds($cfg);
    $knownFunds->Load();

    $knownExpenses = new Expenses($cfg);
    $knownExpenses->Load();

    $bookings = new Bookings($cfg);
    $bookings->Load();

    $transactionsJson = file_get_contents($cfg->transactionsFileName);
    if ($transactionsJson == null)
    {
        die("<h1>Brak zarejestrowanych transakcji</h1>");
    }
    $transactions = json_decode($transactionsJson);

    $transactionDrawer = 
        new TransactionDrawer($bookings, $knownFunds, $knownExpenses, $knownPersons, $knownAccounts, $auth);

    if ($auth->HasPermission(Permission::BookTransactions))
    {
        echo "<h2>Transakcje</h2>";
        echo '<a href="#notbooked" data-bs-toggle="collapse" aria-expanded="true" aria-controls="notbooked"><h3>Nierozliczone</h3></a>';
        echo '<div id="notbooked" class="collapse show">'; // Block Nierozliczone
        
        echo '<div class="row">';
        echo '  <span class="col-sm-6"></span>';
        echo '  <span class="col-sm-2 text-center">Kwota</span>';
        echo '  <span class="col-sm-1 text-center">Do rozliczenia</span>';
        echo '  <span class="col-sm-3"></span>';
        echo '</div>';

        foreach ($transactions as $transaction) 
        {
            if ($bookings->IsConfirmed($transaction->id))
            {
                continue;
            }

            $transactionDrawer->Draw($transaction, true);
        } 
        echo '</div>'; // Block Nierozliczone
    }

    echo "<h2>Rozliczone</h2>";
  
    echo '<div id="" class="">'; // Block Rozliczone

    echo '<div class="row">';
    echo '  <span class="col-sm-6"></span>';
    echo '  <span class="col-sm-2 text-center">Kwota</span>';
    echo '  <span class="col-sm-4"></span>';
    echo '</div>';
    
    foreach ($transactions as $transaction) 
    {
        if ($bookings->IsConfirmed($transaction->id))
        {
            $transactionDrawer->Draw($transaction, false);
        }
    }

    echo '</div>'; // Block Rozliczone
    
    echo "<br/>";
    echo "<br/>";


    // book income transaction FORM
    echo '<div id="incomeBookForm" class="modal" tabindex="-1">';
    echo '<div class="modal-dialog modal-lg">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<h5 class="modal-title">Wpłata na zbiórkę</h5>';
    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    echo '</div>';
    echo '<div class="modal-body">';
        echo '<div>';
            echo "<span>Wpłacający:</span><span id='form-sender' class='tran-from'></span>";
        echo '</div>';
        echo '<div>';
            echo "<span class='tran-descr-label'>Opis:</span><span id='form-description' class='tran-descr'></span>";
        echo '</div>';
    echo '</div>';
    echo '<input type="text" class="form-control d-none" id="transactionIdHolder">';
    echo '<div class="modal-body">';
    echo '<div class="input-group mb-3">';
    echo '<select class="form-select-person form-select" id="form-selected-person" required>';
    echo '<option selected disabled value="">Osoba...</option>';
    foreach ($knownPersons->List() as $person) {
        echo '<option value="' . $person->id . '">' . $person->name . '</option>';
        echo 'console.log(' . $person->name . ')';
    }     
    echo '</select>';
    echo '<select class="form-select-fund form-select" id="form-selected-fund" required>';
    echo '<option selected disabled value="">Zbiórka...</option>';
    foreach ($knownFunds->ListActiveSorted() as $fund) {
        echo '<option value="' . $fund->id . '">' . $fund->name . '</option>';
        echo 'console.log(' . $fund->name . ')';
    }     
    echo '</select>';
    echo '<input id="form-income-money" type="text" class="form-control" aria-label="Money" required>';
    echo '<span class="input-group-text">zł</span>';
    echo '</div>';
    echo '</div>';
    echo '<div class="modal-footer">';
    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>';
    echo '<button id="saveIncomeTransactionBtn" type="button" class="btn btn-primary">Zapisz</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    // book expense transaction FORM
    echo '<div id="expenseBookForm" class="modal" tabindex="-1">';
    echo '<div class="modal-dialog modal-lg">';
    echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '   <h5 class="modal-title">Wydatek</h5>';
        echo '   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '</div>';
        echo '<div class="modal-body">';
            echo '<div>';
                echo "<span>Wpłacający:</span><span id='expenseBookForm-sender' class='tran-from'></span>";
            echo '</div>';
            echo '<div>';
                echo "<span class='tran-descr-label'>Opis:</span><span id='expenseBookForm-description' class='tran-descr'></span>";
            echo '</div>';
        echo '</div>';
        echo '<input type="text" class="form-control d-none" id="expenseBookForm-transaction" />';
        echo '<div class="modal-body">';
        echo '   <div class="input-group mb-3">';
        echo '       <select class="form-expense-expense form-select" id="expenseBookForm-expense" required>';
        echo '           <option selected disabled value="">Cel wydatku...</option>';
        foreach ($knownExpenses->ListActive() as $expense) {
            echo '<option value="' . $expense->id . '">' . $expense->name . '</option>';
            echo 'console.log(' . $expense->name . ')';
        }     
        echo '       </select>';
        echo '       <input id="expenseBookForm-money" type="text" class="form-control" aria-label="Money" required />';
        echo '       <span class="input-group-text">zł</span>';
        echo '   </div>';
        echo '   <div class="input-group mb-3">';
        echo '       <span class="input-group-text">Opis</span>';
        echo '       <input id="expenseBookForm-expense-descr" type="text" class="form-control" aria-label="Description" required />';
        echo '   </div>';
        echo '</div>';
        echo '<div class="modal-footer">';
        echo '   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>';
        echo '   <button id="expenseBookForm-save" type="button" class="btn btn-primary">Zapisz</button>';
        echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    // confirm transaction FORM
    echo '<div id="confirm-transaction-form" class="modal" tabindex="-1">';
    echo '<div class="modal-dialog modal-sm">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '  <h5 class="modal-title">Potwierdź rozliczenie transakcji</h5>';
    echo '  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    echo '</div>';
    echo '<input type="text" class="form-control d-none" id="confirm-form-transactionIdHolder">';
    echo '<div class="modal-footer">';
    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>';
    echo '<button id="accept-form-btn" type="button" class="btn btn-primary">Zatwierdź</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    
    // scripts

    echo '<script type="application/javascript">'."\n";
    echo '//<![CDATA['."\n";

    echo 'var incomeBookForm = document.getElementById("incomeBookForm");';
    echo 'var transactionIdHolder = document.getElementById("transactionIdHolder");';
    echo 'var saveIncomeTransactionButton = document.getElementById("saveIncomeTransactionBtn");';

    echo 'incomeBookForm.addEventListener("show.bs.modal", function (event) {';
        // Button that triggered the modal
        echo '  var button = event.relatedTarget;';
        echo '  var description = button.getAttribute("data-bs-descr");';
        echo '  var sender = button.getAttribute("data-bs-sender");';
        echo '  var transactionId = button.getAttribute("data-bs-transaction");';
        echo '  var money = button.getAttribute("data-bs-money");';
        // Update the modal's content.
        echo 'var descriptionField = incomeBookForm.querySelector("#form-description");';
        echo 'var senderField = incomeBookForm.querySelector("#form-sender");';
        echo 'var moneyField = incomeBookForm.querySelector("#form-income-money");';
        //echo 'var descriptionField = document.getElementById("form-description");';
        //echo 'var senderField = document.getElementById("form-sender");';
        
        echo 'descriptionField.textContent = description;';
        echo 'senderField.textContent = sender;';
        echo 'transactionIdHolder.value = transactionId;';
        echo 'moneyField.value = money;';
        echo '});';
        
    echo 'saveIncomeTransactionButton.addEventListener("click", function (event) {';
        echo '  var personField = document.getElementById("form-selected-person");';
        echo '  var fundField = document.getElementById("form-selected-fund");';
        echo '  var moneyField = document.getElementById("form-income-money");';

        echo '  var transactionId = transactionIdHolder.value;';
        echo '  var personId = personField.value;';
        echo '  var fundId = fundField.value;';
        echo '  var money = moneyField.value;';

        echo 'console.log(transactionId);';
        echo "var restresult = assign_fund_transaction_sender(transactionId, personId, fundId, money, () => {location.reload();});";
        echo 'console.log(restresult);';
        echo '});';
        

    /// Expense form
    echo 'var expenseBookForm = document.getElementById("expenseBookForm");';
    echo 'var expenseBookFormTransaction = expenseBookForm.querySelector("#expenseBookForm-transaction");';
    echo 'expenseBookForm.addEventListener("show.bs.modal", function (event) {';
        // Button that triggered the modal
        echo '  var button = event.relatedTarget;';
        echo '  var description = button.getAttribute("data-bs-descr");';
        echo '  var sender = button.getAttribute("data-bs-sender");';
        echo '  var transactionId = button.getAttribute("data-bs-transaction");';
        echo '  var money = button.getAttribute("data-bs-money");';
        // Update the modal's content.
        echo 'var descriptionField = expenseBookForm.querySelector("#expenseBookForm-description");';
        echo 'var senderField = expenseBookForm.querySelector("#expenseBookForm-sender");';
        echo 'var moneyField = expenseBookForm.querySelector("#expenseBookForm-money");';
        
        echo 'descriptionField.textContent = description;';
        echo 'senderField.textContent = sender;';
        echo 'expenseBookFormTransaction.value = transactionId;';
        echo 'moneyField.value = money;';
        echo '});';
        
    echo 'var expenseBookFormSave = document.getElementById("expenseBookForm-save");';
    echo 'expenseBookFormSave.addEventListener("click", function (event) {';
        echo '  var expense = expenseBookForm.querySelector("#expenseBookForm-expense").value;';
        echo '  var descr = expenseBookForm.querySelector("#expenseBookForm-expense-descr").value;';
        echo '  var money = expenseBookForm.querySelector("#expenseBookForm-money").value;';
        echo '  var transactionId = expenseBookFormTransaction.value;';

        echo 'console.log(transactionId);';
        echo "var restresult = assign_expense_transaction_sender(transactionId, expense, money, descr, () => {location.reload();});";
        echo 'console.log(restresult);';
        echo '});';
        


    /// commit form
    echo 'var confirmTransactionForm = document.getElementById("confirm-transaction-form");';
    echo 'var confirmFormTransactionIdHolder = document.getElementById("confirm-form-transactionIdHolder");';
    echo 'var acceptFormBtn = document.getElementById("accept-form-btn");';

    echo 'confirmTransactionForm.addEventListener("show.bs.modal", function (event) {';
        // Button that triggered the modal
        echo '  var button = event.relatedTarget;';
        echo '  var transactionId = button.getAttribute("data-bs-transaction");';
        
        echo 'confirmFormTransactionIdHolder.value = transactionId;';
        echo '});';
        
    echo 'acceptFormBtn.addEventListener("click", function (event) {';
        echo '  var transactionId = confirmFormTransactionIdHolder.value;';

        echo 'console.log(transactionId);';
        echo "var restresult = commit_transaction_sender(transactionId, () => {location.reload();});";
        echo 'console.log(restresult);';
        echo '});';

    echo "\n";
    echo '//]]>'."\n";
    echo '</script>';
  
?>