<?php
use XoopsModules\Tadtools\Utility;

include_once "../../mainfile.php";
include_once "header.php";

$sql = "ALTER TABLE `xx_club_main`
CHANGE `club_grade` `club_grade` set('1','2','3','4','5','6','7','8','9') COLLATE 'utf8_general_ci' NOT NULL DEFAULT '7,8' AFTER `club_note`";
$xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

$sql = "select club_id, club_grade from `xx_club_main`";
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

while (list($club_id, $club_grade) = $xoopsDB->fetchRow($result)) {

    $v = explode(",", $club_grade);
    $new_v = [];
    foreach ($v as $vv) {
        $new_v[] = $vv + 6;
    }
    $new_vv = implode(',', $new_v);

    $sql = "update `xx_club_main` set `club_grade`='{$new_vv}' where `club_id`='{$club_id}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    echo "$sql<br>";

}
