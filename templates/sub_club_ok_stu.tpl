<{if 'view_ok'|have_club_power}>
    <{if 'import'|have_apply_power}>
        <div class="pull-right float-right">
            <{if 'download'|have_club_power}>
                <div style="margin-bottom:4px;">
                    <a href="<{$xoops_url}>/modules/club/excel_club_stu.php?club_id=<{$club_id}>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="下載「<{$club_title}>」的點名表"><i class="fa fa-users"></i> 下載點名表</a>
                    <a href="<{$xoops_url}>/modules/club/excel_score_import.php?club_id=<{$club_id}>" class="btn btn-sm btn-warning" data-toggle="tooltip" title="下載「<{$club_title}>」的成績匯入檔"><i class="fa fa-download"></i> 下載成績匯入檔</a>
                </div>
            <{/if}>

            <form action="index.php" method="post" class="form-inline"  enctype="multipart/form-data">
                <input type="file"  name="scorefile" class="form-control" id="scorefile" placeholder="請選擇 xlsx 檔">
                <input type="hidden" name="club_id" value="<{$club_id}>">
                <button type="submit" name="op" value="import_score" class="btn btn-info">匯入成績</button>
            </form>
        </div>
    <{/if}>
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
                <th>功能</th>
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
                <td>
                    <a href="index.php?op=choice_result_del_ok&apply_id=<{$stu.apply_id}>&club_id=<{$club_id}>&to=clubTab1"><img src="images/multiply.png" alt="點我取消正取" data-toggle="tooltip" title="點我取消正取"></a>
                </td>
            </tr>
        <{/foreach}>
        </tbody>
    </table>
<{/if}>