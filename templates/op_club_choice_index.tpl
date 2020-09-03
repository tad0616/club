<{if $clubs}>
    <h2 class="club"><{$apply.stu_grade}>年<{$apply.stu_class}>班<{$apply.stu_name}>的社團志願選填</h2>
    <{if $stu_edit_able}>
        <div id="club_choice_save_msg">
            <div class="alert alert-info">
                <ol>
                    <li>請直接按住社團名稱，拉動排序，進行志願序調整</li>
                    <li>左邊若為 <span class="choice_sort">?</span> 表示尚未進行志願排序</li>
                    <li>左邊若有數字，如： <span class="choice_sort">1</span> 表示該社團是您的第 1 志願</li>
                    <li>右邊數字，如：<span class="badge badge-success badge-pill">12</span>，表示有 12 個人將該社團設為第一志願</li>
                    <li>選填時間為 <{$setup.stu_start_sign.0}> 至 <{$setup.stu_stop_sign.0}> 止</li>
                </ol>
            </div>
        </div>
    <{else}>
        <div class="alert alert-danger">
        目前無法排序選填，選填時間為 <{$setup.stu_start_sign.0}> 至 <{$setup.stu_stop_sign.0}> 止
        </div>
    <{/if}>

    <{if 'import'|have_apply_power}>
        <div class="row">
            <div class="col-sm-5 text-left">
                <form action="index.php" method="post">
                    <div class="input-group">
                        <div class="input-group-prepend input-group-addon">
                            <span class="input-group-text">內定正取：</span>
                        </div>
                        <select name="club_id" id="club_id" class="form-control">
                            <option value=""></option>
                            <{foreach from=$club_choice key=club_id item=choice}>
                                <option value="<{$choice.club_id}>" <{if $choice.choice_result=="正取"}>selected<{/if}>><{$choice.club_title}></option>
                            <{/foreach}>
                        </select>
                        <div class="input-group-append input-group-btn">
                            <input type="hidden" name="apply_id" value="<{$apply_id}>">
                            <input type="hidden" name="to" value="clubTab1">
                            <button type="submit" class="btn btn-primary" name="op" value="choice_result_ok">儲存</button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="col-sm-7 text-right">
                <a href="index.php?op=not_chosen_yet&year=<{$year}>&seme=<{$seme}>" class="btn btn-success"><i class="fa fa-undo" aria-hidden="true"></i> 回尚未選填學生一覽</a>
                <{if $mode=='apply_by_officer'}>
                    <a href="index.php?op=batch_apply&year=<{$year}>&seme=<{$seme}>&apply_id=<{$apply_id}>&stu_id=<{$apply.stu_id}>" class="btn btn-primary"><i class="fa fa-random" aria-hidden="true"></i> 替<{$apply.stu_name}>亂數選填</a>
                <{/if}>
            </div>
        </div>
    <{/if}>

    <div class="row" id="club_choice_sort">
        <{foreach from=$club_choice key=club_id item=choice}>
            <div class="col-sm-4" id="sort_<{$club_id}>">
                <div class="club_choice" style="overflow: hidden; height: 2.6em; white-space: nowrap; <{if $choice.choice_result=="正取" and ($stu_can_see_result=='1' or ($stu_can_see_result=='0' and !$smarty.session.stu_id))}>background:yellow;<{/if}>" <{if $choice1.$club_id}>data-toggle="tooltip" title="已有<{$choice1.$club_id}>人將之設為第一志願"<{/if}>>
                    <span class="choice_sort"><{if $choice.choice_sort}><{$choice.choice_sort}><{else}>?<{/if}></span>
                    <{if $choice.choice_result=="正取" and ($stu_can_see_result=='1' or ($stu_can_see_result=='0' and !$smarty.session.stu_id))}>
                        <span style="color: blue;"><{$choice.choice_result}></span>
                        <{if $choice.club_score}> (<span style="color: green;"><{$choice.club_score}>分</span>)<{/if}>
                    <{/if}>
                    <a href="index.php?club_id=<{$choice.club_id}>"><{$choice.club_title}></a>
                    <{if $choice1.$club_id}>
                        <span class="badge badge-success badge-pill"><{$choice1.$club_id}></span>
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