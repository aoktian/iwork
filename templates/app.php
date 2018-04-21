<?php $this->layout('layouts/plane', ['title' => '帐号登录']) ?>
<?php $this->start('body') ?>

<div class="line"></div>

<div class="row"><div class="col-md-4 col-md-offset-4">

<img src="/assets/logo.png">
<hr/>

<?php if (count($errors) > 0): ?>
<div class="alert alert-danger">
    <p><strong>Whoops!</strong> There were some problems with your input.</p>
    <p></p>
    <ul>
<?php foreach($errors as $error): ?>
    <li><?=$this->e($error)?></li>
<?php endforeach ?>
    </ul>
</div>
<?php endif ?>

<form method="post" action="/auth/login" autocomplete="off">
<div class="form-group">
    <div class="input-group input-group-lg">
        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
        <input class="form-control" placeholder="邮箱" type="email" name="email" />
    </div>
</div>
<div class="form-group">
    <div class="input-group input-group-lg">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
        <input class="form-control" placeholder="密码" type="password" name="password" />
    </div>
</div>


<div class="form-group">
    <button class="btn btn-success btn-lg btn-block" type="submit" id="loginBtn">登录</button>
</div>

</form>

<hr />
<a href="/auth/register" class="btn btn-default btn-lg btn-block">没有帐号，去注册</a>

</div></div>
<?php $this->end()?>
