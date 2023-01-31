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

$topElves = array_slice($expedition->elves, 0, 3);

$totalCalories = 0;
foreach($topElves as $elf) {
    $totalCalories += $elf->getCalories();
}

$end = microtime(true) - $start;

?>

<h3>Part 1:</h3>
We should ask Elf #<?=$topElves[0]->id?> who currently has the most calories to spare at <?=$topElves[0]->getCalories()?><br />
<br />
<h3>Part 2:</h3>
Top 3 elves:
<table>
    <thead>
    <tr>
        <th>Elf</th>
        <th>Calories</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($topElves as $elf): ?>
    <tr>
        <td><?=$elf->id?></td>
        <td><?=$elf->getCalories()?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td></td>
        <td><?=$totalCalories?></td>
    </tr>
    </tbody>
</table>
<br />

Time Took: <?=$end?>ms
