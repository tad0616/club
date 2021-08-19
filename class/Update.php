<?php
namespace XoopsModules\Club;

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

/**
 * Class Update
 */
class Update
{

    //data_center 加入 sort
    public static function chk_dc_sort()
    {
        global $xoopsDB;
        $sql = 'select count(`sort`) from ' . $xoopsDB->prefix('club_data_center');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    public static function go_dc_sort()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('club_data_center') . " ADD `sort` mediumint(9) unsigned COMMENT '顯示順序' after `col_id`";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/club/admin/index.php', 30, $xoopsDB->error());
    }

}
