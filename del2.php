<?php
use XoopsModules\Club\Club_apply;
use XoopsModules\Club\Club_choice;
use XoopsModules\Club\Tools;
use XoopsModules\Tadtools\Utility;

include_once "header.php";
$year = 108;
$seme = 2;

// 找出所有社團
$club_arr = Tools::get_club($year, $seme);

// 找出600個學生
$sql = "select a.stu_id, a.stu_name, a.stu_email, b.stu_grade, b.stu_class, b.stu_seat_no from `xx_scs_students` as a
join `xx_scs_general` as b on a.stu_id=b.stu_id where b.school_year={$year} order by rand() limit 0,600";
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

while (list($stu_id, $stu_name, $stu_email, $stu_grade, $stu_class, $stu_seat_no) = $xoopsDB->fetchRow($result)) {
    if (empty($stu_email)) {
        $n1 = 109 - $stu_grade - 12;
        $class = sprintf("%'.02d", $stu_class);
        $seat_no = sprintf("%'.02d", $stu_seat_no);
        $stu_email = "st{$n1}{$stu_grade}{$class}{$seat_no}@tn.edu.tw";
    }

    // 取得申請編號
    $apply = Club_apply::get('', $stu_id, $year, $seme);
    $apply_id = $apply['apply_id'];
    // 若無申請編號建立之
    if (empty($apply_id)) {
        $apply_id = Club_apply::store($stu_id, $year, $seme, $stu_name, $stu_grade, $stu_class, $stu_seat_no);
    }
    shuffle($club_arr);
    $club_arr_list = implode(',', $club_arr);

    // 刪除原有申請
    $sql = "delete from `" . $xoopsDB->prefix("club_choice") . "`
    where `apply_id` = '{$apply_id}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    // 重新申請
    $choice_sort = 1;
    foreach ($club_arr as $club_id) {
        Club_choice::store($apply_id, $club_id, $choice_sort);
        $choice_sort++;
    }

    echo "<div>{$stu_name}({$apply_id}): $club_arr_list</div>";
}
