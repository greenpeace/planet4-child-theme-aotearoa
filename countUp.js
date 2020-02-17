$('.countUp').each(function() {
  
  var now = Math.round(new Date().getTime());
  console.log("now: ",now);
  
  var then = new Date('2018.05.12').getTime(); // May 12 2018
  console.log("then: ",then);

  var difference = now-then;
  difference = Math.round( difference/(1000 * 3600 * 24)-1 ); // convert to days and subtract today
  
  $('.countUp').attr('data-count',difference);
  
  var $this = $(this),
      countTo = $this.attr('data-count');
  
  $({ countNum: $this.text()}).animate({
    countNum: countTo
  },

  {

    duration: 3000,
    easing:'swing',
    step: function() {
      $this.text(Math.floor(this.countNum));
    },
    complete: function() {
      $this.text(this.countNum);
      //alert('finished');
    }

  });  
  
  
});
