<?php
namespace XoopsModules\Club;

use XoopsModules\Scs\Scs_general;
use XoopsModules\Tadtools\TadDataCenter;

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
                $sql = "select a.`stu_id`,b.`stu_seat_no` from `" . $xoopsDB->prefix("scs_students") . "` as a
                join `" . $xoopsDB->prefix("scs_general") . "` as b on a.`stu_id` = b.`stu_id`
                where a.`stu_name`='{$name}' and b.`stu_grade`='{$stu_grade}' and b.`stu_class`='{$stu_class}' and b.`school_year`='{$school_year}'";
                // die($sql);
                $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                $total = $xoopsDB->getRowsNum($result);
                if ($total > 1) {
                    redirect_header($_SERVER['PHP_SELF'], 3, "{$name} 共有 {$total} 筆同名資料，請設定學生電子郵件（OpenID用的Email）以便精確判斷。");
                } else {
                    list($stu_id, $stu_seat_no) = $xoopsDB->fetchRow($result);
                }
            }

            return [$stu_id, $stu_seat_no];
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
        $stus = [];
        $sql = "select a.`stu_id`, a.`stu_grade`, a.`stu_class`, a.`stu_seat_no`, b.`stu_name`
            from `" . $xoopsDB->prefix("scs_general") . "` as a
            join `" . $xoopsDB->prefix("scs_students") . "` as b on a.`stu_id`=b.`stu_id`
            where a.`stu_id`='{$stu_id}' and a.`school_year`='{$school_year}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $stu = $xoopsDB->fetchArray($result);
        return $stu;
    }

    //取得某學年度的社團id
    public static function get_club($year, $seme)
    {
        global $xoopsDB;
        $club_arr = [];
        $sql = "select club_id from `" . $xoopsDB->prefix("club_main") . "` where club_year='$year' and club_seme='$seme' order by rand()";
        $club_arr = [];
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($club_id) = $xoopsDB->fetchRow($result)) {
            $club_arr[$club_id] = $club_id;
        }
        return $club_arr;
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
        $club_year = Tools::get_club_year();
        $TadDataCenter->set_col('club_setup', $club_year);
        $setup = $TadDataCenter->getData();
        $xoopsTpl->assign('setup', $setup);
        $now = time();
        $start = strtotime($setup['stu_start_sign'][0]);
        $stop = strtotime($setup['stu_stop_sign'][0]);
        $edit_able = ($now >= $start and $now <= $stop) ? true : false;
        $xoopsTpl->assign('edit_able', $edit_able);
        return $edit_able;
    }

    // 給選單用
    public static function menu_option($stu_id = '', $def_stu_grade = '', $def_stu_class = '')
    {
        global $xoopsTpl, $xoopsDB;
        $xoopsTpl->assign('stu_id', $stu_id);

        $and_stu_id = '';
        if ($stu_id) {
            $and_stu_id = "and `stu_id` = '{$stu_id}'";
        }

        $and_stu_grade = '';
        if ($def_stu_grade) {
            $and_stu_grade = "and `stu_grade` = '{$def_stu_grade}'";
            $xoopsTpl->assign('stu_grade', $def_stu_grade);
        }

        $and_stu_class = '';
        if ($def_stu_class) {
            $and_stu_class = "and `stu_class` = '{$def_stu_class}'";
            $xoopsTpl->assign('stu_class', $def_stu_class);
        }

        $sql = "select * from `" . $xoopsDB->prefix("scs_general") . "`
        where 1 {$and_stu_id} {$and_stu_grade} {$and_stu_class} order by `stu_grade`, `stu_class`, `stu_class`, `stu_seat_no`";

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $arr = $school_year_to_grade = [];
        while ($data = $xoopsDB->fetchArray($result)) {
            $g = $data['stu_grade'];
            $y = $data['school_year'];
            $arr[$g] = $data;
            $arr[$g]['favorite_subject'] = explode(';', $data['favorite_subject']);
            $arr[$g]['difficult_subject'] = explode(';', $data['difficult_subject']);
            $arr[$g]['expertise'] = explode(';', $data['expertise']);
            $arr[$g]['interest'] = explode(';', $data['interest']);
            $arr[$g]['grade_class'] = "{$y}-{$g}-{$data['stu_class']}";
            $school_year_to_grade[$y] = $g;
        }

        $school_year = self::get_school_year();
        $xoopsTpl->assign('school_year', $school_year);

        $school_year_arr = self::get_general_data_arr('scs_general', 'school_year');
        $xoopsTpl->assign('school_year_arr', $school_year_arr);

        $condition['school_year'] = $school_year;
        $stu_grade_arr = self::get_general_data_arr('scs_general', 'stu_grade', $condition);
        $xoopsTpl->assign('stu_grade_arr', $stu_grade_arr);

        $menu_stu_grade = $school_year_to_grade[$school_year];

        if (!empty($menu_stu_grade)) {
            if ($stu_id) {
                $xoopsTpl->assign('stu_grade', $menu_stu_grade);
            }

            $condition['stu_grade'] = $menu_stu_grade;
            $stu_class_arr = self::get_general_data_arr('scs_general', 'stu_class', $condition);
            $xoopsTpl->assign('stu_class_arr', $stu_class_arr);
        }

        if (!empty($arr[$menu_stu_grade]['stu_class'])) {
            if ($stu_id) {
                $xoopsTpl->assign('stu_class', $arr[$menu_stu_grade]['stu_class']);
            }

            $condition['stu_class'] = $arr[$menu_stu_grade]['stu_class'];
            $stu_arr = Scs_general::get_general_stu_arr($condition);
            $xoopsTpl->assign('stu_arr', $stu_arr);
        }

        if ($stu_id) {
            $sql = "select stu_seat_no,school_year,stu_grade,stu_class from `" . $xoopsDB->prefix("scs_general") . "`
            where stu_id ='{$stu_id}'";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            list($stu_seat_no, $school_year, $stu_grade, $stu_class) = $xoopsDB->fetchRow($result);
            $xoopsTpl->assign('stu_seat_no', $stu_seat_no);

            // 下一筆
            $sql = "select a.stu_id,a.stu_seat_no,b.stu_name from `" . $xoopsDB->prefix("scs_general") . "` as a
            join `" . $xoopsDB->prefix("scs_students") . "` as b on a.stu_id=b.stu_id
            where a.stu_seat_no > {$stu_seat_no} and a.school_year='{$school_year}' and a.stu_grade='{$stu_grade}' and a.stu_class='{$stu_class}'
            order by a.`stu_seat_no`  LIMIT 0,1";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            list($next['stu_id'], $next['stu_seat_no'], $next['stu_name']) = $xoopsDB->fetchRow($result);
            $xoopsTpl->assign('next', $next);

            // 上一筆
            $sql = "select a.stu_id,a.stu_seat_no,b.stu_name from `" . $xoopsDB->prefix("scs_general") . "` as a
            join `" . $xoopsDB->prefix("scs_students") . "` as b on a.stu_id=b.stu_id
            where a.stu_seat_no < {$stu_seat_no} and a.school_year='{$school_year}' and a.stu_grade='{$stu_grade}' and a.stu_class='{$stu_class}'
            order by a.`stu_seat_no` DESC LIMIT 0,1";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            list($previous['stu_id'], $previous['stu_seat_no'], $previous['stu_name']) = $xoopsDB->fetchRow($result);
            $xoopsTpl->assign('previous', $previous);
        }
    }

    public static function get_config_arr($table = '', $name = '', $col = '')
    {
        global $xoopsTpl, $xoopsModuleConfig;

        $def_arr = explode(';', $xoopsModuleConfig[$name]);
        $col = empty($col) ? $name : $col;
        $db_arr = self::get_general_data_arr($table, $col);
        $all_arr = array_merge($db_arr, $def_arr);
        $arr = array_unique($all_arr);
        $xoopsTpl->assign($name . '_arr', $arr);
    }

    //轉為民國
    public static function tw_birthday($birthday = '')
    {
        list($y, $m, $d) = explode('-', $birthday);
        $y = $y - 1911;
        return "{$y}-{$m}-{$d}";
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

}
