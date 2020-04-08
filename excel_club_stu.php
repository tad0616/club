<?php
use XoopsModules\Club\Club_choice;
use XoopsModules\Club\Club_main;
use XoopsModules\Club\Tools;
use XoopsModules\Tadtools\TadDataCenter;

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

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$year = system_CleanVars($_REQUEST, 'year', Tools::get_club_year(), 'int');
$seme = system_CleanVars($_REQUEST, 'seme', Tools::get_club_seme(), 'int');
$club_id = system_CleanVars($_REQUEST, 'club_id', 0, 'int');

Tools::chk_club_power(__FILE__, __LINE__, 'download');

$TadDataCenter = new TadDataCenter('club');
$TadDataCenter->set_col('club_setup', $year);
$setup = $TadDataCenter->getData();
$club_date_arr = explode(';', $setup['club_date'][0]);
$scsModuleConfig = Tools::get_xoopsModuleConfig();

if ($club_id) {
    $club = Club_main::get($club_id);
    $excel_title = "{$year}-{$seme}{$scsModuleConfig['school_name']}「{$club['club_title']}」社團點名表";
} else {
    $clubs = Club_main::get_all($year, $seme, true);
    $excel_title = "{$year}-{$seme}{$scsModuleConfig['school_name']}社團點名表";
}

/** Error reporting */
error_reporting(E_ALL);

require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel.php'; //引入 PHPExcel 物件庫
require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php'; //引入 PHPExcel_IOFactory 物件庫
$objPHPExcel = new PHPExcel(); //實體化Excel

$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);

$p = 0;
if ($club_id) {
    club_data($p, $club_id, $club);
} else {
    foreach ($clubs as $club_id => $club) {
        club_data($p, $club_id, $club);
        $p++;
    }
}

$objPHPExcel->setActiveSheetIndex(0);
$excel_title = (_CHARSET === 'UTF-8') ? iconv('UTF-8', 'Big5', $excel_title) : $excel_title;
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename={$excel_title}.xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(false);
$objWriter->save('php://output');
exit;

function num2alpha($n)
{
    for ($r = ""; $n >= 0; $n = intval($n / 26) - 1) {
        $r = chr($n % 26 + 0x41) . $r;
    }

    return $r;
}

function club_data($p, $club_id, $club)
{
    global $objPHPExcel, $club_date_arr, $styleArray;

    $objPHPExcel->createSheet(); //建立新的工作表，上面那三行再來一次，編號要改
    $objPHPExcel->setActiveSheetIndex($p);
    $objActSheet = $objPHPExcel->getActiveSheet();
    $objActSheet->setTitle($club['club_title']); //設定標題

    $objActSheet->getColumnDimension('A')->setWidth(6);
    $objActSheet->getColumnDimension('B')->setWidth(6);
    $objActSheet->getColumnDimension('C')->setWidth(10);
    $objActSheet->getColumnDimension('D')->setWidth(10);

    $n = 3;
    foreach ($club_date_arr as $club_date) {
        $n++;
        $col = num2alpha($n);
        $objActSheet->getColumnDimension($col)->setWidth(8);
    }

    $objPHPExcel->getDefaultStyle()->getFont()->setName('新細明體')->setSize(12);
    $objActSheet->getStyle("A1")->getFont()->setName('標楷體')->setSize(16)->setBold(true);
    $objActSheet->mergeCells("A1:{$col}1")->setCellValue('A1', "{$scsModuleConfig['school_name']}{$year}學年度第{$seme}學期 {$club['club_title']} 點名表");

    $objActSheet->setCellValue('A2', '班級')
        ->setCellValue('B2', '座號')
        ->setCellValue('C2', '學號')
        ->setCellValue('D2', '姓名');

    $n = 3;
    foreach ($club_date_arr as $club_date) {
        $n++;
        $col = num2alpha($n);
        $objActSheet->setCellValue("{$col}2", $club_date);
    }

    $i = 2;
    $ok_stu = Club_choice::choice_result_ok($club_id);
    foreach ($ok_stu as $stu) {
        $i++;
        $objActSheet->setCellValue("A{$i}", "{$stu['stu_grade']}-{$stu['stu_class']}")
            ->setCellValue("B{$i}", $stu['stu_seat_no'])
            ->setCellValue("C{$i}", $stu['stu_no'])
            ->setCellValue("D{$i}", $stu['stu_name']);
        $n = 3;
        foreach ($club_date_arr as $club_date) {
            $n++;
            $col = num2alpha($n);
            $objActSheet->setCellValue("{$col}{$i}", '');
        }

    }

    $objActSheet->getStyle("A1:{$col}{$i}")->applyFromArray($styleArray);

    $objActSheet->getStyle("A1:{$col}{$i}")->getAlignment()
        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER) //垂直置中
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //水平置中

}
