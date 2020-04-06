<?php
require_once __DIR__ . '/header.php';

$sort = 1;
foreach ($_POST['tr'] as $club_id) {
    $sql = "update `" . $xoopsDB->prefix("club_main") . "` set ``='{$sort}' where `club_id`='{$club_id}'";
    $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . " (" . date("Y-m-d H:i:s") . ")");
    $sort++;
}
echo _TAD_SORTED . " (" . date("Y-m-d H:i:s") . ")";
