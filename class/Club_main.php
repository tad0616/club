<?php
namespace XoopsModules\Club;

use XoopsModules\Club\Club_choice;
use XoopsModules\Club\Club_main;
use XoopsModules\Club\Tools;
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\DataList;
use XoopsModules\Tadtools\EasyResponsiveTabs;
use XoopsModules\Tadtools\FormValidator;
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

class Club_main
{
    //列出所有 club_main 資料
    public static function index($year, $seme)
    {
        global $xoopsDB, $xoopsTpl, $xoopsModuleConfig;

        // 找出各社團被當作第一志願的人數
        $choice1 = Club_choice::get_sort_count($year, $seme, '', 1);
        $xoopsTpl->assign('choice1', $choice1);

        // 找出各社團被正取人數
        $clubs_ok_num = Club_choice::get_ok_num($year, $seme);
        $clubs_ok_sum = (int) array_sum($clubs_ok_num);
        $xoopsTpl->assign('clubs_ok_num', $clubs_ok_num);
        $xoopsTpl->assign('clubs_ok_sum', $clubs_ok_sum);

        $xoopsTpl->assign('year', $year);
        $xoopsTpl->assign('seme', $seme);

        // 找出所有學生人數
        // 可選填年級
        $stu_can_apply_grade_arr = explode(';', $xoopsModuleConfig['stu_can_apply_grade']);
        $xoopsTpl->assign('stu_can_apply_grade_txt', implode('、', $stu_can_apply_grade_arr));
        $stu_id_arr = Tools::get_school_year_stus_id($year, $stu_can_apply_grade_arr);
        $stu_count = sizeof($stu_id_arr);
        $xoopsTpl->assign('stu_count', $stu_count);

        // 尚未正取數
        $clubs_not_ok_sum = $stu_count - $clubs_ok_sum;
        $xoopsTpl->assign('clubs_not_ok_sum', $clubs_not_ok_sum);

        // 找出已選填人數
        $chosen_stu = Club_choice::get_chosen_stu($year, $seme);
        $chosen_count = sizeof($chosen_stu);
        $xoopsTpl->assign('chosen_count', $chosen_count);

        // 找出尚未選填人數
        $not_chosen_yet_count = $stu_count - $chosen_count;
        $xoopsTpl->assign('not_chosen_yet_count', $not_chosen_yet_count);

        $clubs = self::get_all($year, $seme, '', true);
        //刪除確認的JS
        $SweetAlert = new SweetAlert();
        $SweetAlert->render('club_main_destroy_func',
            "{$_SERVER['PHP_SELF']}?op=club_main_destroy&club_id=", "club_id");

        $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
        $xoopsTpl->assign('clubs', $clubs);
    }

    //club_main編輯表單
    public static function create($club_id = '')
    {
        global $xoopsDB, $xoopsTpl, $xoopsUser, $xoopsModuleConfig;
        Tools::chk_club_power(__FILE__, __LINE__, 'create');

        //抓取預設值
        $DBV = !empty($club_id) ? self::get($club_id) : [];

        //預設值設定

        //設定 club_id 欄位的預設值
        $club_id = !isset($DBV['club_id']) ? $club_id : $DBV['club_id'];
        $xoopsTpl->assign('club_id', $club_id);
        //設定 club_year 欄位的預設值
        $club_year = empty($DBV['club_year']) ? Tools::get_club_year() : $DBV['club_year'];
        $xoopsTpl->assign('club_year', $club_year);
        $xoopsTpl->assign('club_year_arr', ['108', '109']);
        //設定 club_seme 欄位的預設值
        $club_seme = empty($DBV['club_seme']) ? Tools::get_club_seme() : $DBV['club_seme'];
        $xoopsTpl->assign('club_seme', $club_seme);
        $xoopsTpl->assign('club_seme_arr', ['1', '2']);
        //設定 club_title 欄位的預設值
        $club_title = !isset($DBV['club_title']) ? '' : $DBV['club_title'];
        $xoopsTpl->assign('club_title', $club_title);
        //設定 club_num 欄位的預設值
        $club_num = !isset($DBV['club_num']) ? '' : $DBV['club_num'];
        $xoopsTpl->assign('club_num', $club_num);
        //設定 club_tea_name 欄位的預設值
        $club_tea_name = !isset($DBV['club_tea_name']) ? '' : $DBV['club_tea_name'];
        $xoopsTpl->assign('club_tea_name', $club_tea_name);
        //設定 club_tea_uid 欄位的預設值
        $user_uid = $xoopsUser ? $xoopsUser->uid() : "";
        $club_tea_uid = !isset($DBV['club_tea_uid']) ? $user_uid : $DBV['club_tea_uid'];
        $xoopsTpl->assign('club_tea_uid', $club_tea_uid);
        //設定 club_desc 欄位的預設值
        $club_desc = !isset($DBV['club_desc']) ? '' : $DBV['club_desc'];
        $xoopsTpl->assign('club_desc', $club_desc);
        //設定 club_place 欄位的預設值
        $club_place = !isset($DBV['club_place']) ? '' : $DBV['club_place'];
        $xoopsTpl->assign('club_place', $club_place);
        //設定 club_note 欄位的預設值
        $club_note = !isset($DBV['club_note']) ? '' : $DBV['club_note'];
        $xoopsTpl->assign('club_note', $club_note);
        //設定 club_grade 欄位的預設值
        $club_grade = !isset($DBV['club_grade']) ? [] : explode(',', $DBV['club_grade']);
        $xoopsTpl->assign('club_grade', $club_grade);

        $op = empty($club_id) ? "club_main_store" : "club_main_update";

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        DataList::render();
        DataList::render(); //課程說明
        $ck = new CkEditor("club", "club_desc", $club_desc);
        $ck->setHeight(200);
        $ck->setToolbarSet('mySimple');
        $editor = $ck->render();
        $xoopsTpl->assign('club_desc_editor', $editor);

        //加入Token安全機制
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);
        $xoopsTpl->assign('action', $_SERVER["PHP_SELF"]);
        $xoopsTpl->assign('next_op', $op);

        $xoopsTpl->assign('stu_can_apply_grade', explode(';', $xoopsModuleConfig['stu_can_apply_grade']));
    }

    //新增資料到club_main中
    public static function store($club = [])
    {
        global $xoopsDB, $xoopsUser;
        Tools::chk_club_power(__FILE__, __LINE__, 'store');

        $myts = \MyTextSanitizer::getInstance();
        if (!empty($club)) {
            foreach ($club as $k => $v) {
                $$k = $myts->addSlashes($v);
            }
        } else {
            //XOOPS表單安全檢查
            Utility::xoops_security_check();

            $club_year = (int) $_POST['club_year'];
            $club_seme = (int) $_POST['club_seme'];
            $club_title = $myts->addSlashes($_POST['club_title']);
            $club_num = $myts->addSlashes($_POST['club_num']);
            $club_tea_name = $myts->addSlashes($_POST['club_tea_name']);
            //取得使用者編號
            $club_tea_uid = ($xoopsUser) ? $xoopsUser->uid() : 0;
            $club_tea_uid = !empty($_POST['club_tea_uid']) ? (int) $_POST['club_tea_uid'] : $club_tea_uid;
            $club_desc = $myts->addSlashes($_POST['club_desc']);
            $club_place = $myts->addSlashes($_POST['club_place']);
            $club_note = $myts->addSlashes($_POST['club_note']);
            $club_grade = implode(',', $_POST['club_grade']);
        }

        $sql = "insert into `" . $xoopsDB->prefix("club_main") . "` (
        `club_year`,
        `club_seme`,
        `club_title`,
        `club_num`,
        `club_tea_name`,
        `club_tea_uid`,
        `club_desc`,
        `club_place`,
        `club_note`,
        `club_grade`
        ) values(
        '{$club_year}',
        '{$club_seme}',
        '{$club_title}',
        '{$club_num}',
        '{$club_tea_name}',
        '{$club_tea_uid}',
        '{$club_desc}',
        '{$club_place}',
        '{$club_note}',
        '{$club_grade}'
        )";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $club_id = $xoopsDB->getInsertId();

        return $club_id;
    }

    //以流水號秀出某筆club_main資料內容
    public static function show($club_id = '')
    {
        global $xoopsDB, $xoopsTpl;

        if (empty($club_id)) {
            return;
        }

        $club_id = (int) $club_id;
        $all = self::get($club_id);

        //找出目前當作第一志願的學生
        $choice1_stu_arr = Club_choice::get_sort_stu($club_id, 1);
        $xoopsTpl->assign('choice1_stu_arr', $choice1_stu_arr);

        // 已正取學生
        $ok_stu = Club_choice::choice_result_ok($club_id);
        $ok_num = \sizeof($ok_stu);
        $xoopsTpl->assign('ok_stu', $ok_stu);
        $xoopsTpl->assign('ok_num', $ok_num);

        $myts = \MyTextSanitizer::getInstance();
        //過濾讀出的變數值
        $all['club_id'] = (int) $all['club_id'];
        $all['club_year'] = $myts->htmlSpecialChars($all['club_year']);
        $all['club_seme'] = $myts->htmlSpecialChars($all['club_seme']);
        $all['club_title'] = $myts->htmlSpecialChars($all['club_title']);
        $all['club_num'] = $myts->htmlSpecialChars($all['club_num']);
        $all['club_tea_name'] = $myts->htmlSpecialChars($all['club_tea_name']);
        $all['club_tea_uid'] = (int) $all['club_tea_uid'];
        $all['club_desc'] = $myts->displayTarea($all['club_desc'], 1, 1, 0, 1, 0);
        $all['club_place'] = $myts->htmlSpecialChars($all['club_place']);
        $all['club_note'] = $myts->displayTarea($all['club_note'], 0, 1, 0, 1, 1);
        $all['club_grade'] = explode(',', $all['club_grade']);
        $all['club_grade_txt'] = implode('、', $all['club_grade']);

        //以下會產生這些變數： $club_year, $club_seme, $club_title, $club_num, $club_tea_name, $club_tea_uid, $club_desc, $club_place, $club_note
        foreach ($all as $k => $v) {
            $$k = $v;
            $xoopsTpl->assign($k, $v);
        }

        //將 uid 編號轉換成使用者姓名（或帳號）
        $club_tea_uid_name = \XoopsUser::getUnameFromId($club_tea_uid, 1);
        if (empty($club_tea_uid_name)) {
            $club_tea_uid_name = \XoopsUser::getUnameFromId($club_tea_uid, 0);
        }

        $xoopsTpl->assign('club_tea_uid_name', $club_tea_uid_name);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('club_main_destroy_func', "{$_SERVER['PHP_SELF']}?op=club_main_destroy&club_id=", "club_id");

        $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);

        $EasyResponsiveTabs = new EasyResponsiveTabs('#clubTab');
        $EasyResponsiveTabs->rander();

    }

    //更新club_main某一筆資料
    public static function update($club_id = '')
    {
        global $xoopsDB, $xoopsUser;
        Tools::chk_club_power(__FILE__, __LINE__, 'update');

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        $club_id = (int) $_POST['club_id'];
        $club_year = (int) $_POST['club_year'];
        $club_seme = (int) $_POST['club_seme'];
        $club_title = $myts->addSlashes($_POST['club_title']);
        $club_num = $myts->addSlashes($_POST['club_num']);
        $club_tea_name = $myts->addSlashes($_POST['club_tea_name']);
        //取得使用者編號
        $club_tea_uid = ($xoopsUser) ? $xoopsUser->uid() : 0;
        $club_tea_uid = !empty($_POST['club_tea_uid']) ? (int) $_POST['club_tea_uid'] : $club_tea_uid;
        $club_desc = $myts->addSlashes($_POST['club_desc']);
        $club_place = $myts->addSlashes($_POST['club_place']);
        $club_note = $myts->addSlashes($_POST['club_note']);
        $club_grade = implode(',', $_POST['club_grade']);

        $sql = "update `" . $xoopsDB->prefix("club_main") . "` set
        `club_year` = '{$club_year}',
        `club_seme` = '{$club_seme}',
        `club_title` = '{$club_title}',
        `club_num` = '{$club_num}',
        `club_tea_name` = '{$club_tea_name}',
        `club_tea_uid` = '{$club_tea_uid}',
        `club_desc` = '{$club_desc}',
        `club_place` = '{$club_place}',
        `club_note` = '{$club_note}',
        `club_grade` = '{$club_grade}'
        where `club_id` = '$club_id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return $club_id;
    }

    //刪除club_main某筆資料資料
    public static function destroy($club_id = '')
    {
        global $xoopsDB;
        Tools::chk_club_power(__FILE__, __LINE__, 'destroy');

        if (empty($club_id)) {
            return;
        }

        $sql = "delete from `" . $xoopsDB->prefix("club_main") . "`
        where `club_id` = '{$club_id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    }

    //以流水號取得某筆club_main資料
    public static function get($club_id = '')
    {
        global $xoopsDB;

        if (empty($club_id)) {
            return;
        }

        $sql = "select * from `" . $xoopsDB->prefix("club_main") . "`
        where `club_id` = '{$club_id}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        return $data;
    }

    //取得club_main所有資料陣列
    public static function get_all($club_year = '', $club_seme = '', $grade = '', $filter = false)
    {
        global $xoopsDB;
        $and_grade = $grade ? "and (`club_grade` & '$grade' or `club_grade` = '$grade')" : '';
        $sql = "select * from `" . $xoopsDB->prefix("club_main") . "` where club_year='$club_year' and club_seme='$club_seme' $and_grade";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        $myts = \MyTextSanitizer::getInstance();
        while ($all = $xoopsDB->fetchArray($result)) {
            if ($filter) {
                $all['club_id'] = (int) $all['club_id'];
                $all['club_year'] = $myts->htmlSpecialChars($all['club_year']);
                $all['club_seme'] = $myts->htmlSpecialChars($all['club_seme']);
                $all['club_title'] = $myts->htmlSpecialChars($all['club_title']);
                $all['club_num'] = $myts->htmlSpecialChars($all['club_num']);
                $all['club_tea_name'] = $myts->htmlSpecialChars($all['club_tea_name']);
                $all['club_tea_uid'] = (int) $all['club_tea_uid'];
                $all['club_desc'] = $myts->displayTarea($all['club_desc'], 1, 1, 0, 1, 0);
                $all['club_place'] = $myts->htmlSpecialChars($all['club_place']);
                $all['club_note'] = $myts->displayTarea($all['club_note'], 0, 1, 0, 1, 1);
                $all['club_grade'] = explode(',', $all['club_grade']);
                $all['club_grade_txt'] = implode('、', $all['club_grade']);
            }
            $club_id = $all['club_id'];
            $data_arr[$club_id] = $all;
        }
        return $data_arr;
    }

    //取得某學年度的社團id
    public static function get_clubs($year, $seme, $grade = '')
    {
        global $xoopsDB;
        $club_arr = [];
        $and_grade = $grade ? "and (`club_grade` & '$grade' or `club_grade` = '$grade')" : '';
        $sql = "select club_id from `" . $xoopsDB->prefix("club_main") . "` where `club_year`='$year' and `club_seme`='$seme' $and_grade order by rand()";
        $club_arr = [];
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($club_id) = $xoopsDB->fetchRow($result)) {
            $club_arr[$club_id] = $club_id;
        }
        return $club_arr;
    }

    //取得有社團資料的學年學期
    public static function get_clubs_ys()
    {
        global $xoopsDB;
        $club_arr = [];
        $sql = "select club_year,club_seme,count(*) from `" . $xoopsDB->prefix("club_main") . "` group by `club_year`, `club_seme`";
        $ys_arr = [];
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($club_year, $club_seme, $count) = $xoopsDB->fetchRow($result)) {
            $key = "{$club_year}-{$club_seme}";
            $ys_arr[$key] = $count;
        }
        return $ys_arr;
    }
}
