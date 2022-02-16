<?php
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

$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_CLUB_NAME;
$modversion['version'] = '1.0';
$modversion['description'] = _MI_CLUB_DESC;
$modversion['author'] = _MI_CLUB_AUTHOR;
$modversion['credits'] = _MI_CLUB_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GPL see LICENSE';
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = basename(__DIR__);

//---模組狀態資訊---//
$modversion['status_version'] = '1.0';
$modversion['release_date'] = '2020-04-10';
$modversion['module_website_url'] = 'https://tad0616.net';
$modversion['module_website_name'] = _MI_CLUB_AUTHOR_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://tad0616.net';
$modversion['author_website_name'] = _MI_CLUB_AUTHOR_WEB;
$modversion['min_php'] = '5.4';
$modversion['min_xoops'] = '2.5';

//---paypal資訊---//
$modversion['paypal'] = array();
$modversion['paypal']['business'] = 'tad0616@gmail.com';
$modversion['paypal']['item_name'] = 'Donation :' . _MI_CLUB_AUTHOR;
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---安裝設定---//
$modversion['onInstall'] = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][] = "club_apply";
$modversion['tables'][] = "club_choice";
$modversion['tables'][] = "club_data_center";
$modversion['tables'][] = "club_main";

//---後台使用系統選單---//
$modversion['system_menu'] = 1;

//---後台管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/main.php';
$modversion['adminmenu'] = 'admin/menu.php';

//---前台主選單設定---//
$modversion['hasMain'] = 1;

//---樣板設定---//
$modversion['templates'][] = array('file' => 'club_adm_main.tpl', 'description' => 'club_adm_main.tpl');
$modversion['templates'][] = array('file' => 'club_index.tpl', 'description' => 'club_index.tpl');
$modversion['templates'][] = array('file' => 'club_club.tpl', 'description' => 'club_club.tpl');

//---偏好設定---//
$i = 0;

$i++;
$modversion['config'][$i] = [
    'name' => 'stu_can_see_result',
    'title' => '_MI_CLUB_STU_CAN_SEE_RESULT',
    'description' => '_MI_CLUB_STU_CAN_SEE_RESULT_DESC',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => '1',
];

$i++;
$modversion['config'][$i] = [
    'name' => 'stu_can_apply_grade',
    'title' => '_MI_CLUB_STU_CAN_APPLY_GRADE',
    'description' => '_MI_CLUB_STU_CAN_APPLY_GRADE_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '7;8',
];
