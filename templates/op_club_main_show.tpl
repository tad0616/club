<div class="pull-right bar">
    <{if 'destroy'|have_club_power and !$stu_arr}>
        <a href="javascript:club_main_destroy_func(<{$club_id}>);" class="btn btn-danger">刪除<{$club_title}></a>
    <{/if}>
    <{if 'update'|have_club_power}>
        <a href="<{$xoops_url}>/modules/club/index.php?op=club_main_edit&club_id=<{$club_id}>" class="btn btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 編輯<{$club_title}></a>
    <{/if}>
    <{if 'create'|have_club_power}>
        <a href="<{$xoops_url}>/modules/club/index.php?op=club_main_create" class="btn btn-info"><i class="fa fa-plus"></i> 新增社團</a>
    <{/if}>
    <a href="<{$xoops_url}>/modules/club/" class="btn btn-success"><i class="fa fa-undo" aria-hidden="true"></i> 回社團一覽</a>
</div>


<h2 class="club">
<{$club_year}>-<{$club_seme}> <{$club_title}>
</h2>

<div class="alert alert-warning">
    <span data-toggle="tooltip" title="正取人數 / 招收人數"><i class="fa fa-users"></i> <{$ok_num}>/<{$club_num}></span>
    <span data-toggle="tooltip" title="上課地點"><i class="fa fa-map-marker"></i> <{$club_place}></span>
    <span data-toggle="tooltip" title="社團老師"><i class="fa fa-user-circle-o"></i> <{$club_tea_uid_name}></span>
</div>

<{if $club_desc}>
    <div class="my-border">
        <{$club_desc}>
    </div>
<{/if}>

<{if 'view_ok'|have_club_power}>
    <div style="margin:30px auto;">
        <div id="clubTab">
            <ul class="resp-tabs-list vert">
                <{if $ok_num}><li> <{$club_title}>正式社員 </li><{/if}>
                <li> 將<{$club_title}>填為第一志願者 </li>
                <{if $club_note}><li> 備註 </li><{/if}>
            </ul>

            <div class="resp-tabs-container vert">
                <{if $ok_num}><div> <{includeq file="$xoops_rootpath/modules/$xoops_dirname/templates/sub_club_ok_stu.tpl"}> </div><{/if}>
                <div> <{includeq file="$xoops_rootpath/modules/$xoops_dirname/templates/sub_choice1_stu.tpl"}> </div>
                <{if $club_note}><div> <{$club_note}> </div><{/if}>
            </div>
        </div>
    </div>
<{/if}>

<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
