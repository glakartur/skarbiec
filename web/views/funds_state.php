<?php 
    // require_once('config.php');
    // $cfg = new Config();
    
    include_once("config.php");
    include_once("domain/persons.php");
    include_once("domain/bookings.php");
    include_once("domain/funds.php");
    include_once("domain/permissions.php");
    
    $knownPersons = new Persons($cfg);
    $knownPersons->Load();
    
    $knownFunds = new Funds($cfg);
    $knownFunds->Load();
    
    $bookings = new Bookings($cfg);
    $bookings->Load();
    
    // $personsJson = file_get_contents($cfg->personsFileName);
    // $persons = json_decode($personsJson);
    // usort($persons, fn($a, $b) => strcmp($a->name, $b->name));
    
    // $fundsJson = file_get_contents($cfg->fundsFileName);
    // $funds = json_decode($fundsJson);
    // usort($funds, fn($a, $b) => strcmp($a->name, $b->name));

    
    $privPersons = [];
    foreach ($knownPersons->List() as $person)
    {
        if ($auth->HasPermission(Permission::AllStudents)
                || $auth->HasPermission(Permission::StudentPrefix . $person->id))
            array_push($privPersons, $person->id);
    }

    $privFunds = [];
    $visibleFunds = [];
    $showAllPersons = false;
    foreach ($knownFunds->List() as $fund) 
    {
        if (!empty($privPersons))
            array_push($visibleFunds, $fund);

        if ($auth->HasPermission(Permission::AllFundsState)
                || $auth->HasPermission(Permission::FundStatePrefix . $fund->id))
        {
            if (!in_array($fund, $visibleFunds))
                array_push($visibleFunds, $fund);
            array_push($privFunds, $fund->id);
            $showAllPersons = true;
        }
    }


    echo "<h2>Stan wpłat</h2>" . "\n";

    echo "<table class='table table-striped'>" . "\n";
    
    echo "<thead><tr><th scope='col'></th>" . "\n";
    foreach ($visibleFunds as $fund) 
    {
        echo "<th scope='col' class='text-center'><div>$fund->name</div>" . "\n";
        echo "<div>$fund->description</div>" . "\n";

        if (!$knownFunds->IsActive($fund))
        {
            echo '      <div class="badge bg-secondary text-white fw-normal font-size-sm">Zakończona</div>' . "\n";
        }
        else if ($fund->required)
        {
            echo '      <div class="badge bg-danger text-white fw-normal font-size-sm">Obowiązkowa</div>' . "\n";
        }
        else
        {
            echo '      <div class="badge bg-light text-dark fw-normal font-size-sm">Dobrowolna</div>' . "\n";
        }

        echo "</th>" . "\n";

    }         
    echo "</tr></thead>" . "\n";

    $sums = [];

    echo "<tbody>" . "\n";

    foreach ($knownPersons->List() as $person)
    {
        if (!$showAllPersons
                && ! in_array($person->id, $privPersons))
            continue;

        echo "<tr><th scope='row'>$person->name</th>" . "\n";
        foreach ($visibleFunds as $fund) 
        {
            $money = $bookings->SumarizePersonFund($person->id, $fund->id);

            $sums[$fund->id] += $money;

            if (in_array($fund->id, $privFunds) || in_array($person->id, $privPersons))
            {
                if ($money == 0)
                    echo "<td></td>" . "\n";
                else 
                    echo "<td class='text-center'>$money zł</td>" . "\n";
            }
            else
            {
                echo "<td></td>" . "\n";
            }
        }         
        echo "</tr>" . "\n";
    }
    
    echo "</tbody>" . "\n";


    // TODO: show complete sumf for filtered records
    if ($auth->HasPermission(Permission::AllFundsState))
    {
        // SUMS
        echo "<thead><tr><th scope='col'></th>" . "\n";

        foreach ($knownFunds->List() as $fund) 
        {
            if (!$auth->HasPermission(Permission::AllFundsState)
                    & !$auth->HasPermission(Permission::FundStatePrefix . $fund->id))
                continue;

            $money = $sums[$fund->id];

            if ($money == 0)
                echo "<th></th>" . "\n";
            else 
                echo "<th scope='col' class='text-center'>" . $money . " zł</th>" . "\n";

        }         

        echo "</tr></thead>" . "\n";
    }

    echo "</table>" . "\n";

?>
