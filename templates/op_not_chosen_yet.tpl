<div id="club_main_save_msg"></div>
<h2 class="club"><{$year}>學年度第<{$seme}>學期尚未選填學生一覽</h2>

<{if 'import'|have_apply_power}>
    <div class="text-right">
    <a href="index.php" class="btn btn-success">回社團一覽</a>
    <a href="index.php?op=batch_apply&year=<{$year}>&seme=<{$seme}>" class="btn btn-primary">批次亂數選填</a>
    </div>
<{/if}>
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