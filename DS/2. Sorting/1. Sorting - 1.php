<?php

// ============================================================
// SORTING ALGORITHMS — Complete Revision Guide
// Topics : Selection Sort | Bubble Sort | Insertion Sort
//          Merge Sort     | Quick Sort
// ============================================================
// Intuition behind sorting:
//   - Move elements to their correct positions one by one
//   - Each algorithm uses a different strategy to "find" that position
// ============================================================


// ============================================================
// 1. SELECTION SORT
// ============================================================
// Intuition:
//   In every pass, SELECT the smallest element from the unsorted
//   portion and PLACE it at the beginning of that portion.
//   After i passes, the first i elements are sorted.
//
// Example:  [4, 2, 0, 6, 1]
//   Pass 0:  min=0 at index 2 → swap with index 0 → [0, 2, 4, 6, 1]
//   Pass 1:  min=1 at index 4 → swap with index 1 → [0, 1, 4, 6, 2]
//   Pass 2:  min=2 at index 4 → swap with index 2 → [0, 1, 2, 6, 4]
//   Pass 3:  min=4 at index 4 → swap with index 3 → [0, 1, 2, 4, 6]
//
// TC: O(N²)  — always, regardless of input (no early exit possible)
// SC: O(1)   — in-place, only a few variables used
// Best use : Small arrays; minimises the number of SWAPS (at most N-1 swaps)

function selectionSort(array &$array): void
{
    $n = count($array);

    // Outer loop: position to fill (sorted boundary moves right)
    for ($i = 0; $i < $n - 1; $i++) {

        $minIndex = $i; // Assume current position holds the minimum

        // Inner loop: find the real minimum in the unsorted portion [i+1 .. n-1]
        for ($j = $i + 1; $j < $n; $j++) {
            if ($array[$j] < $array[$minIndex]) {
                $minIndex = $j; // Found a smaller element, update index
            }
        }

        // Swap only if a smaller element was found (avoids unnecessary swap)
        if ($minIndex !== $i) {
            [$array[$i], $array[$minIndex]] = [$array[$minIndex], $array[$i]];
        }
    }
}

// --- Run ---
$arr = [4, 2, 0, 6, 1];
selectionSort($arr);
echo "Selection Sort:  ";
print_r($arr);
// Output: [0, 1, 2, 4, 6]


// ============================================================
// 2. BUBBLE SORT (Iterative + Early-Exit Optimisation)
// ============================================================
// Intuition:
//   In every pass, compare adjacent pairs and BUBBLE the largest
//   element to the end of the unsorted portion.
//   After i passes, the last i elements are in their final place.
//
// Optimisation:
//   If no swap happened in a full pass → array is already sorted.
//   Break early → best case O(N) for an already-sorted array.
//
// Dry Run: [4, 2, 0, 6, 1]
//   Pass 1 (i=1): compare pairs 0-1,1-2,2-3,3-4
//     (4,2)→swap [2,4,0,6,1], (4,0)→swap [2,0,4,6,1],
//     (4,6)→ok,  (6,1)→swap [2,0,4,1,6]   swapped=true
//   Pass 2 (i=2): (2,0)→swap,(2,4)→ok,(4,1)→swap → [0,2,1,4,6] swapped=true
//   Pass 3 (i=3): (0,2)→ok,(2,1)→swap → [0,1,2,4,6] swapped=true
//   Pass 4 (i=4): (0,1)→ok  swapped=false → BREAK
//   Result: [0, 1, 2, 4, 6]
//
// TC: O(N²) worst/average  |  O(N) best (already sorted)
// SC: O(1)   — in-place

function bubbleSort(array &$array): void
{
    $n = count($array);

    // Outer loop: after each pass, the last $i elements are sorted
    for ($i = 1; $i < $n; $i++) {
        $swapped = false;

        // Inner loop: bubble the largest in [0 .. n-i] to position n-i
        for ($j = 0; $j < $n - $i; $j++) {
            if ($array[$j] > $array[$j + 1]) {
                [$array[$j], $array[$j + 1]] = [$array[$j + 1], $array[$j]];
                $swapped = true;
            }
        }

        // Early exit: if no swap occurred, the array is already sorted
        if (!$swapped) {
            break;
        }
    }
}

// --- Run ---
$arr = [4, 2, 0, 6, 1];
bubbleSort($arr);
echo "Bubble Sort:     ";
print_r($arr);
// Output: [0, 1, 2, 4, 6]


// ============================================================
// 2b. BUBBLE SORT (Recursive Version)
// ============================================================
// Intuition:
//   Replace the outer for-loop with recursion.
//   Each recursive call handles one pass (bubbles max to end),
//   then recurses on the remaining subarray of size $l - 1.
//   Base case: if size ≤ 1 OR no swap happened, stop.
//
// Dry Run: [4, 2, 0, 6, 1]  ($l = 4, last valid index)
//   Call  1 ($l=4): bubbles 6 to index 4  → [2,0,4,1,6] swapped=true
//   Call  2 ($l=3): bubbles 4 to index 3  → [0,2,1,4,6] swapped=true
//   Call  3 ($l=2): bubbles 2 to index 2  → [0,1,2,4,6] swapped=true
//   Call  4 ($l=1): size≤1 → return
//   Result: [0, 1, 2, 4, 6]
//
// TC: O(N²) worst  |  O(N) best (already sorted, $swap turns false immediately)
// SC: O(N) — recursion call stack

function recursiveBubbleSort(array &$arr, int $l, bool $swap): void
{
    // Base case: single element OR no swaps in last pass → sorted
    if ($l <= 1 || !$swap) {
        return;
    }

    $swapped = false;

    // One pass: bubble the largest element in [0 .. $l] to position $l
    for ($i = 0; $i < $l; $i++) {
        if ($arr[$i] > $arr[$i + 1]) {
            [$arr[$i], $arr[$i + 1]] = [$arr[$i + 1], $arr[$i]];
            $swapped = true;
        }
    }

    // Recurse on the subarray [0 .. $l-1] (last position is now final)
    recursiveBubbleSort($arr, $l - 1, $swapped);
}

// --- Run ---
$nums = [4, 2, 0, 6, 1];
recursiveBubbleSort($nums, count($nums) - 1, true);
echo "Recursive Bubble Sort: ";
print_r($nums);
// Output: [0, 1, 2, 4, 6]


// ============================================================
// 3. INSERTION SORT (Iterative)
// ============================================================
// Intuition:
//   Think of sorting a hand of cards.
//   Pick one card at a time and INSERT it into its correct
//   position among the already-sorted cards on the left.
//
// Dry Run: [4, 2, 0, 6, 1]
//   i=1: key=2, shift 4 right → [4,4,0,6,1] → insert → [2,4,0,6,1]
//   i=2: key=0, shift 4,2 right → insert → [0,2,4,6,1]
//   i=3: key=6, 4<6 no shift   → [0,2,4,6,1]
//   i=4: key=1, shift 6,4,2 right → insert → [0,1,2,4,6]
//   Result: [0, 1, 2, 4, 6]
//
// TC: O(N²) worst/average  |  O(N) best (already sorted, inner while never runs)
// SC: O(1)   — in-place

function insertionSort(array &$array): void
{
    $n = count($array);

    // Outer loop: $i is the boundary; [0 .. i-1] is always sorted
    for ($i = 1; $i < $n; $i++) {
        $j = $i;

        // Shift elements of the sorted portion that are greater than array[$j]
        // one position to the right, making room for array[$j]
        while ($j > 0 && $array[$j - 1] > $array[$j]) {
            [$array[$j], $array[$j - 1]] = [$array[$j - 1], $array[$j]];
            $j--;
        }
        // array[$j] is now at its correct position in the sorted portion
    }
}

// --- Run ---
$arr = [4, 2, 0, 6, 1];
insertionSort($arr);
echo "Insertion Sort:  ";
print_r($arr);
// Output: [0, 1, 2, 4, 6]


// ============================================================
// 3b. INSERTION SORT (Recursive Version)
// ============================================================
// Intuition:
//   Replace the outer for-loop with recursion.
//   insertionSortRec($arr, i, n):
//     - Base case: i == n → all elements processed, done.
//     - Sort first i elements (already done in previous call).
//     - Insert element at position i into [0 .. i-1] using
//       the inner while-loop logic.
//     - Recurse for i+1.
//
// TC: O(N²) worst  |  O(N) best
// SC: O(N) — recursion stack

function insertionSortRec(array &$array, int $i, int $n): void
{
    // Base case: all positions processed
    if ($i === $n) {
        return;
    }

    $j = $i;

    // Insert array[$i] into its correct position in sorted [0 .. i-1]
    while ($j > 0 && $array[$j - 1] > $array[$j]) {
        [$array[$j], $array[$j - 1]] = [$array[$j - 1], $array[$j]];
        $j--;
    }

    // Move to the next element
    insertionSortRec($array, $i + 1, $n);
}

// --- Run ---
$nums = [4, 2, 0, 6, 1];
insertionSortRec($nums, 1, count($nums));
echo "Recursive Insertion Sort: ";
print_r($nums);
// Output: [0, 1, 2, 4, 6]


// ============================================================
// 4. MERGE SORT (Divide and Conquer)
// ============================================================
// Intuition:
//   DIVIDE the array into two halves recursively until each
//   subarray has only 1 element (trivially sorted).
//   Then MERGE the sorted halves back together in order.
//
// Dry Run: [4, 2, 0, 6, 1]
//
//   Divide phase:
//     [4,2,0,6,1]
//       ├── [4,2]       mid = 1
//       │     ├── [4]   (base)
//       │     └── [2]   (base)
//       └── [0,6,1]     mid = 3
//             ├── [0]   (base)
//             └── [6,1]
//                   ├── [6]  (base)
//                   └── [1]  (base)
//
//   Merge phase (bottom-up):
//     merge [4] + [2]     → [2, 4]
//     merge [6] + [1]     → [1, 6]
//     merge [0] + [1,6]   → [0, 1, 6]
//     merge [2,4] + [0,1,6] → [0, 1, 2, 4, 6]  ✓
//
// TC: O(N log N) — always (log N levels, each level does O(N) merge work)
// SC: O(N) — temporary array used during merge

function mergeSort(array &$arr, int $left, int $right): void
{
    // Base case: subarray of size 0 or 1 is already sorted
    if ($left >= $right) {
        return;
    }

    // Find the middle index (avoids integer overflow vs ($left+$right)/2)
    $mid = $left + intdiv($right - $left, 2);

    mergeSort($arr, $left, $mid);       // Sort left  half [left  .. mid]
    mergeSort($arr, $mid + 1, $right);  // Sort right half [mid+1 .. right]

    mergeSortedHalves($arr, $left, $mid, $right); // Merge the two sorted halves
}

function mergeSortedHalves(array &$arr, int $l, int $mid, int $r): void
{
    $left  = $l;        // Pointer for the left  half [l   .. mid]
    $right = $mid + 1;  // Pointer for the right half [mid+1 .. r]
    $temp  = [];        // Temporary array to hold merged result

    // Compare elements from both halves and pick the smaller one
    while ($left <= $mid && $right <= $r) {
        if ($arr[$left] <= $arr[$right]) {
            $temp[] = $arr[$left++];  // Left element is smaller (or equal), take it
        } else {
            $temp[] = $arr[$right++]; // Right element is smaller, take it
        }
    }

    // Copy any remaining elements from the left half
    while ($left <= $mid) {
        $temp[] = $arr[$left++];
    }

    // Copy any remaining elements from the right half
    while ($right <= $r) {
        $temp[] = $arr[$right++];
    }

    // Write merged result back into the original array segment [l .. r]
    for ($i = $l; $i <= $r; $i++) {
        $arr[$i] = $temp[$i - $l];
    }
}

// --- Run ---
$arr = [4, 2, 0, 6, 1];
mergeSort($arr, 0, count($arr) - 1);
echo "Merge Sort:      ";
print_r($arr);
// Output: [0, 1, 2, 4, 6]


// ============================================================
// 5. QUICK SORT (Divide and Conquer)
// ============================================================
// Intuition:
//   Choose a PIVOT element. Rearrange the array so that:
//     - All elements < pivot are on its LEFT
//     - All elements > pivot are on its RIGHT
//   The pivot is now at its FINAL sorted position.
//   Recursively apply the same logic to left and right halves.
//
// Pivot strategy used: LAST element as pivot (Lomuto partition)
//
// Dry Run: [4, 2, 0, 6, 1]  pivot = 1
//   partition: i=-1
//     j=0: arr[0]=4 > 1 → skip
//     j=1: arr[1]=2 > 1 → skip
//     j=2: arr[2]=0 < 1 → i=0, swap arr[0] & arr[2] → [0,2,4,6,1]
//     j=3: arr[3]=6 > 1 → skip
//   Final swap: arr[i+1] & pivot  → [0,2,4,6,1] → swap index 1 & 4 → [0,1,4,6,2]
//   Wait — let me redo:  arr=[4,2,0,6,1], pivot=arr[4]=1, i=-1
//     j=0: 4>1 skip; j=1: 2>1 skip; j=2: 0<1 → i=0, swap(arr[0],arr[2]) → [0,2,4,6,1]
//     j=3: 6>1 skip
//   Place pivot: swap(arr[i+1], arr[r]) → swap(arr[1], arr[4]) → [0,1,4,6,2]
//   pivot index = 1
//   Left  [0]     → already sorted (size 1)
//   Right [4,6,2] → pivot=2, sort → [2,4,6]
//   Final: [0, 1, 2, 4, 6]  ✓
//
// TC: O(N log N) average  |  O(N²) worst (sorted array with last-element pivot)
// SC: O(log N) average recursion stack  |  O(N) worst

function quickSort(array &$arr, int $low, int $high): void
{
    if ($low >= $high) {
        return; // Base case: 0 or 1 element — already sorted
    }

    // Partition: place pivot at its correct position and get its index
    $pivotIndex = partition($arr, $low, $high);

    quickSort($arr, $low, $pivotIndex - 1);  // Sort left  of pivot
    quickSort($arr, $pivotIndex + 1, $high); // Sort right of pivot
}

function partition(array &$arr, int $low, int $high): int
{
    $pivot = $arr[$high]; // Choose last element as pivot
    $i     = $low - 1;   // $i tracks the boundary of elements < pivot

    // Walk through [low .. high-1]; move elements smaller than pivot to the left
    for ($j = $low; $j < $high; $j++) {
        if ($arr[$j] <= $pivot) {
            $i++;
            [$arr[$i], $arr[$j]] = [$arr[$j], $arr[$i]]; // Swap smaller element to left region
        }
    }

    // Place pivot at its correct sorted position (right after all smaller elements)
    [$arr[$i + 1], $arr[$high]] = [$arr[$high], $arr[$i + 1]];

    return $i + 1; // Return the pivot's final index
}

// --- Run ---
$arr = [4, 2, 0, 6, 1];
quickSort($arr, 0, count($arr) - 1);
echo "Quick Sort:      ";
print_r($arr);
// Output: [0, 1, 2, 4, 6]


// ============================================================
// COMPARISON SUMMARY
// ============================================================
//
//  Algorithm     | Best     | Average  | Worst    | Space | Stable
// ---------------+----------+----------+----------+-------+-------
//  Selection     | O(N²)    | O(N²)    | O(N²)    | O(1)  | No
//  Bubble        | O(N)     | O(N²)    | O(N²)    | O(1)  | Yes
//  Insertion     | O(N)     | O(N²)    | O(N²)    | O(1)  | Yes
//  Merge         | O(NlogN) | O(NlogN) | O(NlogN) | O(N)  | Yes
//  Quick         | O(NlogN) | O(NlogN) | O(N²)    | O(logN)| No
//
//  Stable = equal elements keep their original relative order.
//  Selection Sort: best for minimising SWAPS (only N-1 swaps).
//  Insertion Sort: best for NEARLY sorted data (almost O(N)).
//  Merge Sort    : guaranteed O(NlogN), best for LINKED LISTS.
//  Quick Sort    : fastest in practice (cache-friendly), but
//                  avoid last-element pivot on sorted input.


// ============================================================
// PRACTICE PROBLEMS & APPLICATIONS
// ============================================================
//
//  EASY
//  1. Sort an array of 0s, 1s, and 2s (Dutch National Flag / LeetCode 75)
//     → Modified partition of Quick Sort
//  2. Find the Kth largest element in an array (LeetCode 215)
//     → Partial Quick Sort (QuickSelect — stop when pivot index == K)
//  3. Sort array by parity (even first, then odd) (LeetCode 905)
//     → Partition idea from Quick Sort
//
//  MEDIUM
//  4. Merge Intervals (LeetCode 56)
//     → Sort by start time, then merge overlapping intervals
//  5. Count Inversions in an array
//     → Modified Merge Sort — count right-half elements picked before left-half
//  6. Sort an array of strings by length, then lexicographically
//     → Custom comparator with usort()
//  7. Find the minimum number of swaps to sort an array
//     → Selection Sort observation — count swaps in selection sort
//
//  HARD
//  8. Largest Number formed from array elements (LeetCode 179)
//     → Custom sort: compare "$a$b" vs "$b$a"
//  9. Maximum Gap (LeetCode 164)
//     → Sort + find maximum difference between consecutive elements
// 10. Wiggle Sort II (LeetCode 324)
//     → Partial sort (median via QuickSelect) + interleaving
//
// ============================================================
// KEY PATTERNS & VARIATIONS FOR REVISION
// ============================================================
//
//  PATTERN 1 — When to use which sort:
//    • Small array (N < 20)        → Insertion Sort (low overhead)
//    • Nearly sorted array          → Insertion Sort (O(N) best case)
//    • Guarantee O(NlogN) always   → Merge Sort
//    • In-place with good avg perf → Quick Sort
//    • Minimise number of writes   → Selection Sort
//
//  PATTERN 2 — Stability matters when:
//    Sorting objects by multiple keys (e.g., sort by name, then by age).
//    Use Merge Sort or Insertion Sort (both stable).
//
//  PATTERN 3 — Merge Sort variations:
//    • Count inversions (modify merge step)
//    • External sort (data too large for RAM — merge from disk)
//    • Sort linked list (no random access → merge sort preferred)
//
//  PATTERN 4 — Quick Sort variations:
//    • 3-way Quick Sort (for arrays with many duplicates)
//    • Randomised Quick Sort (random pivot → avoids O(N²) on sorted input)
//    • QuickSelect (find Kth smallest in O(N) average)
//
// ============================================================
// IMPORTANT TIPS & EDGE CASES
// ============================================================
//
//  1. Empty array / single element:
//     All sorting functions handle this via base case ($n <= 1 or $left >= $right).
//
//  2. Already sorted array:
//     - Bubble Sort with swapped flag → O(N) ✓
//     - Insertion Sort inner while never runs → O(N) ✓
//     - Quick Sort with last-element pivot → O(N²) ✗ (use random pivot)
//
//  3. All elements same:
//     - Merge Sort handles gracefully (≤ check in merge keeps it stable)
//     - Quick Sort: O(N²) with Lomuto — use 3-way partition
//
//  4. Integer overflow in mid calculation:
//     Use  $mid = $left + intdiv($right - $left, 2)
//     NOT  $mid = intdiv($left + $right, 2)  (can overflow for large indices)
//
//  5. PHP operator precedence trap (original bug in this file):
//     (int)($l+$r)/2  is  ((int)($l+$r)) / 2  — gives a FLOAT
//     Always use intdiv() or (int)(($l+$r)/2) for clarity.
//
//  6. Use || (logical OR) not | (bitwise OR) in conditions:
//     | does not short-circuit → may evaluate the right operand even when
//     left is already true, causing off-by-one access on arrays.
//
//  7. Pass arrays by reference (&$array) in PHP:
//     Without &, PHP copies the array — changes do NOT affect the original.

?>