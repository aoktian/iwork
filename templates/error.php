<?php $this->layout('layouts/dashboard') ?>
<?php $this->start('main') ?>

<div class="row">
<div class="col-lg-12">
<div class="alert alert-danger">
	<h4>反馈信息</h4>
	<p><?=$msg?></p>
	<?php if (isset($backurl)) {?>
		<p>
			<a href="<?=$backurl?>" class="btn btn-link">点击返回</a>
		</p>
	<?php } ?>
</div>
</div>
</div>

<?php $this->end() ?>
