# 📘 Bit Manipulation — Complete Interview Handbook

**Pattern #28 of 28 (Final Pattern) | DSA Pattern Mastery Series**
**Audience:** Senior/Staff SWE interviews (Google, Amazon, Meta, Microsoft, Uber, Airbnb, Atlassian, Grab, Careem, Noon, Talabat, Dubai/Saudi & India Tier-1/Tier-2 product companies)
**Companion:** Pairs with Striver's A2Z DSA Course — Bit Manipulation section

---

## Table of Contents
1. Pattern Overview · 2. Recognition Guide · 3. Decision Framework · 4. Why This Pattern Works · 5. Algorithm Framework · 6. Time & Space Complexity · 7. Edge Cases · 8. Pros & Cons · 9. Real World Applications · 10. Interview Strategy · 11. Pattern Variations · 12. Comparison With Other Patterns · 13. Problem Classification · 14. 30 Interview Problems · 15. Common Mistakes · 16. Optimization Techniques · 17. Multiple Language Templates · 18. Dry Runs · 19. Advanced Concepts · 20. Senior Engineer Insights · 21. Cheat Sheet · 22. Revision Notes · 23. Practice Roadmap · 24. Memory Tricks · 25. Final Summary

---

## SECTION 1 — Pattern Overview

### 1.1 What Is This Pattern?
Bit Manipulation directly operates on the binary representation of numbers using bitwise operators (`AND &`, `OR |`, `XOR ^`, `NOT ~`, left shift `<<`, right shift `>>`) to solve problems with extreme constant-factor efficiency, often reducing what would otherwise require O(n) extra space or O(n log n) time to O(1) space and O(n) or better time, by exploiting the mathematical properties of binary numbers directly at the hardware level.

### 1.2 Why Was This Pattern Invented?
Computers store and process all integers in binary at the hardware level, and CPUs have single-cycle native instructions for bitwise operations (AND, OR, XOR, shifts) — recognizing and exploiting properties like "XOR-ing a number with itself yields zero," "AND-ing with `(n-1)` clears the lowest set bit," or "a number is a power of two iff exactly one bit is set" lets engineers solve certain problems (parity checking, subset enumeration, single-number-detection, fast multiplication/division by powers of two) dramatically faster and with less memory than general-purpose approaches.

### 1.3 Real Intuition Behind The Pattern
Imagine **flipping a row of light switches** — each switch (bit) is independently on (1) or off (0), and certain puzzles (like "which switch is the odd one out," or "generate every possible combination of switches") become trivial once you think of the problem purely in terms of switch positions rather than the numbers they represent — for example, "find the single number that appears once while all others appear twice" becomes trivial by XOR-ing every switch state together, since paired identical states cancel out (XOR of two identical bits is 0), leaving only the unpaired switch's state.

### 1.4 Mental Model
"Can this problem be reframed in terms of individual bit positions or bit-level properties, rather than the numbers as a whole?" — parity, uniqueness, counting set bits, subset generation, and certain arithmetic tricks (fast power-of-two checks, swapping without a temp variable) all become elegant and highly efficient once viewed through this bit-level lens.

### 1.5 Visual Explanation
```
Find the single number in [4, 1, 2, 1, 2] (all others appear twice):

  4 = 100
  1 = 001
  2 = 010
  1 = 001
  2 = 010

XOR all together:
  100 ^ 001 = 101
  101 ^ 010 = 111
  111 ^ 001 = 110
  110 ^ 010 = 100  →  100 = 4  ✓ (the unpaired number)

Why it works: XOR is commutative & associative, and x ^ x = 0, x ^ 0 = x
  So: 4^1^2^1^2 = 4^(1^1)^(2^2) = 4^0^0 = 4
```

### 1.6 Simple Analogy
Bit Manipulation is like **using a light-switch panel to represent a set** — each switch represents "is this element in the set?" — turning subset generation, membership testing, and set operations (union = OR, intersection = AND, symmetric difference = XOR) into simple, blazing-fast integer operations instead of explicit data structure manipulation.

### 1.7 When Should I Immediately Think About Using This Pattern?
- "Find the single/missing/duplicate number" among otherwise-paired or otherwise-complete values (XOR tricks).
- "Count set bits," "check if power of two," "without using extra space," "in O(1) space."
- Subset/combination generation where a Bitmask (cross-reference Pattern #26, DP-Bitmask) represents which elements are included.
- "Without using arithmetic operators (+, -, *, /)," implying bitwise arithmetic tricks are expected.

---

## SECTION 2 — Recognition Guide

### 2.1 Keywords
| Keyword | Signal |
|---|---|
| "single number," "missing number," "duplicate" | XOR-based bit trick |
| "without extra space" / "O(1) space" | Strong hint toward a bit-manipulation trick replacing a hash set |
| "power of two/four," "count set bits" | Direct bit-counting/bit-testing techniques |
| "subsets," "all combinations" | Bitmask enumeration (0 to 2^n - 1) |
| "without using + or -" | Bitwise arithmetic (half-adder/full-adder logic via AND/XOR) |

### 2.2 Hidden Hints
A problem constrains extra space to O(1) or explicitly forbids extra data structures (hash sets, arrays) for what looks like a "find the unique/missing element" problem — this is the classic tell that XOR or bit-summing tricks are the intended solution.

### 2.3 Interview Clues
Interviewer says "can you do this without extra space?" after a hash-set-based solution — this is almost always steering toward a bit-manipulation (typically XOR) approach.

### 2.4 Common Trick Words
"Bitwise," "binary representation," "set bits," "toggle," "flip," "XOR," "power of two."

### 2.5 What Interviewers Expect
Fluency with the core bitwise identities (`n & (n-1)` clears lowest set bit, `n & -n` isolates lowest set bit, `x ^ x = 0`, `x ^ 0 = x`) and the ability to derive a bit-level trick for a specific problem on the spot, not just memorized recall — plus correct handling of negative numbers (two's complement) and language-specific bitwise operator quirks (e.g., Java's unsigned right shift `>>>` vs. `>>`).

### 2.6 When NOT To Use This Pattern
- The problem doesn't have a natural binary/set-membership framing — forcing bit tricks onto an unrelated problem adds obfuscation without benefit.
- Readability matters more than micro-optimization and a clearer approach (hash set, sorting) is equally efficient asymptotically — use bit tricks primarily when they provide genuine complexity or space advantages, not just because they're clever.
- The problem involves floating-point numbers, where naive bitwise reinterpretation requires careful, non-trivial IEEE-754 handling.

---

## SECTION 3 — Decision Framework

```
Does the problem involve finding unique/missing/duplicate elements, or checking simple numeric properties (power of two, parity)?
        │
       Yes
        ▼
Can the problem be reframed in terms of bit-level operations (XOR cancellation, bit counting, bit isolation)?
        │
       Yes → USE BIT MANIPULATION (typically O(n) time, O(1) space)
        │
        No
        ▼
Does the problem require enumerating all subsets of a small set (n ≤ ~20-25)?
        │
       Yes → USE BITMASK ENUMERATION (cross-reference Pattern #26, DP-Bitmask, for the DP variant)
        │
        No
        ▼
Is there no natural binary/bit-level framing?
        │
       Yes → USE A DIFFERENT PATTERN (Hashing #9, Sorting #7, etc.)
```
**Why:** Bit Manipulation earns its place specifically when it eliminates the need for extra space (replacing a hash set with XOR accumulation) or provides constant-factor speed advantages unavailable via other patterns — applying it purely for its own sake, without one of these concrete benefits, sacrifices code clarity for no real gain.

---

## SECTION 4 — Why This Pattern Works

**Mathematical Foundation:** Bitwise operators correspond exactly to Boolean algebra applied independently to each bit position of a number's binary representation — because each bit position is processed completely independently (no carrying, unlike arithmetic addition), operations like XOR, AND, and OR can be reasoned about per-bit and then composed, which is what enables elegant proofs like the XOR-cancellation trick.

**Correctness Proof (XOR Single-Number Trick):** *Claim:* XOR-ing every element of an array where all elements appear exactly twice except one, which appears exactly once, yields that unique element. *Proof:* XOR is commutative (`a^b = b^a`) and associative (`(a^b)^c = a^(b^c)`), so the order of XOR-ing doesn't matter — group the array's XOR expression so that each duplicate pair is adjacent: this regroups the total XOR into `(x1^x1) ^ (x2^x2) ^ ... ^ (unique)`. Since `x^x = 0` for any `x`, and `0^y = y` for any `y`, every duplicate pair cancels to 0, and XOR-ing all these zeros with the unique element leaves exactly the unique element. **QED.**

**Correctness Proof (`n & (n-1)` clears the lowest set bit):** *Proof:* let the lowest set bit of `n` be at position `k` (so `n = ...1000...0` with the 1 at position `k`). Then `n - 1` flips that bit to 0 and flips all lower bits (which were all 0) to 1: `n-1 = ...0111...1`. AND-ing `n` and `n-1`: at position `k`, `n` has 1 and `n-1` has 0 → result 0. At positions below `k`, `n` has 0 (by definition of "lowest set bit") and `n-1` has 1 → result 0. At positions above `k`, both `n` and `n-1` are unchanged from `n`'s original bits → result matches `n`'s original bits. So `n & (n-1)` equals `n` with exactly its lowest set bit cleared. **QED.** This underlies Brian Kernighan's bit-counting algorithm (Section 19).

---

## SECTION 5 — Algorithm Framework

### 5.1 Step-by-Step Framework
1. Identify the bit-level property the problem hinges on (uniqueness → XOR; counting → Kernighan's trick; power-of-two → single-bit check; subset generation → bitmask loop).
2. Select the specific bitwise identity/operator that captures this property.
3. Apply it in a single pass (or a small fixed number of passes) over the input.
4. Handle sign/overflow/negative-number edge cases explicitly (two's complement nuances).

### 5.2 General Template — Find the Single Number (all others appear twice)
```
function singleNumber(nums):
    result = 0
    for num in nums:
        result = result XOR num
    return result
```

### 5.3 General Template — Count Set Bits (Brian Kernighan's Algorithm)
```
function countSetBits(n):
    count = 0
    while n != 0:
        n = n AND (n - 1)   # clears the lowest set bit
        count = count + 1
    return count
```

### 5.4 General Template — Generate All Subsets via Bitmask
```
function allSubsets(nums):
    n = length(nums)
    result = []
    for mask from 0 to (2^n - 1):
        subset = []
        for i from 0 to n-1:
            if (mask AND (1 << i)) != 0:
                subset.append(nums[i])
        result.append(subset)
    return result
```

### 5.5 Interview Thinking Process
1. "I'll check if this problem has a natural bit-level framing — uniqueness, counting, power-of-two, or subset enumeration."
2. "I'll identify the specific bitwise identity that captures the needed property (XOR-cancellation, `n & (n-1)`, `n & -n`, shifting)."
3. "I'll verify correctness with a small dry run, paying close attention to negative numbers / two's complement / language-specific shift behaviors."
4. "I'll confirm this genuinely improves on a straightforward hash-set/array approach in space or time, not just cleverness for its own sake."

---

## SECTION 6 — Time & Space Complexity

| Case | Time | Space | Why |
|---|---|---|---|
| Worst Case | O(n) for single-pass array tricks (XOR); O(log n) or O(number of bits) for per-number bit operations | O(1) | Bitwise operations are single-cycle CPU instructions; no auxiliary structures needed |
| Average Case | O(n) / O(log n) | O(1) | Same regardless of data distribution |
| Best Case | O(n) / O(log n) | O(1) | No shortcuts typically apply/needed given the operation's inherent simplicity |
| Amortized | O(n) total for array-wide tricks | O(1) | This O(1) space is the core advantage over hash-set-based alternatives |
| Bitmask Enumeration | O(2^n · n) to generate/process all subsets | O(n) per subset (or O(1) if processing masks without materializing subsets) | Necessary for exhaustive subset enumeration; only tractable for small n (≤ ~20-25) |

---

## SECTION 7 — Edge Cases

| Edge Case | Example | Handling |
|---|---|---|
| Negative numbers | Two's complement representation | Verify sign-bit behavior; be careful with right shifts (arithmetic `>>` vs logical `>>>`) |
| Zero | `n = 0` | Has no set bits; power-of-two check and bit-counting must handle it correctly (0 is NOT a power of two) |
| Integer overflow | Left-shifting near the type's bit-width limit | Be aware of language-specific integer sizes (32-bit vs 64-bit) and overflow/wraparound behavior |
| All bits set (`-1` in two's complement, or `2^n - 1` unsigned) | `~0` | Verify behavior of NOT and shifts at this boundary |
| Single-element array (XOR-based problems) | `[5]` | Returns 5 trivially (XOR of one number with nothing is itself) |
| n = 0 for bitmask enumeration | Empty set | Only mask 0 (empty subset) exists; loop from 0 to 2^0 - 1 = 0, handled naturally |

**Common mistakes:** using `>>` (arithmetic/sign-extending shift) when `>>>` (logical/zero-filling shift) is needed (or vice versa) — especially relevant in Java/JavaScript; forgetting that `n & (n-1)` and similar tricks assume specific bit-width conventions; off-by-one errors in bitmask loop bounds (`1 << n` vs `1 << (n-1)`); not handling negative numbers' two's complement representation when reasoning about "set bits."

---

## SECTION 8 — Pros & Cons

**Advantages:** Extremely fast (single-cycle CPU instructions) and typically O(1) extra space, replacing what would otherwise require a hash set or auxiliary array; elegant, compact solutions once the right trick is identified.
**Disadvantages:** Can significantly hurt readability/maintainability if overused or applied without clear justification; easy to introduce subtle bugs around sign-extension, overflow, and language-specific shift semantics.
**Trade-offs:** Bit Manipulation (O(1) space, very fast, but less readable and easier to get subtly wrong) vs. Hashing (O(n) space, very readable, robust) — prefer bit tricks specifically when the space savings or performance genuinely matter (e.g., embedded systems, extremely large inputs, or explicit O(1)-space interview constraints).
**Limitations:** Doesn't generalize automatically — each new problem requires identifying its own specific bit-level property; not directly applicable to problems without a natural binary/set framing.
**Inefficient when:** Never asymptotically inefficient when applicable — the primary risk is misapplication/reduced clarity, not performance.

---

## SECTION 9 — Real World Applications

| Domain | Application |
|---|---|
| Compilers & Low-Level Systems | Flags and permission bits (Unix file permissions: read/write/execute encoded as bits) use bitwise OR/AND for combining/checking flags |
| Networking | IP address subnet masking uses AND operations directly on binary representations |
| Cryptography | XOR-based stream ciphers (one-time pad, RC4) rely fundamentally on XOR's self-inverse property |
| Graphics/Color Encoding | RGB(A) color channels are frequently packed into a single integer using shifts and masks |
| Databases/Bloom Filters | Bloom filters use bitwise operations over bit arrays for extremely space-efficient approximate set membership |
| Compression Algorithms | Many compression formats pack data at the bit level (not just byte level) for maximal density |
| Hardware/Embedded Systems | Register manipulation (setting/clearing/toggling specific hardware flag bits) is pure bit manipulation |
| Competitive Programming / Bitmask DP | Bitmask techniques (Pattern #26 cross-reference) rely entirely on bit manipulation fluency |
| Game Development | Collision detection layers and flags are frequently encoded and checked via bitmasks |
| Distributed Systems | Certain hashing/partitioning schemes use bitwise operations for fast, deterministic bucket assignment |

---

## SECTION 10 — Interview Strategy

**How seniors answer:** They immediately recognize the XOR/bit-counting/bitmask signal from the problem's space constraints or phrasing, name the specific identity being used, and briefly justify correctness (e.g., "XOR cancels pairs because `x^x=0`") — they also proactively flag potential negative-number/overflow pitfalls before being asked.

**How juniors answer:** They may know isolated bit tricks (e.g., "power of two check") but struggle to derive a new trick on the spot for a less-familiar problem, and often overlook sign/overflow edge cases.

**Typical follow-ups:** "Can you explain why this bitwise trick works?" "How would this behave with negative numbers?" "Can you do this without using the `+` or `-` operators?" "What's the time/space complexity, and how does it compare to a hash-set approach?"

**Optimization questions:** "Can you count set bits faster than checking each bit individually?" (Yes — Brian Kernighan's `n & (n-1)` trick runs in O(number of set bits) rather than O(number of total bits).)

---

## SECTION 11 — Pattern Variations

| Variation | Description | Example |
|---|---|---|
| XOR Cancellation | Exploit `x^x=0`, `x^0=x` to find unique/missing elements | Single Number, Missing Number |
| Bit Counting | Count set bits efficiently (Kernighan's algorithm, or precomputed DP) | Number of 1 Bits, Counting Bits |
| Bit Isolation | Isolate the lowest/highest set bit (`n & -n`, `n & (n-1)`) | Power of Two check, Single Number III |
| Bitmask Subset Enumeration | Iterate `0` to `2^n - 1` to represent all subsets | Subsets, cross-reference DP-Bitmask (#26) |
| Bitwise Arithmetic | Simulate `+`/`-` using AND/XOR/shift (half-adder/full-adder logic) | Sum of Two Integers |
| Bit Manipulation Tricks for Swapping/Toggling | XOR swap, toggle specific bits | Swap without temp variable, Flip bits |

---

## SECTION 12 — Comparison With Other Patterns

| Pattern | Difference | When To Prefer |
|---|---|---|
| Hashing (#9) | Uses auxiliary O(n) space for set/frequency tracking, more general and readable | Space isn't constrained, or the problem lacks a clean bit-level framing |
| DP-Bitmask (#26) | Uses bitmasks as DP *states* (with memoization), not just direct manipulation | The subset-enumeration problem also has overlapping subproblems requiring memoization |
| Sorting-Cyclic Sort (#7) | Uses index-value placement instead of bit tricks for missing-number-style problems | Values are constrained to a specific range `[1,n]` amenable to cyclic placement |

### Comparison Table
| Aspect | Bit Manipulation | Hashing | DP-Bitmask |
|---|---|---|---|
| Space | O(1) | O(n) | O(2^n) (state table) |
| Readability | Lower (requires bit-level reasoning) | Higher | Moderate |
| Best for | Space-constrained uniqueness/counting/property checks | General set/frequency problems | Subset problems with overlapping subproblems |

---

## SECTION 13 — Problem Classification

| Difficulty | Characteristics | Examples |
|---|---|---|
| Easy | Direct application of a single well-known trick | Single Number, Number of 1 Bits, Power of Two |
| Medium | Combining multiple tricks or handling two unique elements | Single Number II, Single Number III, Counting Bits |
| Hard | Bitwise arithmetic simulation, complex multi-step bit logic | Sum of Two Integers, Maximum XOR of Two Numbers in an Array |
| Very Hard | Bit tricks combined with Tries or advanced DP | Maximum XOR With an Element From Array, bitmask DP combinations (cross-reference #26) |

---

## SECTION 14 — 30 Interview Problems

| # | Problem | Difficulty | Companies | Why Pattern Applies | Learning Objective |
|---|---|---|---|---|---|
| 1 | Single Number | Easy | Amazon, Meta, Google | XOR cancellation | Foundational XOR trick |
| 2 | Number of 1 Bits | Easy | Amazon, Microsoft | Brian Kernighan's bit-counting | Foundational bit-counting |
| 3 | Power of Two | Easy | Amazon, Google | `n & (n-1) == 0` single-bit check | Bit-isolation trick |
| 4 | Missing Number | Easy | Amazon, Meta | XOR with index-value pairing | XOR trick variant |
| 5 | Counting Bits | Easy/Medium | Amazon, Google | Bit-count DP using `i & (i-1)` | Bit counting + DP combination |
| 6 | Reverse Bits | Easy | Amazon, Apple | Bit-by-bit reversal via shifting | Shift manipulation fluency |
| 7 | Single Number II | Medium | Amazon, Google | Bit-counting with modulo-3 logic | Advanced XOR/counting generalization |
| 8 | Single Number III | Medium | Amazon, Google | XOR + partitioning by differing bit | Advanced two-unique-element XOR |
| 9 | Sum of Two Integers | Medium | Amazon, Google, Meta | Bitwise addition simulation (AND for carry, XOR for sum) | Bitwise arithmetic mastery |
| 10 | Bitwise AND of Numbers Range | Medium | Amazon, Google | Common prefix bit-shifting trick | Range-based bit reasoning |
| 11 | Maximum XOR of Two Numbers in an Array | Medium | Google, Amazon | Trie + bitwise greedy XOR maximization | Bit manipulation + Trie combination (cross-reference #16) |
| 12 | Total Hamming Distance | Medium | Google, Amazon | Per-bit-position counting | Bit-position-wise aggregation |
| 13 | Divide Two Integers | Medium | Amazon, Google | Bitwise shift-based division simulation | Bitwise arithmetic (division) |
| 14 | Gray Code | Medium | Amazon, Google | `i ^ (i >> 1)` formula | Bit-transformation formula fluency |
| 15 | UTF-8 Validation | Medium | Google, Amazon | Bit-pattern matching for byte validation | Bit-pattern recognition |
| 16 | Subsets (Bitmask Enumeration) | Medium | Amazon, Meta, Google | Bitmask subset generation | Foundational bitmask enumeration |
| 17 | Flip Bits to Convert A to B (classic) | Easy/Medium | Google, Amazon (conceptually asked) | XOR + bit-counting | Combined XOR/counting application |
| 18 | Find the Duplicate Number (contrast, cycle detection) | Medium | Amazon, Google | Contrast: Floyd's Cycle Detection (#3), not pure bit manipulation | Pattern-boundary awareness |
| 19 | XOR Queries of a Subarray | Medium | Amazon, Google | Prefix-XOR technique | Prefix-XOR (analogous to Prefix Sum #5) |
| 20 | Maximum XOR With an Element From Array | Hard | Google, Amazon | Trie + offline query bitwise greedy | Advanced Trie + bit manipulation |
| 21 | Minimum Flips to Make a OR b Equal to c | Medium | Amazon, Google | Per-bit-position case analysis | Bit-position case analysis |
| 22 | Binary Number with Alternating Bits | Easy | Amazon | XOR with shifted self to detect alternation | Pattern-detection via XOR |
| 23 | Complement of Base 10 Integer | Easy | Amazon, Google | Bitmask generation matching bit-length | Bitmask construction fluency |
| 24 | Hamming Distance | Easy | Amazon, Google | XOR + bit-counting | Combined XOR/counting foundational |
| 25 | Set Mismatch (contrast, Cyclic Sort or XOR) | Easy | Amazon | XOR-based dual-unknown resolution | Advanced XOR variant |
| 26 | Number Complement | Easy | Amazon | Bitmask + XOR/NOT combination | Bitmask + complement fluency |
| 27 | Subsets II (Bitmask with duplicates) | Medium | Amazon, Meta | Bitmask enumeration with duplicate-skip logic | Advanced bitmask enumeration |
| 28 | Minimum XOR Sum of Two Arrays | Hard | Google, Amazon | Bitmask DP (cross-reference #26) | Bitmask DP + bit reasoning combination |
| 29 | Find XOR Sum of All Pairs Bitwise AND | Hard | Google, Amazon | XOR/AND distributive bit-level reasoning | Advanced bitwise algebra |
| 30 | Smallest Sufficient Team (contrast, Bitmask DP) | Hard | Google, Amazon | Bitmask DP application (cross-reference #26) | Cross-pattern reinforcement |

---

## SECTION 15 — Common Mistakes

1. Confusing arithmetic (sign-extending) right shift `>>` with logical (zero-filling) right shift `>>>` — especially critical in Java/JavaScript for negative numbers. *Fix:* explicitly verify which shift semantics the language and problem require.
2. Off-by-one errors in bitmask loop bounds (using `1 << n` when `1 << (n-1)` was intended, or vice versa). *Fix:* carefully verify the loop's range against a small example (n=2 or n=3) before trusting it.
3. Forgetting that `0` is not a power of two, and that bit-counting/bit-isolation tricks need explicit handling for the zero case. *Fix:* always dry-run the zero edge case.
4. Assuming bitwise tricks work identically across languages without checking integer width/overflow semantics (32-bit vs. 64-bit, signed vs. unsigned). *Fix:* verify the target language's integer representation before applying advanced tricks.
5. Overusing bit manipulation for problems where it doesn't provide genuine efficiency or clarity benefits, needlessly obscuring the solution. *Fix:* only reach for bit tricks when they provide a concrete, articulable space or time advantage.

**Why people fail:** Bit-level reasoning is one layer of abstraction below normal "problem-solving" thinking, so mistakes here are often silent — code compiles and often even passes several test cases (especially with small positive numbers) while harboring latent bugs that surface only with negative numbers, zero, or numbers near the integer width boundary; this makes rigorous edge-case testing especially critical for this pattern.

---

## SECTION 16 — Optimization Techniques

- **Time:** Brian Kernighan's `n & (n-1)` trick counts set bits in O(number of set bits) rather than O(total bit width), an improvement when the number of set bits is sparse.
- **Space:** Bit manipulation is the canonical O(1)-space technique, frequently replacing an O(n)-space hash set entirely (e.g., XOR-based single-number/missing-number solutions).
- **Readability:** Always comment the specific bitwise identity being used (e.g., `// n & (n-1) clears the lowest set bit`) since bit-level code is inherently less self-documenting than higher-level alternatives.
- **Interview performance:** Explicitly verbalize why a specific bitwise trick is correct (briefly restate the underlying identity) rather than silently applying it — this demonstrates genuine understanding rather than memorized pattern-matching.

---

## SECTION 17 — Multiple Language Templates

### Java
```java
public int singleNumber(int[] nums) {
    int result = 0;
    for (int num : nums) {
        result ^= num;
    }
    return result;
}

public int hammingWeight(int n) {
    int count = 0;
    while (n != 0) {
        n = n & (n - 1);
        count++;
    }
    return count;
}
```

### JavaScript
```javascript
function singleNumber(nums) {
    let result = 0;
    for (const num of nums) {
        result ^= num;
    }
    return result;
}

function hammingWeight(n) {
    let count = 0;
    while (n !== 0) {
        n = n & (n - 1);
        count++;
    }
    return count;
}
```

### PHP
```php
function singleNumber(array $nums): int {
    $result = 0;
    foreach ($nums as $num) {
        $result ^= $num;
    }
    return $result;
}

function hammingWeight(int $n): int {
    $count = 0;
    while ($n !== 0) {
        $n = $n & ($n - 1);
        $count++;
    }
    return $count;
}
```

### Python
```python
def single_number(nums):
    result = 0
    for num in nums:
        result ^= num
    return result

def hamming_weight(n):
    count = 0
    while n != 0:
        n &= (n - 1)
        count += 1
    return count
```

### Go
```go
func singleNumber(nums []int) int {
    result := 0
    for _, num := range nums {
        result ^= num
    }
    return result
}

func hammingWeight(n uint32) int {
    count := 0
    for n != 0 {
        n &= (n - 1)
        count++
    }
    return count
}
```

### C++
```cpp
int singleNumber(vector<int>& nums) {
    int result = 0;
    for (int num : nums) {
        result ^= num;
    }
    return result;
}

int hammingWeight(uint32_t n) {
    int count = 0;
    while (n != 0) {
        n &= (n - 1);
        count++;
    }
    return count;
}
```

---

## SECTION 18 — Dry Runs

### Small Input
`nums = [4, 1, 2, 1, 2]` (Single Number)
```
result = 0
result ^= 4 → 100
result ^= 1 → 100 ^ 001 = 101
result ^= 2 → 101 ^ 010 = 111
result ^= 1 → 111 ^ 001 = 110
result ^= 2 → 110 ^ 010 = 100 = 4

Output: 4 ✓
```

### Large Input (Conceptual)
For an array of 10^6 numbers, the XOR-based approach costs O(10^6) single-cycle XOR operations with O(1) extra space — versus a hash-set approach requiring O(10^6) space, illustrating the pattern's core space advantage at scale.

### Corner Case
`n = 0` (Number of 1 Bits): loop condition `n != 0` is false immediately, `count` remains 0 — correctly reflecting that 0 has no set bits.

---

## SECTION 19 — Advanced Concepts

- **Brian Kernighan's Algorithm (formalized):** repeatedly applying `n = n & (n-1)` clears one set bit per iteration, so the loop runs exactly (number of set bits) times, rather than (total bit width) times — a meaningful improvement for sparse bit patterns, and the standard technique referenced in senior-level bit manipulation discussions.
- **Isolating the lowest set bit (`n & -n`):** in two's complement representation, `-n` equals `~n + 1`; ANDing `n` with `-n` isolates exactly the lowest set bit — used in Fenwick Trees (Binary Indexed Trees) for efficient range-sum updates/queries, a direct real-world application of this specific bit trick.
- **Bitwise arithmetic simulation (Sum of Two Integers):** addition without `+` can be simulated as `XOR` (sum without carry) combined with `AND` shifted left by one (the carry), repeated until no carry remains — mirroring how a hardware full-adder circuit computes binary addition.
- **XOR + Trie combination (Maximum XOR problems):** building a binary Trie (Pattern #16 cross-reference) over the bit representations of numbers allows greedily maximizing XOR by traversing toward the "opposite bit" at each level whenever possible — an advanced Staff-level combination of two patterns.

---

## SECTION 20 — Senior Engineer Insights

Staff engineers treat Bit Manipulation as a targeted optimization tool, not a default: they reach for it specifically when a concrete space or time constraint justifies the readability trade-off (explicit O(1)-space requirements, embedded/performance-critical systems, or a clean XOR-cancellation structure), and they're equally comfortable explaining *why* a given identity is correct as they are applying it — treating memorized tricks without underlying justification as a red flag in their own work. They're also acutely aware of cross-language subtleties (Java's `>>>` vs `>>`, Python's arbitrary-precision integers behaving differently from fixed-width languages) and proactively call these out rather than assuming uniform behavior.

---

## SECTION 21 — Cheat Sheet

```
PATTERN: Bit Manipulation
RECOGNIZE: "single/missing/duplicate number," "O(1) space," "power of two," "count set bits," "subsets" (small n)
KEY IDENTITIES:
    x ^ x = 0,  x ^ 0 = x                  → XOR cancellation (uniqueness tricks)
    n & (n-1)                              → clears lowest set bit (Kernighan's bit-count)
    n & -n                                 → isolates lowest set bit (Fenwick Tree indexing)
    n & (n-1) == 0  (n > 0)                → checks if n is a power of two
    1 << i                                 → represents "element i is included" in a bitmask
COMPLEXITY: O(n) time, O(1) space for array-wide tricks; O(2^n) for full subset enumeration
WATCH FOR: sign-extension vs logical shifts, negative numbers, zero edge case, integer overflow/width
DOESN'T APPLY WHEN: no natural bit-level/set framing, or clarity matters more than micro-optimization
```

---

## SECTION 22 — Revision Notes (5-Minute Review)

- XOR cancellation (`x^x=0`, `x^0=x`) solves "single/unique element among pairs" problems in O(n) time, O(1) space.
- `n & (n-1)` clears the lowest set bit — the basis of Brian Kernighan's O(set bits) counting algorithm.
- `n & -n` isolates the lowest set bit — used in Fenwick Trees.
- Power of two check: `n > 0 && (n & (n-1)) == 0`.
- Bitmask enumeration (`0` to `2^n - 1`) generates all subsets — only tractable for small n (≤ ~20-25).
- Always verify negative-number/shift-semantics/overflow behavior explicitly per language.

---

## SECTION 23 — Practice Roadmap

| Stage | Focus | Recommended LeetCode Problems |
|---|---|---|
| Beginner | Basic XOR and bit-counting | Single Number (136), Number of 1 Bits (191), Power of Two (231) |
| Intermediate | Combined tricks, prefix-XOR, bitmask enumeration | Missing Number (268), Counting Bits (338), Subsets (78) |
| Advanced | Bitwise arithmetic simulation, multi-unique XOR | Sum of Two Integers (371), Single Number II (137), Single Number III (260) |
| Expert | Bit manipulation + Trie/DP combinations | Maximum XOR of Two Numbers in an Array (421), Minimum XOR Sum of Two Arrays (1879) |

---

## SECTION 24 — Memory Tricks

- **Mnemonic:** "**XOR** = e**X**clusive **OR**, cancels pairs, reveals the loner" — for uniqueness-style problems.
- **Visualization:** **A row of light switches**, each independently on/off — subset enumeration, membership testing, and set operations map directly to bitmask operations on this switch panel.
- **Recognition shortcut:** "O(1) space" + "find the unique/missing/duplicate" → XOR trick candidate; "count set bits" → Kernighan's `n & (n-1)`; "all subsets," small n → bitmask enumeration loop.

---

## SECTION 25 — Final Summary

Bit Manipulation exploits the binary representation of numbers directly, using CPU-native bitwise operators to achieve O(1)-space, single-cycle-instruction solutions for problems involving uniqueness (XOR cancellation), counting (Kernighan's algorithm), numeric properties (power-of-two checks), and subset enumeration (bitmask loops) — techniques that are dramatically more space-efficient than general-purpose alternatives like hash sets, but that demand careful, explicit attention to sign-extension, integer width, overflow, and language-specific shift semantics, since bugs at this level of abstraction are often silent and only surface on adversarial edge cases (negative numbers, zero, boundary bit-widths) rather than typical test inputs.

---

# 🎓 Series Complete: All 28 DSA Patterns

This concludes the **DSA Pattern Mastery Series** — 28 comprehensive interview handbooks spanning every major algorithmic pattern from Two Pointers through Bit Manipulation, each self-contained with full theory, proofs, multi-language implementations, and curated interview problem sets. Refer back to `00-Pattern-Roadmap.md` for the complete learning sequence and progress tracker.
