<h2 class="club"><{$year}>學年度第<{$seme}>學期尚未選填學生一覽</h2>

<div class="text-right">
    <a href="index.php" class="btn btn-success"><i class="fa fa-undo" aria-hidden="true"></i> 回社團一覽</a>
    <{if 'import'|have_apply_power and $not_chosen_yet_count!=0}>
        <a href="index.php?op=batch_apply&year=<{$year}>&seme=<{$seme}>" class="btn btn-primary"><i class="fa fa-random" aria-hidden="true"></i> 替<{$not_chosen_yet_count}>位學生批次亂數選填</a>
    <{/if}>
</div>

<table class="table table-striped table-hover" style="background:white;margin:20px auto;">
    <thead>
        <tr class="info">

            <th>
                班級
            </th>
            <th>
                尚未選填者
            </th>
        </tr>
    </thead>

    <tbody>
        <{foreach from=$not_chosen_yet_stu_arr key=class item=stu_arr}>
            <tr>
                <td>
                    <{$class}>（共<{$stu_arr|@sizeof}>位）
                </td>
                <td>
                <{foreach from=$stu_arr key=stu_seat_no item=stu}>
                    <a href="index.php?mode=apply_by_officer&stu_id=<{$stu.stu_id}>"><{$stu.stu_name}>(<{$stu_seat_no}>)</a>
                <{/foreach}>
                </td>
            </tr>
        <{/foreach}>
    </tbody>
</table>
<{if $not_data}>
    查無學生資料者：
    <{foreach from=$not_data item=stu_id}>
        <{$stu_id}>
    <{/foreach}>
<{/if}>