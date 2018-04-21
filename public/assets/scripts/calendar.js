/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: calendar.js 21580 2011-04-01 02:22:19Z svn_project_zhangjie $
*/

var controlid = null;
var currdate = null;
var startdate = null;
var enddate  = null;
var yy = null;
var mm = null;
var hh = null;
var ii = null;
var currday = null;
var addtime = false;
var today = new Date();
var lastcheckedyear = false;
var lastcheckedmonth = false;

function c_$(id) {
    return document.getElementById(id);
}
function loadcalendar() {
	s = '';
	s += '<div id="calendar" style="display:none; position:absolute; z-index:100000;" onclick="c_doane(event)">';
	s += '<div style="width: 210px;"><table cellspacing="0" cellpadding="0" width="100%" style="text-align: center;">';
	s += '<tr align="center" id="calendar_week"><td><a href="javascript:;" onclick="refreshcalendar(yy, mm-1)" title="上一月">&laquo;</a></td><td colspan="5" style="text-align: center"><a href="javascript:;" onclick="showdiv(\'year\');c_doane(event)" class="dropmenu" title="点击选择年份" id="year"></a>&nbsp; - &nbsp;<a id="month" class="dropmenu" title="点击选择月份" href="javascript:;" onclick="showdiv(\'month\');c_doane(event)"></a></td><td><A href="javascript:;" onclick="refreshcalendar(yy, mm+1)" title="下一月">&raquo;</A></td></tr>';
	s += '<tr id="calendar_header"><td>日</td><td>一</td><td>二</td><td>三</td><td>四</td><td>五</td><td>六</td></tr>';
	for(var i = 0; i < 6; i++) {
		s += '<tr>';
		for(var j = 1; j <= 7; j++)
			s += "<td id=d" + (i * 7 + j) + ">&nbsp;</td>";
		s += "</tr>";
	}
	s += '<tr id="hourminute" class="pns"><td colspan="5" align="left"><input type="text" size="1" value="" id="hour" class="px vm" onKeyUp=\'this.value=this.value > 23 ? 23 : zerofill(this.value);controlid.value=controlid.value.replace(/\\d+(\:\\d+)/ig, this.value+"$1")\'> 点 <input type="text" size="1" value="" id="minute" class="px vm" onKeyUp=\'this.value=this.value > 59 ? 59 : zerofill(this.value);controlid.value=controlid.value.replace(/(\\d+\:)\\d+/ig, "$1"+this.value)\'> 分</td><td align="right" colspan="2"><button class="pn" onclick="confirmcalendar();"><em>确定</em></button></td></tr>';
	s += '</table></div></div>';
	s += '<div id="calendar_year" onclick="c_doane(event)" style="display: none;z-index:100001;"><div class="col">';
	for(var k = 2020; k >= 1931; k--) {
		s += k != 2020 && k % 10 == 0 ? '</div><div class="col">' : '';
		s += '<a href="javascript:;" onclick="refreshcalendar(' + k + ', mm);c_$(\'calendar_year\').style.display=\'none\'"><span' + (today.getFullYear() == k ? ' class="calendar_today"' : '') + ' id="calendar_year_' + k + '">' + k + '</span></a><br />';
	}
	s += '</div></div>';
	s += '<div id="calendar_month" onclick="c_doane(event)" style="display: none;z-index:100001;">';
	for(var k = 1; k <= 12; k++) {
		s += '<a href="javascript:;" onclick="refreshcalendar(yy, ' + (k - 1) + ');c_$(\'calendar_month\').style.display=\'none\'"><span' + (today.getMonth()+1 == k ? ' class="calendar_today"' : '') + ' id="calendar_month_' + k + '">' + k + ( k < 10 ? '&nbsp;' : '') + ' 月</span></a><br />';
	}
	s += '</div>';

	var div = document.createElement('div');
	div.innerHTML = s;
	c_$('append_parent').appendChild(div);
	document.onclick = function(event) {
		closecalendar(event);
	};
	c_$('calendar').onclick = function(event) {
		c_doane(event);
		c_$('calendar_year').style.display = 'none';
		c_$('calendar_month').style.display = 'none';
	};
}
function closecalendar(event) {
	c_$('calendar').style.display = 'none';
	c_$('calendar_year').style.display = 'none';
	c_$('calendar_month').style.display = 'none';
}

function parsedate(s) {
	/(\d+)\-(\d+)\-(\d+)\s*(\d*):?(\d*)/.exec(s);
	var m1 = (RegExp.$1 && RegExp.$1 > 1899 && RegExp.$1 < 2101) ? parseFloat(RegExp.$1) : today.getFullYear();
	var m2 = (RegExp.$2 && (RegExp.$2 > 0 && RegExp.$2 < 13)) ? parseFloat(RegExp.$2) : today.getMonth() + 1;
	var m3 = (RegExp.$3 && (RegExp.$3 > 0 && RegExp.$3 < 32)) ? parseFloat(RegExp.$3) : today.getDate();
	var m4 = (RegExp.$4 && (RegExp.$4 > -1 && RegExp.$4 < 24)) ? parseFloat(RegExp.$4) : 0;
	var m5 = (RegExp.$5 && (RegExp.$5 > -1 && RegExp.$5 < 60)) ? parseFloat(RegExp.$5) : 0;
	/(\d+)\-(\d+)\-(\d+)\s*(\d*):?(\d*)/.exec("0000-00-00 00\:00");
	return new Date(m1, m2 - 1, m3, m4, m5);
}

function settime(d) {
	if(!addtime) {
		c_$('calendar').style.display = 'none';
		c_$('calendar_month').style.display = 'none';
	}
	controlid.value = yy + "-" + zerofill(mm + 1) + "-" + zerofill(d) + (addtime ? ' ' + zerofill(c_$('hour').value) + ':' + zerofill(c_$('minute').value) : '');
}

function confirmcalendar() {
	if(addtime && controlid.value === '') {
		controlid.value = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate() + ' ' + zerofill(c_$('hour').value) + ':' + zerofill(c_$('minute').value);
	}
	closecalendar();
}

function c_getEvent() {
	if(document.all) return window.event;
	func = c_getEvent.caller;
	while(func != null) {
		var arg0 = func.arguments[0];
		if (arg0) {
			if((arg0.constructor  == Event || arg0.constructor == MouseEvent) || (typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {
				return arg0;
			}
		}
		func=func.caller;
	}
	return null;
}

function initclosecalendar() {
	var e = c_getEvent();
	var aim = e.target || e.srcElement;
	while (aim.parentNode != document.body) {
		if (aim.parentNode.id == 'append_parent') {
			aim.onclick = function () {closecalendar(e);};
		}
		aim = aim.parentNode;
	}
}
function showcalendar(event, controlid1, addtime1, startdate1, enddate1) {
    loadcalendar();
	controlid = controlid1;
	addtime = addtime1;
	startdate = startdate1 ? parsedate(startdate1) : false;
	enddate = enddate1 ? parsedate(enddate1) : false;
	currday = controlid.value ? parsedate(controlid.value) : today;
	hh = currday.getHours();
	ii = currday.getMinutes();
	var p = c_fetchOffset(controlid);
	c_$('calendar').style.display = 'block';
	c_$('calendar').style.left = p['left']+'px';
	c_$('calendar').style.top	= (p['top'] + 20)+'px';
	c_doane(event);
	refreshcalendar(currday.getFullYear(), currday.getMonth());
	if(lastcheckedyear != false) {
		c_$('calendar_year_' + lastcheckedyear).className = 'calendar_default';
		c_$('calendar_year_' + today.getFullYear()).className = 'calendar_today';
	}
	if(lastcheckedmonth != false) {
		c_$('calendar_month_' + lastcheckedmonth).className = 'calendar_default';
		c_$('calendar_month_' + (today.getMonth() + 1)).className = 'calendar_today';
	}
	c_$('calendar_year_' + currday.getFullYear()).className = 'calendar_checked';
	c_$('calendar_month_' + (currday.getMonth() + 1)).className = 'calendar_checked';
	c_$('hourminute').style.display = addtime ? '' : 'none';
	lastcheckedyear = currday.getFullYear();
	lastcheckedmonth = currday.getMonth() + 1;

	initclosecalendar();
}

function refreshcalendar(y, m) {
	var x = new Date(y, m, 1);
	var mv = x.getDay();
	var d = x.getDate();
	var dd = null;
	yy = x.getFullYear();
	mm = x.getMonth();
	c_$("year").innerHTML = yy;
	c_$("month").innerHTML = mm + 1 > 9  ? (mm + 1) : '0' + (mm + 1);

	for(var i = 1; i <= mv; i++) {
		dd = c_$("d" + i);
		dd.innerHTML = "&nbsp;";
		dd.className = "";
	}

	while(x.getMonth() == mm) {
		dd = c_$("d" + (d + mv));
		dd.innerHTML = '<a href="javascript:;" onclick="settime(' + d + ');return false">' + d + '</a>';
		if(x.getTime() < today.getTime() || (enddate && x.getTime() > enddate.getTime()) || (startdate && x.getTime() < startdate.getTime())) {
			dd.className = 'calendar_expire';
		} else {
			dd.className = 'calendar_default';
		}
		if(x.getFullYear() == today.getFullYear() && x.getMonth() == today.getMonth() && x.getDate() == today.getDate()) {
			dd.className = 'calendar_today';
			dd.firstChild.title = '今天';
		}
		if(x.getFullYear() == currday.getFullYear() && x.getMonth() == currday.getMonth() && x.getDate() == currday.getDate()) {
			dd.className = 'calendar_checked';
		}
		x.setDate(++d);
	}

	while(d + mv <= 40) {
		dd = c_$("d" + (d + mv));
		dd.innerHTML = "&nbsp;";
		d++;
	}

	if(addtime) {
		c_$('hour').value = zerofill(hh);
		c_$('minute').value = zerofill(ii);
	}
}

function c_fetchOffset(obj, mode) {
	var left_offset = 0, top_offset = 0, mode = !mode ? 0 : mode;

	if(obj.getBoundingClientRect && !mode) {
		var rect = obj.getBoundingClientRect();
		var scrollTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
		var scrollLeft = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
		if(document.documentElement.dir == 'rtl') {
			scrollLeft = scrollLeft + document.documentElement.clientWidth - document.documentElement.scrollWidth;
		}
		left_offset = rect.left + scrollLeft - document.documentElement.clientLeft;
		top_offset = rect.top + scrollTop - document.documentElement.clientTop;
	}
	if(left_offset <= 0 || top_offset <= 0) {
		left_offset = obj.offsetLeft;
		top_offset = obj.offsetTop;
		while((obj = obj.offsetParent) != null) {
			position = getCurrentStyle(obj, 'position', 'position');
			if(position == 'relative') {
				continue;
			}
			left_offset += obj.offsetLeft;
			top_offset += obj.offsetTop;
		}
	}
	return {'left' : left_offset, 'top' : top_offset};
}

function c_doane(event, preventDefault, stopPropagation) {
	var preventDefault = typeof(preventDefault) == 'undefined' ? 1 : preventDefault;
	var stopPropagation = typeof(stopPropagation) == 'undefined' ? 1 : stopPropagation;
	e = event ? event : window.event;
	if(!e) {
		e = c_getEvent();
	}
	if(!e) {
		return null;
	}
	if(preventDefault) {
		if(e.preventDefault) {
			e.preventDefault();
		} else {
			e.returnValue = false;
		}
	}
	if(stopPropagation) {
		if(e.stopPropagation) {
			e.stopPropagation();
		} else {
			e.cancelBubble = true;
		}
	}
	return e;
}

function showdiv(id) {
	var p = c_fetchOffset(c_$(id));
	c_$('calendar_' + id).style.left = p['left']+'px';
	c_$('calendar_' + id).style.top = (p['top'] + 16)+'px';
	c_$('calendar_' + id).style.display = 'block';
}

function zerofill(s) {
	var s = parseFloat(s.toString().replace(/(^[\s0]+)|(\s+$)/g, ''));
	s = isNaN(s) ? 0 : s;
	return (s < 10 ? '0' : '') + s.toString();
}
