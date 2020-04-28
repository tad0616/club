<!--套用formValidator驗證機制-->
<form action="<{$smarty.server.PHP_SELF}>" method="post" id="myForm" enctype="multipart/form-data"class="form-horizontal">

    <!--社團名稱-->
    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_CLUB_CLUB_TITLE}>
        </label>
        <div class="col-sm-4">
            <input type="text" name="club_title" id="club_title" class="form-control validate[required]" value="<{$club_title}>" placeholder="<{$smarty.const._MD_CLUB_CLUB_TITLE}>">
        </div>

        <!--授課教師-->
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_CLUB_CLUB_TEA_NAME}>
        </label>
        <div class="col-sm-4">
            <input type="text" name="club_tea_name" id="club_tea_name" class="form-control " value="<{$club_tea_name}>" placeholder="<{$smarty.const._MD_CLUB_CLUB_TEA_NAME}>">
        </div>
    </div>

    <!--學年-->
    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_CLUB_CLUB_YEAR}>
        </label>
        <div class="col-sm-4">
            <input type="text" name="club_year" list="club_year" class="form-control validate[required]" value="<{$club_year}>" placeholder="<{$smarty.const._MD_CLUB_CLUB_YEAR}>">
            <datalist id="club_year">
                <{foreach from=$club_year_arr key=k item=title}>
                    <option value="<{$title}>">
                <{/foreach}>
            </datalist>
        </div>

        <!--學期-->
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_CLUB_CLUB_SEME}>
        </label>
        <div class="col-sm-4">
            <input type="text" name="club_seme" list="club_seme" class="form-control validate[required]" value="<{$club_seme}>" placeholder="<{$smarty.const._MD_CLUB_CLUB_SEME}>">
            <datalist id="club_seme">
                <{foreach from=$club_seme_arr key=k item=title}>
                    <option value="<{$title}>">
                <{/foreach}>
            </datalist>
        </div>
    </div>

    <!--上課人數-->
    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_CLUB_CLUB_NUM}>
        </label>
        <div class="col-sm-4">
            <input type="text" name="club_num" id="club_num" class="form-control validate[required]" value="<{$club_num}>" placeholder="<{$smarty.const._MD_CLUB_CLUB_NUM}>">
        </div>

        <!--地點-->
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_CLUB_CLUB_PLACE}>
        </label>
        <div class="col-sm-4">
            <input type="text" name="club_place" id="club_place" class="form-control " value="<{$club_place}>" placeholder="<{$smarty.const._MD_CLUB_CLUB_PLACE}>">
        </div>
    </div>

    <!--開放選填年級-->
    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_CLUB_CLUB_GRADE}>
        </label>
        <div class="col-sm-10">
            <{foreach from=$stu_can_apply_grade item=grade}>
                <div class="form-check form-check-inline checkbox-inline">
                    <label class="form-check-label" for="club_grade_<{$grade}>">
                        <input class="form-check-input validate[required]" type="checkbox" name="club_grade[]" id="club_grade_<{$grade}>" value="<{$grade}>" <{if $grade|in_array:$club_grade}>checked<{/if}>> 國中 <{$grade}> 年級
                    </label>
                </div>
            <{/foreach}>
        </div>

    </div>



    <!--課程說明-->
    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_CLUB_CLUB_DESC}>
        </label>
        <div class="col-sm-10">
            <{$club_desc_editor}>
        </div>
    </div>


    <!--備註-->
    <div class="form-group row">
        <label class="col-sm-2 control-label col-form-label text-md-right">
            <{$smarty.const._MD_CLUB_CLUB_NOTE}>
        </label>
        <div class="col-sm-10">
            <textarea name="club_note" rows=3 id="club_note" class="form-control " placeholder="<{$smarty.const._MD_CLUB_CLUB_NOTE}>"><{$club_note}></textarea>
        </div>
    </div>

    <div class="text-center">

        <!--教師uid-->
        <input type='hidden' name="club_tea_uid" value="<{$club_tea_uid}>">

        <{$token_form}>

        <input type="hidden" name="op" value="<{$next_op}>">
        <input type="hidden" name="club_id" value="<{$club_id}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>
