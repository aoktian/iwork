<?php $this->layout('layouts/dashboard', ['title' => '编辑反馈']) ?>
<?php $this->start('main') ?>

<form method="POST" action="/feedback/store" onsubmit="$('#feedbackContent').val( $('#summernote').summernote( 'code' ) ); return true;">
<input type="hidden" name="id" value="<?=$feedback->id?>">
<input type="hidden" name="row[pid]" value="<?=$feedback->pid?>">
<input type="hidden" id="feedbackContent" name="row[message]">

<div class="row">
  <div class="col-lg-12">
    <div class="form-group">
      <div id="summernote" height="500"><?=$feedback->message?></div>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <button type="submit" class="btn btn-danger btn-lg btn-block"> 提 交 </button>
    </div>
    <div class="col-sm-2">
    </div>
</div>

</form>

<?php $this->end() ?>
<?php $this->start('js') ?>

<script type="text/javascript">
$(document).ready(function( ) {
  initEditor( "summernote" );
});
</script>
<?php $this->end() ?>
