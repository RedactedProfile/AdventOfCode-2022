<?php
$start = microtime(true);

class Elf {
    public int $id = 0;
    private array $items = [];
    private int $calorieCount = 0;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function addItem(int $item): self {
        $this->items[] = $item;
        return $this;
    }

    public function cacheCount(): int {
        $this->calorieCount = array_sum($this->items);
        return $this->calorieCount;
    }

    public function getCalories(): int {
        return $this->calorieCount;
    }
}

class ExpeditionGroup {
    public static $LastID = 0;

    public array $elves = [];

    public function addElf(Elf $elf): self {
        $this->elves[] = $elf;
        return $this;
    }

    public function sortByCalories() {
        usort($this->elves, static function(Elf $a, Elf $b) {
            return $a->getCalories() === $b->getCalories() ? 0 : ($a->getCalories() > $b->getCalories() ? -1 : 1);
        });
    }

    public function getTopCalorieCarrier(): Elf {
        return $this->elves[array_key_first($this->elves)];
    }

    public static function CreateElf(): Elf {
        self::$LastID++;
        return new Elf(self::$LastID);
    }
}

$expedition = new ExpeditionGroup();

$handle = fopen('input.txt', 'rb');

if($handle) {
    $currentElf = ExpeditionGroup::CreateElf();

    while(($line = fgets($handle)) !== false) {
        $line = trim($line);

        if(!$line) {
            $currentElf->cacheCount();
            $expedition->addElf($currentElf);
            $currentElf = ExpeditionGroup::CreateElf();
            continue;
        }

        $currentElf->addItem((int)$line);
    }

    $currentElf->cacheCount();
    $expedition->addElf($currentElf);
}

fclose($handle);

$expedition->sortByCalories();

$elf = $expedition->getTopCalorieCarrier();

$end = microtime(true) - $start;

?>

We should ask Elf #<?=$elf->id?> who currently has the most calories to spare at <?=$elf->getCalories()?><br />
<br />

Time Took: <?=$end?>ms
