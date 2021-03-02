<?php namespace App\Services\Excels;

require_once dirname(__FILE__)."/PHPExcel.php";

class ExcelRepository  {

    public function getAlphabetColumn()
    {
        $alphabet = range('A', 'Z');
        foreach ($alphabet as $a) {
            foreach (range('A', 'Z') as $b) {
                array_push($alphabet, $a.$b);
            }
        }

        return $alphabet;
    }

	public function readExcel($file)
	{
        $objPHPExcel = \PHPExcel_IOFactory::load($file);

        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        foreach ($cell_collection as $cell)
        {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

            $data[$row][$column] = $data_value;

        }

        $objPHPExcel->disconnectWorksheets();
        return $data;
	}

    public function excelDateFormat($date)
    {
        return \PHPExcel_Style_NumberFormat::toFormattedString($date, "YYYY-MM-DD");
    }

    public function writeExcel($inputs, $sheet_name, $sheet_dir = '', $title_name = '')
    {
        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();

        // Add data
        $columes = $this->getAlphabetColumn();

        foreach ($inputs as $row => $data)
        {
            foreach ($data as $col => $value)
            {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columes[$col].($row + 1), $value);
            }
        }

        // Rename worksheet
        if ($title_name != "")
        {
            $objPHPExcel->getActiveSheet()->setTitle($title_name);
        }

        foreach($this->getAlphabetColumn() as $columnID)
        {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $sheet_fullname = ($sheet_dir != '' && file_exists($sheet_dir)) ? $sheet_dir.'/'.$sheet_name : $sheet_name;

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($sheet_fullname.'.xlsx');

        return 'success';
    }

    public function removeAllFileInTempExcelForlder($sheet_dir)
    {
        $files = glob($sheet_dir.'/*');

        foreach($files as $file) {
            if(is_file($file)) unlink($file);
        }

        return true;
    }
}