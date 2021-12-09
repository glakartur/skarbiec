<?php 
    include_once("config.php");
    include_once("domain/permissions.php");
    include_once("domain/bookings.php");
    include_once("domain/funds.php");
    include_once("domain/accounts.php");
    include_once("domain/transfers.php");
    include_once("domain/persons.php");

    $knownFunds = new Funds($cfg);
    $knownFunds->Load();
    
    $bookings = new Bookings($cfg);
    $bookings->Load();
    
    $knownAccounts = new Accounts($cfg);
    $knownAccounts->Load();

    $transfers = new Transfers($cfg);
    $transfers->Load();    

    $knownPersons = new Persons($cfg);
    $knownPersons->Load();
    
    echo '<div class="row">';
    echo '  <div class="col-sm-10"><h2>Zbiórki</h2></div>';
    if ($auth->HasPermission(Permission::EditFund))
    {
        echo '  <div class="col-sm-2 text-end"><button type="button" class="btn btn-outline-dark btn-sm text-center" data-bs-toggle="modal" data-bs-target="#addFundForm"><i data-feather="plus-square"></i>Dodaj</button></div>';
    }
    echo '</div>';
    
    echo '<div class="list-group list-group-flush">';

    echo '<div class="list-group-item list-group-item-success">';
    echo '<div class="row">';
    echo '  <span class="col-sm-2"></span>';
    echo '  <span class="col-sm-2 text-center"></span>';
    echo '  <span class="col-sm-1 text-center">Składka</span>';
    echo '  <span class="col-sm-1 text-center">Oczekiwane</span>';
    echo '  <span class="col-sm-1 text-center">Zebrane</span>';
    echo '  <span class="col-sm-1 text-center">Niedopłata</span>';
    echo '  <span class="col-sm-2 text-center">Rachunek</span>';
    echo '  <span class="col-sm-3"></span>';
    echo '</div>';
    echo '</div>';

    foreach ($knownFunds->List() as $fund) 
    {
        $expected = $fund->value * $knownPersons->Count();
        $sum = $transfers->SumarizeFundIncomes($fund->id);
        $isActive = $knownFunds->IsActive($fund);

        echo '<div class="list-group-item list-group-item-action' . ($isActive ? "" : " list-group-item-light") . '">';
        echo '<div class="row">';
        echo '  <span class="col-sm-2">' . $fund->name . '</span>';
        echo '  <span class="col-sm-2">';
        if (!$isActive)
        {
            echo '      <span class="badge bg-secondary text-white fw-normal font-size-sm">Zakończona</span>';
        }
        else if ($fund->required)
        {
            echo '      <span class="badge bg-danger text-white fw-normal font-size-sm">Obowiązkowa</span>';
        }
        else
        {
            echo '      <span class="badge bg-light text-dark fw-normal font-size-sm">Dobrowolna</span>';
        }
        echo '  </span>';
        echo '  <span class="col-sm-1 text-center">' . $fund->value . ' zł</span>';
        echo '  <span class="col-sm-1 text-center">' . ($fund->required ? $expected . " zł" : "-") . '</span>';
        echo '  <span class="col-sm-1 text-center">' . $sum . ' zł</span>';
        echo '  <span class="col-sm-1 text-center">' . ($fund->required ? $sum - $expected . " zł" : "") . '</span>';
        echo '  <span class="col-sm-2 text-center">' . $knownAccounts->Get($fund->accountId)->name . '</span>';
        echo '  <span class="col-sm-1">';
        if ($isActive 
            && $auth->HasPermission(Permission::EditFund) 
            && (!$fund->required || (($sum - $expected) == 0)))
        {
            echo '<div class="text-center"><button type="button" class="btn btn-outline-dark btn-xsm" data-bs-toggle="modal" data-bs-target="#closeFundForm" ' .
                'data-bs-name="' . $fund->name . '" ' .
                'data-bs-id="' . $fund->id . '">' .
                'zakończ</button></div>';
        }
        echo '  </span>';
        echo '</div>';
        echo '</div>';
    }     
    echo '</div>';

    if ($auth->HasPermission(Permission::EditFund))
    {
        // #addFundForm
        echo '<div id="addFundForm" class="modal" tabindex="-1">';
        echo '<div class="modal-dialog modal-lg">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title">Nowa zbiórka</h5>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '</div>';
        echo '<div class="modal-body">';
        echo '  <div class="input-group mb-3">';
        echo '    <span class="input-group-text">Nazwa</span>';
        echo '    <input type="text" id="addFundForm-name" class="form-control" aria-label="Nazwa" required>';
        echo '    <span class="input-group-text">Zbiórka obowiązkowa';
        echo '      <input type="checkbox" id="addFundForm-required" class="form-check-input" aria-label="Składka obowiązkowa" required>';
        echo '    </span>';
        echo '  </div>';
    
        echo '  <div class="input-group mb-3">';
        echo '    <span class="input-group-text">Kwota</span>';
        echo '    <input type="text" id="addFundForm-money" class="form-control" aria-label="Kwota" required>';
        echo '    <span class="input-group-text">Rachunek</span>';
        echo '    <select id="addFundForm-account" class="form-select-account form-select" required>';
        echo '      <option selected disabled value="">Rachunek...</option>';
        foreach ($knownAccounts->ListActiveOrdered() as $account) 
        {
            echo '      <option value="' . $account->id . '">' . $account->name . '</option>';
        }     
        echo '    </select>';
        echo '  </div>';

        echo '  <div class="input-group mb-3">';
        echo '    <span class="input-group-text">Opis kwoty</span>';
        echo '    <input type="text" id="addFundForm-description" class="form-control" aria-label="Opis kwoty" required>';
        echo '  </div>';

        echo '</div>';
        echo '<div class="modal-footer">';
        echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>';
        echo '<button id="addFundForm-commit" type="button" class="btn btn-primary">Zatwierdź</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        // #closeFundForm
        echo '<div id="closeFundForm" class="modal" tabindex="-1">';
        echo '  <div class="modal-dialog modal-lg">';
        echo '    <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '          <h5 class="modal-title">Zamknięcie zbiórki</h5>';
        echo '          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '      <div class="modal-body">';
        echo '          Czy potwierdzasz zamknięcie zbiórki <span id="closeFundForm-name"></span>?';
        echo '          <input type="text" class="form-control d-none" id="closeFundForm-fundId">';
        echo '      </div>';
        echo '      <div class="modal-footer">';
        echo '          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>';
        echo '          <button id="closeFundForm-commit" type="button" class="btn btn-primary">Zamknij</button>';
        echo '      </div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';


        echo '<script type="application/javascript">'."\n";
        echo '  activate_incomes_forms();'."\n";
        echo '</script>';


        /*

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
        */
    }
?>
