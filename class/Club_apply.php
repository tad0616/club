<?php
namespace XoopsModules\Club;

use XoopsModules\Club\Club_apply;
use XoopsModules\Club\Tools;
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

class Club_apply
{
    //列出所有 club_apply 資料
    public static function index($stu_id)
    {
        global $xoopsDB, $xoopsTpl;
        Tools::chk_apply_power(__FILE__, __LINE__, 'index', $stu_id);

        $myts = \MyTextSanitizer::getInstance();

        $sql = "select * from `" . $xoopsDB->prefix("club_apply") . "` where stu_id='{$stu_id}'";

        //Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = Utility::getPageBar($sql, 20, 10);
        $bar = $PageBar['bar'];
        $sql = $PageBar['sql'];
        $total = $PageBar['total'];
        $xoopsTpl->assign('bar', $bar);
        $xoopsTpl->assign('total', $total);

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $all_club_apply = [];
        while ($all = $xoopsDB->fetchArray($result)) {
            //過濾讀出的變數值
            $all['apply_id'] = (int) $all['apply_id'];
            $all['stu_id'] = $myts->htmlSpecialChars($all['stu_id']);
            $all['stu_name'] = $myts->htmlSpecialChars($all['stu_name']);
            $all['stu_grade'] = $myts->htmlSpecialChars($all['stu_grade']);
            $all['stu_class'] = $myts->htmlSpecialChars($all['stu_class']);
            $all['stu_seat_no'] = $myts->htmlSpecialChars($all['stu_seat_no']);
            $all['apply_year'] = $myts->htmlSpecialChars($all['apply_year']);
            $all['apply_seme'] = $myts->htmlSpecialChars($all['apply_seme']);
            $all['stu_uid'] = (int) $all['stu_uid'];
            $all['apply_time'] = $myts->htmlSpecialChars($all['apply_time']);

            //將 uid 編號轉換成使用者姓名（或帳號）
            $all['stu_uid_name'] = \XoopsUser::getUnameFromId($all['stu_uid'], 1);
            if (empty($all['stu_uid_name'])) {
                $all['stu_uid_name'] = \XoopsUser::getUnameFromId($all['stu_uid'], 0);
            }

            $all_club_apply[] = $all;
        }

        //刪除確認的JS
        $SweetAlert = new SweetAlert();
        $SweetAlert->render('club_apply_destroy_func',
            "{$_SERVER['PHP_SELF']}?op=club_apply_destroy&apply_id=", "apply_id");

        $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
        $xoopsTpl->assign('all_club_apply', $all_club_apply);
    }

    //club_apply編輯表單
    public static function create($stu_id = '', $apply_id = '')
    {
        global $xoopsDB, $xoopsTpl, $xoopsUser;
        Tools::chk_apply_power(__FILE__, __LINE__, 'create', $stu_id);

        //抓取預設值
        $DBV = !empty($apply_id) ? self::get($apply_id) : [];

        //預設值設定

        //設定 apply_id 欄位的預設值
        $apply_id = !isset($DBV['apply_id']) ? $apply_id : $DBV['apply_id'];
        $xoopsTpl->assign('apply_id', $apply_id);
        //設定 stu_id 欄位的預設值
        $stu_id = !isset($DBV['stu_id']) ? '' : $DBV['stu_id'];
        $xoopsTpl->assign('stu_id', $stu_id);
        //設定 stu_name 欄位的預設值
        $stu_name = !isset($DBV['stu_name']) ? '' : $DBV['stu_name'];
        $xoopsTpl->assign('stu_name', $stu_name);
        //設定 stu_grade 欄位的預設值
        $stu_grade = !isset($DBV['stu_grade']) ? '' : $DBV['stu_grade'];
        $xoopsTpl->assign('stu_grade', $stu_grade);
        //設定 stu_class 欄位的預設值
        $stu_class = !isset($DBV['stu_class']) ? '' : $DBV['stu_class'];
        $xoopsTpl->assign('stu_class', $stu_class);
        //設定 stu_seat_no 欄位的預設值
        $stu_seat_no = !isset($DBV['stu_seat_no']) ? '' : $DBV['stu_seat_no'];
        $xoopsTpl->assign('stu_seat_no', $stu_seat_no);
        //設定 apply_year 欄位的預設值
        $apply_year = !isset($DBV['apply_year']) ? '' : $DBV['apply_year'];
        $xoopsTpl->assign('apply_year', $apply_year);
        //設定 apply_seme 欄位的預設值
        $apply_seme = !isset($DBV['apply_seme']) ? '' : $DBV['apply_seme'];
        $xoopsTpl->assign('apply_seme', $apply_seme);
        //設定 stu_uid 欄位的預設值
        $user_uid = $xoopsUser ? $xoopsUser->uid() : "";
        $stu_uid = !isset($DBV['stu_uid']) ? $user_uid : $DBV['stu_uid'];
        $xoopsTpl->assign('stu_uid', $stu_uid);
        //設定 apply_time 欄位的預設值
        $apply_time = !isset($DBV['apply_time']) ? date("Y-m-d H:i:s") : $DBV['apply_time'];
        $xoopsTpl->assign('apply_time', $apply_time);

        $op = empty($apply_id) ? "club_apply_store" : "club_apply_update";

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        //加入Token安全機制
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);
        $xoopsTpl->assign('action', $_SERVER["PHP_SELF"]);
        $xoopsTpl->assign('next_op', $op);
    }

    //新增資料到club_apply中
    public static function store($stu_id, $apply_year, $apply_seme, $stu_name = '', $stu_grade = '', $stu_class = '', $stu_seat_no = '')
    {
        global $xoopsDB, $xoopsUser;

        $myts = \MyTextSanitizer::getInstance();

        $stu_id = (int) $stu_id;
        Tools::chk_apply_power(__FILE__, __LINE__, 'store', $stu_id);

        $stu_name = !empty($stu_name) ? $stu_name : $myts->addSlashes($xoopsUser->name());
        $stu_grade = !empty($stu_grade) ? $stu_grade : (int) $xoopsUser->user_from();
        $stu_class = !empty($stu_class) ? $stu_class : (int) $xoopsUser->user_sig();
        $stu_seat_no = !empty($stu_seat_no) ? $stu_seat_no : (int) $_SESSION['stu_seat_no'];
        $apply_year = (int) $apply_year;
        $apply_seme = (int) $apply_seme;
        //取得使用者編號
        $stu_uid = ($xoopsUser) ? $xoopsUser->uid() : 0;
        $stu_uid = !empty($_POST['stu_uid']) ? (int) $_POST['stu_uid'] : $stu_uid;
        $apply_time = date("Y-m-d H:i:s", xoops_getUserTimestamp(time()));

        $sql = "insert into `" . $xoopsDB->prefix("club_apply") . "` (
        `stu_id`,
        `stu_name`,
        `stu_grade`,
        `stu_class`,
        `stu_seat_no`,
        `apply_year`,
        `apply_seme`,
        `stu_uid`,
        `apply_time`
        ) values(
        '{$stu_id}',
        '{$stu_name}',
        '{$stu_grade}',
        '{$stu_class}',
        '{$stu_seat_no}',
        '{$apply_year}',
        '{$apply_seme}',
        '{$stu_uid}',
        '{$apply_time}'
        )";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $apply_id = $xoopsDB->getInsertId();

        return $apply_id;
    }

    //以流水號秀出某筆club_apply資料內容
    public static function show($apply_id = '')
    {
        global $xoopsDB, $xoopsTpl;

        if (empty($apply_id)) {
            return;
        }

        $apply_id = (int) $apply_id;
        $all = self::get($apply_id);
        Tools::chk_apply_power(__FILE__, __LINE__, 'show', $all['stu_id']);

        $myts = \MyTextSanitizer::getInstance();
        //過濾讀出的變數值
        $all['apply_id'] = (int) $all['apply_id'];
        $all['stu_id'] = $myts->htmlSpecialChars($all['stu_id']);
        $all['stu_name'] = $myts->htmlSpecialChars($all['stu_name']);
        $all['stu_grade'] = $myts->htmlSpecialChars($all['stu_grade']);
        $all['stu_class'] = $myts->htmlSpecialChars($all['stu_class']);
        $all['stu_seat_no'] = $myts->htmlSpecialChars($all['stu_seat_no']);
        $all['apply_year'] = $myts->htmlSpecialChars($all['apply_year']);
        $all['apply_seme'] = $myts->htmlSpecialChars($all['apply_seme']);
        $all['stu_uid'] = (int) $all['stu_uid'];
        $all['apply_time'] = $myts->htmlSpecialChars($all['apply_time']);

        //以下會產生這些變數： $stu_id, $stu_name, $stu_grade, $stu_class, $stu_seat_no, $apply_year, $apply_seme, $stu_uid, $apply_time
        foreach ($all as $k => $v) {
            $$k = $v;
            $xoopsTpl->assign($k, $v);
        }

        //將 uid 編號轉換成使用者姓名（或帳號）
        $stu_uid_name = \XoopsUser::getUnameFromId($stu_uid, 1);
        if (empty($stu_uid_name)) {
            $stu_uid_name = \XoopsUser::getUnameFromId($stu_uid, 0);
        }

        $xoopsTpl->assign('stu_uid_name', $stu_uid_name);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('club_apply_destroy_func', "{$_SERVER['PHP_SELF']}?op=club_apply_destroy&apply_id=", "apply_id");

        $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    }

    //更新club_apply某一筆資料
    public static function update($apply_id = '')
    {
        global $xoopsDB, $xoopsUser;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        $apply_id = (int) $_POST['apply_id'];
        $stu_id = (int) $_POST['stu_id'];
        Tools::chk_apply_power(__FILE__, __LINE__, 'update', $stu_id);

        $stu_name = $myts->addSlashes($_POST['stu_name']);
        $stu_grade = (int) $_POST['stu_grade'];
        $stu_class = (int) $_POST['stu_class'];
        $stu_seat_no = (int) $_POST['stu_seat_no'];
        $apply_year = (int) $_POST['apply_year'];
        $apply_seme = (int) $_POST['apply_seme'];
        //取得使用者編號
        $stu_uid = ($xoopsUser) ? $xoopsUser->uid() : 0;
        $stu_uid = !empty($_POST['stu_uid']) ? (int) $_POST['stu_uid'] : $stu_uid;
        $apply_time = date("Y-m-d H:i:s", xoops_getUserTimestamp(time()));

        $sql = "update `" . $xoopsDB->prefix("club_apply") . "` set
        `stu_id` = '{$stu_id}',
        `stu_name` = '{$stu_name}',
        `stu_grade` = '{$stu_grade}',
        `stu_class` = '{$stu_class}',
        `stu_seat_no` = '{$stu_seat_no}',
        `apply_year` = '{$apply_year}',
        `apply_seme` = '{$apply_seme}',
        `stu_uid` = '{$stu_uid}',
        `apply_time` = '{$apply_time}'
        where `apply_id` = '$apply_id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return $apply_id;
    }

    //刪除club_apply某筆資料資料
    public static function destroy($apply_id = '')
    {
        global $xoopsDB;
        Tools::chk_apply_power(__FILE__, __LINE__, 'destroy', $stu_id);

        if (empty($apply_id)) {
            return;
        }

        $sql = "delete from `" . $xoopsDB->prefix("club_apply") . "`
        where `apply_id` = '{$apply_id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    }

    //以流水號取得某筆club_apply資料
    public static function get($apply_id = '', $stu_id = '', $apply_year = '', $apply_seme = '')
    {
        global $xoopsDB;

        if (empty($apply_id) and empty($stu_id)) {
            return;
        }

        if (!empty($apply_id)) {
            $sql = "select * from `" . $xoopsDB->prefix("club_apply") . "`
            where `apply_id` = '{$apply_id}'";
        } elseif (!empty($stu_id) and !empty($apply_year) and !empty($apply_seme)) {
            $sql = "select * from `" . $xoopsDB->prefix("club_apply") . "`
            where `stu_id` = '{$stu_id}' and apply_year='{$apply_year}' and apply_seme='{$apply_seme}'";
        }
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        return $data;
    }

    //取得club_apply所有資料陣列
    public static function get_all()
    {
        global $xoopsDB;
        $sql = "select * from `" . $xoopsDB->prefix("club_apply") . "`";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        while ($data = $xoopsDB->fetchArray($result)) {
            $apply_id = $data['apply_id'];
            $data_arr[$apply_id] = $data;
        }
        return $data_arr;
    }

    //新增club_apply計數器
    public static function add_counter($apply_id = '')
    {
        global $xoopsDB;

        if (empty($apply_id)) {
            return;
        }

        $sql = "update `" . $xoopsDB->prefix("club_apply") . "` set `` = `` + 1
        where `apply_id` = '{$apply_id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //自動取得club_apply的最新排序
    public static function max_sort()
    {
        global $xoopsDB;
        $sql = "select max(``) from `" . $xoopsDB->prefix("club_apply") . "`";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($sort) = $xoopsDB->fetchRow($result);
        return ++$sort;
    }

}
