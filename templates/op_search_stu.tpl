<h2 class="my">搜尋「<{$key}>」結果</h2>

<{if 'search_stu'|have_club_power}>
    <div class="row" style="margin:10px auto;">
        <div class="col-sm-5">
            <form action="index.php" method="post">
                <div class="input-group">
                    <div class="input-group-prepend input-group-addon">
                        <span class="input-group-text">搜尋學生：</span>
                    </div>
                    <input type="text" name="key" value="<{$key}>" class="form-control" placeholder="姓名或學號">
                    <div class="input-group-append input-group-btn">
                        <input type="hidden" name="year" value="<{$year}>">
                        <input type="hidden" name="seme" value="<{$seme}>">
                        <button type="submit" class="btn btn-primary" name="op" value="search_stu">搜尋</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-7 text-right" >
        </div>
    </div>
<{/if}>

<table class="table table-striped">
    <tr>
        <th>學期</th>
        <th>班級</th>
        <th>座號</th>
        <th>姓名</th>
        <th>學號</th>
        <th>填寫日期</th>
    </tr>
    <{foreach from=$all_stu item=stu}>
        <tr>
            <td><{$stu.stu_grade}>-<{$stu.stu_class}></td>
            <td><{$stu.stu_grade}>-<{$stu.stu_class}></td>
            <td><{$stu.stu_seat_no}></td>
            <td><a href="index.php?mode=apply_by_officer&stu_id=<{$stu.stu_id}>"><{$stu.stu_name}></a></td>
            <td><a href="index.php?mode=apply_by_officer&stu_id=<{$stu.stu_id}>"><{$stu.stu_no}></a></td>
            <td><{$stu.apply_time}></td>
        </tr>
    <{/foreach}>
</table>