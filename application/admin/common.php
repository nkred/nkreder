<?php
//uuse think\Db;
//import('phpexcel.PHPExcel', EXTEND_PATH);
//use PHPExcel;
function classsorting($data, $pid = 0, $level = 0, $html = "<b style='font-size:16px;'>↑__</b>")
{
    static $arr = array();
    foreach ($data as $v) {
        if ($v['pid'] == $pid) {
            $v['html'] = str_repeat($html, $level);
            $v['hierarchy'] = $level; //循环的层级
            $arr[] = $v;
            classsorting($data, $v['id'], $level + 1);
        }
    }
    return $arr;
}
function classnav($data, $pid = 0)
{
    $arr = array();
    foreach ($data as $v) {
        if ($v['pid'] == $pid) {
            $v['class'] = classnav($data, $v['id']);
            $arr[] = $v;
        }
    }
    return $arr;
}
function savesName()
{
    $data = ['ID','被保险人','车牌号','送单人','出单公司','保费','车船','合计',
        '客户点位','双返点客户点位','实收保费','客户返点','双返点(客户)','公司点位',
        '双返点公司点位', '公司返点','双返点(公司)','净费','利润','备注','运费','地址',
        '是否出单','是否付款','出单日期'];
    return $data;
}
function findName($data)
{
    $str ='';
    foreach ($data as $v){
        switch ($v){
            case 'ID': $m = 'id';break;
            case '被保险人': $m = 'insurant';break;
            case '车牌号': $m = 'plate';break;
            case '送单人': $m = 'person';break;
            case '出单公司': $m = 'company';break;
            case '保费': $m = 'premium';break;
            case '车船': $m = 'vehicles';break;
            case '合计': $m = 'total';break;
            case '客户点位': $m = 'customer';break;
            case '双返点客户点位': $m = 's_customer';break;
            case '实收保费': $m = 'receipts';break;
            case '客户返点': $m = 'c_return';break;
            case '双返点(客户)': $m = 'sc_return';break;
            case '公司点位': $m = 'firm';break;
            case '双返点公司点位': $m = 's_firm';break;
            case '公司返点': $m = 'f_return';break;
            case '双返点(公司)': $m = 'sf_return';break;
            case '净费': $m = 'n_fee';break;
            case '利润': $m = 'profit';break;
            case '备注': $m = 'remarks';break;
            case '运费': $m = 'freight';break;
            case '地址': $m = 'address';break;
            case '是否出单': $m = 'issue';break;
            case '是否付款': $m = 'payment';break;
            case '出单日期': $m = 'time';break;
        }
        if($str !=''){
            $str = $str .',';
        }
        $str = $str . $m;
    }
    return $str;
}
/*
 * @ system专用无限极分类排序组合
 * @ param array $data 要排序组合的数据
 * @ param int $pid 顶级id
 * @ param int $level html内容组装的次数
 * @ param string $html 排序后前面的标识
 * @ return 返回组合好的多维数组
 */
function systemNav($data, $pid = 0, $level = 0, $html = "&nbsp;&nbsp;&nbsp;")
{

    static $arr = array();
    foreach ($data as $v) {
        if ($v['pid'] == $pid) {
            $v['html'] = str_repeat($html, $level);
            $arr[] = $v;
            systemNav($data, $v['id'], $level + 1);
        }
    }
    return $arr;
}
function uploadFile($name,$width,$height){
    $file = request()->file($name);
    if($file){
        $filePaths = 'uploads';
        if(!file_exists($filePaths)){
            mkdir($filePaths,0777,true);
        }
        $info = $file->move($filePaths);
        if($info){
            $imgpath = 'uploads/'.'/'.$info->getSaveName();
            $image = \think\Image::open($imgpath);
            $date_path = 'uploads/'.date('Ymd');
            if(!file_exists($date_path)){
                mkdir($date_path,0777,true);
            }
            $thumb_path = $date_path.'/'.$info->getFilename();
            $image->thumb($width, $height)->save($thumb_path);
            $data['img'] = $imgpath;
            $data['thumb_img'] = $thumb_path;
            return $data['thumb_img'];
        }else{
            // 上传失败获取错误信息
            return $file->getError();
        }
    }
}
/**
 * excel表格导出
 * @param string $fileName 文件名称
 * @param array $headArr 表头名称
 * @param array $data 要导出的数据
 * @author static7  */
function excelExport($fileName = '', $headArr = [], $data = []) {
    vendor("PHPExcel.PHPExcel");
    $fileName .= "_" . date("Y_m_d",time()) . ".xls";
    $objPHPExcel = new \PHPExcel();
    $objPHPExcel->getProperties();
    $key = ord("A"); // 设置表头
    foreach ($headArr as $v) {
        $colum = chr($key);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $key += 1;

    }
    $column = 2;
    $objActSheet = $objPHPExcel->getActiveSheet();
    foreach ($data as $key => $rows) { // 行写入
        $span = ord("A");
        foreach ($rows as $keyName => $value) { // 列写入
            $objActSheet->setCellValue(chr($span) . $column, $value);
            $span++;
        }
        $column++;
    }
    $fileName = iconv("utf-8", "gb2312", $fileName); // 重命名表
    $objPHPExcel->setActiveSheetIndex(0); // 设置活动单指数到第一个表,所以Excel打开这是第一个表
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=$fileName");
    header('Cache-Control: max-age=0');
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); // 文件通过浏览器下载
    exit();
}
function goodsClassNav($data, $pid = 0, $level, $html = "&nbsp;&nbsp;&nbsp;")
{
    static $arr = array();
    foreach ($data as $v) {
        if ($v['pid'] == $pid) {
            $v['html'] = str_repeat($html, $level);
            $arr[] = $v;
            goodsClassNav($data, $v['id'], $level + 1);
        }
    }
    return $arr;
}
function deldir($path){
    //如果是目录则继续
    if(is_dir($path)){
        //扫描一个文件夹内的所有文件夹和文件并返回数组
        $p = scandir($path);
        foreach($p as $val){
            //排除目录中的.和..
            if($val !="." && $val !=".."){
                //如果是目录则递归子目录，继续操作
                if(is_dir($path.$val)){
                    //子目录中操作删除文件夹和文件
                    deldir($path.$val.'/');
                    //目录清空后删除空文件夹
                    @rmdir($path.$val.'/');
                }else{
                    //如果是文件直接删除
                    unlink($path.$val);
                }
            }
        }
    }
}
