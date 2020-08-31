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

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
$modhandler = xoops_gethandler('module');
$xoopsScsModule = $modhandler->getByDirname("scs");
if (!$xoopsScsModule) {
    redirect_header('/modules/system/admin.php?fct=modulesadmin&op=install&module=scs', 15, "請先安裝「國中輔導系統」，並匯入學生資料，始能使用「國中社團選填系統」。");
}

require_once XOOPS_ROOT_PATH . '/modules/club/preloads/autoloader.php';
require_once XOOPS_ROOT_PATH . '/modules/scs/preloads/autoloader.php';
require_once XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';

xoops_loadLanguage('main', $xoopsModule->getVar('dirname'));

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new \XoopsTpl();
}

xoops_cp_header();

// Define Stylesheet and JScript
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/css/admin.css');
//$xoTheme->addScript("browse.php?Frameworks/jquery/jquery.js");
//$xoTheme->addScript("browse.php?modules/" . $xoopsModule->getVar("dirname") . "/js/admin.js");
