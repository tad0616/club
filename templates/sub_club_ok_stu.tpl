<{if 'view_ok'|have_club_power}>
    <h3 class="club" style="margin:20px auto;">
    <{$club_year}>-<{$club_seme}> <{$club_title}>正取社員一覽
    </h3>

    <table class="table table-striped table-hover" style="background:white;margin:20px auto;">
        <thead>
            <tr class="info">
                <th></th>
                <th>班級</th>
                <th>座號</th>
                <th>姓名</th>
                <th>志願序</th>
                <th>成績</th>
            </tr>
        </thead>

        <tbody>
        <{foreach from=$ok_stu item=stu name=stu}>
            <tr>
                <td><{$smarty.foreach.stu.iteration}></td>
                <td><{$stu.stu_grade}>-<{$stu.stu_class}></td>
                <td><{$stu.stu_seat_no}></td>
                <td><a href="index.php?stu_id=<{$stu.stu_id}>"><{$stu.stu_name}></a></td>
                <td>第 <{$stu.choice_sort}> 志願</td>
                <td><{$stu.club_score}></td>
            </tr>
        <{/foreach}>
        </tbody>
    </table>
<{/if}>