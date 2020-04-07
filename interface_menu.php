<?php
use XoopsModules\Club\Tools;

//判斷是否對該模組有管理權限
if (!isset($_SESSION['club_adm'])) {
    $_SESSION['club_adm'] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

// 若是學生（其值是編號）
if (!isset($_SESSION['stu_id']) or !isset($_SESSION['stu_seat_no'])) {
    if ($xoopsUser) {
        list($_SESSION['stu_id'], $_SESSION['stu_seat_no'], $_SESSION['stu_no']) = Tools::isStudent();
    } else {
        return false;
    }
}

// 若是承辦人（會傳回年度陣列）
if (!isset($_SESSION['officer'])) {
    $_SESSION['officer'] = ($xoopsUser) ? Tools::isOfficer() : false;
}

$interface_menu[_TAD_TO_MOD] = "index.php";
$interface_icon[_TAD_TO_MOD] = "fa-chevron-right";

if ($_SESSION['club_adm']) {
    $interface_menu[_TAD_TO_ADMIN] = "admin/main.php";
    $interface_icon[_TAD_TO_ADMIN] = "fa-sign-in";
}
