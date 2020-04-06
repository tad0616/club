<?php
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

/********************* 自訂函數 *********************/
function vv($array = [])
{
    Utility::dd($array);
}

function have_club_power($kind = '')
{
    return Tools::chk_club_power(__FILE__, __LINE__, $kind, 'return');
}

function have_apply_power($kind = '', $stu_id = '')
{
    return Tools::chk_apply_power(__FILE__, __LINE__, $kind, $stu_id, 'return');
}
