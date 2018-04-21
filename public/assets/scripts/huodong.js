var handler = { };
handler.caty1 = function( ) {
    var data = {
        iid : $("#iid").val(),
        ccf : { rate : $('#raterate').val() }
    };
    return data;
};
handler.caty2 = function( ) {
    var data = {
        iid : $("#iid").val(),
        ccf : {
            tab     : $('#act-name').val(),
            tabname : $('#act-name').val(),
            desc    : $('#act-desc').val(),
            list    : []
        }
    };
    $('#catys .panel-body').each(function(){
        var $this = $(this);
        var confList = {};
        confList.need      = $this.find('[name="need"]').val();
        confList.caty      = $this.find('[name="caty"]').val();
        confList.id        = $this.find('[name="id"]').val();
        confList.icon      = $this.find('[name="icon"]').val();
        confList.title     = $this.find('[name="title"]').val();

        var confListItems = [];
        $this.find('.conf-list-itmes').each(function(){
            var $this           = $(this);
            var confListItem    = {};
            confListItem.id     = $this.find('[name="id"]').val();
            confListItem.num    = $this.find('[name="num"]').val();
            confListItems.push(confListItem);
        });

        confList.items    =  confListItems;
        data.ccf.list.push(confList);
    });

    return data;
};

handler.caty3 = function( ) {
    var data = {
        iid : $("#iid").val(),
        ccf       : {
            icon      : $('#catys [name="icon"]').val(),
            zhuxian   : {
                drop : $('#catys [name="drop"]').val(),
                gg   : $('#catys [name="gg"]').val()
            },
            tabname   : $('#act-name').val(),
            desc      : $('#act-desc').val(),
            exchange  : []
        }
    };
    $('#catys .panel-body').each(function(){
        var $this = $(this);
        var confList = {};

        var confListNeed = {};
        $this.find('.conf-list-need').each(function(){
            var $this           = $(this);
            var id     = $this.find('[name="id"]').val();
            var num    = $this.find('[name="num"]').val();
            confListNeed[id] =num;
        });

        confList.need = confListNeed;

        var confListTarget = {};
        $this.find('.conf-list-target').each(function(){
            var $this           = $(this);
            var id     = $this.find('[name="id"]').val();
            var num    = $this.find('[name="num"]').val();
            confListTarget[id] =num;
        });

        confList.target = confListTarget;

        confList.limitNum        = $this.find('[name="limitNum"]').val();

        data.ccf.exchange.push(confList);
    });

    return data;
};

handler.caty4 = function( ) {
    var data = {
        iid       : "danbichongzhi",
        ccf       : {
            tab   : $('#act-name').val(),
            desc      : $('#act-desc').val(),
            list      : []
        }

    };
    $('#catys .panel-body').each(function(){
        var $this = $(this);
        var confList = {};
        confList.need      = $this.find('[name="need"]').val();
        confList.title     = $this.find('[name="title"]').val();
        confList.times     = $this.find('[name="times"]').val();

        var confListItems = [];
        $this.find('.conf-list-itmes').each(function(){
            var $this           = $(this);
            var confListItem    = {};
            confListItem.id     = $this.find('[name="id"]').val();
            confListItem.num    = $this.find('[name="num"]').val();
            confListItems.push(confListItem);
        });

        confList.items    =  confListItems;
        data.ccf.list.push(confList);
    });

    return data;
};

handler.caty5 = function( ) {
    var data = {
        iid : "dengluhaoli",
        ccf : {
            items : { }
        }
    };
    var confData = {};
    $('#catys .panel-body').each(function(){
        var $this = $(this);
        var confList = {};

        $this.find('.conf-list-item').each(function(){
            var $this              = $(this);
            var id                 = $this.find('[name="id"]').val();
            var num                = $this.find('[name="num"]').val();
            confList[id]           = num;
        });

        var day = $this.find('[name="day"]').val();
        data.ccf.items[day] = confList;
    });

    return data;
};

handler.caty6 = function(){
    var data = {
        iid  : 'zsfl',
        ccf  :  {
            times : $('#catys [name="times"]').val()
        }
    };
    return data;
};

handler.caty7 = function(){
    var data = {
        iid  : 'leichongdays',
        ccf  :  {
            items : { }
        }
    };

    var confData = {};
    $('#catys .panel-body').each(function(){
        var $this = $(this);
        var confList = {};

        $this.find('.conf-list-item').each(function(){
            var $this              = $(this);
            var id                 = $this.find('[name="id"]').val();
            var num                = $this.find('[name="num"]').val();
            confList[id]           = num;
        });

        var day = $this.find('[name="day"]').val();
        data.ccf.items[day] = confList;
    });
    return data;
};


function onChangeCaty( caty ) {
    $.ajax({
      url:'/huodong/caty/' + caty
    }).done(function(sss){
        $('#catys').html(sss);
    });
}

function copyList( o, n ) {
    var demo = $(o);
    for (var i = 0; i < n; i++) {
        demo = demo.parent();
    }
    demo.after(demo.clone());
}

function removeList( o, n ) {
    var demo = $(o);
    for (var i = 0; i < n; i++) {
        demo = demo.parent();
    }
    demo.remove();
}

function checkServers(){
    if(!$('#serverids').val()){
        alert('请选择区服！');
        return false;
    }else{
        return true;
    }
}

function checkName(){
    if(!$('#act-name').val() || !$('#act-desc').val()){
        alert('请输入活动名称和描述');
        return false;
    }else{
        return true;
    }
}

function checkTime(){
    if(!$('#start_time').val()||!$('#end_time').val()){
        alert('请输入活动开始时间和结束时间');
        return false;
    }else{
        return true;
    }
}

function onhuodongcommit( ) {
    var caty = $("#selectedcaty").val( );
    // if (!checker["caty" + caty]( )) {
    //   return false;
    // }

    if (!checkServers()||!checkTime() || !checkName()){
        return false;
    }

    $("#start_time").val( $("#start_time").val( ));
    $("#end_time").val( $("#end_time").val( ));

    data.start_time = Date.parse(new Date($("#start_time").val( ))) / 1000;
    data.end_time = Date.parse(new Date($("#end_time").val( ))) / 1000;

    var $oldstarttime = $("#oldstarttime").val();
    if ($oldstarttime > 0) {
        if ( $oldstarttime != data.start_time && $oldstarttime > (Date.parse(new Date()) / 1000) ) {
            alert("活动已经开启不能修改时间");
            return;
        }
    }

    var data = handler["caty" + caty]( );

    data.cutDay = $("#cutday").val( )*1;

    if ((data.end_time - data.start_time) < data.cutDay * 3600 * 24) {
        alert('领奖时间不能大于活动的时间');
        return false;
    }
    var dataJson = JSON.stringify( data );
    $("#dataaa").val( dataJson );

    //console.log(JSON.stringify( data )); return false;
    return true;
}
