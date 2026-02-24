/**
 * Chunk array by total element
 * 
 * @param {array} arr 
 * @param {integer} size 
 * @returns {array}
 */
export const arrayChunk = (arr, size) => {
  return arr.reduce((acc, val, ind) => {
      const subIndex = ind % size;

      if(!Array.isArray(acc[subIndex])){
        acc[subIndex] = [val];
      } else {
        acc[subIndex].push(val);
      };

      return acc;
  }, []);
}

/**
 * Chunk array by size each element
 * 
 * @param {array} arr 
 * @param {integer} size 
 * @returns {array}
 */
export const arrayChunk2 = (arr, size) => {
  arr = Array.isArray(arr) ? arr : []
  
  return Array.from({ length: Math.ceil(arr.length / size) }, (v, i) =>
    arr.slice(i * size, i * size + size)
  );
}