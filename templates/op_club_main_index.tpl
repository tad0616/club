<div id="club_main_save_msg"></div>

<div class="pull-right bar">
    <{if 'create'|have_club_power}>
        <a href="<{$xoops_url}>/modules/club/index.php?op=club_main_create" class="btn btn-info"><i class="fa fa-plus"></i> 新增社團</a>
    <{/if}>
    <{if 'update'|have_club_power and $not_chosen_yet_count==0 and $not_ok_sum!=0}>
        <a href="index.php?op=choice_result_all_random" class="btn btn-primary"><i class="fa fa-check-square-o" aria-hidden="true"></i> 批次亂數錄取</a>
    <{/if}>
</div>

<h2 class="club"><{$year}>學年度第<{$seme}>學期社團一覽</h2>

<div class="alert alert-info">
<{if $not_chosen_yet_count > 0}>
    <{$chosen_count}> 人已完成社團志願選填，
    <a href="index.php?op=not_chosen_yet&year=<{$year}>&seme=<{$seme}>"><{$not_chosen_yet_count}> 人</a>尚未選填。
<{else}>
    共 <{$chosen_count}> 人，已全數完成社團志願選填。
<{/if}>
已正取數共 <{$ok_sum}> 人，
尚未正取數共 <a href="index.php?op=no_result_yet&year=<{$year}>&seme=<{$seme}>"><{$not_ok_sum}></a> 人。
</div>
<table class="table table-striped table-hover" style="background:white;">
    <thead>
        <tr class="info">

            <!--學年學期-->
            <th><{$smarty.const._MD_CLUB_CLUB_YEAR}><{$smarty.const._MD_CLUB_CLUB_SEME}></th>
            <!--社團名稱-->
            <th><{$smarty.const._MD_CLUB_CLUB_TITLE}></th>
            <!--上課人數-->
            <th><{$smarty.const._MD_CLUB_CLUB_NUM}></th>
            <{if $not_chosen_yet_count or $not_ok_sum}>
            <th>第一志願數</th>
            <{/if}>
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
                    <a href="<{$xoops_url}>/modules/club/index.php?club_id=<{$data.club_id}>" <{if $choice1.$club_id > $data.club_num and ($not_chosen_yet_count or $not_ok_sum) }>style="color:red;"<{/if}>>
                    <{$data.club_title}>
                    </a>
                </td>
                <!--上課人數-->
                <td><{$data.club_num}></td>
                <!--第一志願數-->
                <{if $not_chosen_yet_count or $not_ok_sum}>
                    <td><{$choice1.$club_id}></td>
                <{/if}>
                <!--已正取數-->
                <td><{$ok_num.$club_id}></td>
                <!--授課教師-->
                <td><{$data.club_tea_name}></td>
                <!--地點-->
                <td><{$data.club_place}></td>

                <{if 'update'|have_club_power}>
                    <td nowrap>
                        <{if !$choice1.$club_id and !$ok_num.$club_id}>
                            <a href="javascript:club_main_destroy_func(<{$data.club_id}>);" class="btn btn-sm btn-danger" title="<{$smarty.const._TAD_DEL}>"><i class="fa fa-trash-o"></i></a>
                        <{/if}>
                        <a href="<{$xoops_url}>/modules/club/index.php?op=club_main_edit&club_id=<{$data.club_id}>" class="btn btn-sm btn-warning" title="<{$smarty.const._TAD_EDIT}>"><i class="fa fa-pencil"></i></a>

                    </td>
                <{/if}>
            </tr>
        <{/foreach}>
    </tbody>
</table>


<{$bar}>