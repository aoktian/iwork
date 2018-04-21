
<?php $this->layout('layouts/dashboard', ['title' => '个人信息']) ?>
<?php $this->start('main') ?>

<div class="row">
	<div class="col-md-4 col-md-offset-1">

<form method="post" action="/user/update" autocomplete="off">
<div class="form-group">
    <div class="input-group input-group-lg">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        <input readonly class="form-control" type="text" name="name" value="<?= $user->name ?>" />
        <select name="department" class="form-control">
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => $user->department ]) ?>
        </select>
    </div>
</div>

<div class="form-group">
    <button class="btn btn-danger btn-lg btn-block" type="submit" id="loginBtn">提交修改</button>
</div>

</form>


</div></div>


<?php $this->end() ?>
