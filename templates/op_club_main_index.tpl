
<h2 class="club"><{$year}>學年度第<{$seme}>學期社團一覽</h2>


<div class="my-border">
<{if $setup.stu_start_sign.0 and $setup.stu_stop_sign.0}>
    <ol>
        <{if $stu_edit_able}>
            <li>選填時間為 <{$setup.stu_start_sign.0}> 至 <{$setup.stu_stop_sign.0}> 止</li>
        <{else}>
            <li>目前無法排序選填，選填時間為 <{$setup.stu_start_sign.0}> 至 <{$setup.stu_stop_sign.0}> 止</li>
        <{/if}>
        <{if 'update'|have_club_power}>
            <{if $not_chosen_yet_count > 0}>
                <li><{$chosen_count}> 人已完成社團志願選填，
                <a href="index.php?op=not_chosen_yet&year=<{$year}>&seme=<{$seme}>"><{$not_chosen_yet_count}> 人</a>尚未選填。</li>
            <{else}>
                <li>共 <{$chosen_count}> 人，已全數完成社團志願選填。</li>
            <{/if}>

            <li>已正取數共 <{$clubs_ok_sum}> 人，尚未正取數共 <a href="index.php?op=no_result_yet&year=<{$year}>&seme=<{$seme}>"><{$clubs_not_ok_sum}></a> 人。</li>
        <{/if}>
    </ol>
<{else}>
    尚未進行學期相關設定，<a href="admin/main.php">請至後台設定之</a>。
<{/if}>
</div>

<div class="text-right" style="margin:10px auto;">
    <{if 'create'|have_club_power}>
        <a href="<{$xoops_url}>/modules/club/index.php?op=club_main_create" class="btn btn-info"><i class="fa fa-plus"></i> 新增社團</a>
    <{/if}>
    <{if 'import'|have_apply_power and $not_chosen_yet_count!=0 and $chosen_count!=0 and !$stu_edit_able}>
        <a href="index.php?op=batch_apply&year=<{$year}>&seme=<{$seme}>" class="btn btn-primary" data-toggle="tooltip" title="替 <{$not_chosen_yet_count}> 位尚未完成填寫志願序的學生進行隨機亂數選填"><i class="fa fa-random" aria-hidden="true"></i> 替<{$not_chosen_yet_count}>位學生批次亂數選填</a>
    <{elseif 'update'|have_club_power and $not_chosen_yet_count==0 and $clubs_not_ok_sum!=0 and !$stu_edit_able}>
        <a href="index.php?op=choice_result_all_random" class="btn btn-primary" data-toggle="tooltip" title="將 <{$clubs_not_ok_sum}> 位尚未錄取的學生依照其志願序優先順序隨機錄取"><i class="fa fa-check-square-o" aria-hidden="true"></i> 批次亂數錄取</a>
    <{elseif 'download'|have_club_power and $not_chosen_yet_count==0 and $clubs_not_ok_sum==0 and !$stu_edit_able}>
        <a href="<{$xoops_url}>/modules/club/excel_list_by_club.php?year=<{$year}>&seme=<{$seme}>" class="btn btn-success"><i class="fa fa-download"></i> 各社團名單</a>
        <a href="<{$xoops_url}>/modules/club/excel_list_by_class.php?year=<{$year}>&seme=<{$seme}>" class="btn btn-success"><i class="fa fa-download"></i> 各班級名單</a>
        <a href="<{$xoops_url}>/modules/club/excel_club_stu.php?year=<{$year}>&seme=<{$seme}>" class="btn btn-primary"><i class="fa fa-users"></i> 社團點名表</a>
    <{/if}>
</div>

<{if $clubs}>
    <table class="table table-striped table-hover" style="background:white;">
        <thead>
            <tr class="info">

                <!--學年學期-->
                <th><{$smarty.const._MD_CLUB_CLUB_YEAR}><{$smarty.const._MD_CLUB_CLUB_SEME}></th>
                <!--社團名稱-->
                <th><{$smarty.const._MD_CLUB_CLUB_TITLE}></th>
                <!--上課人數-->
                <th><{$smarty.const._MD_CLUB_CLUB_NUM}></th>
                <th>第一志願數</th>
                <th>已正取數</th>
                <!--授課教師-->
                <th><{$smarty.const._MD_CLUB_CLUB_TEA_NAME}></th>
                <!--地點-->
                <th><{$smarty.const._MD_CLUB_CLUB_PLACE}></th>
                <{if 'update'|have_club_power}>
                    <th><{$smarty.const._TAD_FUNCTION}></th>
                <{/if}>
            </tr>
        </thead>

        <tbody id="club_main_sort">
            <{foreach from=$clubs key=club_id item=data}>
                <tr id="tr_<{$data.club_id}>">
                    <!--學年學期-->
                    <td><{$data.club_year}>-<{$data.club_seme}></td>
                    <!--社團名稱-->
                    <td>
                        <a href="<{$xoops_url}>/modules/club/index.php?club_id=<{$data.club_id}>" <{if $choice1.$club_id > $data.club_num and ($not_chosen_yet_count or $clubs_not_ok_sum) }>style="color:red;"<{/if}>>
                        <{$data.club_title}>
                        </a>
                    </td>
                    <!--上課人數-->
                    <td><{$data.club_num}></td>
                    <!--第一志願數-->
                    <td><{$choice1.$club_id}></td>
                    <!--已正取數-->
                    <td><{$clubs_ok_num.$club_id}></td>
                    <!--授課教師-->
                    <td><{$data.club_tea_name}></td>
                    <!--地點-->
                    <td><{$data.club_place}></td>
                    <{if 'update'|have_club_power}>
                        <td nowrap>
                            <{if !$choice1.$club_id and !$clubs_ok_num.$club_id}>
                                <a href="javascript:club_main_destroy_func(<{$data.club_id}>);" class="btn btn-sm btn-danger" title="<{$smarty.const._TAD_DEL}>"><i class="fa fa-trash-o"></i></a>
                            <{/if}>
                            <a href="<{$xoops_url}>/modules/club/index.php?op=club_main_edit&club_id=<{$data.club_id}>" class="btn btn-sm btn-warning" title="<{$smarty.const._TAD_EDIT}>"><i class="fa fa-pencil"></i></a>
                            <{if 'download'|have_club_power}>
                                <a href="<{$xoops_url}>/modules/club/excel_club_stu.php?club_id=<{$data.club_id}>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="下載「<{$data.club_title}>」的點名表"><i class="fa fa-users"></i></a>
                                <a href="<{$xoops_url}>/modules/club/excel_score_import.php?club_id=<{$data.club_id}>" class="btn btn-sm btn-warning" data-toggle="tooltip" title="下載「<{$data.club_title}>」的成績匯入檔"><i class="fa fa-download"></i></a>
                            <{/if}>

                        </td>
                    <{/if}>
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
