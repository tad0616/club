<?php
namespace XoopsModules\Club;

use XoopsModules\Club\Club_apply;
use XoopsModules\Club\Club_choice;
use XoopsModules\Club\Club_main;
use XoopsModules\Club\Tools;
use XoopsModules\Tadtools\SweetAlert;
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

class Club_choice
{
    //列出所有 club_choice 資料
    public static function index($year, $seme, $stu_id = '', $mode = '')
    {
        global $xoopsDB, $xoopsTpl;
        $clubs = Club_main::get_all($year, $seme, true);
        // 社團數
        $club_count = sizeof($clubs);

        $xoopsTpl->assign('year', $year);
        $xoopsTpl->assign('seme', $seme);
        $xoopsTpl->assign('mode', $mode);
        $xoopsTpl->assign('clubs', $clubs);

        $stu_id = (empty($stu_id) or !empty($_SESSION['stu_id'])) ? $_SESSION['stu_id'] : $stu_id;

        $apply = Club_apply::get('', $stu_id, $year, $seme);
        $apply_id = $apply['apply_id'];
        if (empty($apply_id)) {
            if (!empty($_SESSION['stu_id'])) {
                $apply_id = Club_apply::store($stu_id, $year, $seme);
            } else {
                $stu = Tools::get_stu($stu_id, $year);
                $apply_id = Club_apply::store($stu_id, $year, $seme, $stu['stu_name'], $stu['stu_grade'], $stu['stu_class'], $stu['stu_seat_no']);

            }

            $apply = Club_apply::get('', $stu_id, $year, $seme);
            $apply_id = $apply['apply_id'];
        }
        $xoopsTpl->assign('apply', $apply);
        $xoopsTpl->assign('apply_id', $apply_id);

        $myts = \MyTextSanitizer::getInstance();

        $choice_arr = self::get($apply_id);
        // 已填志願數
        $choice_count = sizeof($choice_arr);
        if ($choice_count != $club_count) {
            foreach ($clubs as $club_id => $club) {
                if (!isset($choice_arr[$club_id])) {
                    self::store($apply_id, $club_id);
                }
            }

            $choice_arr = self::get($apply_id);
        }

        $club_choice = [];
        foreach ($choice_arr as $all) {
            //過濾讀出的變數值
            $all['apply_id'] = (int) $all['apply_id'];
            $all['club_id'] = $club_id = (int) $all['club_id'];
            $all['choice_sort'] = (int) $all['choice_sort'];
            $all['club_title'] = $myts->htmlSpecialChars($clubs[$club_id]['club_title']);
            $all['choice_result'] = $myts->htmlSpecialChars($all['choice_result']);
            $all['club_score'] = $myts->htmlSpecialChars($all['club_score']);
            $all['score_date'] = $myts->htmlSpecialChars($all['score_date']);

            $club_choice[$club_id] = $all;
        }
        $xoopsTpl->assign('club_choice', $club_choice);

        $choice1 = self::get_sort_count($year, $seme, 1);
        $xoopsTpl->assign('choice1', $choice1);

        //刪除確認的JS
        $SweetAlert = new SweetAlert();
        $SweetAlert->render('club_choice_destroy_func',
            "{$_SERVER['PHP_SELF']}?op=club_choice_destroy&apply_id=", "apply_id");

        $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    }

    //club_choice編輯表單
    public static function create($apply_id = '')
    {
        global $xoopsDB, $xoopsTpl, $xoopsUser;
    }

    //新增資料到club_choice中
    public static function store($apply_id, $club_id, $choice_sort = 0)
    {
        global $xoopsDB, $xoopsUser;
        Tools::chk_apply_power(__FILE__, __LINE__, 'store', $_SESSION['stu_id']);

        $myts = \MyTextSanitizer::getInstance();

        $apply_id = (int) $apply_id;
        $club_id = (int) $club_id;
        $choice_sort = (int) $choice_sort;

        $sql = "insert into `" . $xoopsDB->prefix("club_choice") . "` (
        `apply_id`,
        `club_id`,
        `choice_sort`
        ) values(
        '{$apply_id}',
        '{$club_id}',
        '{$choice_sort}'
        )";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //以流水號秀出某筆club_choice資料內容
    public static function show($apply_id = '')
    {
        global $xoopsDB, $xoopsTpl;

    }

    //更新club_choice某一筆資料
    public static function update($apply_id = '')
    {
        global $xoopsDB, $xoopsUser;
    }

    //刪除club_choice某筆資料資料
    public static function destroy($apply_id = '')
    {
        global $xoopsDB;
        $stu = Club_apply::get($apply_id);
        Tools::chk_apply_power(__FILE__, __LINE__, 'destroy', $stu['stu_id']);

        if (empty($apply_id)) {
            return;
        }

        $sql = "delete from `" . $xoopsDB->prefix("club_choice") . "`
        where `apply_id` = '{$apply_id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    }

    //以流水號取得某筆club_choice資料
    public static function get($apply_id = '')
    {
        global $xoopsDB;

        if (empty($apply_id)) {
            return;
        }

        $sql = "select * from `" . $xoopsDB->prefix("club_choice") . "`
        where `apply_id` = '{$apply_id}' order by choice_sort, rand()";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while ($all = $xoopsDB->fetchArray($result)) {
            $club_id = $all['club_id'];
            $data[$club_id] = $all;
        }
        return $data;
    }

    //取得club_choice所有資料陣列
    public static function get_all($apply_id)
    {
        global $xoopsDB;
        $sql = "select * from `" . $xoopsDB->prefix("club_choice") . "` where apply_id='$apply_id' order by choice_sort";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        while ($data = $xoopsDB->fetchArray($result)) {
            $data_arr[] = $data;
        }
        return $data_arr;
    }

    //取得個社團被當作第一志願的數量
    public static function get_sort_count($year, $seme, $sort = 1)
    {
        global $xoopsDB;
        $sql = "select b.club_id, count(*) as n from `" . $xoopsDB->prefix("club_apply") . "` as a
        join `" . $xoopsDB->prefix("club_choice") . "` as b on a.apply_id = b.apply_id
        where a.apply_year='$year' and a.apply_seme='$seme' and b.choice_sort='$sort'
        group by b.club_id";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        while (list($club_id, $count) = $xoopsDB->fetchRow($result)) {
            $data_arr[$club_id] = (int) $count;
        }
        return $data_arr;
    }

    //取得個社團被當作第一志願的學生
    public static function get_sort_stu($club_id, $sort = 1)
    {
        global $xoopsDB;
        $sql = "select a.*, b.choice_result, b.club_score from `" . $xoopsDB->prefix("club_apply") . "` as a
        join `" . $xoopsDB->prefix("club_choice") . "` as b on a.apply_id = b.apply_id
        where b.club_id='$club_id' and b.choice_sort='$sort'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $stu_arr = [];
        while ($all = $xoopsDB->fetchArray($result)) {
            $stu_grade = sprintf("%'.02d", $all['stu_grade']);
            $stu_class = sprintf("%'.02d", $all['stu_class']);
            $stu_seat_no = sprintf("%'.02d", $all['stu_seat_no']);
            $key = "{$stu_grade}{$stu_class}{$stu_seat_no}";
            $stu_arr[$key] = $all;
        }
        return $stu_arr;
    }

    //取得個指定學年學期已填的數量
    public static function get_chosen_stu($year, $seme)
    {
        global $xoopsDB;
        $sql = "select a.stu_id from `" . $xoopsDB->prefix("club_apply") . "` as a
        join `" . $xoopsDB->prefix("club_choice") . "` as b on a.apply_id = b.apply_id
        where a.apply_year='$year' and a.apply_seme='$seme' and b.choice_sort!=0
        group by b.apply_id";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        while (list($stu_id) = $xoopsDB->fetchRow($result)) {
            $data_arr[$stu_id] = $stu_id;
        }
        return $data_arr;
    }

    // 找出未選填者
    public static function not_chosen_yet($year, $seme, $only_stu_id = false)
    {
        global $xoopsTpl;
        $xoopsTpl->assign('year', $year);
        $xoopsTpl->assign('seme', $seme);
        $stu_id_arr = Tools::get_school_year_stus_id($year);
        $chosen_stu = self::get_chosen_stu($year, $seme);
        $stu_arr = array_diff($stu_id_arr, $chosen_stu);
        $not_chosen_yet_stu_arr = [];

        if ($only_stu_id) {
            return $stu_arr;
        }

        foreach ($stu_arr as $stu_id) {

            $stu = Tools::get_stu($stu_id, $year);
            $stu_grade = sprintf("%'.02d", $stu['stu_grade']);
            $stu_class = sprintf("%'.02d", $stu['stu_class']);
            $stu_seat_no = (int) $stu['stu_seat_no'];
            $key = "{$stu_grade}-{$stu_class}";
            if (!empty($stu)) {
                $not_chosen_yet_stu_arr[$key][$stu_seat_no] = $stu;
            } else {
                $not_data[] = $stu_id;
            }
        }

        ksort($not_chosen_yet_stu_arr);
        $xoopsTpl->assign('not_data', $not_data);
        $xoopsTpl->assign('not_chosen_yet_stu_arr', $not_chosen_yet_stu_arr);
    }

    // 批次亂數選
    public static function batch_apply($year, $seme, $apply_id = '')
    {
        global $xoopsTpl, $xoopsDB;

        // 找出所有社團
        $club_arr = self::get_sort_count($year, $seme, 1);
        asort($club_arr);

        if ($apply_id) {
            self::rand_apply($club_arr, $year, $seme, $apply_id);
        } else {
            $stu_arr = self::not_chosen_yet($year, $seme, true);
            foreach ($stu_arr as $stu_id) {

                $apply = Club_apply::get('', $stu_id, $year, $seme);
                $apply_id = $apply['apply_id'];
                if (empty($apply_id)) {
                    $stu = Tools::get_stu($stu_id, $year);
                    $apply_id = Club_apply::store($stu_id, $year, $seme, $stu['stu_name'], $stu['stu_grade'], $stu['stu_class'], $stu['stu_seat_no']);

                    $apply = Club_apply::get('', $stu_id, $year, $seme);
                    $apply_id = $apply['apply_id'];
                }
                self::rand_apply($club_arr, $year, $seme, $apply_id);
            }
        }

    }

    // 亂數選填（沒人選的社團優先）
    public static function rand_apply($club_arr, $year, $seme, $apply_id = '')
    {
        global $xoopsTpl, $xoopsDB;

        // 刪除原有申請
        $sql = "delete from `" . $xoopsDB->prefix("club_choice") . "`
        where `apply_id` = '{$apply_id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        // 重新申請
        $choice_sort = 1;
        foreach ($club_arr as $club_id => $count) {
            self::store($apply_id, $club_id, $choice_sort);
            $choice_sort++;
        }

    }

    // 修改錄取狀態
    public static function set_choice_result($apply_id, $club_id, $val = '')
    {
        global $xoopsDB;
        Tools::chk_apply_power(__FILE__, __LINE__, 'update');
        $sql = "update `" . $xoopsDB->prefix("club_choice") . "` set choice_result='$val' where apply_id='{$apply_id}' and club_id='{$club_id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    // 亂數錄取
    public static function choice_result_random($club_id)
    {
        global $xoopsDB;
        Tools::chk_apply_power(__FILE__, __LINE__, 'update');
        $sql = "update `" . $xoopsDB->prefix("club_choice") . "` set choice_result='$val' where apply_id='{$apply_id}' and club_id='{$club_id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }
}
