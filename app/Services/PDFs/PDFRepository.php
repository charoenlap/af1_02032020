<?php namespace App\Services\PDFs;

use Codedge\Fpdf\Fpdf\Fpdf;
use App\Services\PDFs\PDF_Table;

class PDFRepository  {

	private function thai($txt)
	{
		return iconv('UTF-8', 'cp874', $txt);
	}

	private function content($fpdf, $shipper, $consignee, $connote_key, $y_offset, $page,$cod='',$cr='',$price='')
	{
	    // $consignee_address = iconv('UTF-8','TIS-620', $consignee->address);

		// BORDER.
		$fpdf->SetLineWidth(1);
		$fpdf->Rect(1, 1+$y_offset, 208, 137, 'D');

		// TITLE.
		$w = [43, 60, 60, 45];
		$fpdf->SetLineWidth(0.2);
	    $fpdf->SetXY(1, 1+$y_offset);
	    $fpdf->SetFont('THSarabun', '', 11);
	    $fpdf->Image(helperDirPublic()."/qrcode-web.jpg", 14, 2+$y_offset, 18, 18, "JPG");
	    $fpdf->MultiCell($w[0], 4.4, $this->thai("\n\n\n\n     www.airforceoneexpress.com"), 'B');
	    $fpdf->SetXY(1+$w[0], 1+$y_offset);
	    $fpdf->Image(helperDirPublic()."/logo-bw.jpg", $w[0]+5, 2+$y_offset, $w[1]-10, 18, "JPG");
	    $fpdf->MultiCell($w[1], 4.4, $this->thai("\n\n\n\n      เลขประจำตัวผู้เสียภาษีอากร 0105547016372"), 'B');
	    $fpdf->SetXY(1+$w[0]+$w[1], 1+$y_offset);
	    $fpdf->SetFont('THSarabun', 'B', 12);
	    $fpdf->MultiCell($w[2], 5.5, $this->thai(" สำนักงานใหญ่ : 519 ซอยศูนย์วิจัย 4 \n แขวงบางกะปิ เขตห้วยขวาง กรุงเทพฯ 10310 \n Tel. 02-3188795-8 Fax. 02-3188799 \n E-mail : admin@af1exp.com"), 'B');
	    $fpdf->SetXY(1+$w[0]+$w[1]+$w[2], 1+$y_offset);
	    // $fpdf->SetFont('THSarabun', '', 10);
	    // $fpdf->MultiCell(10, 1.35, $this->thai('original'), '');
	    $fpdf->SetFont('THSarabun', 'B', 14);
	    $fpdf->Image('data:image/png;base64,'.\DNS1D::getBarcodePNG($connote_key, "C128", 3, 50), 170, 9+$y_offset, 33, 9, "PNG");
	    $fpdf->MultiCell($w[3], 7.35, $this->thai("                             ".$page." \n\n        ".$connote_key), 'B');
	    // HEADER TABLE.
	    $w = [80, 80, 48];
	    $y = $fpdf->GetY();
	    $fpdf->SetXY(1, $y);
	    $fpdf->SetFont('THSarabun', '', 11);
	    $fpdf->MultiCell($w[0], 4, $this->thai(" 1.  S H I P P E R (ผู้ส่ง) \n   ACCOUNT NUMBER (รหัสลูกค้า)"), 'RB');
	    $fpdf->SetXY($w[0]+1, $y);
	    $fpdf->MultiCell($w[0], 4, $this->thai(" 2.  CONSIGNEE (ผู้รับ) \n\n"), 'RB');
	    $fpdf->SetXY($w[0]+$w[1]+1, $y);
	    $fpdf->MultiCell($w[2], 4, $this->thai("5.  SIZE & WEIGHT (ขนาดและน้ำหนัก) \n\n"), 'RB');
	    // ROW #1.
	    $y = $fpdf->GetY();
	    $fpdf->SetXY(1, $y);
	    $fpdf->SetFont('THSarabun', '', 9);
	    $fpdf->MultiCell($w[0], 5.5, $this->thai("  COMPANY NAME (ชื่อบริษัท)"), 'R');
	    $fpdf->SetXY($w[0]+1, $y);
	    $fpdf->MultiCell($w[0], 5.5, $this->thai("  COMPANY NAME (ชื่อบริษัท)"), 'R');
	    $fpdf->SetXY($w[0]+$w[1]+1, $y);
	    $fpdf->MultiCell($w[2], 5.5, $this->thai("  NO OF PIECES (จำนวนชิ้น)"), 'RB');
	    // ROW #2.
	    $y = $fpdf->GetY();
	    $fpdf->SetXY(1, $y);
	    $fpdf->SetFont('THSarabun', '', 10);
	    $fpdf->MultiCell($w[0], 4.5, $this->thai("         ".$shipper->company."\n\n  SENDER'S NAME (ชื่อผู้ส่ง) :  ".$shipper->person."\n  ADDRESS (ที่อยู่) \n   ".$shipper->address." ".$shipper->district." ".$shipper->province." ".$shipper->postcode."\n\n  POSTAL CODE (รหัสไปรษณีย์) :     ".$shipper->postcode)."\n\n", 'R');
	    $fpdf->SetXY($w[0]+1, $y);
	    $fpdf->MultiCell($w[0], 4.5, $this->thai("        ".$consignee->company."\n\n  RECEIVER'S NAME (ชื่อผู้รับ) :  ".$consignee->person." \n  ADDRESS (ที่อยู่) \n   ".$consignee->address." ".$consignee->district." ".$consignee->province." ".$consignee->postcode."\n\n  POSTAL CODE (รหัสไปรษณีย์) :     ".$consignee->postcode), 'R');
	    $fpdf->SetXY($w[0]+$w[1]+1, $y);
	    $fpdf->MultiCell($w[2], 4, $this->thai("  Packages (สิ่งของ) \n     W (กว้าง)     L (ยาว)     H (สูง)       น้ำหนัก\n 1. _______x_______x_______     ________\n 2. _______x_______x_______     ________\n 3. _______x_______x_______     ________\n 4. _______x_______x_______     ________\n 5. _______x_______x_______     ________\n\n           Total (ยอมรวมทั้งสิ้น)    __________ \n"), 'LRB');
	    // ROW #3.
	    $y = $fpdf->GetY();
	    $fpdf->SetXY(1, $y);
	    // $fpdf->SetFont('THSarabun', '', 9);
	    $fpdf->MultiCell($w[0], 6, $this->thai("  PHONE (โทรศัพท์) :    ".$shipper->mobile), 'RB');
	    $fpdf->SetXY($w[0]+1, $y);
	    $fpdf->MultiCell($w[0], 6, $this->thai("  PHONE (โทรศัพท์) :     ".$consignee->mobile), 'RB');
	    $fpdf->SetFont('THSarabun', '', 11);
	    $fpdf->SetXY($w[0]+$w[1]+1, $y);
	    $fpdf->MultiCell($w[2], 6, $this->thai(" 4. SERVICE TYPE (ประเภทบริการ)"), 'R');
	    // ROW #4.
	    $y4 = $fpdf->GetY();
	    $fpdf->SetXY(1, $y4);
	    $fpdf->SetFont('THSarabun', '', 11);
	    $fpdf->MultiCell($w[0], 7, $this->thai(" 3. SENDER'S AUTHORIZATION AND SIGNATURE (ลายมือชื่อลูกค้า)"), 'RB');
	    $fpdf->SetXY($w[0]+1, $y4);
	    $fpdf->MultiCell($w[0], 7, $this->thai("  FULL DESCRIPTION OF CONTENTS AND SPECIAL INSTRUCTION"), 'R');
	    // ROW #5.
	    $y5 = $fpdf->GetY();
	    $fpdf->SetXY(1, $y5);
	    $fpdf->SetFont('THSarabun', '', 6.7);
	    $fpdf->MultiCell($w[0], 2.5, $this->thai("         I  /  we,  on behalf  of the Sender,  agree  that  the  use  of  this consignment note  consitutes my /\n  our agreement  to the Terms  and  Conditions  of  transport  on the back  of thie consignent note  and \n  hereby  certify  that  all facts  and  information  stated  therein  are true and correct.  I / we have read \n  through the Terms  and  Conditions  and  acknowledge  the  right  of  the  Carrier  to  exonerate  itself \n  form  the  liability  or  to limit its liability  as  specified  therein  and  undertake  to  comply  with such \n       ข้าพเจ้าในฐานะผู้ส่งของ ตกลงว่าการใช้ใบตราส่งนี้ของข้าพระเจ้าเป็นการยอมรับตามข้อกำหนดและเงื่อนไขการขนส่งที่ \nปรากฎอยู่ด้านหลังชองใบตาส่งนี้ และขอรับรอง ณ ที่นี้ว่าบรรดาข้อความและข้อมูลต่างๆ ที่ปรากฎอยู่เ็นความจริงและถูกต้อง \nข้าพเจ้าได้อ่านข้กำหนดและเงื่อนไขการขนส่งโดยตลอดแล้ว ขอยอมรับว่าผู้ขนส่งมีสิทธิที่จะยกเว้นความรับผิด หรือจำกัดความ \nรับผิดตามที่ระบุไว้และยอมรับที่จะปฏิบัติตามข้อกำหนดและเงื่อนไขดังกล่าว เพื่อเป็นหลักฐานแห่งการนี้ ข้าพเจ้าขอลงนามในวัน\nและเวลาการส่งของตามที่ระบุไว้ข้างใต้นี้  "), 'RB');
	    $fpdf->SetXY($w[0]+1, $y5);
	    $fpdf->SetFont('THSarabun', '', 9);
	    $fpdf->MultiCell($w[0], 2.5, $this->thai("  (รายละเอียดสิ่งของและคำสั่งซื้อ) \n\n".(!empty($price) ? "     COD : ".$price." บาท" : "")."\n\n".(!empty($cr) ? "     Customer Ref. : ".$cr : "")."\n\n\n"), 'RB');
	    $fpdf->SetFont('THSarabun', '', 12);
	    $fpdf->SetXY($w[0]+1, $fpdf->GetY());
	    $fpdf->MultiCell($w[0], 7.5, $this->thai("          PROOF OF DELIVERY (POD) (หลักฐานการส่งมอบ)"), 'RB');
	    // ROW #6.
	    $y6 = $fpdf->GetY();
	    $fpdf->SetXY(1, $y6);
	    $fpdf->SetFont('THSarabun', '', 9);
	    $fpdf->MultiCell($w[0], 4.5, $this->thai("  SENDER'S SIGNATURE (ลายมือชื่อลูกค้า) \n                                                             DATE (วันที่) _____ / _____ / _______ \n                                                             TIME (เวลา) ______________ AM /PM \n"), 'RB');
	    $fpdf->SetXY($w[0]+1, $y6);
	    $fpdf->SetFont('THSarabun', '', 12);
	    $fpdf->MultiCell($w[1], 6, $this->thai("  PRINT NAME ONLY \n\n"), 'R');
	    // ROW #7.
	    $y7 = $fpdf->GetY();
	    $fpdf->SetXY(1, $y7+1);
	    $fpdf->SetFont('THSarabun', '', 9);
	    $fpdf->MultiCell($w[0], 4.5, $this->thai("  RECEIVED BY AFO \n                                                             DATE (วันที่) _____ / _____ / _______ \n                                                             TIME (เวลา) ______________ AM /PM"), 'R');
	    $fpdf->SetXY($w[0]+1, $y7);
	    $fpdf->SetFont('THSarabun', '', 10);
	    $fpdf->MultiCell($w[1], 5, $this->thai("\n\n  DATE (วันที่) _____ / _____ / _______ : TIME (เวลา) __________ AM / PM"), 'R');

	    // LAST COLUMN.
	    $fpdf->SetXY($w[0]+$w[1]+1, $y4);
	    $fpdf->SetFont('THSarabun', '', 10);
	    $fpdf->MultiCell($w[2], 6, $this->thai("           DOCUMENT (เอกสาร) \n           SMALL PARCEL (พัสดุ)\n           10.00 EXPRESS\n           12.00 EXPRESS\n           SAME-DAY (BKK ONLY)\n           NEXT DAY\n           INSURANCE (DECLARED VALUE)\n           COD (รับเงินสด)\n           RETURN INVOICE (เอกสารกลับ)"), 'R');
	    for ($i = 0; $i < 9; $i++) {
	    	$fpdf->Image(helperDirPublic()."/checkbox.jpg", $w[0]+$w[1]+4, ($i*6)+$y4+1, 3.8, 3.8, "JPG");
	    }
	}

	public function genConnote($shipper, $consignee, $connote_key,$cod='',$cr='',$price='')
	{
		$fpdf = new FPDF;
	    $fpdf->SetTitle('AF1-CONNOTE.pdf');
	    $fpdf->AddPage();
	    $fpdf->SetXY(0, 0);
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');
	    $fpdf->AddFont('THSarabun','','THSarabun.php');
	    $fpdf->SetFont('THSarabun', 'B', 16);

	    $this->content($fpdf, $shipper, $consignee, $connote_key, 0, 'original',$cod,$cr,$price);
	    $this->content($fpdf, $shipper, $consignee, $connote_key, 139, ' copy1',$cod,$cr,$price);

	    $fpdf->AddPage();
	    $fpdf->SetXY(0, 0);
	    $fpdf->AddFont('THSarabun','B','THSarabun.php');
	    $fpdf->AddFont('THSarabun','','THSarabun.php');
	    $fpdf->SetFont('THSarabun', 'B', 16);

	    $this->content($fpdf, $shipper, $consignee, $connote_key, 0, ' copy2',$cod,$cr,$price);
	    $this->content($fpdf, $shipper, $consignee, $connote_key, 139, ' copy3',$cod,$cr,$price);

	    $fpdf->Output('I', 'AF1-CONNOTE.pdf');
	    die();
	}
}