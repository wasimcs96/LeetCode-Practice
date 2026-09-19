<?php

/*
|--------------------------------------------------------------------------
| Problem: Minimize Max Distance to Gas Station (LC 774)
| Source: DS/4. BinerySearch/BinerySearch.php
| Pattern: Heap / Priority Queue
|--------------------------------------------------------------------------
*/

/*
Problem Description:
Minimise Maximum Distance between Gas Stations

Example:
Input: arr = [1,13,17,23] (gas station positions), k = 5 (stations to add)
Output: the maximum remaining gap after optimally inserting 5 stations (printed via the demo's test() call).

Why This Pattern:
Not Binary Search: the implemented optimal solution (SplPriorityQueue) repeatedly extracts and re-splits the currently-largest gap - a Heap used to drive a greedy 'always improve the worst segment' choice, the classic Heap + Greedy combination.
*/

class GasStationSolver
{

    /**
     * @param Integer[] $arr
     * @param int $k
     * @return Float
     */
    //Brute Force Approach
    public function minimiseMaxDistance(array $arr, int $k): float
    {
        $length = count($arr);
        $hashMap = array_fill(0, $length-1, 0);
        //print_r($hashMap);
        for($i=0;$i<$k;$i++){
            $maxSectionInd = -1;
            $maxSectionLength = -1.0;
            for($j=0;$j<$length-1;$j++){
                $diff = $arr[$j+1] - $arr[$j];
                $sectionLength = $diff / ($hashMap[$j] + 1);

                if($sectionLength > $maxSectionLength){
                    $maxSectionLength = $sectionLength;
                    $maxSectionInd = $j;
                }
            }
            $hashMap[$maxSectionInd]++;
        }
        $maxDistance = -1;
        for($j=0;$j<$length-1;$j++){
            $sectionDistance = $arr[$j+1] - $arr[$j];
            $distanceBetweenStation = $sectionDistance / ($hashMap[$j] + 1);

            $maxDistance = max($distanceBetweenStation, $maxDistance);
        }

        return $maxDistance;

    }

    //Optimal
    public static function minimiseMaxDistanceV2(array $arr, int $k): float
    {
        $n = count($arr);

        // Number of extra gas stations added in each segment
        $howMany = array_fill(0, $n - 1, 0);

        // Max Heap
        $pq = new SplPriorityQueue();

        // Extract both priority and data
        $pq->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

        // Insert all initial segments
        for ($i = 0; $i < $n - 1; $i++) {
            $distance = $arr[$i + 1] - $arr[$i];

            // data = index, priority = section length
            $pq->insert($i, $distance);
        }
        //print_r($pq);
        // Place k gas stations
        for ($station = 0; $station < $k; $station++) {

            // Largest segment
            $top = $pq->extract();

            $idx = $top['data'];

            // Add one station in this segment
            $howMany[$idx]++;

            $totalDistance = $arr[$idx + 1] - $arr[$idx];
            
            $newLength = floatval($totalDistance) / floatval($howMany[$idx] + 1);

            // Push updated segment back into heap
            
            $pq->insert($idx, $newLength);
        }
        //print_r($pq);

        // Top of heap contains the maximum remaining section length
        return $pq->current()['priority'];
    }

    public function test(array $arr, int $k): float{
        $length = count($arr);
        $howMany = array_fill(0,$length,0);

        $pq = new SplPriorityQueue();
        $pq->SetExtractFlags(3);

        for($i=0;$i<$length-1;$i++){
            $distance = $arr[$i+1] - $arr[$i];
            $pq->insert($i, $distance);
        }
        for($i=0;$i<$k;$i++){
            $top = $pq->extract();
            $idx = $top['data'];

            $howMany[$idx]++;
            $distance = $arr[$idx+1] - $arr[$idx];
            $newLength = floatval($distance) / floatval($howMany[$idx]+1);

            $pq->insert($idx, $newLength);
        }
        return $pq->current()['priority'];
    }


}

//Example usage
$arr = [1,13,17,23];
$k = 5;

$solver = new GasStationSolver();
$ans = $solver->test($arr, $k);

echo "The answer is: " . $ans . PHP_EOL;


/*
|--------------------------------------------------------------------------
| Problem: Sort Characters by Frequency (LC 451)
| Source: DS/5. String/String_enhancement.php
| Pattern: Heap / Priority Queue
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: given a string, rearrange it so characters are ordered by
       DECREASING frequency (ties can be broken arbitrarily, though this
       implementation breaks ties alphabetically for determinism).
 Why it exists: introduces the "invert a frequency map into a
   count-to-characters map" technique, a stepping stone toward true
   Bucket Sort and toward heap-based Top-K-Frequent-style problems.


 --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
 Asked at      : Amazon, Google, Bloomberg -- a moderately common medium testing the 'invert a frequency map into buckets' technique.
 Constraints   : 1 <= s.length <= 5*10^5 -> O(n + k log k) where k is the distinct-character count expected (effectively O(n) for a bounded alphabet); avoid re-scanning the full string more than a constant number of times.
 Time-boxing   : Total ~10 min: 2 min restate, 8 min count-then-invert-then-rebuild + dry run showing the frequency-to-characters inversion clearly.
 60-Sec Pitch  : "I count character frequencies, then INVERT that map so each distinct count maps to a list of characters sharing it, sort those counts descending, and rebuild the answer tier by tier, breaking ties alphabetically within each tier."

Example:
Input: s="tree"
Output: "eert" (or "eetr" - either is a valid LC451 answer)

Why This Pattern:
Ordering characters by descending frequency is the same problem a max-heap solves directly (extract the most frequent remaining character repeatedly); this implementation reaches the same ordering via bucket-sort-by-count instead of an explicit heap.
*/

function frequencySort(string $s): string
{
    $n = strlen($s);
    if ($n <= 1) return $s;   // Already trivially "sorted" for 0 or 1 characters

    // Step 1: count frequency of every character
    $frequencyMap = [];
    foreach (str_split($s) as $char) {
        $frequencyMap[$char] = ($frequencyMap[$char] ?? 0) + 1;
    }

    // Step 2: invert the map -- count -> list of characters with that count
    $countToChars = [];
    foreach ($frequencyMap as $char => $count) {
        $countToChars[$count][] = $char;
    }

    krsort($countToChars);   // Highest frequency first

    // Step 3: rebuild the answer, highest frequency tier first, alphabetical within a tier
    $answer = '';
    foreach ($countToChars as $count => $chars) {
        sort($chars);   // Deterministic tie-breaking within the same frequency tier
        foreach ($chars as $char) {
            $answer .= str_repeat($char, $count);
        }
    }

    return $answer;
}

