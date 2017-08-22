<?php
/**
 * Basic PHP diceroll function
 *
 * @param $rolls Number of dice to roll
 * @param $sides Number of sides per die
 * @return int   The sum of the dice rolled
 */
function roll($rolls, $sides) {
    $num = 0;
    while($rolls--) {
        $num += (floor(mt_rand(1, $sides)) % $sides) + 1;
    }
    return $num;
}

/**
 * Given an integer base number, this calculates the triangle number of the base.
 * Triangle numbers are the numbers on the bottom-right side of triangles formed by
 * mapping the positive integers on increasing intervals... Best to just demonstrate:
 *
 * 1 (n = 1)  1 (n = 2)   1 (n = 3)    1 (n = 4)
 * (t = 1)   2 3 (t = 3) 2 3 (t = 6)  2 3 (t = 10)
 *                      4 5 6        4 5 6
 *                                  7 8 9 10
 * @param $base
 * @return int
 */
function triNum($base) {
    return ($base * ($base + 1)) / 2;
}

/**
 * Given a dice roll (integer greater than 1)
 * @param $roll
 * @param $maxCategory
 * @return int
 */
function getCategory($roll, $maxCategory) {
    // Iterate to find the category the dice roll fits into
    for($i=1; $i <= $maxCategory; $i++) {
        if($roll <= triNum($i)) {
            return $i;
        }
    }
    // 0 means you fucked up, and the roll falls into a bin that is higher than the max categories specified.
    return 0;
}

// Default to 3 categories
$maxCategories = 3;
if(isset($_REQUEST['maxCategories']) && is_numeric($_REQUEST['maxCategories'])) {
    $maxCategories = min((int)$_REQUEST['maxCategories'], 100);
}

/**
 * The number of sides on the die to roll is equal to the triangle number corresponding to
 * the number of categories you want.
 */
$sides = triNum($maxCategories);

/**
 * Some test code to roll 10000 times and display the result.
 */
$result = [];

// Roll the results
for($i=0;$i<10000;$i++) {
    $result[getCategory(roll(1, $sides), $maxCategories)]++;
}

// Sort by category ascending
ksort($result);

// Calculate percentages for each category
$percentages = $result;
$sum = array_sum($result);
foreach($percentages as &$percentage) {
    $percentage = number_format((($percentage / $sum) * 100), 2) . "%";
}

// Display
echo "rolling a $sides sided die:\n\n";
printCategoryResults($result, $percentages);

function printCategoryResults($results, $percentages) {
    echo "<table border=2 cellpadding=2><thead><tr><td>Category</td><td># Rolled</td><td>% Rolled</td></tr></thead>";
    echo "<tbody>";
    foreach($results as $category => $result) {
        echo "<tr><td>$category</td><td>$result</td><td>{$percentages[$category]}</td></tr>";
    }
    echo "</tbody></table>";
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
    <label>
        Number of categories:
        <input type="number" value="<?php echo $maxCategories?>" name="maxCategories" />
    </label>
    <input type="submit" value="Roll Again" />
</form>