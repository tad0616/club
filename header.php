<?php
use XoopsModules\Club\Tools;
/**
 * Club module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package    Club
 * @since      2.5
 * @author     tad
 * @version    $Id $
 **/

include_once "../../mainfile.php";
include_once 'preloads/autoloader.php';
include_once "function.php";

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

$interface_menu['社團一覽'] = "index.php";
$interface_icon['社團一覽'] = "fa-chevron-right";

if ($_SESSION['club_adm']) {
    $interface_menu[_TAD_TO_ADMIN] = "admin/main.php";
    $interface_icon[_TAD_TO_ADMIN] = "fa-sign-in";
}
