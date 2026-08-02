/**
 * Debouncing is a practice used to 
 * improve browser performance
 * when calling an API while user typing 
 * into the input
 * 
 * @param {function} cb
 * @param {number} delay
 */

export default function debounce(cb, delay = 2000) {
  let timeout;
  
  return (...args) => {
    clearTimeout(timeout);

    timeout = setTimeout(() => cb(...args), delay);
  }
}