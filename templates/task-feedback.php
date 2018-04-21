<?php foreach ($feedbacks as $feedback) :?>
<?php $this->insert('feedback-one', [ 'feedback' => $feedback, 'users' => $users ]) ?>
<?php endforeach ?>

<br>
<br>
<div class="line"></div>
<blockquote><p class="text-primary">在下面提交完成情况、测试反馈、其他意见......</p></blockquote>
<form method="POST" action="/feedback/store" onsubmit="return commitFeedback( );">
<input type="hidden" id="formFeedbackContent" name="row[message]">
<input type="hidden" name="row[pid]" value="<?=$task->id?>">
<div class="row line">
<div class="col-lg-12">
      <textarea id="summernote" height="300"></textarea>
</div>
</div>
<div class="row line">
    <div class="col-sm-4">
        <button type="submit" class="btn btn-danger btn-lg btn-block"> 提交反馈 </button>
    </div>
</div>

</form>

<script type="text/javascript">
var users = <?=json_encode( $users )?>;
var tags = <?=json_encode($tags)?>;

$(document).ready(function( ) {
  initEditor( "summernote" );
});
</script>
