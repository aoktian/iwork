<?php $this->layout('layouts/dashboard', ['title' => '维度统计']) ?>
<?php $this->start('main') ?>

<?php
$select = [
  'type' => '分类',
  'caty' => '类型', 'leader' => '成员', 'status' => '状态',
  'module' => '模块', 'pro' => '项目', 'tag' => '版本',
  'author' => '报告', 'tester' => '验收'

];
?>
<div class="alert alert-success">
<div class="row">

<div class="col-lg-12">
<form class="form-inline" role="form" method="GET" action="/tag/vvv">

<div class="form-group">
<select name="row[pro]" onchange="onFilterChangePro( this.value );" class="form-control">
  <option value="0">项目</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => isset($wheres['pro']) ? $wheres['pro'] : 0 ]) ?>
</select>
</div>

<div class="form-group">
<select name="row[tag]" class="form-control" id="filterTags">
  <option value="0">版本</option>
<?php $this->insert('selection-users', ['data' => $tags, 'slt' => isset($wheres['tag']) ? $wheres['tag'] : 0 ]) ?>
</select>
</div>

<div class="form-group">
<select name="row[department]" class="form-control" onchange="onChangeDepartment( this.value )">
  <option value="0">部门</option>
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => isset($wheres['department']) ? $wheres['department'] : 0 ]) ?>
</select>
</div>

<div class="form-group">
<select name="row[leader]" class="form-control"  id="leaders">
  <option value="0">负责人</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => isset($wheres['leader']) ? $wheres['leader'] : 0 ]) ?>
</select>
</div>

<div class="form-group">
<select name="row[caty]" onchange="onFilterChangePro( this.value );" class="form-control">
  <option value="0">分类</option>
<?php $this->insert('selection-users', ['data' => $catys, 'slt' => isset($wheres['caty']) ? $wheres['caty'] : 0 ]) ?>
</select>
</div>

<div class="form-group"> <div class="input-group">
      <span class="input-group-addon">开始</span>
<input value="<?=$t_start?>" name="t_start" class="form-control" type="text" onclick="showcalendar(event, this)">
</div></div>

<div class="form-group"> <div class="input-group">
      <span class="input-group-addon">结束</span>
<input value="<?=$t_end?>" name="t_end" class="form-control" type="text" onclick="showcalendar(event, this)">
</div></div>

<button class="btn btn-success"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> 查询</button>
</form>
</div>

</div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h2 class="panel-title">
      统计结果
    </h2>
  </div>
  <div class="panel-body">

<div class="form-inline">

<div class="form-group"> <div class="input-group">
      <span class="input-group-addon">维度A</span>
<select id="s1" class="form-control">
  <option value="0">选择</option>
<?php $this->insert('selection', ['data' => $select, 'slt' => 'caty' ]) ?>
</select>
</div></div>

<div class="form-group"> <div class="input-group">
      <span class="input-group-addon">维度B</span>
<select id="s2" class="form-control">
  <option value="0">选择</option>
<?php $this->insert('selection', ['data' => $select, 'slt' => 'leader' ]) ?>
</select>
</div></div>

<button onclick="onLook();" class="btn btn-success"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> 变换 </button>
</div>

<div class="line"></div>
<div class="line"></div>
<hr/>
<div id="myChart">

</div>

  </div>
</div>


<?php $this->end() ?>
<?php $this->start('js') ?>
<script type="text/javascript">
var users = <?=json_encode($users)?>;
var tags = <?=json_encode($tags)?>;

var sdata = <?php echo json_encode($a, JSON_UNESCAPED_UNICODE); ?>;

function onLook( ) {
  var s1 = $("#s1").val();
  if (s1 <= 0) {
    alert("维度A必须选择，维度B可以不选。")
    return;
  }

  var s2 = $("#s2").val();
  if (s2 <= 0) { return onLookA(s1); }

  if (s1 == s2) {
    alert("两个维度不能一样。");
    return;
  }

  onLookB( s1, s2 );
}

function onLookB( s1, s2 ) {
  var stats = {};
  var total = 0;
  var maxn = 0;
  for (var i in sdata) {
    var row = sdata[i];
    if (!stats[row[s1]]) {
      stats[row[s1]] = [];
    }
    if (!stats[row[s1]][row[s2]]) {
      stats[row[s1]][row[s2]] = 0;
    }

    stats[row[s1]][row[s2]]++;
    total++;

    if (stats[row[s1]][row[s2]] > maxn) {
      maxn = stats[row[s1]][row[s2]];
    }
  }

  var html = getTotal( total );
  for (var i in stats) {
    html += "<div class='row'>";
    html += "<div class='col-md-1'>";
    html += i;
    html += "</div>";
    html += "<div class='col-md-8'>";
    var j = 0;
    for (var k in stats[i]) {
      html += ppp( stats[i][k], maxn, total, k, j );
      j++;
    }
    html += "</div>";
    html += "</div><hr/>";
  }

  $("#myChart").html( html );
}

function getTotal( total ) {
  var html = "";
  html += "<div class='row'>";
  html += "<div class='col-md-1'>";
  html += "总量";
  html += "</div>";
  html += "<div class='col-md-8'>";
  html += ppp( total, total, total, "", 1 );
  html += "</div>";
  html += "</div><hr/>";

  return html;
}

function onLookA( s1 ) {
  var stats = {};
  var total = 0;
  var maxn = 0;
  for (var i in sdata) {
    var row = sdata[i];
    if (!stats[row[s1]]) {
      stats[row[s1]] = 0;
    }
    stats[row[s1]]++;
    total++;
    if (stats[row[s1]] > maxn) {
      maxn = stats[row[s1]];
    }
  }

  var html = getTotal( total );
  for (var i in stats) {
    html += "<div class='row'>";
    html += "<div class='col-md-1'>";
    html += i;
    html += "</div>";
    html += "<div class='col-md-8'>";
    html += ppp( stats[i], maxn, total, "", 0 );
    html += "</div>";
    html += "</div>";
  }

  $("#myChart").html( html );

}

function ppp( n, maxn, total, name, colori ) {
  var colors = ["", "progress-bar-success", "progress-bar-info", "progress-bar-warning", "progress-bar-danger"]
  var color = colors[colori % colors.length];
  var nn = Math.ceil(n / maxn * 100);
  var s = '<div class="progress">';
  s += '<div class="progress-bar ' + color + '" role="progressbar" aria-valuenow="';
  s += nn;
  s += '" aria-valuemin="0" aria-valuemax="100" style="width: ';
  s += nn + '%;text-align:left;padding-left:5px;min-width:150px">';
  if (name == "" ) {
    s += n;
  } else {
    s += name + " &nbsp;&nbsp;&nbsp;&nbsp;" + n + "/" + total + " " + (n/total * 100).toFixed(2) + "%";
  }
  s += '</div></div>';
  s += "<div class='line'></div>";
  return s;
}

$(document).ready(function( ) {
  onLook();
});
</script>

<?php $this->end() ?>
