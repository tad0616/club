<h2 class="club"><{$year}>學年度第<{$seme}>學期尚未錄取學生一覽</h2>

<div class="pull-right float-right bar">
    <a href="index.php" class="btn btn-success"><i class="fa fa-undo" aria-hidden="true"></i> 回社團一覽</a>
    <{if 'import'|have_apply_power and $clubs_not_ok_sum!=0}>
        <a href="index.php?op=choice_result_all_random" class="btn btn-primary" data-toggle="tooltip" title="將 <{$clubs_not_ok_sum}> 位尚未錄取的學生依照其志願序優先順序隨機錄取"> <i class="fa fa-check-square-o" aria-hidden="true"></i> 批次亂數錄取</a>
    <{/if}>
</div>
<table class="table table-striped table-hover" style="background:white;margin:20px auto;">
    <thead>
        <tr class="info">

            <th>
                班級
            </th>
            <th>
                尚未錄取者（共 <{$clubs_not_ok_sum}> 位）
            </th>
        </tr>
    </thead>

    <tbody>
        <{foreach from=$no_result_yet key=class item=stu_arr}>
            <tr>
                <td class="no">
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

<{if $not_chosen}>
    無選填志願者：
    <{foreach from=$not_chosen item=stu}>
    <{$stu.stu_grade}>年<{$stu.stu_class}>班 <{$stu.stu_name}> (<{$stu.stu_seat_no}>) <br>
    <{/foreach}>
<{/if}>
<{if $not_data}>
    查無學生資料者：
    <{foreach from=$not_data item=stu_id}>
        <{$stu_id}>
    <{/foreach}>
<{/if}>