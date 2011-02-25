<?php
# Allow only posting of data unless you know the secret pass-code-phrase-entry-word
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
	if (empty($_REQUEST['pw']) || $_REQUEST['pw'] != 'heynowheynowaikoaikoallday') {
		@header('HTTP/1.1 405 Method Not Allowed');
		@header('Status: 405 Method Not Allowed');
		exit;
	}
}

# prevent caching
@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
@header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');

// constants
define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT'].'/');
define('LIB_DIR', ROOT_DIR.'lib/'); // requires trailing slash
define('FPDF_FONTPATH', LIB_DIR.'fonts/'); // requires trailing slash
define('PDF_TEMPLATE_DIR', ROOT_DIR.'assets/pdf/'); // requires trailing slash

// get libs
require_once(PDF_TEMPLATE_DIR.'pdf-config.php');
require_once(LIB_DIR.'fpdf16/fpdf.php');
require_once(LIB_DIR.'FPDI-1.4/fpdi.php');

// Config variable entities
$lang = (!empty($_REQUEST['lang']) && in_array($_REQUEST['lang'], $config['langs']) ? $_REQUEST['lang'] : 'en');
$date = (!empty($_REQUEST['date']) ? $_REQUEST['date'] : date('m.d.Y'));
$endorsed_by = (!empty($_REQUEST['endorsed_by']) ? strtoupper($_REQUEST['endorsed_by']) : strtoupper('Endorsed By'));
$on_date = (!empty($_REQUEST['on_date']) ? $_REQUEST['on_date'] : 'ON '.date('m.d.Y'));
$name = (!empty($_REQUEST['name']) ? strtoupper($_REQUEST['name']) : $config['default_name']);

// init
$loaded_fonts = array();
$pdf =& new FPDI('P', 'pt');
$pdf->SetAuthor($config['info']['author']);
$pdf->SetSubject($config['info']['subject']);
$pdf->SetTitle($config['info']['title']);
$pdf->SetAutoPageBreak(false);

// build
foreach ($config['pages'] as $template) {
	$template_file = PDF_TEMPLATE_DIR.$template['template_basename'].'-'.$lang.'.pdf';
	if (is_file($template_file) && is_readable($template_file)) {
		// add new page from template
		$pagecount = $pdf->setSourceFile($template_file);
		$pgidx = $pdf->importPage(1, '/MediaBox');
		$pgsize = $pdf->getTemplateSize($pgidx);
		$pdf->addPage($template['orientation'], array($pgsize['h'], $pgsize['w']));
		$pdf->useTemplate($pgidx);
		
		// insert text
		if (!empty($template['text'])) {
			foreach ($template['text'] as $varname => $text) {
				// load font
				if (!in_array($text['font'], $loaded_fonts)) {
					$pdf->AddFont($text['font'], '', $config['fonts'][$text['font']]);
					array_push($loaded_fonts, $text['font']);
				}
				
				// setup font for this text
				$pdf->SetFont($text['font'], '', $text['size']);
				$pdf->SetTextColor($text['color']['r'], $text['color']['g'], $text['color']['b']);
				$pdf->SetXY($text['position']['x'], $text['position']['y']);
				
				// text align
				$align = 'L';
				if (!empty($text['align']) && in_array($text['align'], array('L', 'C', 'R'))) {
					$align = $text['align'];
				}
				
				// box width
				$width = 0;
				if (!empty($text['position']['w'])) {
					$width = intval($text['position']['w']);
				}
				
				// write				
				$pdf->Cell($width, 0, $$varname, 0, 0, $align);
			}
		}				
	}
}

// deliver
$pdf->Output('foo.pdf', $config['delivery_method']);

?>