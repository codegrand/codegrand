<?php
//============================================================+
// File name   : example_002.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 002 for TCPDF class
//               Removing Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD 
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Bank Mandate Form');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 15, 10);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage();

// set some text to print
$html = '
<style>
	.textcenter {text-align:center;}
	.textright {text-align:right;}
	.border {border:1px solid #000;}
</style>

<table cellpadding="0" border="0" cellspacing="0" style="color: #000; font-size: 8pt; line-height:12px; color:#000;">  
	<tr>
		<td>
			<table cellpadding="4" border="0" cellspacing="0">
				<tr>
					<td width="28%"><b><u>NACH/ECS/AUTO DEBIT<br>MANDATE INSTRUCTION FORM</u></b></td>
					<td width="52%"><b>UMRN:</b> 6565656565659865356859</td>
					<td width="20%" class="textright"><b>Date:</b>22-10-2018</td>
				</tr>
			</table>
			<table cellpadding="2" border="0" cellspacing="0">
				<tr>
					<td width="15%">
						<table cellpadding="2" border="0" cellspacing="0" width="90%">
							<tr>
								<td>Tick ( <img src="images/check-icon-nb-1.png" height="8px;"> )</td>
							</tr>
							<tr>
								<td class="border">CREATE <img src="images/check-icon-nb-2.png" height="8px;"></td>
							</tr>
							<tr>
								<td class="border">MODIFY</td>
							</tr>
							<tr>
								<td class="border">CANCEL</td>
							</tr>
						</table>
					</td>
					<td width="85%">
						<table cellpadding="4" border="0" cellspacing="0">
							<tr>
								<td width="55%" class="textright"><b>Sponsor Bank Code:</b> _____________________________</td>
								<td width="45%" ><b>Utility Code</b> __________________________________</td>
							</tr>
							<tr>
								<td><b>I/We hereby authorize:</b> BSE Limited</td>
								<td ><b>to debit (tick <img src="images/check-icon-nb-1.png" height="8px;"> ) :</b> SB/CA/CC/SB-NRE/SB-NRO/Other</td>
							</tr>
							<tr>
								<td colspan="2"><b>Bank a/c number:</b> 0 0 1 9 0 1 0 1 0 1 5 9</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table cellpadding="4" border="0" cellspacing="0">
				<tr>
					<td width="40%"><b>with Bank:</b> ICICI BANK LIMITED</td>
					<td width="30%"><b>IFSC:</b> I C I C 0 0 0 0 0 1 9</td>
					<td width="30%" class="textright"><b>or MICR:</b> 4 0 0 2 2 9 0 0 6</td>
				</tr>
			</table>
			<table cellpadding="4" border="0" cellspacing="0">
				<tr>
					<td width="80%"><b>an amount of Rupees:</b> Ten Lacs only</td>
					<td width="20%"><b>Rs.</b> 1000000.00 </td>
				</tr>
			</table>
			<table cellpadding="4" border="0" cellspacing="0">
				<tr>
					<td width="60%"><b>FREQUENCY: &nbsp; &nbsp;</b> <img src="images/uncheck-icon.jpg" height="8px;"> Mthly &nbsp; &nbsp;<img src="images/uncheck-icon.jpg" height="8px;"> Qtly &nbsp; &nbsp;<img src="images/uncheck-icon.jpg" height="8px;"> H-Yrly  &nbsp; &nbsp; <img src="images/uncheck-icon.jpg" height="8px;"> Yrly &nbsp; &nbsp; <img src="images/check-icon.jpg" height="8px;"> As & when presented </td>
					<td width="40%" class="textright"><b>DEBIT TYPE: &nbsp; &nbsp;</b> <img src="images/uncheck-icon.jpg" height="8px;"> Fixed Amount &nbsp; &nbsp;<img src="images/check-icon.jpg" height="8px;"> Maximum Amount</td>
				</tr>
			</table>
			<table cellpadding="4" border="0" cellspacing="0">
				<tr>
					<td width="30%"><b>Reference 1 (Mandate Reference No.):</b></td>
					<td width="30%">1826314</td>
					<td width="10%"><b>Phone No:</b></td>
					<td width="30%">9004054630</td>
				</tr>
				<tr>
					<td><b>Reference 2 (Unique Client Code-UCC):</b></td>
					<td>BLYPK5232J </td>
					<td><b>Email ID:</b></td>
					<td>akkikulshrestha28@gmail.com </td>
				</tr>
			</table>
			<table cellpadding="4" border="0" cellspacing="0">
				<tr>
					<td style="font-size:7pt;">I agree for the debit of mandate processing charges by the bank whom I am authorizing to debit my account as per latest schedule of charges of the bank.</td>
				</tr>
			</table>
			<table cellpadding="1" border="0" cellspacing="0">
				<tr>
					<td width="25%">
						<table cellpadding="4" border="0" cellspacing="0" class="border">
							<tr>
								<td colspan="2"><b>PERIOD</b></td>
							</tr>
							<tr>
								<td width="20%">From</td>
								<td width="80%">0 9 1 0 2 0 1 8</td>
							</tr>
							<tr>
								<td>To</td>
								<td>0 9 1 0 2 0 1 8</td>
							</tr>
							<tr>
								<td>Or</td>
								<td><img src="images/check-icon.jpg" height="8px;"> Until Cancelled</td>
							</tr>
						</table>
					</td>
					<td width="75%">
						<table cellpadding="5" border="0" cellspacing="0">
							<tr>
								<td colspan="3" height="40"></td>
							</tr>
							<tr>
								<td width="33.33%">___________________________</td>
								<td width="33.33%">___________________________</td>
								<td width="33.33%">___________________________</td>
							</tr>
							<tr>
								<td>1. _________________________</td>
								<td>2. _________________________</td>
								<td>3. _________________________</td>
							</tr>
						</table>
					</td>					
				</tr>
				<tr>
					<td></td>
				</tr>
			</table>
			<table cellpadding="2" border="0" cellspacing="0" style="font-size:5.5pt; line-height:6pt; border-top:1px solid #000;">
				<tr>
					<td></td>
				</tr>
				<tr>
					<td>- This is to confirm that the declaration has been carefully read, understood & made by me/us. I am authorizing the user entity/ Corporate to debit my account, based on the instructions as agreed and signed by me.</td>
				</tr>
				<tr>
					<td>- I have understood that I am authorised to cancel/amend this mandate by appropriately communicating the cancellation / amendment request to the User entity / Corporate or the bank where I have authorized the debit.</td>
				</tr>
				<tr>
					<td style="border-bottom:1px dashed #000;"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Bank-Mandate-Form.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
