<{if $clubs}>
    <h2 class="club"><{$apply.stu_name}>的社團志願選填</h2>
    <{if $stu_edit_able}>
        <div id="club_choice_save_msg">
            <div class="alert alert-info">
            1.請直接按住社團名稱，拉動排序，進行志願序調整<br>
            2.選填時間為 <{$setup.stu_start_sign.0}> 至 <{$setup.stu_stop_sign.0}> 止
            </div>
        </div>
    <{else}>
        <div class="alert alert-danger">
        目前無法排序選填，選填時間為 <{$setup.stu_start_sign.0}> 至 <{$setup.stu_stop_sign.0}> 止
        </div>
    <{/if}>

    <{if 'import'|have_apply_power}>
        <div class="text-right">
            <a href="index.php?op=not_chosen_yet&year=<{$year}>&seme=<{$seme}>" class="btn btn-success"><i class="fa fa-undo" aria-hidden="true"></i> 回尚未選填學生一覽</a>
            <{if $mode=='apply_by_officer'}>
                <a href="index.php?op=batch_apply&year=<{$year}>&seme=<{$seme}>&apply_id=<{$apply_id}>&stu_id=<{$apply.stu_id}>" class="btn btn-primary"><i class="fa fa-random" aria-hidden="true"></i> 替<{$apply.stu_name}>亂數選填</a>
            <{/if}>
        </div>
    <{/if}>

    <div class="row" id="club_choice_sort">
        <{foreach from=$club_choice key=club_id item=choice}>
            <div class="col-sm-4" id="sort_<{$club_id}>">
                <div class="club_choice" <{if $choice1.$club_id}>data-toggle="tooltip" title="有<{$choice1.$club_id}>人將之設為第 1 志願"<{/if}>>
                <span class="choice_sort"><{if $choice.choice_sort}><{$choice.choice_sort}><{else}>?<{/if}></span>
                <a href="index.php?club_id=<{$choice.club_id}>"><{$choice.club_title}></a>
                <{if $choice.choice_result=="正取"}>
                <img src="images/checked.png" alt="<{$choice.choice_result}>">
                <span style="color: blue;"><{$choice.choice_result}></span>
                <{/if}>
                </div>
            </div>
        <{/foreach}>
    </div>

    <{if $stu_edit_able}>
        <script type="text/javascript">
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
                $('#club_choice_sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
                    var order = $(this).sortable('serialize');
                    console.log(order);
                    $.post('club_choice_save_sort.php', order+"&apply_id=<{$apply_id}>", function(theResponse){
                        $('#club_choice_save_msg').html(theResponse);
                        $('.choice_sort').each(function( index ) {
                            var sort = index+1;
                            $(this).html(sort);
                        });
                    });
                }
                });
            });
        </script>
    <{/if}>
<{/if}>