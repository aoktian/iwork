<?php $this->layout('layouts/plane', ['title' => '注册帐号']) ?>
<?php $this->start('body') ?>

<div class="line"></div>

<div class="row"><div class="col-md-4 col-md-offset-4">

<img src="/assets/logo.png">
<hr/>
<?php if (count($errors) > 0) :?>
<div class="alert alert-danger">
	<p><strong>Whoops!</strong> There were some problems with your input.</p>
	<p></p>
	<ul>
		<?php foreach ($errors as $error) :?>
			<li><?= $error ?></li>
		<?php endforeach?>
	</ul>
</div>
<?php endif?>

<form method="post" action="/auth/register" autocomplete="off">
<div class="form-group">
    <div class="input-group input-group-lg">
        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
        <input class="form-control" placeholder="邮箱" type="email" name="row[email]" value="" />
    </div>
</div>

<div class="form-group">
    <div class="input-group input-group-lg">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        <input class="form-control" placeholder="姓名" type="text" name="row[name]" value="" />
    </div>
</div>
<div class="form-group">
    <div class="input-group input-group-lg">
        <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
        <select name="row[department]" class="form-control">
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => 0 ]) ?>
        </select>
    </div>
</div>

<div class="form-group">
    <div class="input-group input-group-lg">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
        <input class="form-control" placeholder="密码" type="password" name="row[password]" />
    </div>
</div>
<div class="form-group">
    <div class="input-group input-group-lg">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
        <input class="form-control" placeholder="确认密码" type="password" name="password_confirmation" />
    </div>
</div>


<div class="form-group">
    <button class="btn btn-success btn-lg btn-block" type="submit" id="loginBtn">注册</button>
</div>

</form>

<hr />
<a href="/auth/login" class="btn btn-default btn-lg btn-block">已有帐号，去登录</a>

</div></div>

<?php $this->end() ?>
