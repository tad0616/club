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

    /*
public static function chk_1()
{
global $xoopsDB;
$sql = 'SELECT count(`tag`) FROM ' . $xoopsDB->prefix('club_files_center');
$result = $xoopsDB->query($sql);
if (empty($result)) {
return true;
}

return false;
}

public static function go_1()
{
global $xoopsDB;
$sql = 'ALTER TABLE ' . $xoopsDB->prefix('club_files_center') . "
ADD `upload_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上傳時間',
ADD `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上傳者',
ADD `tag` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '註記'
";
$xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());
}
 */

}
