<?php
use XoopsModules\Tadtools\Utility;

include_once "header.php";

$sql = "select a.stu_name, a.stu_email, b.stu_grade, b.stu_class, b.stu_seat_no, a.stu_no from `xx_scs_students` as a
join `xx_scs_general` as b on a.stu_id=b.stu_id where b.school_year=108";
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

while (list($stu_name, $stu_email, $stu_grade, $stu_class, $stu_seat_no, $stu_no) = $xoopsDB->fetchRow($result)) {
    if (empty($stu_email)) {
        $n1 = 109 - $stu_grade - 12;
        $class = sprintf("%'.02d", $stu_class);
        $seat_no = sprintf("%'.02d", $stu_seat_no);
        $stu_email = "st{$n1}{$stu_grade}{$class}{$seat_no}@tn.edu.tw";
    }

    // add_user_to_xoops($stu_name, $stu_grade, $stu_class, $stu_email, 2, time());
    echo "<div>$stu_name, $stu_email, $stu_grade, $stu_class</div>";
}

function add_user_to_xoops($name = "", $stu_grade = "", $stu_class = "", $email = "", $groupid = "", $user_regdate = "")
{
    global $xoopsConfig, $xoopsDB, $xoopsUser;

    $sql = "select uid from " . $xoopsDB->prefix("users") . " where name='{$name}'";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($uid) = $xoopsDB->fetchRow($result);
    if (!empty($uid)) {
        return $uid;
    }

    if ($email == "") {
        $email = "none_mail@none.none";
    }

    define('XOOPS_XMLRPC', 1);
    $_SERVER['REQUEST_METHOD'] = 'POST';

    xoops_load("userUtility");
    $myts = \MyTextSanitizer::getInstance();

    $member_handler = xoops_gethandler('member');

    $newuser = $member_handler->createUser();
    $newuser->setVar('uname', $name);
    $newuser->setVar('name', $name);
    $newuser->setVar('email', $email);
    $newuser->setVar('user_icq', 'student');
    $newuser->setVar('user_from', $stu_grade);
    $newuser->setVar('user_sig', $stu_class);
    $newuser->setVar('pass', md5($name));
    $newuser->setVar('user_avatar', 'avatars/blank.gif');
    $actkey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
    $newuser->setVar('actkey', $actkey);
    $newuser->setVar('user_regdate', $user_regdate);
    $newuser->setVar('uorder', $xoopsConfig['com_order']);
    $newuser->setVar('umode', $xoopsConfig['com_mode']);
    $newuser->setVar('level', 1);
    $newuser->setVar('theme', 'school2019');
    $newuser->setVar('timezone_offset', "8.0");
    $newuser->setVar('user_intrest', '110328');

    if (!$member_handler->insertUser($newuser, true)) {
        redirect_header($_SERVER['HTTP_REFERER'], 10, "無法新增XOOPS帳號" . implode('<br />', $newuser->
                getErrors()));
    }

    $newid = $newuser->uid();
    if ($groupid == XOOPS_GROUP_USERS) {
        if (!addUserToGroup(XOOPS_GROUP_USERS, $newid)) {
            redirect_header($_SERVER['HTTP_REFERER'], 10, "無法加入群組");
        }
    } else {
        if (!addUserToGroup(XOOPS_GROUP_USERS, $newid) or !addUserToGroup($groupid, $newid)) {
            redirect_header($_SERVER['HTTP_REFERER'], 10, "無法加入指定群組");
        }
    }

    return $newid;
}

function addUserToGroup($groupid, $uid)
{
    global $xoopsDB;

    $sql = "INSERT INTO " . $xoopsDB->prefix("groups_users_link") . " (`groupid`, `uid`) values('$groupid', '$uid')";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    return true;
}
