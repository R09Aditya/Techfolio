document.addEventListener('mousemove', function(e) {
    var cursor = document.querySelector('.cursor');
    cursor.style.left = e.pageX + 'px';
    cursor.style.top = e.pageY + 'px';
});

document.addEventListener('mouseover', function(e) {
    var cursor = document.querySelector('.cursor');
    if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.tagName === 'IMG') {
        cursor.style.transform = 'scale(1.5)';
    } else {
        cursor.style.transform = 'scale(1)';
    }
});

scrollTop =
       window.pageYOffset || document.documentElement.scrollTop;
        
        
// Get the current page scroll position in the horizontal direction 

 scrollLeft =
   window.pageXOffset || document.documentElement.scrollLeft;
   
   
  // if any scroll is attempted,
 // set this to the previous value
 window.onscroll = function() {
  window.scrollTo(scrollLeft, scrollTop);
 };
