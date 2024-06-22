<?php
class Solution {
    public function countGoodNumbers($n) {
        $evenChoices = 5; // Number of choices for even indices (0, 2, 4, ...)
        $oddChoices = 4; // Number of choices for odd indices (1, 3, 5, ...)

        // Calculate the number of ways to fill even and odd indices
        $evenCount = ($n % 2 === 0) ? $this->modPow($evenChoices, $n / 2) : $this->modPow($evenChoices, ($n + 1) / 2);
        $oddCount = $this->modPow($oddChoices, $n / 2);

        // Multiply the counts and return the result modulo 10^9 + 7
        return ($evenCount * $oddCount) % 1000000007;
    }

    private function modPow($x, $n) {
        if ($n === 0) return 1;
        if ($n % 2 === 1) return ($x * $this->modPow($x, $n - 1)) % 1000000007;
        $temp = $this->modPow($x, $n / 2);
        return ($temp * $temp) % 1000000007;
    }
}

$solu = new Solution();
echo $solu->countGoodNumbers(4); // Output: 400

?>
