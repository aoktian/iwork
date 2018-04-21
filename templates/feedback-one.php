<div class="panel panel-default">
    <div class="panel-heading">
<a name="feedback.<?=$feedback->id?>">#<?=$feedback->id?></a>
&nbsp;&nbsp;&nbsp;&nbsp;
作者：<?=$users[$feedback->author]->name?> (<?=$feedback->created_at?>)
&nbsp;&nbsp;&nbsp;&nbsp;
修改：<?=$users[$feedback->changer]->name?> (<?=$feedback->updated_at?>)
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="/feedback/edit/<?=$feedback->id?>">重新编辑</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="/feedback/show/<?=$feedback->id?>">修改日志</a>
    </div>
    <div class="panel-body" id="feedback-<?=$feedback->id?>">
<?=$feedback->message;?>
    </div>
    <!-- /.panel-body -->
</div>
