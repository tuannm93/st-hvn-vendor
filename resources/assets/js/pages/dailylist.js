var processDailyTab = (function () {
  
  var $dailyListEl = $('#daily_list');

  var getTabList = function () {
    return $dailyListEl.find('li.nav-item');
  };

  var removeColorClass = function (tabs) {
    $.each(tabs, function (i, el) {
      $(el).find('a').removeAttr('data-toggle');
      $(el).find('a').removeClass('color');
    });
  };
 
  var init = function () {
    // remove data-toggle attribute
    var tabs = getTabList();
    $.each(tabs, function (idx, el) {
      $(el).on('click', 'a', function(e) {
        e.preventDefault();
        // remove
        removeColorClass(tabs);
        
        $(this).addClass('color');
      });
    });
  };

  return {
    init: init
  };
})();

