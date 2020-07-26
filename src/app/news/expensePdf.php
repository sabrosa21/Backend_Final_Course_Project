<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/fpdf182/fpdf.php');
require_once('../includes/functions.php');

$token = !empty($_GET['token']) ? $_GET['token'] : null;

/* ----------------- GET THE EXPENSE WITH THE MATCHING TOKEN ---------------- */
$sql = "SELECT u.fullname, u.email, e.* FROM expenses as e, users as u WHERE e.users_id=u.id and e.token = ? ";
$stmt = conn()->prepare($sql);
if ($stmt->execute([$token])) {
  $n = $stmt->rowCount();
  if ($n === 1) {
    $r = $stmt->fetch();
    $stmt = null;
  }
}

class PDF extends FPDF
{
  function Header()
  {
    global $title;

    $this->SetFont('Arial', 'B', 40);
    //--- Center the page title
    $w = $this->GetStringWidth($title) + 6;
    $this->SetX((210 - $w) / 2);
    //--- Bg color
    $this->SetFillColor(255, 255, 255);
    //--- Text color
    $this->SetTextColor(49, 151, 149);
    //--- Title
    $this->Cell($w, 15, $title, 0, 1, 'C', true);
    //--- Line break
    $this->Ln(10);
  }

  function Footer()
  {
    //--- Year
    $date = date('Y');
    //--- Author
    $author = '© Eurico Correia, ';
    //--- Margin bottom
    $this->SetY(-15);
    //--- Text color
    $this->SetTextColor(128);
    //--- Footer text
    $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', $author) . $date, 0, 0, 'C');
  }

  function SectionTitle($txt)
  {
    $this->SetFont('Arial', 'B', '13');
    $this->Cell(30, 5, $txt, 0, 0, 'L');
  }

  function TxtBody($type, $txtb)
  {
    $this->SetFont('Arial', '', '12');
    $this->$type === 0 ? $this->Cell(50, 10, 'teste', 0, 1, 'L') : $this->MultiCell(0, 5, $txtb, 0, 'L');
    // $this->$type(0, 5, $txtb, 0, 'L');
  }

  function Status($value)
  {
    $text = '';

    //--- Set the text and color based on status input
    switch ($value) {
        //--- Submitted
      case 0:
        $this->SetTextColor(206, 150, 38);
        $text = 'Submitted';
        break;
        //--- Accepted
      case 1:
        $this->SetTextColor(56, 161, 105);
        $text = 'Accepted';
        break;
        //--- Rejected
      case 2:
        $this->SetTextColor(229, 62, 62);
        $text = 'Rejected';
        break;
    }
    $text = strtoupper($text);
    $this->TxtBody(0, $text);
  }
}
//--- Create new pdf object
$pdf = new PDF();

$title = 'Expense details';
$user = $r['fullname'] ? $r['fullname'] : $r['email'];
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTitle($title);

//--- 1st row
$pdf->Cell(100, 10, 'Print date: ' . date('d/m/Y'), 0, 0, 'L');
$pdf->Cell(90, 10, 'User: ' . iconv('UTF-8', 'windows-1252', $user), 0, 0, 'R');

//--- Line
$pdf->Line(10, 50, 200, 50);

//--- 2nd row
$pdf->Ln(25);
$pdf->SectionTitle('Description: ');
$pdf->TxtBody(1, iconv('UTF-8', 'windows-1252', $r['description']));

//--- 3rd row
$pdf->Ln(10);
$pdf->SectionTitle('Reason: ');
$pdf->TxtBody(1, iconv('UTF-8', 'windows-1252', $r['reason']));

//--- 4th row
$pdf->Ln(10);
$pdf->SectionTitle('Date: ');
$pdf->TxtBody(0, date('d/m/Y', strtotime($r['date'])));

//--- 5th row
$pdf->Ln(10);
$pdf->SectionTitle('Cost: ');
$pdf->TxtBody(0, $r['cost'] . iconv('UTF-8', 'windows-1252', ' €'));

//--- 6th row
$pdf->Ln(10);
$pdf->SectionTitle('Status: ');
$pdf->Status($r['status']);

$pdf->Output();
