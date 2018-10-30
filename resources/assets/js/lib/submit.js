// ボタンの連続防止
$(function(){
	$('form').submit(function() {
		$(this).submit(function() {
			return false;
		});
	});
});