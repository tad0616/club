<?php
use Xmf\Request;
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
$op = Request::getString('op');
$club_id = Request::getInt('club_id');
$year = Request::getInt('year', Tools::get_club_year());
$seme = Request::getInt('seme', Tools::get_club_seme());

Tools::chk_club_power(__FILE__, __LINE__, 'download');

$club = Club_main::get($club_id);

$scsModuleConfig = Tools::get_xoopsModuleConfig();

$excel_title = "{$year}-{$seme}{$scsModuleConfig['school_name']}「{$club['club_title']}」成績匯入檔";

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

$objPHPExcel->createSheet(); //建立新的工作表，上面那三行再來一次，編號要改
$objPHPExcel->setActiveSheetIndex(0);
$objActSheet = $objPHPExcel->getActiveSheet();
$objActSheet->setTitle($club['club_title']); //設定標題

$objActSheet->getColumnDimension('A')->setWidth(6);
$objActSheet->getColumnDimension('B')->setWidth(6);
$objActSheet->getColumnDimension('C')->setWidth(10);
$objActSheet->getColumnDimension('D')->setWidth(10);
$objActSheet->getColumnDimension('E')->setWidth(10);

$objPHPExcel->getDefaultStyle()->getFont()->setName('新細明體')->setSize(12);

$objActSheet->setCellValue('A1', '班級')
    ->setCellValue('B1', '座號')
    ->setCellValue('C1', '學號')
    ->setCellValue('D1', '姓名')
    ->setCellValue('E1', '成績');

$i = 1;
$ok_stu = Club_choice::choice_result_ok($club_id);
foreach ($ok_stu as $stu) {
    $i++;
    $objActSheet->setCellValue("A{$i}", "{$stu['stu_grade']}-{$stu['stu_class']}")
        ->setCellValue("B{$i}", $stu['stu_seat_no'])
        ->setCellValue("C{$i}", $stu['stu_no'])
        ->setCellValue("D{$i}", $stu['stu_name'])
        ->setCellValue("E{$i}", '');
}

$objActSheet->getStyle("A1:E{$i}")->applyFromArray($styleArray);

$objActSheet->getStyle("A1:E{$i}")->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER) //垂直置中
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //水平置中

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
