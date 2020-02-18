$(function() {
  // May 12 2018
  //var then = new Date('2018.05.12').getTime();
  var then = new Date(2018, 04, 12).getTime();
  console.log("Date(2018, 04, 12) ",then);
  
  then = Math.round( then/(1000 * 3600 * 24) ); // convert milliseconds to days
  console.log("then ",then);
  
  var now = new Date().getTime();
  now = Math.round( now/(1000 * 3600 * 24) ); // convert milliseconds to days
  console.log("now ",now);
  
  var difference = now-then;
  $('#countUp').attr('data-count',difference);
  
  var $this = $('#countUp'),
  countTo = $this.attr('data-count');
  
  $({ countNum: $this.text()}).animate({
    countNum: countTo
  },{

    duration: 5000,
    easing:'linear',
    step: function() {
      $this.text(Math.floor(this.countNum));
    },
    complete: function() {
      $this.text(this.countNum);
      //alert('finished');
    }

  });  

});
