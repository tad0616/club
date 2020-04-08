<?php
namespace XoopsModules\Club;

use XoopsModules\Club\Club_choice;
use XoopsModules\Tadtools\TadDataCenter;
use XoopsModules\Tadtools\Utility;

/**
 * Scs module
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
 * @package    Scs
 * @since      2.5
 * @author     tad
 * @version    $Id $
 **/

/**
 * Class Tools
 */
class Tools
{

    // 取得目前學年學期
    public static function get_club_ym()
    {
        $y = self::get_club_year();
        $m = self::get_club_seme();
        return "{$y}-{$m}";
    }

    //取得學年度
    public static function get_club_year()
    {
        $y = date('Y');
        $m = date('n');
        if ($m >= 8) {
            $club_year = $y - 1911;
        } else {
            $club_year = $y - 1912;
        }
        return $club_year;
    }
    //取得學年度
    public static function get_club_seme()
    {
        $m = date('n');
        if ($m >= 8 or $m < 2) {
            $club_seme = 1;
        } else {
            $club_year = 2;
        }
        return $club_year;
    }

    public static function isStudent()
    {
        global $xoopsUser, $xoopsDB;
        if ($xoopsUser->user_icq() == 'student') {
            $email = $xoopsUser->email();
            $name = $xoopsUser->name();
            $stu_grade = $xoopsUser->user_from();
            $stu_class = $xoopsUser->user_sig();
            $school_year = self::get_club_year();
            if ($email) {
                $sql = "select `stu_id` from `" . $xoopsDB->prefix("scs_students") . "`
                where `stu_email`='{$email}'";
                $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                list($stu_id) = $xoopsDB->fetchRow($result);
            }

            if (empty($stu_id)) {
                $sql = "select a.`stu_id`,b.`stu_seat_no`, a.`stu_no` from `" . $xoopsDB->prefix("scs_students") . "` as a
                join `" . $xoopsDB->prefix("scs_general") . "` as b on a.`stu_id` = b.`stu_id`
                where a.`stu_name`='{$name}' and b.`stu_grade`='{$stu_grade}' and b.`stu_class`='{$stu_class}' and b.`school_year`='{$school_year}'";
                // die($sql);
                $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                $total = $xoopsDB->getRowsNum($result);
                if ($total > 1) {
                    redirect_header($_SERVER['PHP_SELF'], 3, "{$name} 共有 {$total} 筆同名資料，請設定學生電子郵件（OpenID用的Email）以便精確判斷。");
                } else {
                    list($stu_id, $stu_seat_no, $stu_no) = $xoopsDB->fetchRow($result);
                }
            }

            return [$stu_id, $stu_seat_no, $stu_no];
        }
        return false;
    }

    //取得某學年度的學生id
    public static function get_school_year_stus_id($school_year)
    {
        global $xoopsDB;
        $stus = [];
        $sql = "select `stu_id` from `" . $xoopsDB->prefix("scs_general") . "` where school_year='{$school_year}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($stu_id) = $xoopsDB->fetchRow($result)) {
            $stus[$stu_id] = $stu_id;
        }
        return $stus;
    }

    //取得某學年度的學生id
    public static function get_stu($stu_id, $school_year)
    {
        global $xoopsDB;
        $sql = "select a.`stu_id`, a.`stu_grade`, a.`stu_class`, a.`stu_seat_no`, b.`stu_no`, b.`stu_name`
            from `" . $xoopsDB->prefix("scs_general") . "` as a
            join `" . $xoopsDB->prefix("scs_students") . "` as b on a.`stu_id`=b.`stu_id`
            where a.`stu_id`='{$stu_id}' and a.`school_year`='{$school_year}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $stu = $xoopsDB->fetchArray($result);
        return $stu;
    }

    //取得某學年度的所有學生id
    public static function get_stus($school_year)
    {
        global $xoopsDB;
        $stu_arr = [];
        $sql = "select a.`stu_id`, a.`stu_grade`, a.`stu_class`, a.`stu_seat_no`, b.`stu_no`, b.`stu_name`, e.`club_title`, e.`club_year`, e.`club_seme`
            from `" . $xoopsDB->prefix("scs_general") . "` as a
            join `" . $xoopsDB->prefix("scs_students") . "` as b on a.`stu_id`=b.`stu_id`
            join `" . $xoopsDB->prefix("club_apply") . "` as c on b.`stu_id`=c.`stu_id`
            join `" . $xoopsDB->prefix("club_choice") . "` as d on c.`apply_id`=d.`apply_id`
            join `" . $xoopsDB->prefix("club_main") . "` as e on e.`club_id`=d.`club_id`
            where  a.`school_year`='{$school_year}' and d.choice_result='正取'
            order by a.`stu_grade`, a.`stu_class`, a.`stu_seat_no`";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while ($all = $xoopsDB->fetchArray($result)) {
            $stu_grade = $all['stu_grade'];
            $stu_class = $all['stu_class'];
            $stu_seat_no = $all['stu_seat_no'];
            $stu_arr[$stu_grade][$stu_class][$stu_seat_no] = $all;
        }
        return $stu_arr;
    }
    public static function isOfficer($uid = '', $return_arr = true)
    {
        global $xoopsUser, $xoopsDB;
        if ($xoopsUser->user_icq() == 'teacher') {

            if (empty($uid)) {
                $uid = $xoopsUser->uid();
            }
            $sql = "select `col_sn`,`data_name` from `" . $xoopsDB->prefix("club_data_center") . "`
            where `col_name`='club_setup' and `data_value`='{$uid}' and `data_name`='officer' order by col_sn desc";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            $year = [];
            while (list($year, $class) = $xoopsDB->fetchRow($result)) {
                $years[$year] = $year;
            }
            if (!empty($years)) {
                if ($return_arr) {
                    return $years;
                } else {
                    return true;
                }
            }
        }
        return false;
    }

    // 檢查是否有權限
    public static function chk_club_power($file, $line, $kind = '', $mode = '')
    {

        switch ($kind) {
            case 'index':
            case 'show':
                return true;
                break;

            case 'create':
            case 'store':
            case 'update':
            case 'view_ok':
            case 'download':
            case 'destroy':
                if ($_SESSION['club_adm'] or $_SESSION['officer']) {
                    return true;
                }

                break;
        }

        if ($mode == 'return') {
            return false;
        } else {
            $file = \addslashes($file);
            redirect_header($_SERVER['HTTP_REFERER'], 3, _TAD_PERMISSION_DENIED . "<div>$file:$line</div>");
        }

    }
    // 檢查是否有選填權限
    public static function chk_apply_power($file, $line, $kind = '', $stu_id = '', $mode = '')
    {
        global $xoopsUser;

        switch ($kind) {
            // 觀看學生的選填列表
            case 'index':
            case 'show':
                if ($_SESSION['club_adm'] or $_SESSION['officer']) {
                    return true;
                } elseif ($_SESSION['stu_id'] and $_SESSION['stu_id'] == $stu_id) {
                    return true;
                }
                break;

            case 'create':
            case 'store':
                if ($_SESSION['club_adm'] or $_SESSION['officer']) {
                    return true;
                } elseif ($_SESSION['stu_id'] and $_SESSION['stu_id'] == $stu_id) {
                    return true;
                }
                break;

            case 'update':
                if ($_SESSION['club_adm'] or $_SESSION['officer']) {
                    return true;
                } elseif ($_SESSION['stu_id'] and $_SESSION['stu_id'] == $stu_id) {
                    return true;
                }
                break;

            // 下載評分表
            case 'import':
            case 'download':
                if ($_SESSION['club_adm'] or $_SESSION['officer']) {
                    return true;
                }
                break;

            case 'destroy':
                if ($_SESSION['club_adm'] or $_SESSION['officer']) {
                    return true;
                }
                break;

        }

        if ($mode == 'return') {
            return false;
        } else {
            $file = \addslashes($file);
            redirect_header($_SERVER['HTTP_REFERER'], 3, _TAD_PERMISSION_DENIED . "<div>$file:$line</div>");
        }

    }

    public static function stu_edit_able()
    {
        global $xoopsTpl;
        $TadDataCenter = new TadDataCenter('club');
        $club_year = self::get_club_year();
        $TadDataCenter->set_col('club_setup', $club_year);
        $setup = $TadDataCenter->getData();
        $xoopsTpl->assign('setup', $setup);
        $now = time();
        $start = strtotime($setup['stu_start_sign'][0]);
        $stop = strtotime($setup['stu_stop_sign'][0]);
        if (empty($start) or empty($stop)) {
            return false;
        }
        $edit_able = ($now >= $start and $now <= $stop) ? true : false;
        $xoopsTpl->assign('edit_able', $edit_able);
        return $edit_able;
    }

    //取得某項陣列
    public static function get_general_data_arr($table = '', $col = 'school_year', $condition = [])
    {
        global $xoopsDB;
        $arr = $where_condition = [];
        $where = "";
        if ($condition) {
            foreach ($condition as $k => $v) {
                $where_condition[] = "`{$k}`='{$v}'";
            }
            $where = "where " . implode(' and ', $where_condition);
        }
        $sql = "select `{$col}` from `" . $xoopsDB->prefix($table) . "` $where group by `{$col}` order by `{$col}`";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($data) = $xoopsDB->fetchRow($result)) {
            if (empty($data)) {
                continue;
            }
            if (strpos($data, ';') !== false) {
                $opt_arr = explode(';', $data);
                foreach ($opt_arr as $opt) {
                    $arr[] = $opt;
                }
            } else {
                $arr[] = $data;
            }

        }
        return $arr;
    }

    //取得輔導系統的偏好設定
    public static function get_xoopsModuleConfig()
    {
        $modhandler = xoops_gethandler('module');
        $xoopsModule = $modhandler->getByDirname("scs");
        $config_handler = xoops_gethandler('config');
        $xoopsModuleConfig = $config_handler->getConfigsByCat(0, $xoopsModule->mid());
        return $xoopsModuleConfig;
    }

    // 匯入成績
    public static function import_score($club_id)
    {
        global $xoopsDB;

        self::chk_club_power(__FILE__, __LINE__, 'import');

        $inputFileName = $_FILES['scorefile']['tmp_name'];
        $club_stu_arr = Club_choice::choice_result_ok($club_id);
        $stu_apply_id = [];
        foreach ($club_stu_arr as $stu) {
            $stu_apply_id[$stu['stu_no']] = $stu['apply_id'];
        }

        require XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        $PHPExcel = $reader->load($inputFileName);

        $sheet = $PHPExcel->getSheet(0); // 讀取第一個工作表(編號從 0 開始)
        $highestRow = $sheet->getHighestRow(); // 取得總列數

        for ($row = 2; $row <= $highestRow; $row++) {

            $stu_no = $sheet->getCellByColumnAndRow(2, $row)->getValue();
            $stu_name = $sheet->getCellByColumnAndRow(3, $row)->getValue();
            $club_score = $sheet->getCellByColumnAndRow(4, $row)->getValue();

            //寫入資料庫
            $sql = "update `" . $xoopsDB->prefix("club_choice") . "` set club_score='$club_score' where apply_id='{$stu_apply_id[$stu_no]}' and club_id='{$club_id}' and choice_result='正取'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

}
