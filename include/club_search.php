<?php
//搜尋程式

function 搜尋函數名稱($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;
    if (get_magic_quotes_gpc()) {
        foreach ($queryarray as $k => $v) {
            $arr[$k] = addslashes($v);
        }
        $queryarray = $arr;
    }
    $sql = "SELECT `流水號欄位`,`標題欄位`,`日期欄位`, `uid欄位` FROM " . $xoopsDB->prefix("資料表") . " WHERE 篩選條件";
    if ($userid != 0) {
        $sql .= " AND `uid欄位`=" . $userid . " ";
    }
    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((`標題欄位` LIKE '%{$queryarray[0]}%'  OR `其他欲搜尋欄位` LIKE '%{$queryarray[0]}%' )";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "(`標題欄位` LIKE '%{$queryarray[$i]}%' OR  `其他欲搜尋欄位` LIKE '%{$queryarray[$i]}%' )";
        }
        $sql .= ") ";
    }
    $sql .= "ORDER BY  `日期欄位` DESC";
    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret    = array();
    $i      = 0;
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $ret[$i]['image'] = "images/圖示.png";
        $ret[$i]['link']  = "單一頁面.php?流水號欄位=" . $myrow['流水號欄位'];
        $ret[$i]['title'] = $myrow['標題欄位'];
        $ret[$i]['time']  = strtotime($myrow['日期欄位']);
        $ret[$i]['uid']   = $myrow['uid欄位'];
        $i++;
    }
    return $ret;
}
