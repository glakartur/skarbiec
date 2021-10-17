<?php
    include_once("config.php");
    include_once("domain/permissions.php");
    include_once("domain/accounts.php");
    include_once("domain/transfers.php");
    include_once("domain/funds.php");
    include_once("domain/expenses.php");

    $knownAccounts = new Accounts($cfg);
    $knownAccounts->Load();

    $transfers = new Transfers($cfg);
    $transfers->Load();

    $funds = new Funds($cfg);
    $funds->Load();

    $expenses = new Expenses($cfg);
    $expenses->Load();


    echo '<div class="row">';
    echo '  <div class="col-sm-10"><h2>Rachunki</h2></div>';
    if ($auth->HasPermission(Permission::EditAccount))
    {
        echo '  <div class="col-sm-2 text-end"><button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addAccountForm"><i data-feather="plus-square"></i>Dodaj rachunek</button></div>';
    }
    echo '</div>';
    
    echo '<div class="list-group list-group-flush">';

    echo '<div class="list-group-item list-group-item-warning">';
    echo '<div class="row">';
    echo '  <span class="col-sm-3"></span>';
    echo '  <span class="col-sm-1"></span>';
    echo '  <span class="col-sm-2 text-center">Stan</span>';
    echo '  <span class="col-sm-1 text-center"></span>';
    echo '  <span class="col-sm-1 text-center">Wpływy</span>';
    echo '  <span class="col-sm-1 text-center">Wydatki</span>';
    echo '  <span class="col-sm-3"></span>';
    echo '</div>';
    echo '</div>';
    
    foreach ($knownAccounts->List() as $account) 
    {
        $isActive = $knownAccounts->IsActive($account);

        echo '<div class="list-group-item list-group-item-action' . ($isActive ? "" : " list-group-item-light") . '">';
        echo '<div class="row">';
        echo '  <span class="col-sm-3">' . $account->name . '</span>';
        echo '  <span class="col-sm-1">';
        if (!$isActive)
        {
            echo '      <span class="badge bg-secondary text-white fw-normal font-size-sm">Zamknięty</span>';
        }
        echo '  </span>';
        echo '  <span class="col-sm-2 text-center">' . $transfers->Sumarize($account->id) . ' zł</span>';
        echo '  <span class="col-sm-1 text-center"></span>';
        echo '  <span class="col-sm-1 text-center">' . $transfers->SumarizeAccountFunds($account->id) . ' zł</span>';
        echo '  <span class="col-sm-1 text-center">' . $transfers->SumarizeAccountExpenses($account->id) . ' zł</span>';
        echo '  <span class="col-sm-3">';
        $activeFunds = $funds->ListActiveForAccount($account->id);
        if (!empty($activeFunds))
        {
            echo "<span>Aktywne zbiórki:</span><ul>";
            foreach ($activeFunds as $fund)
            {
                echo "<li class='active-fund'>{$fund->name}</li>";
            }
            echo "</ul>";
        }
        $activeExpenses = $expenses->ListActiveForAccount($account->id);
        if (!empty($activeExpenses))
        {
            echo "<span>Aktywne cele wydatkowe:</span><ul>";
            foreach ($activeExpenses as $expense)
            {
                echo "<li class='active-expense'>{$expense->name}</li>";
            }
            echo "</ul>";
        }

        if ($isActive && empty($activeFunds) && empty($activeExpenses) && ($transfers->Sumarize($account->id) == 0) 
            && $auth->HasPermission(Permission::EditAccount))
        {
            echo '<div class="text-center"><button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#closeAccountForm" ' .
            'data-bs-name="' . $account->name . '" ' .
            'data-bs-id="' . $account->id . '">' .
            'zamknij</button></div>';
        }
        echo '  </span>';
        echo '</div>';
        echo '</div>';
    }     
    echo '</div>';

    echo "<h2>Historia</h2>";
    




    if ($auth->HasPermission(Permission::EditAccount))
    {
        // #addAccountForm
        echo '<div id="addAccountForm" class="modal" tabindex="-1">';
        echo '<div class="modal-dialog modal-sm">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title">Nowy rachunek</h5>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '</div>';
        echo '<div class="modal-body">';
        echo '<div class="form-floating">';
        echo '  <input type="text" id="addAccountForm-name" class="form-control" aria-label="Nazwa" required>';
        echo '  <label for="floatingInput">Nazwa rachunku</label>';
        echo '</div>';
        echo '</div>';
        echo '<div class="modal-footer">';
        echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>';
        echo '<button id="addAccountForm-commit" type="button" class="btn btn-primary">Zapisz</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

    
        // scripts

        echo '<script type="application/javascript">'."\n";
        echo '//<![CDATA['."\n";

        echo 'var addAccountForm = document.getElementById("addAccountForm");';
        echo 'var addAccountFormCommit = document.getElementById("addAccountForm-commit");';

        echo 'addAccountFormCommit.addEventListener("click", function (event) {';
            echo '  var nameField = document.getElementById("addAccountForm-name");';
            echo '  var name = nameField.value;';

            echo "var restresult = rest_add_account(name, () => {location.reload();});";
            echo 'console.log(restresult);';
            echo '});';
            
        echo "\n";
        echo '//]]>'."\n";
        echo '</script>';


        // #closeAccountForm
        echo '<div id="closeAccountForm" class="modal" tabindex="-1">';
        echo '  <div class="modal-dialog modal-lg">';
        echo '    <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '          <h5 class="modal-title">Zamknięcie rachunku</h5>';
        echo '          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '      <div class="modal-body">';
        echo '          Czy potwierdzasz zamknięcie rachunku <span id="closeAccountForm-name"></span>?';
        echo '          <input type="text" class="form-control d-none" id="closeAccountForm-id">';
        echo '      </div>';
        echo '      <div class="modal-footer">';
        echo '          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>';
        echo '          <button id="closeAccountForm-commit" type="button" class="btn btn-primary">Zamknij</button>';
        echo '      </div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';


        echo '<script type="application/javascript">'."\n";
        echo '  activate_accounts_forms();'."\n";
        echo '</script>';        
    }
  
?>