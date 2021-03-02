public function getTest()
	{

		$fpdf = new FPDF;
	    $fpdf->SetTitle('Receipt-1111.pdf');
	    $fpdf->AddPage();
	    $fpdf->SetXY(0, 0);
	    $fpdf->Image("http://skn_pedseupow.dev/af1.png",5,10,201,30,"PNG");
	    $fpdf->SetXY(10, $fpdf->GetY() + 40);

	     // ORDER TABLE.
	    $left = 5;
	    $line = 7;
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');//หนา
	    $fpdf->SetFont('THSarabun', 'B', 16);
	    $fpdf->SetX($left);
	    $fpdf->Cell($columb1 = 67, $line, iconv('UTF-8', 'cp874', '  S H I P P E R (ผู้ส่ง)'), "TRL", 0, 'L');
	    $fpdf->Cell($columb2 = 67, $line, iconv('UTF-8', 'cp874', ''), "TR", 0, 'L');
	    $fpdf->Cell($columb3 = 67, $line, iconv('UTF-8', 'cp874', ''), "TR", 0, 'L');
	    $fpdf->SetXY($left, $fpdf->GetY() + $line);

	    // HEAD TABLE.
	    $line = 7;
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');//หนา
	    $fpdf->SetFont('THSarabun', 'B', 12);
		$fpdf->Cell($columb1, $line, iconv('UTF-8', 'cp874', '1 ACCOUNT NUMBER (รหัสลูกค้า)'), "BRL", 0, 'L');
	    $fpdf->Cell($columb2, $line, iconv('UTF-8', 'cp874', '2 CONSIGNEE (ผู้รับ)'), "BR", 0, 'L');
	    $fpdf->Cell($columb3, $line, iconv('UTF-8', 'cp874', '5 SIZE & WEIGHT (ขนาดและน้ำหนัก)'), "BR", 0, 'L');
	    $fpdf->SetXY($left, $fpdf->GetY() + $line);

	    $line = 7;
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');//หนา
	    $fpdf->SetFont('THSarabun', 'B', 10);
		$fpdf->Cell($columb1, $line, iconv('UTF-8', 'cp874', '  COMPANY NAME (ชื่อบริษัท)'), "LR", 0, 'L');
	    $fpdf->Cell($columb2, $line, iconv('UTF-8', 'cp874', '  COMPANY NAME (ชื่อบริษัท)'), "R", 0, 'L');
	    $fpdf->Cell($columb3, $line, iconv('UTF-8', 'cp874', 'NO. OF PIECES (จำนวนชิ้น)      20 ชิ้น'), "R", 0, 'L');
	    $fpdf->SetXY($left, $fpdf->GetY() + $line);

	    $line = 20;
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');//หนา
	    $fpdf->SetFont('THSarabun', 'B', 8);
		$fpdf->Cell($columb1, $line, iconv('UTF-8', 'cp874', '  -บริษัท มีวิตตี้ ดิจิทัล มีเดีย จำกัด'), "LR", 0, 'L');
	    $fpdf->Cell($columb2, $line, iconv('UTF-8', 'cp874', '  -บริษัท มีวิตตี้ ดิจิทัล มีเดีย จำกัด'), "R", 0, 'L');
	    $fpdf->Cell($columb3, $line, iconv('UTF-8', 'cp874', ''), "TR", 0, 'L');
	    $fpdf->SetXY($left, $fpdf->GetY() + $line);

	    $line = 5;
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');//หนา
	    $fpdf->SetFont('THSarabun', 'B', 10);
		$fpdf->Cell($columb1, $line, iconv('UTF-8', 'cp874', "  SENDER'S NAME (ชื่อผู้ส่ง)   ณเดช คูกิมิยะ"), "LR", 0, 'L');
	    $fpdf->Cell($columb2, $line, iconv('UTF-8', 'cp874', "  RECEIVER'S NAME/DEPT (ชื่อผู้รับ)   บอย ปกรณ์"), "R", 0, 'L');
	    $fpdf->Cell($columb3, $line, iconv('UTF-8', 'cp874', ''), "R", 0, 'L');
	    $fpdf->SetXY($left, $fpdf->GetY() + $line);

	    $line = 5;
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');//หนา
	    $fpdf->SetFont('THSarabun', 'B', 10);
		$fpdf->Cell($columb1, $line, iconv('UTF-8', 'cp874', "  ADDRESS (ที่อยู่)"), "LR", 0, 'L');
	    $fpdf->Cell($columb2, $line, iconv('UTF-8', 'cp874', "  ADDRESS (ที่อยู่)"), "R", 0, 'L');
	    $fpdf->Cell($columb3, $line, iconv('UTF-8', 'cp874', ''), "R", 0, 'L');
	    $fpdf->SetXY($left, $fpdf->GetY() + $line);

	    $line = 20;
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');//หนา
	    $fpdf->SetFont('THSarabun', 'B', 8);
		$fpdf->Cell($columb1, $line, iconv('UTF-8', 'cp874', '  -604/3 แขวง ถนน เพชรบุรี เขต ราชเทวี, ถนน เพชรบุรี กรุงเทพมหานคร'), "LR", 0, 'L');
	    $fpdf->Cell($columb2, $line, iconv('UTF-8', 'cp874', '  -604/3 แขวง ถนน เพชรบุรี เขต ราชเทวี, ถนน เพชรบุรี กรุงเทพมหานคร'), "R", 0, 'L');
	    $fpdf->Cell($columb3, $line, iconv('UTF-8', 'cp874', ''), "R", 0, 'L');
	    $fpdf->SetXY($left, $fpdf->GetY() + $line);

	    $line = 4;
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');//หนา
	    $fpdf->SetFont('THSarabun', 'B', 10);
		$fpdf->Cell($columb1, $line, iconv('UTF-8', 'cp874', "  POSTAL CODE (รหัสไปรษณีย์)   10400"), "LR", 0, 'L');
	    $fpdf->Cell($columb2, $line, iconv('UTF-8', 'cp874', "  POSTAL CODE (รหัสไปรษณีย์)   10400"), "R", 0, 'L');
	    $fpdf->Cell($columb3, $line, iconv('UTF-8', 'cp874', ''), "BR", 0, 'L');
	    $fpdf->SetXY($left, $fpdf->GetY() + $line);

	    $line = 7;
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');//หนา
	    $fpdf->SetFont('THSarabun', 'B', 10);
		$fpdf->Cell($columb1, $line, iconv('UTF-8', 'cp874', "  PHONE (โทรศัพท์)   0897561234"), "LBR", 0, 'L');
	    $fpdf->Cell($columb2, $line, iconv('UTF-8', 'cp874', "  PHONE (โทรศัพท์)   0897561234"), "BR", 0, 'L');
	    $fpdf->SetFont('THSarabun', 'B', 12);
	    $fpdf->Cell($columb3, $line, iconv('UTF-8', 'cp874', '4 SERVICE TYPE (ประเภทบริการ)'), "BR", 0, 'L');
	    $fpdf->SetXY($left, $fpdf->GetY() + $line);

	    $line = 7;
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');//หนา
	    $fpdf->SetFont('THSarabun', 'B', 10);
		$fpdf->Cell($columb1, $line, iconv('UTF-8', 'cp874', "3 SENDER'S AUTHORIZATON AND SIGNATURE (ลายมือชื่อลูกค้า)"), "LR", 0, 'L');
	    $fpdf->Cell($columb2, $line, iconv('UTF-8', 'cp874', 'FULL DESCRIPTION OF CONTENS AND SPECIAL INSTRUCTION'), "R", 0, 'L');
	    $fpdf->SetFont('THSarabun', 'B', 9);
	    $fpdf->Cell($columb3, $line, iconv('UTF-8', 'cp874', 'DOCUMENT (เอกสาร)'), "R", 0, 'L');
	    $fpdf->SetXY($left, $fpdf->GetY() + $line);

	    $fpdf->Output('I', 'Receipt-1111.pdf');
	    die();
	}