$(document).keydown(function (e) {
	if (e != undefined) {
		// [F5]
		if (e.keyCode == 116) {
			e.keyCode = null;
			return false;
		}
		// [BackSpace]
		else if (e.keyCode == 8){
			if ((e.target.tagName.toLowerCase() == "input" && (e.target.type.toLowerCase() == "text" || e.target.type.toLowerCase() == "password" || e.target.type.toLowerCase() == "search"))
			 || (e.target.tagName.toLowerCase() == "textarea")) {
				return true;
			}
			else {
				return false;
			}
		}
		//[Enter]
		else if (e.keyCode == 13){
			if (e.target.tagName.toLowerCase() != "textarea") {
				if (e.target.tagName == "A" && e.target.href != "") {
					e.target.click();
					return false;
				}
				if (setFocusNextElement(e.target.id) == true){
					e.keyCode = null;
					return false;
				}
			}
		}
		else if (e.keyCode  == 119) {	// F8:登録
			if ($('regist')) {
				$('regist').click();
				return false;
			}
	   	}
		else if (e.keyCode  == 27) {	//
			if (typeof(attention_pop) != "undefined") {
				attention_pop.close();
				return false;
			}
	   	}
		return true;

	} else {
		// [F5]
		if (event.keyCode == 116) {
			event.keyCode = null;
			return false;
		}
		// [BackSpace]
		else if (event.keyCode == 8){
			if ((event.target.tagName.toLowerCase() == "input" && (event.target.type.toLowerCase() == "text" || event.target.type.toLowerCase() == "password"))
			 || (event.target.tagName.toLowerCase() == "textarea")) {
				return true;
			}
			else {
				return false;
			}
		}
		//[Enter]
		else if (event.keyCode == 13){
			if (event.target.tagName.toLowerCase() != "textarea") {
				if (event.target.tagName == "A" && event.target.href != "") {
					event.target.click();
					return false;
				}
				if (setFocusNextElement(event.target.id) == true){
					event.keycode = null;
					return false;
				}
			}
		}
		else if (event.keyCode  == 119) {	// F8:登録
			if ($('regist')) {
				$('regist').click();
				return false;
			}
	   	}
		else if (event.keyCode  == 27) {	// ESC
			if (typeof(attention_pop) != "undefined") {
				attention_pop.close();
				return false;
			}
	   	}
		return true;
	}
});

$.ajaxSetup({
    cache: false,
});

jQuery( function() {

	var idname;
	$('.datepicker').click(function () {
		idname = $(this).attr("id");
	});
	$('.datepicker').focus(function () {
		idname = $(this).attr("id");
	});
	var showAdditionalButton = function (input) {
		setTimeout(function() {
			var buttonPane = $( input ).datepicker( "widget" ).find( ".ui-datepicker-buttonpane" );
			var btn = $('<button class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all" type="button">2ヶ月後</button>');
			btn.unbind("click").bind("click", function (id) {
				var date = new Date();
				var after_two_months = date.getFullYear()  + "/" + (date.getMonth() + 3) + "/" + date.getDate();
				$('#'+idname).datepicker("setDate", after_two_months);
			});
			btn.appendTo( buttonPane );
		}, 1 );
    };

	jQuery( '.datepicker' ).datepicker({
		showButtonPanel: true,
		beforeShow: showAdditionalButton,       //追加ボタンを表示します。
		onChangeMonthYear: showAdditionalButton,
		onclick: showAdditionalButton,
	});

	jQuery( '.datepicker_limit' ).datepicker({
		showButtonPanel: true,
        maxDate: 0
	});

	jQuery( '.datetimepicker' ).datetimepicker({
		controlType: 'select',
		oneLine: true,
		timeText: '時間',
		hourText: '時',
		minuteText: '分',
		currentText: '現時刻',
		closeText: '閉じる',
		locale: 'ja'
	});

	jQuery( '.timepicker' ).timepicker({
		controlType: 'select',
    	timeOnlyTitle: '時刻を選択',
    	timeText: '時間',
    	hourText: '時',
    	minuteText: '分',
    	closeText: '閉じる',
		currentText: '現時刻'
	});
} );


(function($) {
	$.rits = {
		setAddress : function(zipForm, prefForm, addressForm1, addressForm2) {

			var zipCode = $("#" + zipForm).val();
			if (zipCode.length == 0) {
				return false;
			}
			var url = _ROOT_ + "ajax/searchaddressbyzip?" + "zip="
					+ encodeURI(zipCode);

			$.ajax({
				type : "GET",
				url : url,
				cache : false,
				success : function(json) {
                    if (!$.isEmptyObject(json)) {
						$("#" + prefForm).val(parseInt(json.MPost.jis_cd)).change();
						$("#" + addressForm1).val(json.MPost.address2);
						$("#" + addressForm2).val(json.MPost.address3);
					}
					if(0 < $(".auto-button").size()){
						autoButton();
					}
					if(0 < $("#business_trip_amount").size()){
						get_business_trip_amount();
					}
					if(0 < $("#selection_system").size()){
						get_selection_system_list();
					}
				},
				error : function() {
					alert("error");
				}
			});
		},

        // 2015.08.30 n.kai ADD start ORANGE-816 加盟店管理の場合、address2にaddress3の内容を追記する
		setAddress2 : function(zipForm, prefForm, addressForm1) {

            var zipCode = $("#" + zipForm).val();
            if (zipCode.length == 0) {
                return false;
            }
            var url = _ROOT_ + "ajax/searchaddressbyzip?" + "zip="
                    + encodeURI(zipCode);

            $.ajax({
                type : "GET",
                url : url,
                cache : false,
                success : function(json) {
                    if (!$.isEmptyObject(json)) {
                        $("#" + prefForm).val(parseInt(json.MPost.jis_cd));
                        $("#" + addressForm1).val(json.MPost.address2 + json.MPost.address3);
                    }
                    if(0 < $(".auto-button").size()){
                        autoButton();
                    }
                    if(0 < $("#business_trip_amount").size()){
                        get_business_trip_amount();
                    }
                    if(0 < $("#selection_system").size()){
                        get_selection_system_list();
                    }
                },
                error : function() {
                    alert("error");
                }
            });
        },
        // 2015.08.30 n.kai ADD end ORANGE-816

        escapeHTML : function(val) {
            return $('<div/>').text(val).html();
        },

        unescapeHTML : function(val) {
            return $('<div/>').html(val).text();
        }

	};

})(jQuery);

$(document).ready(function(){
    // 2015.06.02
    $(".multiple_check_filter").multiselect({
    	minWidth:300,
        selectedList: 50,
        checkAllText: "全選択",
        uncheckAllText: "選択解除",
        noneSelectedText: "--なし--",
    }).multiselectfilter({
        label:'',
        width:95
    });

	$(".check_filter").multiselect({
		minWidth:300,
		selectedList: 50,
		checkAllText: "全選択",
		uncheckAllText: "選択解除",
		multiple: false,
		noneSelectedText: "--なし--",
	}).multiselectfilter({
		label:'',
		width:95
	});
    $(".multiple_check").multiselect({
    	minWidth:300,
        selectedList: 50,
        checkAllText: "全選択",
        uncheckAllText: "選択解除",
        noneSelectedText: "--なし--",
    });
});



/**
/* 次のElementへフォーカスを移す
/* @param pElement
/* @return 結果
*/
function setFocusNextElement(pElement){
	var ret = false;
	var startPos = 0;
	//Div "contents"以下の全要素を取得する(なければ全体から取得)
	var contents = document.getElementById("contents");
	var children = document.getElementsByTagName('*');
//	if(contents){
//		children = contents.all;
//	}
	//現在のエレメントを検索
	for(var i = 0; i < children.length; i++){
		if(children[i].id == pElement) {
			startPos = i + 1;
			break;
		}
	}

	//自分よりも下のオブジェクトを探す
	for(i = startPos; i < children.length; i++){
		if(children[i].type == "text"
		    || children[i].type == "password"
		    || children[i].type == "select-one"
		    || children[i].type == "checkbox"
		    || children[i].type == "radio"
		    || children[i].type == "textarea"
		){
			try{
				if (children[i].disabled != true && children[i].style.visibility != 'hidden' && children[i].style.display != 'none') {
					children[i].focus();
					ret = true;
					break;
				}
			} catch(e) {}
		}

		//なければ頭から検索しなおし
		if(i == children.length -1) {
			i = 0;
		}
		//自分まで戻った場合は抜ける
		if(i == startPos - 1) {
			break;
		}
	}
	return ret;
}

// 2015.05.16 h.hara(S)
//1桁の数字を0埋めで2桁にする
var toDoubleDigits = function(num) {
  num += "";
  if (num.length === 1) {
    num = "0" + num;
  }
 return num;
};
//2015.05.16 h.hara(E)
