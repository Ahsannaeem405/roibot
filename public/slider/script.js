

$(function() {
  $("#price-range").slider({
    step: 1,
    range: true,
    min: 18,
    max: 100,
    values: [18, 25],
    slide: function(event, ui)
    {$("#priceRange").val(ui.values[0] + " to " + ui.values[1]);}
  });
  $("#priceRange").val(18 +" to "+ 25);

});
