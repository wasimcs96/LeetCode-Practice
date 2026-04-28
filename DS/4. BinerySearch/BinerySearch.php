<?php

// ============================================================
// BINARY SEARCH — Advanced Problems (Revision Guide)
// Topics : First & Last Position  | Rotated Sorted Array
//          Min in Rotated Array   | Rotation Count
//          Single Element         | Binary Search on Answers
//          Ship Packages          | Aggressive Cows
//          Book Allocation        | Split Array | Row with Max 1s
// ============================================================
// Core Binary Search Idea:
//   Eliminate HALF the search space at each step.
//   Precondition: the search space must be MONOTONIC.
//   In "search on answer" problems the search space is the
//   *range of possible answer values*, not the array indices.
//
//   Template (search for minimum feasible value):
//     while ($low <= $high) {
//         $mid = $low + intdiv($high - $low, 2);
//         if (isFeasible($mid)) { $ans = $mid; $high = $mid - 1; }
//         else                  { $low  = $mid + 1; }
//     }
// ============================================================


// ============================================================
// 1. FIND FIRST AND LAST POSITION (LeetCode 34)
// ============================================================
// Intuition:
//   Run two separate binary searches:
//     First  search → when key is found, record index and search
//                     LEFT  (high = mid-1) for earlier occurrence.
//     Second search → when key is found, record index and search
//                     RIGHT (low  = mid+1) for later  occurrence.
//
// Dry Run: nums=[5,7,7,8,8,10], key=8
//
//   First Occurrence:
//     low=0, high=5, mid=2 → nums[2]=7 < 8  → low=3
//     low=3, high=5, mid=4 → nums[4]=8 == 8 → ans=4, high=3
//     low=3, high=3, mid=3 → nums[3]=8 == 8 → ans=3, high=2
//     low=3 > high=2 → return 3  ✓
//
//   Last Occurrence:
//     low=0, high=5, mid=2 → nums[2]=7 < 8  → low=3
//     low=3, high=5, mid=4 → nums[4]=8 == 8 → ans=4, low=5
//     low=5, high=5, mid=5 → nums[5]=10 > 8 → high=4
//     low=5 > high=4 → return 4  ✓
//
//   Result: [3, 4]
//
// TC: O(log N) — two binary searches, each O(log N)
// SC: O(log N) — recursive call stack depth

function findFirst(array $nums, int $low, int $high, int $key, int $ans): int
{
    // Base case: search space exhausted
    if ($low > $high) {
        return $ans;
    }

    // Safe mid: avoids integer overflow for large indices
    $mid = $low + intdiv($high - $low, 2);

    if ($nums[$mid] === $key) {
        $ans = $mid;                                       // Record this occurrence
        return findFirst($nums, $low, $mid - 1, $key, $ans); // Search LEFT for earlier
    } elseif ($nums[$mid] > $key) {
        return findFirst($nums, $low, $mid - 1, $key, $ans); // Key must be in left half
    } else {
        return findFirst($nums, $mid + 1, $high, $key, $ans); // Key must be in right half
    }
}

function findLast(array $nums, int $low, int $high, int $key, int $ans): int
{
    // Base case: search space exhausted
    if ($low > $high) {
        return $ans;
    }

    $mid = $low + intdiv($high - $low, 2);

    if ($nums[$mid] === $key) {
        $ans = $mid;                                      // Record this occurrence
        return findLast($nums, $mid + 1, $high, $key, $ans); // Search RIGHT for later
    } elseif ($nums[$mid] > $key) {
        return findLast($nums, $low, $mid - 1, $key, $ans);  // Key must be in left half
    } else {
        return findLast($nums, $mid + 1, $high, $key, $ans); // Key must be in right half
    }
}

// --- Run ---
$nums  = [5, 7, 7, 8, 8, 10];
$key   = 8;
$n     = count($nums);
$first = findFirst($nums, 0, $n - 1, $key, -1);
$last  = findLast($nums,  0, $n - 1, $key, -1);
echo "First & Last Position of $key: [$first, $last]\n"; // [3, 4]


// ============================================================
// 2. SEARCH IN ROTATED SORTED ARRAY (LeetCode 33)
// ============================================================
// Intuition:
//   A rotated sorted array always has ONE sorted half and ONE
//   rotated (unsorted) half at every binary search step.
//
//   Strategy:
//     1. Determine which half is sorted (compare nums[low] & nums[mid]).
//     2. Check if the target lies within the sorted half.
//          YES → eliminate the rotated half (search sorted side).
//          NO  → eliminate the sorted half  (search rotated side).
//
// Dry Run: nums=[4,5,6,7,0,1,2], key=0
//   low=0, high=6, mid=3 → nums[3]=7
//     Left [4..7] sorted (nums[0]=4 ≤ nums[3]=7).
//     Is 0 in [4,7]? No → search right → low=4
//   low=4, high=6, mid=5 → nums[5]=1
//     Left [0..1] sorted (nums[4]=0 ≤ nums[5]=1).
//     Is 0 in [0,1)? Yes → search left → high=4
//   low=4, high=4, mid=4 → nums[4]=0 == 0 → return 4  ✓
//
// TC: O(log N)
// SC: O(1)

function searchRotated(array $nums, int $key): int
{
    $low  = 0;
    $high = count($nums) - 1;

    while ($low <= $high) {
        $mid = $low + intdiv($high - $low, 2);

        if ($nums[$mid] === $key) {
            return $mid; // Target found
        }

        // Determine which half is sorted
        if ($nums[$low] <= $nums[$mid]) {
            // LEFT half [low .. mid] is sorted
            if ($nums[$low] <= $key && $key < $nums[$mid]) {
                $high = $mid - 1; // Target is inside the sorted left half
            } else {
                $low = $mid + 1;  // Target is in the rotated right half
            }
        } else {
            // RIGHT half [mid .. high] is sorted
            if ($nums[$mid] < $key && $key <= $nums[$high]) {
                $low = $mid + 1;  // Target is inside the sorted right half
            } else {
                $high = $mid - 1; // Target is in the rotated left half
            }
        }
    }

    return -1; // Not found
}

// --- Run ---
echo "Search Rotated [4,5,6,7,0,1,2] key=0: " . searchRotated([4,5,6,7,0,1,2], 0) . "\n"; // 4
echo "Search Rotated [4,5,6,7,0,1,2] key=3: " . searchRotated([4,5,6,7,0,1,2], 3) . "\n"; // -1
echo "Search Rotated [3,1]           key=1: " . searchRotated([3, 1], 1)           . "\n"; // 1


// ============================================================
// 3. FIND MINIMUM IN ROTATED SORTED ARRAY (LeetCode 153)
// ============================================================
// Intuition:
//   The minimum is at the "rotation point" — where the array
//   resets from a high value back to a low value.
//
//   Key observation at every step:
//     If nums[low] <= nums[high]: segment is fully sorted.
//       Minimum of this segment = nums[low]. Update & stop.
//     Else (segment is rotated):
//       If LEFT half sorted  (nums[low] <= nums[mid]):
//         Left-half minimum  = nums[low]. Update, search RIGHT.
//       Else RIGHT half sorted:
//         Right-half minimum = nums[mid]. Update, search LEFT.
//
// Dry Run: nums=[4,5,6,7,0,1,2]
//   low=0, high=6, mid=3 → nums[0]=4 > nums[6]=2 → rotated
//     Left sorted? 4 ≤ 7 yes → min=4, low=4
//   low=4, high=6, mid=5 → nums[4]=0 ≤ nums[6]=2 → fully sorted
//     min=min(4,0)=0, break
//   Result: 0  ✓
//
// TC: O(log N)
// SC: O(1)

function findMinInRotated(array $nums): int
{
    $low  = 0;
    $high = count($nums) - 1;
    $min  = PHP_INT_MAX;

    while ($low <= $high) {
        $mid = $low + intdiv($high - $low, 2);

        // Optimisation: fully sorted segment → minimum is nums[low]
        if ($nums[$low] <= $nums[$high]) {
            $min = min($min, $nums[$low]);
            break;
        }

        if ($nums[$low] <= $nums[$mid]) {
            // Left half [low..mid] is sorted; its minimum is nums[low]
            $min = min($min, $nums[$low]);
            $low = $mid + 1;   // Rotation point is in the right half
        } else {
            // Right half [mid..high] is sorted; its minimum is nums[mid]
            $min = min($min, $nums[$mid]);
            $high = $mid - 1;  // Rotation point is in the left half
        }
    }

    return $min;
}

// --- Run ---
echo "Min in Rotated [4,5,6,7,0,1,2]: " . findMinInRotated([4,5,6,7,0,1,2]) . "\n"; // 0
echo "Min in Rotated [11,13,15,17]:    " . findMinInRotated([11,13,15,17])    . "\n"; // 11


// ============================================================
// 4. FIND ROTATION COUNT
// ============================================================
// Intuition:
//   The rotation count equals the INDEX of the minimum element.
//   Example: [4,5,6,7,0,1,2] → min=0 at index 4 → rotated 4 times.
//   We extend findMinInRotated to also track the index of the
//   minimum element found so far.
//
// Dry Run: nums=[5,6,1,2,3,4]
//   low=0, high=5, mid=2 → nums[0]=5 > nums[5]=4 → rotated
//     Left sorted? 5 ≤ 1? No → right sorted: min=1, index=2, high=1
//   low=0, high=1, mid=0 → nums[0]=5, nums[1]=6: 5 ≤ 6 → fully sorted
//     min=min(1,5)=1, index stays 2, break
//   Rotation count = 2  ✓
//
// TC: O(log N)
// SC: O(1)

function findRotationCount(array $nums): int
{
    $n     = count($nums);
    $low   = 0;
    $high  = $n - 1;
    $min   = PHP_INT_MAX;
    $index = 0; // Index of the minimum element = number of rotations

    while ($low <= $high) {
        $mid = $low + intdiv($high - $low, 2);

        // Fully sorted segment: minimum is at nums[low]
        if ($nums[$low] <= $nums[$high]) {
            if ($nums[$low] < $min) {
                $min   = $nums[$low];
                $index = $low;
            }
            break;
        }

        if ($nums[$low] <= $nums[$mid]) {
            // Left half sorted; left-half minimum is nums[low]
            if ($nums[$low] < $min) {
                $min   = $nums[$low];
                $index = $low;
            }
            $low = $mid + 1; // Minimum is in the right (rotated) half
        } else {
            // Right half sorted; right-half minimum is nums[mid]
            if ($nums[$mid] < $min) {
                $min   = $nums[$mid];
                $index = $mid;
            }
            $high = $mid - 1; // Minimum is in the left (rotated) half
        }
    }

    return $index; // Number of rotations = index of minimum element
}

// --- Run ---
echo "Rotation Count [5,6,1,2,3,4]:   " . findRotationCount([5,6,1,2,3,4])   . "\n"; // 2
echo "Rotation Count [4,5,6,7,0,1,2]: " . findRotationCount([4,5,6,7,0,1,2]) . "\n"; // 4
echo "Rotation Count [1,2,3,4,5]:     " . findRotationCount([1,2,3,4,5])     . "\n"; // 0


// ============================================================
// 5. SINGLE ELEMENT IN A SORTED ARRAY (LeetCode 540)
// ============================================================
// Intuition:
//   Every element appears TWICE except one unique element.
//   In a perfectly paired array (no single element):
//     Pairs start at EVEN indices: arr[0]=arr[1], arr[2]=arr[3]…
//   After the single element, pairs shift:
//     Even-indexed element now pairs with index-1 (odd shift).
//
//   Strategy (parity trick):
//     At each mid, determine whether pairs are still "intact":
//       Even mid → its pair should be at mid+1
//         arr[mid] == arr[mid+1] → pairs intact → single is RIGHT → low = mid+2
//         arr[mid] != arr[mid+1] → pairs broken  → single is HERE or LEFT → high = mid
//       Odd mid → its pair should be at mid-1
//         arr[mid] == arr[mid-1] → pairs intact → single is RIGHT → low = mid+1
//         arr[mid] != arr[mid-1] → pairs broken  → single is LEFT → high = mid-1
//
//   Handle index 0 and n-1 as edge cases first (avoids bounds issues).
//
// Dry Run: nums=[1,1,2,3,3,4,4,8,8]
//   Edge check: nums[0]=1==nums[1]=1 → not single at 0
//   Edge check: nums[8]=8==nums[7]=8 → not single at 8
//   low=1, high=7, mid=4 → nums[4]=3 (even index)
//     nums[4]=3 == nums[5]=4? No → pairs broken → high=4
//   low=1, high=4, mid=2 → nums[2]=2 (even index)
//     nums[2]=2 == nums[3]=3? No → high=2
//   low=1, high=2, mid=1 → nums[1]=1 (odd index)
//     nums[1]=1 == nums[0]=1? Yes → pairs intact → low=2
//   low=2, high=2 → return nums[2]=2  ✓
//
// TC: O(log N)
// SC: O(1)

function singleNonDuplicate(array $nums): int
{
    $n    = count($nums);
    $low  = 0;
    $high = $n - 1;

    // Edge cases: single element is at the very beginning or very end
    if ($n === 1)                          return $nums[0];
    if ($nums[0] !== $nums[1])             return $nums[0];
    if ($nums[$n - 1] !== $nums[$n - 2])   return $nums[$n - 1];

    // Narrow search to interior [1 .. n-2] (safe to access mid±1)
    $low  = 1;
    $high = $n - 2;

    while ($low <= $high) {
        $mid = $low + intdiv($high - $low, 2);

        // mid is not part of any pair → it IS the single element
        if ($nums[$mid] !== $nums[$mid - 1] && $nums[$mid] !== $nums[$mid + 1]) {
            return $nums[$mid];
        }

        if ($mid % 2 === 0) {
            // Even index: correct pair is at mid+1
            if ($nums[$mid] === $nums[$mid + 1]) {
                $low = $mid + 2; // Pairs intact → single is to the right
            } else {
                $high = $mid;    // Pair broken → single is at mid or to the left
            }
        } else {
            // Odd index: correct pair is at mid-1
            if ($nums[$mid] === $nums[$mid - 1]) {
                $low = $mid + 1; // Pairs intact → single is to the right
            } else {
                $high = $mid - 1; // Pair broken → single is to the left
            }
        }
    }

    return $nums[$low]; // Converged to the single element
}

// --- Run ---
echo "Single Non-Dup [1,1,2,3,3,4,4,8,8]: " . singleNonDuplicate([1,1,2,3,3,4,4,8,8]) . "\n"; // 2
echo "Single Non-Dup [3,3,7,7,10,11,11]:  " . singleNonDuplicate([3,3,7,7,10,11,11])  . "\n"; // 10


// ============================================================
// BINARY SEARCH ON ANSWERS — Core Concept
// ============================================================
// Whenever you see: "minimise the maximum" OR "maximise the minimum"
//   → Think: Binary Search on the answer value!
//
// Template:
//   $low  = minimum possible answer
//   $high = maximum possible answer
//   while ($low <= $high) {
//       $mid = $low + intdiv($high - $low, 2);
//       if (isFeasible($mid)) { $high = $mid - 1; } // Feasible → try smaller
//       else                  { $low  = $mid + 1; } // Not feasible → go larger
//   }
//   return $low; // Smallest feasible answer
//
// For "maximise minimum" → swap directions, return $high.
// ============================================================


// ============================================================
// 6. CAPACITY TO SHIP PACKAGES WITHIN D DAYS (LeetCode 1011)
// ============================================================
// Intuition:
//   Find the MINIMUM ship capacity to ship all packages in order
//   within $days days (packages must be shipped in given order).
//
//   Search space:
//     low  = max(weights)   → must carry the heaviest single item
//     high = sum(weights)   → ship everything in 1 day
//
//   Feasibility check (canShip):
//     Greedily fill each day; if adding next package exceeds capacity,
//     start a new day. Count total days needed.
//     Return true if daysNeeded <= $days.
//
// Dry Run: weights=[3,2,2,4,1,4], days=3
//   low=4, high=16
//   mid=10: days needed → day1=[3,2,2]=7? no 7≤10 yes, then 7+4=11>10 → day2=[4,1,4]=9 → 2 days ≤ 3 ✓ → high=9
//   mid=6:  day1=[3,2]=5, 5+2=7>6→day2=[2,4]=6, 6+1=7>6→day3=[1,4]=5? 1+4=5≤6→day3=5. 3 days ≤ 3 ✓ → high=5
//   mid=4:  day1=[3]=3? 3+2=5>4→day2=[2,2]=4, 4+4=8>4→day3=[4,1]=5>4? 4+1=5>4→day4=... 4 days > 3 ✗ → low=5
//   mid=5:  day1=[3,2]=5, 5+2=7>5→day2=[2,4]=6>5→day2=[2]=2,2+4=6>5→day3=[4,1,4]=... check: day2=[2],2+4=6>5→day3=[4],4+1=5,5+4=9>5→day4... let me redo:
//     day1: 3, 3+2=5 ≤ 5 → add, 5+2=7 > 5 → new day. day2: 2, 2+4=6 > 5 → new day. day3: 4, 4+1=5 ≤ 5 → add, 5+4=9 > 5 → new day. day4: 4. 4 days > 3 ✗ → low=6
//   low=6 > high=5 → answer = low = 6  ✓
//
// TC: O(N · log(sum − max))  — log iterations × O(N) per feasibility check
// SC: O(1)

function canShip(array $weights, int $capacity, int $days): bool
{
    $daysNeeded  = 1; // We always need at least 1 day
    $currentLoad = 0; // Weight loaded on the current day

    foreach ($weights as $w) {
        if ($currentLoad + $w > $capacity) {
            // This package overflows today's limit → start a new day
            $daysNeeded++;
            $currentLoad = $w; // New day starts with this package
        } else {
            $currentLoad += $w; // Load this package on the current day
        }
    }

    return $daysNeeded <= $days; // Feasible if finished within $days
}

function shipWithinDays(array $weights, int $days): int
{
    $low  = max($weights);       // Minimum: must carry the heaviest package
    $high = array_sum($weights); // Maximum: carry everything in one day

    while ($low <= $high) {
        $mid = $low + intdiv($high - $low, 2);

        if (canShip($weights, $mid, $days)) {
            $high = $mid - 1; // Feasible → try smaller capacity
        } else {
            $low = $mid + 1;  // Not feasible → need more capacity
        }
    }

    return $low; // Smallest capacity that works
}

// --- Run ---
echo "Ship [3,2,2,4,1,4] in 3 days: "         . shipWithinDays([3,2,2,4,1,4], 3)          . "\n"; // 6
echo "Ship [1,2,3,4,5,6,7,8,9,10] in 5 days: " . shipWithinDays([1,2,3,4,5,6,7,8,9,10], 5) . "\n"; // 15


// ============================================================
// 7. AGGRESSIVE COWS (LeetCode 1552 — Magnetic Force Between Balls)
// ============================================================
// Intuition:
//   Place $cows cows in sorted stall positions such that the
//   MINIMUM distance between any two cows is MAXIMISED.
//   ("Maximise the minimum" → binary search on the answer.)
//
//   Search space:
//     low  = 1                           (adjacent stalls)
//     high = max(stalls) - min(stalls)   (entire span)
//
//   Feasibility check (canPlaceCows):
//     Greedily place cows: put the first cow at stalls[0].
//     For each next stall, place a cow only if distance from
//     the last placed cow >= $minDist.
//     Return true if we placed all $cows cows.
//
//   Binary search direction:
//     Feasible   → try larger minimum distance → low  = mid + 1
//     Not feasible → reduce distance           → high = mid - 1
//     Answer = $high (largest feasible distance)
//
// Dry Run: stalls=[0,3,4,7,10,9], cows=4 → sorted=[0,3,4,7,9,10]
//   low=1, high=10
//   mid=5: place at 0, next≥5: 7(7-0=7✓), next≥5: none after 7 except 9(9-7=2✗),10(10-7=3✗) → 2 cows only ✗ → high=4
//   mid=2: place at 0,3,7,9 → 4 cows ✓ → low=3
//   mid=3: place at 0,3,7,10 → 4 cows ✓ → low=4
//   mid=4: place at 0,4,... 4(4-0=4✓), next≥4 from 7: 7(7-4=3✗),9(9-4=5✓) → 0,4,9 → 3 cows ✗ → high=3
//   low=4 > high=3 → answer = high = 3  ✓
//
// TC: O(N log N) for sort + O(N · log(range)) for binary search
// SC: O(1)

function canPlaceCows(array $stalls, int $cows, int $minDist): bool
{
    $cowsPlaced = 1;          // Place the first cow at the first stall
    $lastPlaced = $stalls[0]; // Track the position of the last placed cow
    $n          = count($stalls);

    for ($i = 1; $i < $n; $i++) {
        // Place a cow here only if gap from the last placement is sufficient
        if ($stalls[$i] - $lastPlaced >= $minDist) {
            $cowsPlaced++;
            $lastPlaced = $stalls[$i];
        }

        if ($cowsPlaced >= $cows) {
            return true; // All cows successfully placed
        }
    }

    return false; // Could not place all $cows cows
}

function aggressiveCows(array $stalls, int $cows): int
{
    sort($stalls); // Must sort first — greedy placement only works on sorted stalls

    $low  = 1;
    $high = max($stalls) - min($stalls);

    while ($low <= $high) {
        $mid = $low + intdiv($high - $low, 2);

        if (canPlaceCows($stalls, $cows, $mid)) {
            $low = $mid + 1;  // Feasible → try larger minimum distance
        } else {
            $high = $mid - 1; // Not feasible → reduce distance
        }
    }

    return $high; // Largest feasible minimum distance
}

// --- Run ---
echo "Aggressive Cows [0,3,4,7,10,9] cows=4: " . aggressiveCows([0,3,4,7,10,9], 4) . "\n"; // 3
echo "Aggressive Cows [4,2,1,3,6]    cows=2: " . aggressiveCows([4,2,1,3,6], 2)    . "\n"; // 5


// ============================================================
// 8. ALLOCATE MINIMUM NUMBER OF PAGES (Book Allocation)
//    Similar to LeetCode 410 — Split Array Largest Sum
// ============================================================
// Intuition:
//   Assign $students contiguous segments of books so that the
//   MAXIMUM pages assigned to any student is MINIMISED.
//   ("Minimise the maximum" → binary search on the answer.)
//
//   Constraints:
//     - If students > books: impossible (return -1).
//     - Each student must get at least one book (contiguous).
//
//   Search space:
//     low  = max(books)   → a student gets at least the thickest book
//     high = sum(books)   → one student reads every book
//
//   Feasibility check (canAllocateBooks):
//     Greedily assign books: accumulate pages until next book exceeds
//     $pageLimit → assign to a new student.
//     Return true if studentsNeeded <= $students.
//
// Dry Run: books=[12,34,67,90], students=2
//   low=90, high=203
//   mid=146: accumulate 12→46→113→113+90=203>146 → student2=90. 2 ≤ 2 ✓ → high=145
//   mid=117: 12→46→113, 113+90=203>117 → student2=90. 2 ≤ 2 ✓ → high=116
//   mid=103: 12→46, 46+67=113>103 → student2=67, 67+90=157>103 → student3=90. 3 > 2 ✗ → low=104
//   mid=110: 12→46→113>110? 46+67=113>110 → student2=67, 67+90=157>110 → student3=90. 3 > 2 ✗ → low=111
//   mid=113: 12→46→113, 113+90>113 → student2=90. 2 ≤ 2 ✓ → high=112
//   low=113 > high=112 → answer = low = 113  ✓
//
// TC: O(N · log(sum − max))
// SC: O(1)

function canAllocateBooks(array $books, int $students, int $pageLimit): bool
{
    $studentsNeeded = 1; // Start with one student
    $currentPages   = 0; // Pages accumulated by the current student

    foreach ($books as $pages) {
        if ($currentPages + $pages <= $pageLimit) {
            $currentPages += $pages; // Assign this book to the current student
        } else {
            // This book exceeds the current student's limit → new student
            $studentsNeeded++;
            $currentPages = $pages; // New student starts with this book
        }
    }

    return $studentsNeeded <= $students; // Feasible if within student count
}

function allocateMinPages(array $books, int $students): int
{
    $n = count($books);

    // Edge case: cannot give every student at least one book
    if ($students > $n) {
        return -1;
    }

    $low  = max($books);       // Minimum possible answer
    $high = array_sum($books); // Maximum possible answer

    while ($low <= $high) {
        $mid = $low + intdiv($high - $low, 2);

        if (canAllocateBooks($books, $students, $mid)) {
            $high = $mid - 1; // Feasible → try smaller max pages
        } else {
            $low = $mid + 1;  // Not feasible → increase the page limit
        }
    }

    return $low; // Minimum possible maximum pages assigned to any student
}

// --- Run ---
echo "Alloc Min Pages [12,34,67,90]    students=2: " . allocateMinPages([12,34,67,90], 2)    . "\n"; // 113
echo "Alloc Min Pages [25,46,28,49,24] students=4: " . allocateMinPages([25,46,28,49,24], 4) . "\n"; // 71


// ============================================================
// 9. SPLIT ARRAY LARGEST SUM (LeetCode 410)
// ============================================================
// Intuition:
//   Split the array into exactly $k non-empty contiguous subarrays
//   such that the MAXIMUM subarray sum is MINIMISED.
//   This is IDENTICAL to the Book Allocation problem:
//     array elements → book pages,  $k splits → $k students.
//
// Dry Run: arr=[1,2,3,4,5], k=3
//   low=5, high=15
//   mid=10: [1+2+3+4=10], [5] → 2 parts ≤ 3 ✓ → high=9
//   mid=7:  [1+2+3=6], [4], [5] → 3 parts ≤ 3 ✓ → high=6
//   mid=6:  [1+2+3=6], [4], [5] → 3 parts ≤ 3 ✓ → high=5
//   mid=5:  [1+2=3], [3], [4], [5] → 4 parts > 3 ✗ → low=6
//   low=6 > high=5 → answer = low = 6  ✓
//
// TC: O(N · log(sum − max))
// SC: O(1)

function canSplitArray(array $arr, int $k, int $maxSum): bool
{
    $partsNeeded = 1; // Start with one subarray
    $currentSum  = 0;

    foreach ($arr as $val) {
        if ($currentSum + $val <= $maxSum) {
            $currentSum += $val; // Extend the current subarray
        } else {
            // Start a new subarray with this element
            $partsNeeded++;
            $currentSum = $val;
        }
    }

    return $partsNeeded <= $k; // Feasible if within $k splits
}

function splitArrayLargestSum(array $arr, int $k): int
{
    $low  = max($arr);       // Each subarray must hold at least the largest element
    $high = array_sum($arr); // Worst case: one subarray for the whole array

    while ($low <= $high) {
        $mid = $low + intdiv($high - $low, 2);

        if (canSplitArray($arr, $k, $mid)) {
            $high = $mid - 1; // Feasible → try smaller max sum
        } else {
            $low = $mid + 1;  // Not feasible → increase the sum limit
        }
    }

    return $low; // Minimum possible largest subarray sum
}

// --- Run ---
echo "Split Array [1,2,3,4,5] k=2: " . splitArrayLargestSum([1,2,3,4,5], 2) . "\n"; // 9
echo "Split Array [1,2,3,4,5] k=3: " . splitArrayLargestSum([1,2,3,4,5], 3) . "\n"; // 6


// ============================================================
// 10. FIND ROW WITH MAXIMUM NUMBER OF 1s (Sorted 2D Matrix)
// ============================================================
// Intuition:
//   Each row is sorted (0s then 1s). Use binary search per row to
//   find the FIRST index of 1. Number of 1s in row = m - firstIndex.
//   Track the row with the highest count.
//
// Example:
//   mat = [[0,0,1,1],   → first 1 at col 2, count = 4-2 = 2
//          [0,1,1,1],   → first 1 at col 1, count = 4-1 = 3  ← MAX
//          [0,0,0,1]]   → first 1 at col 3, count = 4-3 = 1
//   Result: row 1  ✓
//
// Dry Run: row=[0,1,1,1], m=4
//   low=0, high=3, mid=1 → mat[1]=1 → first=1, high=0
//   low=0, high=0, mid=0 → mat[0]=0 → low=1
//   low=1 > high=0 → return first=1  ✓
//
// TC: O(N · log M)  — binary search on each of N rows (M columns)
// SC: O(1)

function firstIndexOfOne(array $row, int $m): int
{
    $low   = 0;
    $high  = $m - 1;
    $first = -1; // -1 means this row has no 1s

    while ($low <= $high) {
        $mid = $low + intdiv($high - $low, 2);

        if ($row[$mid] === 1) {
            $first = $mid;    // Found a 1; try to find an earlier one
            $high  = $mid - 1;
        } else {
            $low = $mid + 1;  // Still 0; search to the right
        }
    }

    return $first;
}

function rowWithMaxOnes(array $mat): int
{
    $n          = count($mat);
    $m          = count($mat[0]);
    $maxOnes    = -1;
    $maxOnesRow = -1; // -1 means no row has any 1s

    for ($i = 0; $i < $n; $i++) {
        $firstOne = firstIndexOfOne($mat[$i], $m);

        if ($firstOne === -1) {
            continue; // No 1s in this row
        }

        $count = $m - $firstOne; // Count of 1s = cols from firstOne to end

        if ($count > $maxOnes) {
            $maxOnes    = $count;
            $maxOnesRow = $i;
        }
    }

    return $maxOnesRow;
}

// --- Run ---
$mat = [
    [0, 0, 1, 1],
    [0, 1, 1, 1],
    [0, 0, 0, 1],
];
echo "Row with max 1s: " . rowWithMaxOnes($mat) . "\n"; // 1


// ============================================================
// COMPARISON SUMMARY
// ============================================================
//
//  Problem                     | Approach               | TC               | SC
// -----------------------------+------------------------+------------------+--------
//  First & Last Position       | Recursive Binary Search | O(log N)         | O(log N)
//  Search in Rotated Array     | Modified Binary Search  | O(log N)         | O(1)
//  Min in Rotated Array        | Modified Binary Search  | O(log N)         | O(1)
//  Rotation Count              | Track index of min      | O(log N)         | O(1)
//  Single Non-Duplicate        | Parity-based BS         | O(log N)         | O(1)
//  Ship Within D Days          | BS on Answer (min)      | O(N·log(sum))    | O(1)
//  Aggressive Cows             | BS on Answer (max)      | O(N·log(range))  | O(1)
//  Book Allocation             | BS on Answer (min)      | O(N·log(sum))    | O(1)
//  Split Array Largest Sum     | BS on Answer (min)      | O(N·log(sum))    | O(1)
//  Row with Max 1s             | BS per row              | O(N·log M)       | O(1)


// ============================================================
// PRACTICE PROBLEMS & APPLICATIONS
// ============================================================
//
//  EASY
//  1. Search Insert Position (LeetCode 35)
//     → Basic binary search; return $low when key not found
//  2. First Bad Version (LeetCode 278)
//     → BS on answer: first index where isBadVersion() is true
//  3. Guess Number Higher or Lower (LeetCode 374)
//     → Classic binary search with an external comparator
//
//  MEDIUM
//  4. Search a 2D Matrix (LeetCode 74)
//     → Map mid index to [row][col] and binary search the whole matrix
//  5. Find Peak Element (LeetCode 162)
//     → Compare mid with mid+1: if rising, peak is right; else left
//  6. Koko Eating Bananas (LeetCode 875)
//     → BS on answer: min speed such that ceil(pile/speed) sum ≤ hours
//  7. Search in Rotated Sorted Array II (LeetCode 81)
//     → Duplicate variant: when nums[low]==nums[mid], do low++
//  8. Find Minimum in Rotated Sorted Array II (LeetCode 154)
//     → Duplicate variant: when nums[low]==nums[mid]==nums[high], low++ & high--
//  9. Magnetic Force Between Two Balls (LeetCode 1552)
//     → Aggressive Cows problem exactly
// 10. Capacity To Ship Packages (LeetCode 1011)
//     → Implemented above (Problem 6)
//
//  HARD
// 11. Median of Two Sorted Arrays (LeetCode 4)
//     → BS on partition of smaller array; O(log(min(m,n)))
// 12. Kth Smallest in Sorted Matrix (LeetCode 378)
//     → BS on value; count elements ≤ mid in O(N) per iteration
// 13. Painter's Partition Problem
//     → Identical to Book Allocation (minimise max work per painter)
// 14. Minimize Max Distance to Gas Station (LeetCode 774)
//     → BS on floating-point answer with precision threshold


// ============================================================
// KEY PATTERNS & VARIATIONS FOR REVISION
// ============================================================
//
//  PATTERN 1 — "Rotated Sorted Array" family:
//    Always identify which half is sorted first:
//      nums[low] <= nums[mid] → left half is sorted
//      else                   → right half is sorted
//    Then check if target belongs to the sorted half; eliminate other.
//    Duplicates (LeetCode 81/154): when nums[low]==nums[mid], do low++
//    to break the ambiguity.
//
//  PATTERN 2 — "Binary Search on Answer" template:
//    Trigger words: "minimise the maximum", "maximise the minimum",
//                   "minimum capacity/speed/pages/sum".
//    Steps:
//      a) Define low = min possible answer, high = max possible answer
//      b) Write isFeasible(mid) in O(N) (greedy check)
//      c) Minimise max → answer = low; Maximise min → answer = high
//
//  PATTERN 3 — Parity trick for "Single Element in Pairs":
//    Before the single element: even index pairs with even+1.
//    After  the single element: even index pairs with even-1.
//    Use mid % 2 to classify, then check the correct neighbour.
//
//  PATTERN 4 — Greedy feasibility in "Search on Answer":
//    Ship / Books / Split all share the SAME greedy helper:
//      Accumulate elements; when sum > limit, start a new segment.
//      Count segments and compare with the allowed number.
//
//  PATTERN 5 — Recursive vs Iterative Binary Search:
//    Iterative: preferred (O(1) space, no stack overflow risk).
//    Recursive: cleaner for "first/last occurrence" style problems.
//    Both run in O(log N) time.


// ============================================================
// IMPORTANT TIPS & EDGE CASES
// ============================================================
//
//  1. Safe mid calculation — ALWAYS use:
//       $mid = $low + intdiv($high - $low, 2);
//     NOT intdiv($low + $high, 2) — can overflow for large indices.
//
//  2. Rotated array — not rotated at all:
//     nums[low] <= nums[high] is true for a fully sorted array.
//     All three algorithms (search, findMin, rotationCount) handle
//     this correctly as the fully-sorted base case.
//
//  3. Rotated array — duplicates:
//     Standard O(log N) breaks when nums[low]==nums[mid].
//     LeetCode 81 fix: if (nums[low]==nums[mid] && nums[mid]==nums[high])
//     { low++; high--; continue; }
//
//  4. Single Element (LeetCode 540) edge cases:
//     Check index 0 (nums[0] != nums[1]) and index n-1 before the loop.
//     This lets the loop safely access mid-1 and mid+1 at all times.
//
//  5. Aggressive Cows — MUST sort stalls:
//     Greedy placement only works on a sorted array.
//     Forgetting sort() is the most common bug.
//
//  6. "Minimise max" vs "Maximise min" — direction of binary search:
//     Minimise max (Books, Ship, Split):
//       feasible → high = mid-1;  not feasible → low = mid+1;  answer = $low
//     Maximise min (Aggressive Cows):
//       feasible → low  = mid+1;  not feasible → high = mid-1; answer = $high
//
//  7. Book Allocation — impossible case:
//     If students > count(books), return -1 immediately.
//     Each student must receive at least one book (contiguous block).
//
//  8. PHP-specific:
//     Use intdiv() for integer division to avoid float surprises.
//     Arrays are passed by value by default — add & if in-place
//     modification is needed (not required for these problems).

?>