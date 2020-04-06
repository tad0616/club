<div class="pull-right bar">
    <{if 'destroy'|have_club_power and !$stu_arr}>
        <a href="javascript:club_main_destroy_func(<{$club_id}>);" class="btn btn-danger">刪除<{$club_title}></a>
    <{/if}>
    <{if 'update'|have_club_power}>
        <a href="<{$xoops_url}>/modules/club/index.php?op=club_main_edit&club_id=<{$club_id}>" class="btn btn-warning">編輯<{$club_title}></a>
    <{/if}>
    <{if 'create'|have_club_power}>
        <a href="<{$xoops_url}>/modules/club/index.php?op=club_main_create" class="btn btn-primary">新增社團</a>
    <{/if}>
    <a href="<{$xoops_url}>/modules/club/" class="btn btn-success"><{$smarty.const._TAD_HOME}></a>
</div>


<h2 class="club">
<{$club_year}>-<{$club_seme}> <{$club_title}>
</h2>

<div class="alert alert-warning">
    <span data-toggle="tooltip" title="招收人數"><i class="fa fa-users"></i> <{$club_num}></span>
    <span data-toggle="tooltip" title="上課地點"><i class="fa fa-map-marker"></i> <{$club_place}></span>
    <span data-toggle="tooltip" title="社團老師"><i class="fa fa-user-circle-o"></i> <{$club_tea_uid_name}></span>
</div>

<{if $club_desc}>
    <div class="my-border">
        <{$club_desc}>
    </div>
<{/if}>

<{if $club_note}>
    <div class="alert alert-info">
        <{$club_note}>
    </div>
<{/if}>


<{if 'update'|have_club_power}>
    <div class="pull-right bar">
        <a href="index.php?op=choice_result_random&club_id=<{$club_id}>" class="btn btn-primary">亂數錄取</a>
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
                <th>成績</th>
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
                <td><{$stu.club_score}></td>
                <td>
                <{if $stu.choice_result==''}>
                    <a href="index.php?op=choice_result_ok&apply_id=<{$stu.apply_id}>&club_id=<{$club_id}>"><img src="images/checked.png" alt="優先錄取" data-toggle="tooltip" title="優先錄取"></a>
                <{/if}>
                </td>
            </tr>
        <{/foreach}>
        </tbody>
    </table>
<{/if}>



<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
