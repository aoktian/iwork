<thead>
<tr>
<th width="20" class="text-center">
<input type="checkbox" onclick="checkall('tasklist', 'ids[]',  $(this).prop('checked') );"></th>
<th width="100" class="text-center"> 项目 </th>
<th width="100" class="text-center"> 版本 </th>
<th width="80" class="text-center"> 状态 </th>
<th width="50" class="text-center"> 等级 </th>
<th width="50" class="text-center"> 类型 </th>
<th>标题 </th>
<th width="80" class="text-center"> 部门 </th>
<th width="80" class="text-center"> 负责人 </th>
<th width="80" class="text-center"> 验收人 </th>
<th width="155" class="text-center">期限</th>
<th width="155" class="text-center">修改时间</th>
</tr>
</thead>
<tbody id="tasklist">
<?php $this->insert('task-list-content') ?>
</tbody>
