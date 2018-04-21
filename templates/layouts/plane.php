<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
<meta name="description" content="">
<meta name="author" content="aoktian">

<title><?=$this->e($title)?> - iWork </title>

<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="/assets/stylesheets/bootstrap.min.css" />
<link rel="stylesheet" href="/assets/stylesheets/dashboard.css" />
<link rel="stylesheet" href="/assets/stylesheets/calendar.css" />
<link rel="stylesheet" href="/assets/summernote/summernote.css" />

<script src="/assets/scripts/jquery.min.js"></script>
<script src="/assets/scripts/bootstrap.min.js"></script>
<script src="/assets/summernote/summernote.min.js"></script>
<script src="/assets/summernote/lang/summernote-zh-CN.js"></script>
<script src="/assets/scripts/calendar.js"></script>
<script src="/assets/scripts/worktime.js"></script>

</head>

<body>
  <div id="append_parent"></div>
<div class="container-fluid">
    <?=$this->section('body')?>
</div>

<hr>
<div class="container-fluid">
    <p></p>
    <p></p>
<p class="text-center">aoktian@foxmail.com</p>
    <p></p>
    <p></p>
</div>

<!-- Modal -->
<div class="modal fade" id="msg" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">提示信息</h4>
      </div>
      <div class="modal-body" id="msg-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"> 确 定 </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cfm" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">提示信息</h4>
      </div>
      <div class="modal-body" id="cfm-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"> 取 消 </button>
        <button type="button" class="btn btn-primary" id="cfm-btn"> 确 定 </button>
      </div>
    </div>
  </div>
</div>

<?=$this->section('js')?>
  </body>
</html>