<h2 class="club">
    <form class="form-inline">
        <div class="form-group">
            <select name="club_ys" id="club_ys" class="form-control" onchange="location.href='main.php?club_ys='+this.value">
                <option value="">選學年學期</option>
                <{if $club_year_arr}>
                    <{foreach from=$club_year_arr item=year}>
                        <option value="<{$year}>-1" <{if $club_year == $year and $club_seme == 1}>selected<{/if}>><{$year}>學年度第 1 學期</option>
                        <option value="<{$year}>-2" <{if $club_year == $year and $club_seme == 2}>selected<{/if}>><{$year}>學年度第 2 學期</option>
                    <{/foreach}>
                    <option value="<{$year+1}>-1" <{if $club_year == $year+1 and $club_seme == 1}>selected<{/if}>><{$year+1}>學年度第 1 學期</option>
                    <option value="<{$year+1}>-2" <{if $club_year == $year+1 and $club_seme == 2}>selected<{/if}>><{$year+1}>學年度第 2 學期</option>
                <{else}>
                    <option value="<{$club_year}>-1" <{if $club_seme == 1}>selected<{/if}>><{$year}>學年度第 1 學期</option>
                    <option value="<{$club_year}>-2" <{if $club_seme == 2}>selected<{/if}>><{$year}>學年度第 2 學期</option>
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
            <{$club_year}>學年社團上課日期<br>製作點名簿的日期列用<br>請用「;」隔開，如範例
        </label>
        <div class="col-sm-10">
            <input type="text" name="club[club_date]" id="club_date" class="form-control" value="<{$setup.club_date.0}>" placeholder="3/9 (6);3/9 (7);3/16 (6);3/16 (7);3/23 (6);3/23 (7);3/30 (6);3/30 (7);4/13 (6);4/13 (7);4/20 (6);4/20 (7);6/8 (6);6/8 (7);6/22 (6);6/22 (7)
">
        </div>
    </div>

    <{if $clubs}>
        <div class="alert alert-success">
            <{$club_year}>學年第<{$club_seme}>學期已有 <{$clubs|@sizeof}> 個社團
        </div>
    <{else}>
        <div class="row">
            <div class="col-sm-6">
                <div class="alert alert-danger" role="alert">
                    <{$club_year}>學年第<{$club_seme}>學期尚無社團資料
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <select name="copy_from_ys" class="custom-select form-select">
                        <option value="">不複製社團資料</option>
                        <{foreach from=$club_ys_arr key=ys item=count}>
                            <option value="<{$ys}>">複製 <{$ys}> 的社團資料（共<{$count}>個）</option>
                        <{/foreach}>
                    </select>

                    <div class="input-group-prepend input-group-addon">
                        <div class="input-group-text">
                            <div class="form-check">
                                <input class="form-check-input mt-2" type="checkbox" id="copy_students" name="copy_students" value="1">
                                <label class="form-check-label" for="copy_students">
                                含學生資料
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group-prepend input-group-addon">
                        <span class="input-group-text">到<{$club_year}>學年第<{$club_seme}>學期</span>
                    </div>
                </div>


                <{* <div class="input-group">
                    <select name="copy_from_ys" class="form-control">
                        <option value="">不複製社團資料</option>
                        <{foreach from=$club_ys_arr key=ys item=count}>
                            <option value="<{$ys}>">複製 <{$ys}> 的社團資料（共<{$count}>個）</option>
                        <{/foreach}>
                    </select>
                    <div class="input-group-append input-group-addon">
                        <div class="input-group-text">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="copy_students" value="1">
                                含學生資料
                            </label>
                        </div>
                    </div>
                    <div class="input-group-append input-group-addon">
                        <span class="input-group-text">到<{$club_year}>學年第<{$club_seme}>學期</span>
                    </div>
                </div> *}>
            </div>
        </div>
    <{/if}>


    <div class="text-center" style="margin:30px auto;">
        <input type="hidden" name="club_ys" value="<{$club_year}>-<{$club_seme}>">
        <input type="hidden" name="op" value="save_club_officer">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>
