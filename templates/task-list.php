<?php $this->layout('layouts/dashboard', ['title' => '任务清单']) ?>
<?php $this->start('main') ?>

<div class="panel panel-default">
  <div class="panel-body">

<div class="row">
<div class="col-lg-2">
  <div class="input-group">
    <input id="gid" type="text" class="form-control" placeholder="输入编号直接打开">
    <span class="input-group-btn">
      <button onclick="window.open( '/task/show/' + $('#gid').val() );" class="btn btn-default" type="button">Go!</button>
    </span>
  </div>
</div>

<div class="col-lg-3">
  <div class="input-group">
    <input id="stitle" type="text" class="form-control" placeholder="标题模糊查询">
    <span class="input-group-btn">
      <button onclick='getlist( "title=" + $("#stitle").val() ); ' class="btn btn-default" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search</button>
    </span>
  </div>
</div>

<div class="col-lg-1">
<?php if (I\Request::is('/task/checks')):?>
  <a href="/task/check" target="_blank" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 发布CHECK </a>
<?php else :?>
  <a href="/task/create" target="_blank" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 发布任务 </a>
<?php endif ?>
</div>

<div class="col-lg-6">
<form target="_blank"  class="form-inline" role="form" method="POST" action="/tag/stats">
<div class="form-group"> <div class="input-group">
<span class="input-group-addon">开始</span>
<input name="start" class="form-control" type="text" onclick="showcalendar(event, this)">
</div></div>

<div class="form-group"> <div class="input-group">
<span class="input-group-addon">结束</span>
<input name="end" class="form-control" type="text" onclick="showcalendar(event, this)">
</div></div>

<button class="btn btn-success"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> 查看统计</button>
</form>
</div>

</div>
</div>
</div>

<div class="row">
<div class="col-lg-12" id="taskfilter">
<input type="hidden" itag="val" name="search[author]" value="<?=(isset($options['author']) ? $options['author'] : 0)?>" >

<div class="form-inline">

<div class="form-group"> <div class="input-group">
<span class="input-group-addon">筛选条件：</span>
<select onchange="onFilterChangePro( this.value );topage( 1 );" itag="val" name="search[pro]" class="form-control">
<option value="0">项目</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => isset($options['pro']) ? $options['pro'] : 0 ]) ?>
</select>
</div></div>

<div class="form-group">
<select onchange="topage( 1 );" itag="val" name="search[tag]" class="form-control" id="filterTags">
<option value="0">版本</option>
<?php $this->insert('selection-users', ['data' => $tags, 'slt' => isset($options['tag']) ? $options['tag'] : 0 ]) ?>
</select>
</div>

<div class="form-group">
<select onchange="topage( 1 );" itag="val" name="search[status]" class="form-control">
<option value="0">状态</option>
<?php $this->insert('selection', ['data' => $status, 'slt' => isset($options['status']) ? $options['status'] : 0 ]) ?>
</select>
</div>

<div class="form-group">
<select onchange="topage( 1 );" itag="val" name="search[priority]" class="form-control">
<option value="0">优先级</option>
<?php $this->insert('selection', ['data' => $prioritys, 'slt' => isset($options['priority']) ? $options['priority'] : 0 ]) ?>
</select>
</div>

<div class="form-group">
<select onchange="topage( 1 );" itag="val" name="search[caty]" class="form-control">
<option value="0">类型</option>
<?php $this->insert('selection-users', ['data' => $catys, 'slt' => isset($options['caty']) ? $options['caty'] : 0 ]) ?>
</select>
</div>


<div class="form-group">
<select onchange="onFilterChangeDepartment(this.value);topage( 1 );" itag="val" name="search[department]" class="form-control">
<option value="0">部门</option>
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => isset($options['departments']) ? $options['departments'] : 0 ]) ?>
</select>
</div>

<div class="form-group">
<select onchange="topage( 1 );" itag="val" name="search[leader]" class="form-control" id="filterLeaders">
<option value="0">负责人</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => isset($options['leader']) ? $options['leader'] : 0 ]) ?>
</select>
</div>

<div class="form-group">
<select onchange="topage( 1 );" itag="val" name="search[tester]" class="form-control">
<option value="0">验收人</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => isset($options['tester']) ? $options['tester'] : 0 ]) ?>
</select>
</div>

<div class="form-group">
<select onchange="topage( 1 );" itag="val" name="search[isonline]" class="form-control">
<option value="0">线上</option>
<?php $this->insert('selection', ['data' => [0 => '否', 1 => '是'], 'slt' => isset($options['isonline']) ? $options['isonline'] : 0 ]) ?>
</select>
</div>

<div class="form-group">
<select onchange="topage( 1 );" itag="val" name="orderby" class="form-control" id="filterLeaders">
<option value="0">排序</option>
<?php $this->insert('selection', ['data' => ['updated_at' => '修改时间', 'deadline' => '限期时间'], 'slt' => $orderby ]) ?>
</select>
</div>

</div>
</div>
</div>
<div class="line"></div>

<table class="table table-bordered table-hover table-striped vertical-middle text-center">
<?php $this->insert('task-list-table') ?>
</table>

<?php $this->insert('task-changemore') ?>


<?php $this->end() ?>

<?php $this->start('js') ?>

<script type="text/javascript">
var users = <?=json_encode($users)?>;
var tags = <?=json_encode($tags)?>;

function topage( _page ) {
  if (_page) {
    page = _page;
  }
  var s = "page=" + page + "&" + get_form_values( "taskfilter" );
  s += "&title=" + $("#stitle").val();
  console.log(s);
  getlist( s );
}

function getlist( s ) {
  $.ajax({
    data: s,
    type: "GET",
    url: '/task/index',
    cache: false,
    success: function( res ) {
      $("#tasklist").html( res );
    }
  });
}

setInterval( "topage( );", 1000 * 60 * 5 );

function checkall( id, name, b ) {
    var els = $("#" + id + " :checkbox");
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        if (name == el.prop("name")) {
            if ("undefined" == typeof(b) ) {
                if (el.prop("checked")) {
                    el.prop("checked", false);
                } else {
                    el.prop("checked", true);
                }
            } else{
                el.prop("checked", b);
            }
        }
    }
}

</script>

<?php $this->end() ?>
