<?php
use Xmf\Request;
use XoopsModules\Club\Club_main;
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
$GLOBALS['xoopsOption']['template_main'] = 'club_club.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------功能函數區----------*/

/*-----------變數過濾----------*/
$op = Request::getString('op');
$club_id = Request::getInt('club_id');

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

    //預設動作
    default:
        if (empty($club_id)) {
            Club_main::index();
            $op = 'club_main_index';
        } else {
            Club_main::show($club_id);
            $op = 'club_main_show';
        }
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/club/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
