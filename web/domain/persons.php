<?php

class Persons {
    private $persons = array();
    private $cfg;

    function __construct(Config $cfg)
    {
        $this->cfg = $cfg;
    }

    public function Load()
    {
        $personsJson = file_get_contents($this->cfg->personsFileName);
        if ($personsJson == null)
        {
            return;
        }
        
        $this->persons = json_decode($personsJson);
        //usort($this->persons, fn($a, $b) => strcmp($a->name, $b->name));
    }

    public function Count()
    {
        return count($this->persons);
    }

    public function List()
    {
        return $this->persons;
    }

    public function GetPerson($id)
    {
        foreach ($this->persons as $person)
        {
            if ((int) $person->id == $id)
                return $person;
        }

        return null;
    }
}

?>