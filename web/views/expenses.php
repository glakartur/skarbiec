<?php
    include_once("config.php");
    include_once("domain/permissions.php");
    include_once("domain/accounts.php");
    include_once("domain/expenses.php");
    include_once("domain/transfers.php");

    $knownAccounts = new Accounts($cfg);
    $knownAccounts->Load();

    $transfers = new Transfers($cfg);
    $transfers->Load();

    $expenses = new Expenses($cfg);
    $expenses->Load();

    echo "<h2>Cele wydatków</h2>";
    if ($auth->HasPermission(Permission::EditExpense))
    {
        echo '<div class="row">';
        echo '<div class="col-sm-9"></div>';
        echo '<div class="col-sm-3 text-center"><button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addExpenseForm"><i data-feather="plus-square"></i>Dodaj cel wydatków</button></div>';
        echo '</div>';
    }
    
    echo '<div class="list-group list-group-flush">';

    echo '<div class="list-group-item list-group-item-danger">';
    echo '<div class="row">';
    //echo '<span class="col-sm-4"><button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addExpenseForm"><i data-feather="plus-square"></i>Dodaj</button></span>';
    //echo '  <span class="col-sm-4"><span class="badge bg-light" data-bs-toggle="modal" data-bs-target="#addExpenseForm"><i data-feather="plus-square"></i>Dodaj</span></span>';
    echo '  <span class="col-sm-4"></span>';
    echo '  <span class="col-sm-1"></span>';
    echo '  <span class="col-sm-1 text-center">Wydatki</span>';
    echo '  <span class="col-sm-2 text-center">Rachunek</span>';
    echo '  <span class="col-sm-3"></span>';
    echo '</div>';
    echo '</div>';

    foreach ($expenses->List() as $expense) 
    {
        $isActive = $expenses->IsActive($expense);

        echo '<div class="list-group-item list-group-item-action' . ($isActive ? "" : " list-group-item-light") . '">';
        echo '<div class="row">';
        echo '  <span class="col-sm-4">' . $expense->name . '</span>';
        echo '  <span class="col-sm-1">';
        if (!$isActive)
        {
            echo '      <span class="badge bg-secondary text-white fw-normal font-size-sm">Zakończony</span>';
        }
        echo '  </span>';
        echo '  <span class="col-sm-1 text-center">' . $transfers->SumarizeAccountExpenses($expense->accountId) . ' zł</span>';
        echo '  <span class="col-sm-2 text-center">' . $knownAccounts->Get($expense->accountId)->name . '</span>';

        echo '  <span class="col-sm-1">';
        if ($auth->HasPermission(Permission::EditExpense) && $expenses->IsActive($expense))
        {
            echo '<div class="text-center"><button type="button" class="btn btn-outline-dark btn-xsm" data-bs-toggle="modal" data-bs-target="#closeExpenseForm" ' .
                'data-bs-name="' . $expense->name . '" ' .
                'data-bs-id="' . $expense->id . '">' .
                'zamknij</button></div>';
        }
        echo '  </span>';
        echo '</div>';
        echo '</div>';
    }     
    echo '</div>';

    echo "<h2>Historia</h2>";

    if ($auth->HasPermission(Permission::EditExpense))
    {
        // #addExpenseForm
        echo '<div id="addExpenseForm" class="modal" tabindex="-1">';
        echo '<div class="modal-dialog modal-lg">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title">Nowy cel wydatków</h5>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '</div>';
        echo '<div class="modal-body">';
        echo '  <div class="input-group mb-3">';
        echo '    <span class="input-group-text">Cel</span>';
        echo '    <input type="text" id="addExpenseForm-name" class="form-control" aria-label="Cel wydatków" required>';
        echo '    <select id="addExpenseForm-account" class="form-select-account form-select" required>';
        echo '      <option selected disabled value="">Rachunek...</option>';
        foreach ($knownAccounts->ListActiveOrdered() as $account) 
        {
            echo '      <option value="' . $account->id . '">' . $account->name . '</option>';
        }     
        echo '    </select>';
        echo '  </div>';

        echo '</div>';
        echo '<div class="modal-footer">';
        echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>';
        echo '<button id="addExpenseForm-commit" type="button" class="btn btn-primary">Zatwierdź</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

    
        // #closeExpenseForm
        echo '<div id="closeExpenseForm" class="modal" tabindex="-1">';
        echo '  <div class="modal-dialog modal-lg">';
        echo '    <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '          <h5 class="modal-title">Zamknięcie celu wydatków</h5>';
        echo '          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '      <div class="modal-body">';
        echo '          Czy potwierdzasz zamknięcie celu <span id="closeExpenseForm-name"></span>?';
        echo '          <input type="text" class="form-control d-none" id="closeExpenseForm-id">';
        echo '      </div>';
        echo '      <div class="modal-footer">';
        echo '          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>';
        echo '          <button id="closeExpenseForm-commit" type="button" class="btn btn-primary">Zamknij</button>';
        echo '      </div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';


        // scripts

        echo '<script type="application/javascript">'."\n";
        echo '//<![CDATA['."\n";

        echo 'var addExpenseForm = document.getElementById("addExpenseForm");';
        echo 'var addExpenseFormCommit = document.getElementById("addExpenseForm-commit");';

        echo 'addExpenseFormCommit.addEventListener("click", function (event) {';
            echo '  var name = document.getElementById("addExpenseForm-name").value;';
            echo '  var accountId = document.getElementById("addExpenseForm-account").value;';

            echo "var restresult = rest_add_expense(name, accountId, () => {location.reload();});";
            echo 'console.log(restresult);';
            echo '});';
            
        echo "\n";
        echo '//]]>'."\n";
        echo '</script>';

        echo '<script type="application/javascript">'."\n";
        echo '  activate_expenses_forms();'."\n";
        echo '</script>';

    }
?>