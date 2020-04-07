<?php
use XoopsModules\Club\Club_choice;
use XoopsModules\Club\Club_main;
use XoopsModules\Club\Tools;

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
$stu_id = system_CleanVars($_REQUEST, 'stu_id', 0, 'int');
$apply_id = system_CleanVars($_REQUEST, 'apply_id', 0, 'int');

Tools::chk_club_power(__FILE__, __LINE__, 'download');

$clubs = Club_main::get_all($year, $seme, true);

$excel_title = "{$year}-{$seme}社團結果依社團排列";

/** Error reporting */
error_reporting(E_ALL);

require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel.php'; //引入 PHPExcel 物件庫
require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php'; //引入 PHPExcel_IOFactory 物件庫
$objPHPExcel = new PHPExcel(); //實體化Excel
$objPHPExcel->createSheet(); //建立新的工作表，上面那三行再來一次，編號要改
$objPHPExcel->setActiveSheetIndex(0);
$objActSheet = $objPHPExcel->getActiveSheet(); //指定預設工作表為 $objActSheet
$objActSheet->setTitle($excel_title); //設定標題

$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);

$objActSheet->getColumnDimension('A')->setWidth(6);
$objActSheet->getColumnDimension('B')->setWidth(6);
$objActSheet->getColumnDimension('C')->setWidth(15);
$objActSheet->getColumnDimension('D')->setWidth(15);
$objActSheet->getColumnDimension('E')->setWidth(6);
$objActSheet->getColumnDimension('F')->setWidth(6);
$objActSheet->getColumnDimension('G')->setWidth(30);

$objActSheet->setCellValue('A1', '學年度')
    ->setCellValue('B1', '學期')
    ->setCellValue('C1', '姓名')
    ->setCellValue('D1', '學號')
    ->setCellValue('E1', '班級')
    ->setCellValue('F1', '座號')
    ->setCellValue('G1', '社團名稱');

$objActSheet->getStyle("A1:G1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFBFE9F2');

$i = 1;
$n = 0;
foreach ($clubs as $club_id => $club) {
    $ok_stu = Club_choice::choice_result_ok($club_id);
    foreach ($ok_stu as $stu) {
        $i++;
        $objActSheet->setCellValue("A{$i}", $club['club_year'])
            ->setCellValue("B{$i}", $club['club_seme'])
            ->setCellValue("C{$i}", $stu['stu_name'])
            ->setCellValue("D{$i}", $stu['stu_no'])
            ->setCellValue("E{$i}", "{$stu['stu_grade']}-{$stu['stu_class']}")
            ->setCellValue("F{$i}", $stu['stu_seat_no'])
            ->setCellValue("G{$i}", $club['club_title']);
        if ($n % 2) {
            $objActSheet->getStyle("A{$i}:G{$i}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFDFDFDF');
        }
    }
    $n++;
}

$objActSheet->getStyle("A1:G{$i}")->applyFromArray($styleArray);

$objActSheet->getStyle("A1:G{$i}")->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER) //垂直置中
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //水平置中

$p = 1;
foreach ($clubs as $club_id => $club) {
    $objPHPExcel->createSheet(); //建立新的工作表，上面那三行再來一次，編號要改
    $objPHPExcel->setActiveSheetIndex($p);
    $objActSheet = $objPHPExcel->getActiveSheet();
    $objActSheet->setTitle($club['club_title']); //設定標題

    $objActSheet->getColumnDimension('A')->setWidth(6);
    $objActSheet->getColumnDimension('B')->setWidth(6);
    $objActSheet->getColumnDimension('C')->setWidth(15);
    $objActSheet->getColumnDimension('D')->setWidth(15);
    $objActSheet->getColumnDimension('E')->setWidth(6);
    $objActSheet->getColumnDimension('F')->setWidth(6);
    $objActSheet->getColumnDimension('G')->setWidth(30);

    $objActSheet->setCellValue('A1', '學年度')
        ->setCellValue('B1', '學期')
        ->setCellValue('C1', '姓名')
        ->setCellValue('D1', '學號')
        ->setCellValue('E1', '班級')
        ->setCellValue('F1', '座號')
        ->setCellValue('G1', '社團名稱');

    $objActSheet->getStyle("A1:G1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFBFE9F2');

    $i = 1;
    $ok_stu = Club_choice::choice_result_ok($club_id);
    foreach ($ok_stu as $stu) {
        $i++;
        $objActSheet->setCellValue("A{$i}", $club['club_year'])
            ->setCellValue("B{$i}", $club['club_seme'])
            ->setCellValue("C{$i}", $stu['stu_name'])
            ->setCellValue("D{$i}", $stu['stu_no'])
            ->setCellValue("E{$i}", "{$stu['stu_grade']}-{$stu['stu_class']}")
            ->setCellValue("F{$i}", $stu['stu_seat_no'])
            ->setCellValue("G{$i}", $club['club_title']);
    }

    $objActSheet->getStyle("A1:G{$i}")->applyFromArray($styleArray);

    $objActSheet->getStyle("A1:G{$i}")->getAlignment()
        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER) //垂直置中
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //水平置中

    $p++;
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
