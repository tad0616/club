<{if 'update'|have_club_power}>
    <div class="pull-right bar">
        <a href="index.php?op=choice_result_random&club_id=<{$club_id}>" class="btn btn-primary"><i class="fa fa-check-square-o" aria-hidden="true"></i> 亂數錄取</a>
    </div>

    <h3 class="club" style="margin:20px auto;">
    <{$club_year}>-<{$club_seme}> 選填<{$club_title}>學生一覽
    </h3>

    <table class="table table-striped table-hover" style="background:white;margin:20px auto;">
        <thead>
            <tr class="info">
                <th></th>
                <th>班級</th>
                <th>座號</th>
                <th>姓名</th>
                <th>狀態</th>
                <th>功能</th>
            </tr>
        </thead>

        <tbody>
        <{foreach from=$stu_arr item=stu name=stu}>
            <tr>
                <td><{$smarty.foreach.stu.iteration}></td>
                <td><{$stu.stu_grade}>-<{$stu.stu_class}></td>
                <td><{$stu.stu_seat_no}></td>
                <td><a href="index.php?stu_id=<{$stu.stu_id}>"><{$stu.stu_name}></a></td>
                <td><{$stu.choice_result}></td>
                <td>
                <{if $stu.choice_result=='' and $ok_num < $club_num}>
                    <a href="index.php?op=choice_result_ok&apply_id=<{$stu.apply_id}>&club_id=<{$club_id}>"><img src="images/checked.png" alt="優先錄取" data-toggle="tooltip" title="優先錄取"></a>
                <{/if}>
                </td>
            </tr>
        <{/foreach}>
        </tbody>
    </table>
<{/if}>