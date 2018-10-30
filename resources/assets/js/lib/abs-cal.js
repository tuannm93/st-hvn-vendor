
var disp_day_of_the_week = ['日', '月', '火', '水', '木', '金', '土'];
var disp_month_subtitle = ['JANUARY', 'FEBURARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];

/*
 * Calender
 */
function Cal() 
{
}

/*
 * 
 */
Cal.prototype.dispDayOfWeek = disp_day_of_the_week;
Cal.prototype.dispMonthSubtitle = disp_month_subtitle;
Cal.prototype.calendarBaseClass = "abs-cal";
Cal.prototype.calendarCaptionMonthMainClass = "month_main";
Cal.prototype.calendarCaptionMonthSubClass = "month_sub";
Cal.prototype.calendarCaptionYearClass = "year_main";
Cal.prototype.calendarDateDisabledClass = "grayout";
Cal.prototype.calendarDateSundayClass = "sunday";
Cal.prototype.calendarDateSaturdayClass = "saturday";
Cal.prototype.calendarDateHolidayClass = "holiday";
Cal.prototype.calendarParentId = null;
Cal.prototype.calendarYear = 2015;
Cal.prototype.calendarMonth = 10;
Cal.prototype.holidays = [];
Cal.prototype.getCalendarYM = function () { return this.calendarYear + ("0" + this.calendarMonth).slice(-2);}
/*
 * 
 */
Cal.prototype.onDateDisplayed = function() {
}

/*
 * 
 */
Cal.prototype.CONST_INDEX_SUNDAY = 0;
Cal.prototype.CONST_INDEX_SATURDAY = 6;
Cal.prototype.CONST_START_DAY_OF_WEEK = 1;   //カレンダーの始まりの曜日設定 0:日〜6:土

/*
 * 
 */
Cal.prototype.display = function () {
	var context_cal = this.makeContext();
	context_cal.appendChild(this.makeCalendar());
	
	var div = document.getElementById(this.calendarParentId);
	if (div.hasChildNodes())
		div.removeChild(div.firstChild);
	
	div.appendChild(context_cal);	
}

Cal.prototype.makeContext = function () {
	var div_context = document.createElement("div");
	div_context.className = this.calendarBaseClass;	
	
	return div_context;
}

Cal.prototype.makeCalendar = function () {
	var table_cal = document.createElement('table');
	var caption_cal = this.makeCalendarCaption();
	var thead_cal = this.makeCalendarHead();
	var tbody_cal = this.makeCalendarBody();
	
	table_cal.appendChild(caption_cal);
	table_cal.appendChild(thead_cal);
	table_cal.appendChild(tbody_cal);
	
	return table_cal;
}

Cal.prototype.makeCalendarCaption = function () {
	var div = document.createElement('div');
	var span_main = document.createElement('span');
	span_main.className = this.calendarCaptionMonthMainClass;
	span_main.appendChild(document.createTextNode(this.calendarYear + "年" + this.calendarMonth + "月"));
	div.appendChild(span_main);
	/*
	 * Display month(main)
	 */
	/*
	var span_month_main = document.createElement('span');
	span_month_main.className = this.calendarCaptionMonthMainClass;
	span_month_main.appendChild(document.createTextNode(this.calendarMonth));
	div.appendChild(span_month_main);
	*/
	/*
	 * Display month(sub)
	 */
	/*
	var span_month_sub = document.createElement('span');
	span_month_sub.className = this.calendarCaptionMonthSubClass;
	span_month_sub.appendChild(document.createTextNode(this.dispMonthSubtitle[this.calendarMonth - 1]));
	div.appendChild(span_month_sub);	
	*/
	/*
	 * Display Year
	 */
	/*
	var span_month_sub = document.createElement('span');
	span_month_sub.className = this.calendarCaptionYearClass;
	span_month_sub.appendChild(document.createTextNode(this.calendarYear));
	div.appendChild(span_month_sub);
	*/
	var caption = document.createElement('caption');
	caption.appendChild(div);
	
	return caption;
}

Cal.prototype.makeCalendarHead = function () {
	var day_index = this.CONST_START_DAY_OF_WEEK;
	var thead = document.createElement('thead');		
	var tr = document.createElement('tr');
	for (var i = 0; i < this.dispDayOfWeek.length; i++) {
		var th = document.createElement('th');
		if (day_index == this.CONST_INDEX_SUNDAY) th.className = this.calendarDateSundayClass;
		if (day_index == this.CONST_INDEX_SATURDAY) th.className =  this.calendarDateSaturdayClass;
		th.appendChild(document.createTextNode(this.dispDayOfWeek[day_index]));
		tr.appendChild(th);
		
		day_index = (day_index < this.dispDayOfWeek.length - 1) ? (day_index + 1) : 0;
	}
	thead.appendChild(tr);
	
	return thead;
}

Cal.prototype.makeCalendarBody = function () {
	var tbody = document.createElement('tbody');
	
	date = new Date(this.calendarYear, this.calendarMonth - 1);
	while (date.getDay() != this.CONST_START_DAY_OF_WEEK) {
		date.setDate(date.getDate() - 1);
	}
	for (var row = 0; row < 6; row++) {
		var day_index = this.CONST_START_DAY_OF_WEEK;
		var tr = document.createElement('tr');
		for (var col = 0; col < this.dispDayOfWeek.length; col++) {
			var td = document.createElement('td');
			td.className = function () {
				if (this.calendarMonth != date.getMonth() + 1) return this.calendarDateDisabledClass;
				if (this.holidays.indexOf(date.getDate()) > - 1) return this.calendarDateHolidayClass;
				if (day_index == this.CONST_INDEX_SUNDAY) return this.calendarDateSundayClass;
				if (day_index == this.CONST_INDEX_SATURDAY) return  this.calendarDateSaturdayClass;
				
				return "";
			}.bind(this)();
			td.appendChild(document.createTextNode(date.getDate()));
			if (td.className != this.calendarDateDisabledClass && this.onDateDisplayed) this.onDateDisplayed(td);
			tr.appendChild(td);
			date.setDate(date.getDate() + 1);
			
			day_index = (day_index < this.dispDayOfWeek.length - 1) ? (day_index + 1) : 0
		}
		tbody.appendChild(tr);
	}
	
	return tbody;
}

Cal.prototype.nextMonth = function (go) {
	var _go = (!go) ? 1 : go;
	date = new Date(this.calendarYear, this.calendarMonth);
	date.setMonth((date.getMonth() + _go - 1));
	
	this.calendarMonth = date.getMonth() + 1;
	this.calendarYear = date.getFullYear();
	
	this.display();
}

Cal.prototype.prevMonth = function (go) {
	var _go = (!go) ? 1 : go;
	date = new Date(this.calendarYear, this.calendarMonth);
	date.setMonth((date.getMonth() - _go - 1));
	
	this.calendarMonth = date.getMonth() + 1;
	this.calendarYear = date.getFullYear();
	
	this.display();
}