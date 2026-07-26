# DSA Master Pattern Bible — 30 Patterns, Question Numbers, Anti-Traps

I pulled every distinct idea across all 12 of your screenshots — the LC-numbered
list, the "recognize every pattern" table, the mindmap, the anti-trigger card,
the input-type cheatsheet, the "how to identify" flowchart, the 2026
cheatsheet, and the algorithm-strategies poster — and merged them into one
document. Nothing from your images is dropped; each pattern below now carries
**why it's the right tool + how to spot it + the trap that fools people + the
actual LeetCode drill set**, so you can run this list "blind" without
re-deriving anything mid-interview.

---

## PART 0 — The 10-second diagnosis (before you pick a pattern)

Run these four checks in order, every time, out loud:

**1. What's the input shape?**
| Input | Default pattern candidates |
|---|---|
| Array/String | Two Pointers, Sliding Window, Prefix Sum, Binary Search, HashMap |
| Linked List | Fast/Slow Pointers, Reversal, Merge |
| Tree | DFS (paths/height), BFS (level order) |
| Graph | BFS (unweighted shortest path), DFS (components), Union Find (dynamic connectivity), Topo Sort (dependencies) |
| Range query with updates | Segment Tree / Fenwick Tree |
| Range query, no updates | Prefix Sum |

**2. What's the keyword?**
| Keyword in the problem | Pattern |
|---|---|
| "contiguous", "substring", "subarray" + longest/shortest | Sliding Window |
| "pair", "triplet", sorted array | Two Pointers |
| "shortest path" (unweighted) | BFS |
| "shortest path" (weighted) | Dijkstra / Bellman-Ford |
| "dependencies", "build order", "prerequisite" | Topological Sort |
| "connected components", "islands", "provinces" | DFS/BFS or Union Find |
| "Kth largest/smallest", "top K" | Heap or Quickselect |
| "ways to...", "min/max cost", "count subsequences" | Dynamic Programming |
| "all combinations/permutations/subsets" | Backtracking |
| "next greater/smaller", "span", "histogram" | Monotonic Stack |
| "min/max in every window" | Monotonic Deque |

**3. What's the constraint size?** (this alone often tells you the required
complexity, hence the pattern)
| N | Required complexity | Pattern family |
|---|---|---|
| N ≤ 20 | O(2ⁿ) / O(n!) | Backtracking, brute force, bitmask DP |
| N ≤ 500–1000 | O(n²) | DP tables, brute force pairs |
| N ≤ 10⁴–10⁵ | O(n log n) | Sorting, Binary Search, Heap |
| N ≥ 10⁶ | O(n) or O(n log n) tight | Two Pointers, Sliding Window, HashMap, Prefix Sum, Greedy |

**4. Anti-trigger check — before you commit, ask "does the condition
actually hold, not just the keyword?"** (Full list in Part 2 — this single
habit is what separates candidates who solve it from those who get stuck
mid-code after guessing wrong from a keyword.)

---

## PART 1 — The 30 Patterns, With Drill Sets

Each entry: **recognize it** (the trigger) → **core idea** → **complexity**
→ **LeetCode drill set** (numbers + names, so you're not guessing what "875"
means three weeks from now) → **an edge Claude added: follow-up interviewers ask.**

### 1. Sliding Window
**Recognize it:** contiguous subarray/substring + longest/shortest/at-most-K.
**Core idea:** expand right, shrink left while state invalid, reuse work
between windows instead of recomputing.
**Complexity:** O(n) time, O(k) space.
**Drill set:**
- 3 — Longest Substring Without Repeating Characters
- 76 — Minimum Window Substring
- 209 — Minimum Size Subarray Sum
- 424 — Longest Repeating Character Replacement
- 567 — Permutation in String
- 904 — Fruit Into Baskets
**Follow-up:** "exactly K distinct instead of at-most K?" → atMost(K) − atMost(K−1) trick.

### 2. Two Pointers
**Recognize it:** sorted array/list, need a pair/triplet, or in-place compaction.
**Core idea:** move both ends inward based on a comparison, O(n) instead of O(n²).
**Complexity:** O(n) time, O(1) space.
**Drill set:**
- 11 — Container With Most Water
- 15 — 3Sum
- 16 — 3Sum Closest
- 18 — 4Sum
- 42 — Trapping Rain Water
- 167 — Two Sum II (sorted)
**Follow-up:** "why does moving the smaller-sum pointer never lose the answer?" — be ready to justify the exchange argument, not just recite the move.

### 3. Fast & Slow Pointers (Floyd's)
**Recognize it:** linked list cycle, midpoint, or "meeting point" phrasing.
**Core idea:** two pointers at different speeds; if there's a cycle, they meet.
**Complexity:** O(n) time, O(1) space.
**Drill set:**
- 141 — Linked List Cycle
- 142 — Linked List Cycle II (find the start)
- 19 — Remove Nth Node From End of List
- 876 — Middle of the Linked List
- 160 — Intersection of Two Linked Lists
- 234 — Palindrome Linked List
**Follow-up:** derive why the meeting point + reset-to-head trick finds the cycle's *start* node, not just detects a cycle.

### 4. Binary Search on Sorted Data
**Recognize it:** sorted array, "find position", "search", "first/last occurrence".
**Core idea:** halve the search space each step using a monotonic order.
**Complexity:** O(log n).
**Drill set:**
- 33 — Search in Rotated Sorted Array
- 34 — Find First and Last Position of Element in Sorted Array
- 35 — Search Insert Position
- 153 — Find Minimum in Rotated Sorted Array
- 162 — Find Peak Element
- 704 — Binary Search
**⚠️ Anti-trigger:** *Sorted ≠ automatically Binary Search.* Confirm there's a monotonic yes/no condition you can check at `mid`, not just that the array happens to be sorted.

### 5. Binary Search on the Answer
**Recognize it:** "minimize the maximum", "maximize the minimum", "find minimum X such that condition holds" — no array to search, but a numeric answer space that's monotonic.
**Core idea:** binary search over possible answer values, using a feasibility check at each candidate.
**Complexity:** O(n log(range)).
**Drill set:**
- 875 — Koko Eating Bananas
- 1011 — Capacity To Ship Packages Within D Days
- 410 — Split Array Largest Sum
- 774 — Minimize Max Distance to Gas Station
- 1283 — Find the Smallest Divisor Given a Threshold
- 1482 — Minimum Number of Days to Make m Bouquets
**Signal move:** naming this pattern explicitly (vs just "binary search") is a strong senior-level tell — most mid-level candidates never learn to search on the answer instead of the array.

### 6. Hashing / Frequency Maps
**Recognize it:** duplicates, frequency, "have I seen this", grouping.
**Core idea:** trade space for O(1) average lookup.
**Complexity:** O(n) time, O(n) space.
**Drill set:**
- 1 — Two Sum
- 49 — Group Anagrams
- 128 — Longest Consecutive Sequence
- 217 — Contains Duplicate
- 242 — Valid Anagram
- 347 — Top K Frequent Elements
**⚠️ Anti-trigger:** *HashMap ≠ automatic O(1) magic.* Lookup speed alone doesn't solve state design — you still need to define exactly what the key, value, and "meaning" of each entry are before coding.

### 7. Prefix Sum / Running Sum
**Recognize it:** repeated range-sum queries, "subarray sum equals K".
**Core idea:** precompute cumulative sums once; `sum(L,R) = prefix[R] − prefix[L−1]`.
**Complexity:** O(n) build, O(1) per query.
**Drill set:**
- 303 — Range Sum Query — Immutable
- 560 — Subarray Sum Equals K
- 724 — Find Pivot Index
- 930 — Binary Subarrays With Sum
- 974 — Subarray Sums Divisible by K
- 523 — Continuous Subarray Sum
**⚠️ Anti-trigger:** *Prefix Sum ≠ the full answer by itself.* It's compression, not the solution — target/count questions ("sum equals K") almost always need a HashMap on top of the prefix sums, not prefix sums alone.

### 8. Difference Array / Range Updates
**Recognize it:** "apply the same operation across a range, many times" — the opposite of prefix sum (updates instead of queries).
**Core idea:** mark `+val` at start, `−val` at end+1, then prefix-sum once at the end to materialize all updates in O(n + updates) instead of O(n·updates).
**Complexity:** O(n + q).
**Drill set:**
- 370 — Range Addition
- 1094 — Car Pooling
- 1109 — Corporate Flight Bookings
- 1893 — Check if All the Integers in a Range Are Covered
- 1943 — Describe the Painting
- 2381 — Shifting Letters II
**Note:** this is genuinely underused by candidates — mentioning it unprompted for a "many range updates" problem is a strong signal.

### 9. Monotonic Stack
**Recognize it:** "next greater/smaller element", histogram, span.
**Core idea:** maintain a stack that's always increasing or decreasing; pop when the invariant breaks — each element pushed/popped once, O(n) total.
**Complexity:** O(n).
**Drill set:**
- 739 — Daily Temperatures
- 496 — Next Greater Element I
- 503 — Next Greater Element II (circular)
- 84 — Largest Rectangle in Histogram
- 85 — Maximal Rectangle
- 901 — Online Stock Span

### 10. Monotonic Queue / Deque
**Recognize it:** "min/max within every sliding window" — the deque cousin of pattern 9.
**Core idea:** maintain a deque of candidate indices in decreasing (or increasing) value order; front is always the current window's max/min.
**Complexity:** O(n).
**Drill set:**
- 239 — Sliding Window Maximum
- 862 — Shortest Subarray with Sum at Least K
- 1425 — Constrained Subsequence Sum
- 1438 — Longest Continuous Subarray With Absolute Diff ≤ Limit
- 1499 — Max Value of Equation
- 1696 — Jump Game VI

### 11. Heap / Top K
**Recognize it:** "Kth largest/smallest", "top K frequent", "always need the current best".
**Core idea:** heap keeps the best candidate on top in O(log n) per operation.
**Complexity:** O(n log k) for top-K, O(log n) per op.
**Drill set:**
- 215 — Kth Largest Element in an Array
- 347 — Top K Frequent Elements
- 692 — Top K Frequent Words
- 703 — Kth Largest Element in a Stream
- 973 — K Closest Points to Origin
- 1046 — Last Stone Weight
**⚠️ Anti-trigger:** *Top K ≠ heap automatically.* Check whether sorting, bucket sort (frequencies bounded by n), or Quickselect (O(n) average, one-shot Kth element) is actually cleaner — heap wins when the data streams in or K is queried repeatedly.

### 12. Intervals / Merge Intervals
**Recognize it:** overlapping ranges, meeting times, scheduling.
**Core idea:** sort by start time, then sweep and merge/count.
**Complexity:** O(n log n).
**Drill set:**
- 56 — Merge Intervals
- 57 — Insert Interval
- 252 — Meeting Rooms
- 253 — Meeting Rooms II
- 435 — Non-overlapping Intervals
- 452 — Minimum Number of Arrows to Burst Balloons

### 13. Greedy Scheduling / Sorting
**Recognize it:** "locally best choice leads to global optimum" — but verify, don't assume.
**Core idea:** sort by the right key, then make the obviously-best choice at each step.
**Complexity:** O(n log n) typically (sort-dominated).
**Drill set:**
- 45 — Jump Game II
- 55 — Jump Game
- 406 — Queue Reconstruction by Height
- 621 — Task Scheduler
- 763 — Partition Labels
- 134 — Gas Station
**⚠️ Anti-trigger:** *Greedy-looking ≠ actually greedy.* A locally-best move can fail once future costs change. Greedy needs a proof (exchange argument or matroid structure), not vibes — if you can't justify why the local choice can never be beaten later, you probably need DP instead.

### 14. Linked List Manipulation
**Recognize it:** merge, reverse, reorder, copy — structural rewiring of nodes.
**Core idea:** dummy node + prev/curr/next pointer bookkeeping.
**Complexity:** O(n) time, O(1) space (unless copying with a map).
**Drill set:**
- 21 — Merge Two Sorted Lists
- 23 — Merge k Sorted Lists
- 24 — Swap Nodes in Pairs
- 25 — Reverse Nodes in k-Group
- 92 — Reverse Linked List II
- 138 — Copy List with Random Pointer

### 15. Tree DFS (paths, height, sums)
**Recognize it:** parent-child structure, path sums, depth/diameter questions.
**Core idea:** recurse, combine children's results, decide pre/in/post-order by what info you need before vs. after visiting children.
**Complexity:** O(n).
**Drill set:**
- 104 — Maximum Depth of Binary Tree
- 112 — Path Sum
- 113 — Path Sum II
- 543 — Diameter of Binary Tree
- 124 — Binary Tree Maximum Path Sum
- 226 — Invert Binary Tree
**⚠️ Anti-trigger:** *Tree ≠ recursion only.* Some tree problems (level order, rerooting, parent pointers, iterative traversal for stack-overflow risk) genuinely need BFS, an explicit stack, or parent maps instead — match the traversal to the information flow, not habit.

### 16. Tree BFS / Level Order
**Recognize it:** "level by level", "row-wise", zigzag, right-side view.
**Core idea:** queue-based traversal, process one level fully before the next.
**Complexity:** O(n).
**Drill set:**
- 102 — Binary Tree Level Order Traversal
- 103 — Binary Tree Zigzag Level Order Traversal
- 199 — Binary Tree Right Side View
- 515 — Find Largest Value in Each Tree Row
- 637 — Average of Levels in Binary Tree
- 116 — Populating Next Right Pointers in Each Node

### 17. BST Problems
**Recognize it:** "binary search tree" explicitly, or "kth smallest", in-order gives sorted output.
**Core idea:** exploit the BST invariant (left < node < right) — in-order traversal = sorted sequence.
**Complexity:** O(h) per op, O(n) for full traversal.
**Drill set:**
- 98 — Validate Binary Search Tree
- 99 — Recover Binary Search Tree
- 230 — Kth Smallest Element in a BST
- 235 — Lowest Common Ancestor of a BST
- 450 — Delete Node in a BST
- 700 — Search in a Binary Search Tree

### 18. Backtracking — Basics
**Recognize it:** "generate all permutations/subsets/combinations".
**Core idea:** choose → explore → un-choose, pruning invalid branches early.
**Complexity:** O(2ⁿ) or O(n!) depending on the structure.
**Drill set:**
- 46 — Permutations
- 47 — Permutations II (with duplicates)
- 77 — Combinations
- 78 — Subsets
- 90 — Subsets II
- 39 — Combination Sum

### 19. Backtracking — With Constraints
**Recognize it:** same as above but with a validity check per placement (grid, board, phone keypad).
**Core idea:** same choose/explore/un-choose loop, plus a `isValid()` gate before recursing.
**Complexity:** exponential, bounded by pruning quality.
**Drill set:**
- 40 — Combination Sum II
- 17 — Letter Combinations of a Phone Number
- 79 — Word Search
- 131 — Palindrome Partitioning
- 51 — N-Queens
- 52 — N-Queens II
**⚠️ Anti-trigger:** *Backtracking ≠ "just try everything."* Unpruned brute recursion explodes. Always name your three levers out loud: pruning, choice ordering, and visited-state tracking — that's what keeps it from being an accidental O(n!) TLE.

### 20. Graph BFS / DFS
**Recognize it:** "connected components", "islands", grid flood-fill, unweighted shortest path.
**Core idea:** BFS for shortest path in unweighted graphs; DFS for full connectivity/exploration.
**Complexity:** O(V + E).
**Drill set:**
- 200 — Number of Islands
- 695 — Max Area of Island
- 733 — Flood Fill
- 994 — Rotting Oranges
- 1091 — Shortest Path in Binary Matrix
- 1254 — Number of Closed Islands
**⚠️ Anti-trigger:** *Graph-looking ≠ automatically DFS.* If the task is really "merge these into groups" or answer repeated "are these connected?" queries, Union Find is usually cleaner than DFS from scratch each time.

### 21. Topological Sort / DAG
**Recognize it:** "dependencies", "prerequisites", "build order".
**Core idea:** Kahn's algorithm (BFS with in-degree counting) or DFS post-order + reverse.
**Complexity:** O(V + E).
**Drill set:**
- 207 — Course Schedule
- 210 — Course Schedule II
- 802 — Find Eventual Safe States
- 1462 — Course Schedule IV
- 1203 — Sort Items by Groups Respecting Dependencies
- 2115 — Find All Possible Recipes from Given Supplies

### 22. Union Find / DSU
**Recognize it:** "dynamic grouping", "connected after unions", "redundant edge".
**Core idea:** parent + rank/size arrays with path compression → near-O(1) amortized per operation.
**Complexity:** O(α(n)) per op (inverse Ackermann, effectively constant).
**Drill set:**
- 547 — Number of Provinces
- 684 — Redundant Connection
- 1319 — Number of Operations to Make Network Connected
- 1579 — Remove Max Number of Edges to Keep Graph Fully Traversable
- 990 — Satisfiability of Equality Equations
- 1202 — Smallest String With Swaps
**Say this unprompted:** without *both* path compression and union by rank/size, Union Find degrades toward O(n) — with both, it's nearly O(1) amortized. Naming this is a reliable senior-level signal.

### 23. Shortest Path (Weighted)
**Recognize it:** weighted edges + "shortest path", "cheapest", "minimum cost to reach".
**Core idea:** Dijkstra (non-negative weights, greedy + min-heap), Bellman-Ford (handles negative weights, detects negative cycles).
**Complexity:** Dijkstra O(E log V); Bellman-Ford O(V·E).
**Drill set:**
- 743 — Network Delay Time
- 787 — Cheapest Flights Within K Stops
- 1514 — Path with Maximum Probability
- 1631 — Path With Minimum Effort
- 1334 — Find the City With the Smallest Number of Neighbors at a Threshold Distance
- 1976 — Number of Ways to Arrive at Destination
**⚠️ Anti-trigger:** *BFS ≠ shortest path always.* Plain BFS only guarantees shortest path on unweighted graphs. Weighted edges need Dijkstra, 0-1 BFS, or Bellman-Ford — don't default to BFS just because it worked last time.

### 24. MST / Graph Greedy
**Recognize it:** "connect all nodes with minimum total cost", "minimum spanning tree".
**Core idea:** Kruskal (sort edges, Union Find to avoid cycles) or Prim (grow the tree greedily from a start node with a min-heap).
**Complexity:** O(E log E).
**Drill set:**
- 1584 — Min Cost to Connect All Points
- 1135 — Connecting Cities With Minimum Cost
- 1168 — Optimize Water Distribution in a Village
- 1489 — Find Critical and Pseudo-Critical Edges in MST
- 778 — Swim in Rising Water
- 1102 — Path With Maximum Minimum Value

### 25. Trie
**Recognize it:** prefixes, autocomplete, dictionary lookups.
**Core idea:** prefix tree, one character per node, O(L) per operation instead of scanning the whole dictionary.
**Complexity:** O(L) per insert/search.
**Drill set:**
- 208 — Implement Trie (Prefix Tree)
- 211 — Design Add and Search Words Data Structure
- 212 — Word Search II
- 648 — Replace Words
- 677 — Map Sum Pairs
- 1268 — Search Suggestions System

### 26. Bit Manipulation
**Recognize it:** XOR properties, "without extra space", "power of two", "count set bits".
**Core idea:** exploit bitwise identities (`n & (n-1)` clears lowest set bit; XOR cancels pairs).
**Complexity:** O(1) to O(log(max value)).
**Drill set:**
- 136 — Single Number
- 137 — Single Number II
- 191 — Number of 1 Bits
- 338 — Counting Bits
- 268 — Missing Number
- 190 — Reverse Bits

### 27. 1D DP Basics
**Recognize it:** "min cost / max profit / count ways", current answer depends on a smaller version of itself.
**Core idea:** define `dp[i]`, find the recurrence, base case, then tabulate or memoize.
**Complexity:** O(n) time, often reducible to O(1) space (rolling variables).
**Drill set:**
- 70 — Climbing Stairs
- 198 — House Robber
- 213 — House Robber II (circular)
- 322 — Coin Change
- 279 — Perfect Squares
- 300 — Longest Increasing Subsequence
**⚠️ Anti-trigger:** *Minimum/maximum-in-the-question ≠ automatically DP.* Confirm there are overlapping subproblems and optimal substructure — otherwise a straight greedy or two-pointer scan might already be O(n) and simpler.

### 28. Knapsack / Subset DP
**Recognize it:** "select a subset under a capacity constraint", "partition into two equal-sum groups".
**Core idea:** `dp[i][capacity]` = best achievable using first i items under the capacity; 0/1 vs unbounded changes the iteration direction.
**Complexity:** O(n · capacity).
**Drill set:**
- 416 — Partition Equal Subset Sum
- 494 — Target Sum
- 518 — Coin Change II
- 474 — Ones and Zeroes
- 1049 — Last Stone Weight II
- 879 — Profitable Schemes

### 29. Grid DP
**Recognize it:** 2D grid, "number of paths", "min path sum", movement constrained to right/down (or similar).
**Core idea:** `dp[i][j]` built from `dp[i-1][j]` and `dp[i][j-1]`.
**Complexity:** O(rows · cols).
**Drill set:**
- 62 — Unique Paths
- 63 — Unique Paths II (with obstacles)
- 64 — Minimum Path Sum
- 221 — Maximal Square
- 931 — Minimum Falling Path Sum
- 120 — Triangle

### 30. String DP / Sequence DP
**Recognize it:** two strings compared, "longest common", "edit distance", "distinct subsequences".
**Core idea:** `dp[i][j]` over prefixes of both strings — match/skip/substitute transitions.
**Complexity:** O(m·n).
**Drill set:**
- 1143 — Longest Common Subsequence
- 72 — Edit Distance
- 115 — Distinct Subsequences
- 583 — Delete Operation for Two Strings
- 97 — Interleaving String
- 1312 — Minimum Insertion Steps to Make a String Palindrome

---

## PART 2 — All 12 Anti-Triggers (from your card, kept intact and mapped)

Don't ask *"what keyword did I see?"* Ask *"what condition makes this pattern
actually valid here?"* Keywords suggest; constraints confirm.

1. **Subarray ≠ Sliding Window** — negative numbers break shrink logic; confirm moving left/right has a predictable monotonic effect first.
2. **Sorted ≠ Binary Search** — need a monotonic yes/no condition, not just sorted order.
3. **Top K ≠ Always Heap** — sorting, bucket counting, or Quickselect may be cleaner.
4. **Minimum/Maximum ≠ DP** — need overlapping subproblems + optimal substructure, not just an optimization keyword.
5. **Graph-looking ≠ DFS only** — merging/grouping tasks often mean Union Find.
6. **Greedy-looking ≠ Greedy** — local best can fail once future costs change; greedy needs proof, not vibes.
7. **Tree ≠ Recursion only** — some need BFS, explicit stack, parent pointers, or rerooting.
8. **HashMap ≠ O(1) magic** — you still need to design key/value/meaning correctly.
9. **Prefix Sum ≠ The Answer** — it's compression; target/count problems usually need a map on top.
10. **BFS ≠ Shortest Always** — only true for unweighted edges; weighted needs Dijkstra/0-1 BFS/DP.
11. **Backtracking ≠ Try Everything** — needs pruning, ordering, and visited-state tracking or it explodes.
12. **Two Pointers ≠ Two Indices** — need a genuine safe-movement rule, usually from sorted data or a monotonic condition.

---

## PART 3 — Complexity & Formula Quick Reference

**Time complexity ladder (best to worst):**
`O(1) < O(log n) < O(n) < O(n log n) < O(n²) < O(2ⁿ) < O(n!)`

**Sorting algorithms:**
| Algorithm | Time | Notes |
|---|---|---|
| Merge Sort | O(n log n) | stable, needs O(n) space |
| Quick Sort | O(n log n) avg, O(n²) worst | in-place, unstable |
| Heap Sort | O(n log n) | in-place, unstable |
| Counting Sort | O(n + k) | only for bounded integer ranges |

**Data structure operation costs:**
| Structure | Access | Search | Insert | Delete |
|---|---|---|---|---|
| Array | O(1) | O(n) | O(n) | O(n) |
| HashMap | — | O(1) avg | O(1) avg | O(1) avg |
| Balanced BST | O(log n) | O(log n) | O(log n) | O(log n) |
| Heap | — | O(n) | O(log n) | O(log n) (extract-min/max) |
| Union Find | — | O(α(n)) | — | — |

**Useful formulas:**
- Sum of first n numbers: `n(n+1)/2`
- Sum of squares: `n(n+1)(2n+1)/6`
- `nCr = n! / (r!(n−r)!)`
- `n & (n-1)` clears the lowest set bit
- `n & (-n)` isolates the lowest set bit

---

## PART 4 — Mastery Order (builds on your Striver roadmap)

1. Arrays + Hashing (patterns 6)
2. Two Pointers + Sliding Window (2, 1)
3. Prefix Sum + Difference Array (7, 8)
4. Binary Search + Binary Search on Answer (4, 5)
5. Monotonic Stack/Deque (9, 10)
6. Linked List + Fast/Slow Pointers (14, 3)
7. Trees — DFS/BFS/BST (15, 16, 17)
8. Heap / Top K (11)
9. Intervals + Greedy (12, 13)
10. Backtracking (18, 19)
11. Graphs — BFS/DFS → Union Find → Topo Sort → Shortest Path → MST (20–24)
12. Tries (25)
13. Dynamic Programming — 1D → Knapsack → Grid → String (27–30)
14. Bit Manipulation (26)

Each pattern's drill set above has 6 problems — solve all 180 across the 30
patterns and you will have pattern-matched against nearly every interview
question shape that shows up at Amazon, Grab, and Gulf-market product
companies. When you can name the pattern within the first 30 seconds of
reading a new problem *and* justify why an anti-trigger doesn't apply here,
that's the actual signal that you're interview-ready, not just LeetCode-solved.
