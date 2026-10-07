<?php

function spiralOrder($matrix) {
        $col = count($matrix[0]);
        $row = count($matrix);

        $upRow = 0;
        $rightCol = $col-1;
        $botRow = $row-1;
        $leftCol = 0;
        $ans = [];


        while($upRow <= $botRow && $leftCol <= $rightCol){
            $j=$leftCol;
            
            while($j <= $rightCol){
              $ans[] = $matrix[$upRow][$j];
              $j++;
            }
            $upRow++;

            $i=$upRow;
            while($i <= $botRow){
              $ans[] = $matrix[$i][$rightCol];
              $i++;
            }
            $rightCol--;

            if ($upRow <= $botRow) {
              $j=$rightCol;
              while($j >= $leftCol){
                $ans[] = $matrix[$botRow][$j];
                $j--;
              }
              $botRow--;
            }

            if ($leftCol <= $rightCol) {
              $i = $botRow;
              while($i >= $upRow){
                $ans[] = $matrix[$i][$leftCol];
                $i--; 
              }
              $leftCol++;
            }
            
        }
        return $ans;
    }

    // if you want to rotate clockwise, first transpose first column to first row and then reverse each row 
    // if you want to rotate anti-clockwise, first transpose last column to first row and then reverse each row
    
    // Intuition: Two-step process:
    //   1. Transpose  -> matrix[i][j] swaps with matrix[j][i] where i = n - 1 - j to get the mirror image along the main diagonal  
    //   2. Reverse each row  -> completes the 90 degree clockwise rotation
    // TC: O(n^2)  |  SC: O(1)
    function rotate(&$matrix) {
        $row = count($matrix);
        $col = count($matrix[0]);

        //convert row to column and coulm to row [j=0 => 1,4,7] -> [row=0=>1,4,7]
        //reverse each row in metrix [row=0=>1,4,7] => [4,7,1]

        //convert row to column and coulm to row 
        for ($i = 0; $i < $row; $i++) {
            for ($j = 0; $j < $i; $j++) {
            list($matrix[$j][$i], $matrix[$i][$j]) = [$matrix[$i][$j], $matrix[$j][$i]];
            }
        }

        //reverse each row in metrix
        for($rowStart = 0; $rowStart < $row; $rowStart++){
            $i=0;$j=$col-1;
            while($i < $j){
                list($matrix[$rowStart][$j], $matrix[$rowStart][$i]) = [$matrix[$rowStart][$i], $matrix[$rowStart][$j]];
                $i++; $j--;
            }
        }
            
        //for anticlockwise start from bottom right cornor then rever coulmn values
            // for($j=0;$j<$row;$j++){ 
            //       $up=0; $down = $row-1;
            //       while($up<$down){
            //         list($matrix[$down][$j], $matrix[$up][$j]) = [$matrix[$up][$j], $matrix[$down][$j]];
            //         $up++; $down--;
            //       }
            //     }
    }

    function setZeroes(&$matrix) {
        //Approach - 1: Brute-force with O(m*n) space
        // Intuition: First pass to find all zeroes and store their positions in a hash map. Second pass to set rows and columns to zero based on the stored positions. This approach uses O(m*n) space in the worst case (if all elements are zero).
        // TC: O(m*n)  |  SC: O(m*n) 

        // $n = count($matrix);
        // $m = count($matrix[0]);
        // $hahmap = [];

        // for($i=0; $i<$n; $i++) {
        //     for($j=0; $j<$m; $j++) {
        //         if($matrix[$i][$j] === 0) {
        //             $hahmap[$i."-".$j] = TRUE; // Second Approch you can mark -1 and next time you can check if -1 is there then mark 0 and at last you can change all -1 to 0.
        //             //Third Approch you can create two seprate array for row and column and mark them as 0 and at last you can mark all those row and column as 0.   
        //             //Space complexity will be O(m+n) in this case.
        //         }
        //     }
        // }

        // for($i=0; $i<$n; $i++) {
        //     for($j=0; $j<$m; $j++) {
        //         if($matrix[$i][$j] === 0 && isset($hahmap[$i."-".$j])){ 
        //             $x= $i; $y = $j;
        //             while($y >= 0){
        //                  $matrix[$x][$y] = 0;
        //                  $y--;
        //             }
        //             $y = $j;
        //             while($m > $y){
        //                  $matrix[$x][$y] = 0;
        //                  $y++;
        //             }
        //             $x= $i; $y = $j;
        //             while($x >= 0){
        //                  $matrix[$x][$y] = 0;
        //                  $x--;
        //             }
        //             $x= $i;
        //             while($n > $x){
        //                  $matrix[$x][$y] = 0;
        //                  $x++;
        //             }
        //         }
        //     }
        // }


        //Approch - 2
        // Intuition: Use first row and first column as marker arrays.
        //   - matrix[i][0]=0 marks row i should be zeroed.
        //   - matrix[0][j]=0 marks col j should be zeroed.
        //   - col0 variable tracks whether column 0 itself needs zeroing
        //     (to avoid overwriting the row-0 marker).
        // TC: O(m*n)  |  SC: O(1)

        $n = count($matrix);
        $m = count($matrix[0]);
        $firstRowZero = FALSE;
        $firstColZero = FALSE;

        for($i=0; $i<$n; $i++) {
            if($matrix[$i][0] === 0) {
                $firstColZero = TRUE;
            }
        }

        for($j=0; $j<$m; $j++) {
            if($matrix[0][$j] === 0) {
                $firstRowZero = TRUE;
            }
        }
        
        for($i=1; $i<$n; $i++) {
            for($j=1; $j<$m; $j++) {
                if($matrix[$i][$j] === 0){ 
                    $matrix[0][$j] = 0;
                    $matrix[$i][0] = 0;
                }
            }
        }
        //Output of above loop will be [[0,1,2,0],
        //                             [3,4,5,2],
        //                             [1,3,1,5]
        //                            ]

        for($i=1; $i<$n; $i++) {
            for($j=1; $j<$m; $j++) {
                if($matrix[0][$j] === 0 || $matrix[$i][0] === 0){  // If any of the row or column is marked as 0 then mark all those row and column as 0. 
                    $matrix[$i][$j] = 0;
                }
            }
        }
        //Output of above loop will be [[0,1,2,0],
        //                             [3,4,5,0],
        //                             [1,3,1,0]
        //                            ]
        if($firstRowZero) { 
            for($j=0; $j<$m; $j++) {
                $matrix[0][$j] = 0;
            }
        }
        //Output of above loop will be [[0,0,0,0],
        //                             [3,4,5,0],
        //                             [1,3,1,0]
        //                            ]
        if($firstColZero) {
            for($i=0; $i<$n; $i++) {
                $matrix[$i][0] = 0;
            }
        }
        //Output of above loop will be [[0,0,0,0],
        //                             [0,4,5,0],
        //                             [0,3,1,0]
        //                            ]
    }

    function generateMatrix($n) {
        $matrix = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                // Assign a value (e.g., 0, or a calculated value like $i * $j)
                $matrix[$i][$j] = 0; 
            }
        }

        $col = count($matrix[0]);
        $row = count($matrix);

        $upRow = 0;
        $rightCol = $col-1;
        $botRow = $row-1;
        $leftCol = 0;
        $ans = [];

        $counter = 0;

        while($upRow <= $botRow && $leftCol <= $rightCol && $counter <= $n*$n){
            $j=$leftCol;
            
            while($j <= $rightCol){
              $matrix[$upRow][$j] = ++$counter;
              $j++;
            }
            $upRow++;

            $i=$upRow;
            while($i <= $botRow){
              $matrix[$i][$rightCol] = ++$counter;
              $i++;
            }
            $rightCol--;

            if ($upRow <= $botRow) {
              $j=$rightCol;
              while($j >= $leftCol){
                $matrix[$botRow][$j] = ++$counter;
                $j--;
              }
              $botRow--;
            }

            if ($leftCol <= $rightCol) {
              $i = $botRow;
              while($i >= $upRow){
                $matrix[$i][$leftCol] = ++$counter;
                $i--; 
              }
              $leftCol++;
            }
            
        }
        return $matrix;
    }



