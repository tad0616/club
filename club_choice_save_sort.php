<?php
require_once 'header.php';
$time = date("Y-m-d H:i:s");
$apply_id = (int) $_POST['apply_id'];
$sql = "update `" . $xoopsDB->prefix("club_choice") . "` set `choice_sort`=`choice_sort`+100 where `apply_id`='{$apply_id}'";
$xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . " ({$time})");

$sort = 1;
foreach ($_POST['sort'] as $club_id) {
    $club_id = (int) $club_id;
    $sql = "update `" . $xoopsDB->prefix("club_choice") . "` set `choice_sort`='{$sort}' where `apply_id`='{$apply_id}' and `club_id`='{$club_id}'";
    $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . " ({$time})");
    $sort++;
}

$sql = "update `" . $xoopsDB->prefix("club_apply") . "` set `apply_time`='{$time}' where `apply_id`='{$apply_id}'";
$xoopsDB->queryF($sql);

echo "<div class='alert alert-success'>社團志願序已於 {$time} 重新排序，您可以繼續調整排序，排序完成後即可直接離開</div>";
