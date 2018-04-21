<?php foreach ($data as $v):?>
<option value="<?=$v->id?>" <?=$v->id == $slt ? 'selected' : ''?>><?=$v->name?></option>
<?php endforeach ?>
