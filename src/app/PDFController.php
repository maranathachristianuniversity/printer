<?php
namespace anywhere\app;

use anywhere\engine\AnywhereController;
use anywhere\engine\ParseEngine;
use anywhere\model\DBAnywhere;
use Dompdf\Dompdf;

class PDFController extends AnywhereController
{

    private $outputmode;
    private $paper;
    private $html;
    private $css;
    private $reportname;
    private $requesttype;

    private $requestsample; //json sample data

    private $dompdf;

    public function __construct()
    {
        @session_start();
        $this->dompdf = new DOMPDF();
    }

    public function render($apikey, $pdfID)
    {
        $pdfRender = DBAnywhere::GetPdfRender($apikey, $pdfID)[0];

        $this->outputmode = $pdfRender['outputmode'];
        $this->paper = $pdfRender['paper'];
        $this->html = $pdfRender['html'];
        $this->css = $pdfRender['css'];
        $this->reportname = $pdfRender['reportname'];
        $this->requesttype = $pdfRender['requesttype'];
        $this->requestsample = $pdfRender['requestsample'];

        $head = file_get_contents(FILE . '/storage/head.html');
        $head .= "<link href='" . ROOT . '/storage/' . $pdfRender['ID'] . '/' . $this->css . "' rel='stylesheet'></head><body>";
        $path = file_get_contents(FILE . '/storage/' . $pdfRender['ID'] . '/' . $pdfRender['html']);
        $tail = file_get_contents(FILE . '/storage/tail.html');
        $content = $head . $path . $tail;

        $template = new ParseEngine($content);
        $template->setArrays(json_decode($pdfRender['requestsample']));
        $template->Parse();

        $this->dompdf->setPaper($this->paper);
        $this->dompdf->loadHtml($template->ClearOutput());
        $this->dompdf->render();

        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Author: Anywhere 0.1");
        header('Content-Type: application/pdf');

        if ($this->outputmode == 'Inline') {
            $this->dompdf->stream($this->reportname, array("Attachment" => false));
        }
        if ($this->outputmode == 'Download') {
            $this->dompdf->stream($this->reportname, array("Attachment" => true));
        }
        //echo file_put_contents('Brochure.pdf', $output);
    }

    public function CodeRender($apikey, $pdfID)
    {
        $pdfRender = DBAnywhere::GetPdfRender($apikey, $pdfID)[0];

        $this->outputmode = $pdfRender['outputmode'];
        $this->paper = $pdfRender['paper'];
        $this->html = $pdfRender['html'];
        $this->css = $pdfRender['css'];
        $this->reportname = $pdfRender['reportname'];
        $this->requesttype = $pdfRender['requesttype'];
        $this->requestsample = $pdfRender['requestsample'];

        $head = file_get_contents(FILE . '/storage/head.html');
        $head .= "<link href='" . ROOT . '/storage/' . $pdfRender['ID'] . '/' . $this->css . "' rel='stylesheet'></head><body>";
        $path = file_get_contents(FILE . '/storage/' . $pdfRender['ID'] . '/' . $pdfRender['html']);
        $tail = file_get_contents(FILE . '/storage/tail.html');
        $content = $head . $path . $tail;

        $template = new ParseEngine($content);
        $template->setArrays(json_decode($pdfRender['requestsample']));
        $template->Parse();

        $this->dompdf->setPaper($this->paper);
        $this->dompdf->loadHtml($template->ClearOutput());
        $this->dompdf->render();

        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Author: Anywhere 0.1");
        header('Content-Type: application/pdf');

        $this->dompdf->stream($this->reportname, array("Attachment" => false));
        //echo file_put_contents('Brochure.pdf', $output);
    }

    public function designer()
    {
        if ((int)$_SESSION['statusID'] == 1) {
            $result = DBAnywhere::CountPDFUser($_SESSION['ID'])[0];
            if ((int)$result['result'] >= 2)
                $this->RedirectTo('/limitations');
        }
        $pdfID = DBAnywhere::NewPdfPage($_SESSION['ID']);
        $dataPDF = $_SESSION;
        $dataPDF['pdf'] = DBAnywhere::GetPdfPage($pdfID)[0];
        $this->view('templates/head');
        $this->view('frontend/pdf/designer', $dataPDF);
    }

    public function main()
    {
        return $this->renderview('templates/head');
    }

    public function update($id)
    {
        if (isset($_POST['pdfid']) && isset($_POST['paper']) && isset($_POST['requesttype']) && isset($_POST['requesturl'])) {
            $arrayID = array('PDFID' => $_POST['pdfid']);
            $arrayData = array(
                'PDFID' => $_POST['pdfid'],
                'reportname' => $_POST['reportname'],
                'outputmode' => $_POST['outputmode'],
                'paper' => $_POST['paper'],
                'requesttype' => $_POST['requesttype'],
                'requesturl' => $_POST['requesturl'],
                'requestsample' => $_POST['requestsample'],
            );
            $result = DBAnywhere::UpdatePdfPage($arrayID, $arrayData);
            if ($result)
                $this->RedirectTo('/beranda');
        }

        $dataPDF = $_SESSION;
        $dataPDF['pdf'] = DBAnywhere::GetPdfPage($id)[0];
        $this->view('templates/head');
        $this->view('frontend/pdf/designer', $dataPDF);
    }

    public function html($idpdf)
    {
        $path = FILE . '/storage/' . $_SESSION['ID'];
        $file = $_SESSION;
        $file['pdf'] = DBAnywhere::GetPdfPage($idpdf)[0];

        if (isset($_POST['code'])) {
            file_put_contents($path . '/' . $file['pdf']['html'], $_POST['code']);
        }

        $file['html'] = file_get_contents($path . '/' . $file['pdf']['html']);
        $this->view('frontend/pdf/html', $file);
    }

    public function css($idpdf)
    {
        $this->view('frontend/pdf/css', array());
    }

}