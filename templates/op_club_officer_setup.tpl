<h2 class="club">
<form class="form-inline">
    <div class="form-group">
        <select name="club_year" id="club_year" class="form-control" onchange="location.href='main.php?club_year='+this.value">
        <option value="">選年度</option>
        <{if $club_year_arr}>
            <{foreach from=$club_year_arr item=year}>
                <option value="<{$year}>" <{if $club_year==$year}>selected<{/if}>><{$year}>學年度</option>
            <{/foreach}>
        <{else}>
            <option value="<{$club_year}>"><{$club_year}></option>
        <{/if}>
        </select>
    </div>
    <div class="form-group">
    設定
    </div>
</form>
</h2>
<div class="alert alert-info">
只有老師利用OpenID登入過本站後，其姓名才會出現在下方選單中。
</div>
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
<form action="<{$smarty.server.PHP_SELF}>" method="post" id="myForm" class="form-horizontal">

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group row custom-gutter">
                <label class="col-sm-6 col-form-label text-sm-right control-label">
                    <{$club_year}>學年「社團承辦人」
                </label>
                <div class="col-sm-6">
                    <select class="form-control" name="club[officer]">
                        <option value="">請選擇社團承辦人</option>
                        <{foreach from=$teachers key=uid item=teacher}>
                            <option value="<{$uid}>" <{if $setup.officer.0==$uid}>selected<{/if}>><{$teacher.name}>（<{$teacher.uname}>）</option>
                        <{/foreach}>
                    </select>
                </div>
            </div>

        </div>
        <div class="col-sm-4">
            <div class="form-group row custom-gutter">
                <label class="col-sm-5 col-form-label text-sm-right control-label">
                    學生開始選填社團日期
                </label>
                <div class="col-sm-7">
                    <input type="text" name="club[stu_start_sign]" id="stu_start_sign" class="form-control" value="<{$setup.stu_start_sign.0}>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm', startDate:'%y-%M-%d'})" placeholder="">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group row custom-gutter">
                <label class="col-sm-5 col-form-label text-sm-right control-label">
                    學生結束選填社團日期
                </label>
                <div class="col-sm-7">
                    <input type="text" name="club[stu_stop_sign]" id="stu_stop_sign" class="form-control" value="<{$setup.stu_stop_sign.0}>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm', startDate:'%y-%M-%d'})" placeholder="">
                </div>
            </div>
        </div>
    </div>


    <div class="form-group row custom-gutter">
        <label class="col-sm-2 col-form-label text-sm-right control-label">
            <{$club_year}>學年社團上課日期<br>製作點名簿用<br>請用「;」隔開，如範例
        </label>
        <div class="col-sm-10">
            <input type="text" name="club[club_date]" id="club_date" class="form-control" value="<{$setup.club_date.0}>" placeholder="3/9 (6);3/9 (7);3/16 (6);3/16 (7);3/23 (6);3/23 (7);3/30 (6);3/30 (7);4/13 (6);4/13 (7);4/20 (6);4/20 (7);6/8 (6);6/8 (7);6/22 (6);6/22 (7)
">
        </div>
    </div>


    <div class="text-center">
        <input type="hidden" name="club_year" value="<{$club_year}>">
        <input type="hidden" name="op" value="save_club_officer">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>
