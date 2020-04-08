<?php
use XoopsModules\Club\Club_choice;
use XoopsModules\Club\Club_main;
use XoopsModules\Club\Tools;
use XoopsModules\Tadtools\Utility;
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

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'club_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------功能函數區----------*/

/*-----------變數過濾----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$mode = system_CleanVars($_REQUEST, 'mode', '', 'string');
$year = system_CleanVars($_REQUEST, 'year', Tools::get_club_year(), 'int');
$seme = system_CleanVars($_REQUEST, 'seme', Tools::get_club_seme(), 'int');
$club_id = system_CleanVars($_REQUEST, 'club_id', 0, 'int');
$stu_id = system_CleanVars($_REQUEST, 'stu_id', 0, 'int');
$apply_id = system_CleanVars($_REQUEST, 'apply_id', 0, 'int');

/*-----------執行動作判斷區----------*/
switch ($op) {

    //新增資料
    case 'club_main_store':
        $club_id = Club_main::store();
        header("location: {$_SERVER['PHP_SELF']}?club_id=$club_id");
        exit;

    //更新資料
    case 'club_main_update':
        Club_main::update($club_id);
        header("location: {$_SERVER['PHP_SELF']}?club_id=$club_id");
        exit;

    //新增用表單
    case 'club_main_create':
        Club_main::create();
        break;

    //修改用表單
    case 'club_main_edit':
        Club_main::create($club_id);
        $op = 'club_main_create';
        break;

    //刪除資料
    case 'club_main_destroy':
        Club_main::destroy($club_id);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //列出所資料
    case 'club_main_index':
        Club_main::index();
        break;

    //顯示某筆資料
    case 'club_main_show':
        Club_main::show($club_id);
        break;

    case "not_chosen_yet":
        Club_choice::not_chosen_yet($year, $seme);
        break;

    case "batch_apply":
        Club_choice::batch_apply($year, $seme, $apply_id);
        if ($stu_id) {
            header("location: index.php?stu_id=$stu_id");
        } else {
            header("location: index.php");
        }
        exit;

    case "choice_result_ok":
        Club_choice::set_choice_result($apply_id, $club_id, '正取');
        header("location: index.php?club_id={$club_id}#clubTab2");
        exit;

    case "choice_result_all_random":
        Club_choice::choice_result_all_random($year, $seme);
        header("location: index.php");
        exit;

    case "choice_result_random":
        Club_choice::choice_result_random($club_id, 1);
        header("location: index.php?club_id=$club_id");
        exit;

    case "no_result_yet":
        Club_choice::no_result_yet($year, $seme);
        break;

    case "import_score":
        Tools::import_score($club_id);
        header("location: index.php?club_id=$club_id");
        exit;

    //預設動作
    default:
        $stu_edit_able = Tools::stu_edit_able();
        if ($_SESSION['stu_id']) {
            if (!empty($club_id)) {
                Club_main::show($club_id);
                $op = 'club_main_show';
            } else {
                Club_choice::index($year, $seme);
                $op = 'club_choice_index';
            }
        } else {
            if (!empty($stu_id)) {
                $stu_edit_able = Tools::stu_edit_able();
                Club_choice::index($year, $seme, $stu_id, $mode);
                $op = 'club_choice_index';
            } elseif (!empty($club_id)) {
                Club_main::show($club_id);
                $op = 'club_main_show';
            } else {
                Club_main::index($year, $seme);
                $op = 'club_main_index';
            }
        }
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/club/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
