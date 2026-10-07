function majorityElement(nums) {
        let maxElement=''; let maxFreq = 0;
        let previousEle = ''; let currentFreq = 0;
        nums.sort();
        for(let i of nums){
            currentEle = i;
            if(currentEle == previousEle){
                currentFreq++;
                if(currentFreq > maxFreq){
                    maxFreq = currentFreq;
                    maxElement = currentEle;
                }
            }else{
                previousEle = currentEle;
                currentFreq = 1;
            }
        }

        if(maxFreq > Math.floor(nums.length / 2)){
            return maxElement
        }
        
        return -1;
    }

const nums = [7, 0, 0, 1, 7, 7, 2, 7, 7]
    console.log(majorityElement(nums) )