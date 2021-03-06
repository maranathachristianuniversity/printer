<?php
/**
 * Printer
 *
 * This content is released under the Apache License Version 2.0, January 2004
 * https://www.apache.org/licenses/LICENSE-2.0
 *
 * Copyright (c) 2016, Didit Velliz
 *
 * @author	Didit Velliz
 * @since	Version 1.0.0
 *
 */

namespace controller;

use model\ConstantaModel;
use model\LogPdf;
use model\UserModel;
use plugins\auth\AnywhereAuthenticator;
use Dompdf\Options;
use Dompdf\Dompdf;
use model\PdfModel;
use plugins\controller\AnywhereView;
use pte\Pte;
use satframework\auth\Session;
use satframework\Framework;
use satframework\Response;

/**
 * Class pdf
 * @package controller
 *
 * #ClearOutput false
 * #Master master.html
 * #Value PageTitle PDF Templates
 */
class pdf extends AnywhereView
{

    private $outputmode;
    private $paper;
    private $orientation;
    private $html;
    private $css;
    private $reportname;
    private $requesttype;
    private $requesturl;
    private $requestsample;
    private $cssexternal;

    /**
     * @var Dompdf
     */
    private $dompdf;

    private $head = "<!DOCTYPE html><html><body><style type='text/css'>";
    private $middle = "</style>";
    private $php_head = "<script type='text/php'>";
    private $php_tail = "</script>";
    private $tail = "</body></html>";

    /**
     * pdf constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $this->dompdf = new Dompdf($options);
    }

    /**
     * #Template html false
     * #Auth session true
     * @throws \Exception
     */
    public function Main()
    {
        $session = Session::Get(AnywhereAuthenticator::Instance())->GetLoginData();
        if (!isset($session['ID'])) $this->RedirectTo(Framework::$factory->getBase());

        if ((int)$session['statusID'] == 1) {
            $result = PdfModel::CountPDFUser($session['ID'])[0];
            if ((int)$result['result'] >= $session['limitations']) $this->RedirectTo('limitations');
        }

        $snap_shoot = date('d-m-Y-His');

        $arrayData = array(
            'userID' => $session['ID'],
            'reportname' => 'PDF-' . $snap_shoot . '.pdf',
            'requesturl' => '',
            'css' => file_get_contents(Framework::$factory->getRoot() . '/assets/template/starter.pdf.css'),
            'html' => file_get_contents(Framework::$factory->getRoot() . '/assets/template/starter.pdf.html'),
            'phpscript' => file_get_contents(Framework::$factory->getRoot() . '/assets/template/starter.pdf.php'),
            'outputmode' => 'Inline',
            'orientation' => 'portrait',
            'paper' => 'A4',
            'requesttype' => 'POST',
            'requestsample' => json_encode([
                "biodata" => [
                    [
                        "nama" => "Demo User",
                        "umur" => "22",
                        "dob" => "Jakarta, 21 Maret 1999",
                        "hobi" => "Programming",
                        "alamat" => "-",
                    ],
                    [
                        "nama" => "Anywhere User",
                        "umur" => "21",
                        "dob" => "Jakarta, 02 Maret 1998",
                        "hobi" => "Programming",
                        "alamat" => "-",
                    ]
                ]
            ], JSON_PRETTY_PRINT),
        );

        $pdfID = PdfModel::NewPdfPage($arrayData);
        $dataPDF = PdfModel::GetPdfPage($pdfID)[0];

        $this->RedirectTo('update/' . $dataPDF['PDFID']);
    }

    /**
     * @param $id
     * @return bool
     * #Auth session true
     * @throws \Exception
     * #Master master-codes.html
     */
    public function Update($id)
    {
        $session = Session::Get(AnywhereAuthenticator::Instance())->GetLoginData();
        if (isset($_POST['pdfid'])) {
            $arrayID = array('PDFID' => $_POST['pdfid']);
            $arrayData = array(
                'PDFID' => $_POST['pdfid'],
                'reportname' => $_POST['reportname'],
                'outputmode' => $_POST['outputmode'],
                'paper' => $_POST['paper'],
                'orientation' => $_POST['orientation'],
                'requesttype' => $_POST['requesttype'],
                'requesturl' => $_POST['requesturl'],
                'requestsample' => $_POST['requestsample'],
                'cssexternal' => $_POST['cssexternal'],
                'phpscript' => $_POST['phpscript'],
            );
            $resultUpdate = PdfModel::UpdatePdfPage($arrayID, $arrayData);

            if (!$resultUpdate) {
                $this->RedirectTo(Framework::$factory->getBase() . 'sorry');
            }
        }

        $dataPDF = $session;

        $dataPDF['pdf'] = PdfModel::GetPdfPage($id);

        $dataPDF['PageTitle'] = $dataPDF['pdf'][0]['reportname'];

        foreach ($dataPDF['pdf'] as $key => $value) {
            $dataPDF['pdf'][$key]['apikey'] = $session['apikey'];
            switch ($value['paper']) {
                case 'A3':
                    $dataPDF['pdf'][$key]['A3'] = 'checked';
                    break;
                case 'A4':
                    $dataPDF['pdf'][$key]['A4'] = 'checked';
                    break;
                case 'A5':
                    $dataPDF['pdf'][$key]['A5'] = 'checked';
                    break;
                case 'A6':
                    $dataPDF['pdf'][$key]['A6'] = 'checked';
                    break;
                case 'B5':
                    $dataPDF['pdf'][$key]['B5'] = 'checked';
                    break;
                case 'F4':
                    $dataPDF['pdf'][$key]['F4'] = 'checked';
                    break;
                case 'folio':
                    $dataPDF['pdf'][$key]['folio'] = 'checked';
                    break;
                case 'letter':
                    $dataPDF['pdf'][$key]['letter'] = 'checked';
                    break;
                case 'legal':
                    $dataPDF['pdf'][$key]['legal'] = 'checked';
                    break;
            }
            switch ($value['requesttype']) {
                case 'POST':
                    $dataPDF['pdf'][$key]['POST'] = 'checked';
                    break;
                case 'URL':
                    $dataPDF['pdf'][$key]['URL'] = 'checked';
                    break;
            }
            switch ($value['outputmode']) {
                case 'Inline':
                    $dataPDF['pdf'][$key]['Inline'] = 'checked';
                    break;
                case 'Download':
                    $dataPDF['pdf'][$key]['Download'] = 'checked';
                    break;
            }
            switch ($value['orientation']) {
                case 'portrait':
                    $dataPDF['pdf'][$key]['portrait'] = 'checked';
                    break;
                case 'landscape':
                    $dataPDF['pdf'][$key]['landscape'] = 'checked';
                    break;
            }
        }

        return $dataPDF;
    }

    /**
     * @param $id_pdf
     * @return bool
     *
     * #ClearOutput value false
     * #ClearOutput block false
     * #ClearOutput comment false
     * #Auth session true
     * @throws \Exception
     * #Master master-codes.html
     */
    public function Html($id_pdf)
    {
        $session = Session::Get(AnywhereAuthenticator::Instance())->GetLoginData();
        $file = $session;
        if (isset($_POST['code'])) {
            $arrayID = array('PDFID' => $id_pdf);
            PdfModel::UpdatePdfPage($arrayID, array(
                'html' => $_POST['code']
            ));
        }

        $file['pdf'] = PdfModel::GetPdfPage($id_pdf);

        $file['PageTitle'] = "[HTML] " . $file['pdf'][0]['reportname'];

        foreach ($file['pdf'] as $key => $val) {
            $val['apikey'] = $session['apikey'];
            $file['pdf'][$key] = $val;
        }
        $file['html'] = $file['pdf'][0]['html'];

        $file['designer'] = [];
        $file['style'] = [
            'ID' => $id_pdf
        ];

        return $file;
    }

    /**
     * @param $id_pdf
     * @return bool
     *
     * #ClearOutput value false
     * #ClearOutput block false
     * #ClearOutput comment false
     * #Auth session true
     * @throws \Exception
     * #Master master-codes.html
     */
    public function Style($id_pdf)
    {
        $session = Session::Get(AnywhereAuthenticator::Instance())->GetLoginData();
        $file = $session;
        if (isset($_POST['code'])) {
            $arrayID = array('PDFID' => $id_pdf);
            PdfModel::UpdatePdfPage($arrayID, array(
                'css' => $_POST['code']
            ));
        }

        $file['pdf'] = PdfModel::GetPdfPage($id_pdf);

        $file['PageTitle'] = "[CSS] " . $file['pdf'][0]['reportname'];

        foreach ($file['pdf'] as $key => $val) {
            $val['apikey'] = $session['apikey'];
            $file['pdf'][$key] = $val;
        }
        $file['css'] = $file['pdf'][0]['css'];

        $file['designer'] = [
            'ID' => $id_pdf
        ];
        $file['style'] = [];

        return $file;
    }

    /**
     * @param $api_key
     * @param $pdfId
     * @throws \pte\exception\PteException
     */
    public function CodeRender($api_key, $pdfId)
    {
        $pdfRender = PdfModel::GetPdfRender($api_key, $pdfId)[0];

        $this->outputmode = $pdfRender['outputmode'];
        $this->paper = $pdfRender['paper'];
        $this->orientation = $pdfRender['orientation'];
        $this->html = $pdfRender['html'];
        $this->css = $pdfRender['css'];
        $this->reportname = $pdfRender['reportname'];
        $this->requesttype = $pdfRender['requesttype'];
        $this->requestsample = $pdfRender['requestsample'];
        $this->cssexternal = $pdfRender['cssexternal'];

        $script = $pdfRender['phpscript'];
        $php_script = $this->php_head . $script . $this->php_tail;

        $htmlFactory = $this->head . $this->css . $this->middle . $php_script . $this->cssexternal . $this->html . $this->tail;

        $response = new Response();
        $response->useMasterLayout = false;

        $render = new Pte(false);
        if ($response->useMasterLayout) {
            $render->SetMaster($response->htmlMaster);
        }

        $render->SetValue(json_decode($pdfRender['requestsample'], true));
        $render->SetHtml($htmlFactory, true);
        $template = $render->Output($this, Pte::VIEW_HTML);

        $this->dompdf->setPaper($this->paper, $this->orientation);
        $this->dompdf->loadHtml($template);
        $this->dompdf->render();

        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Author: Anywhere 0.1");
        header('Content-Type: application/pdf');

        $this->dompdf->stream($this->reportname . '.pdf', array("Attachment" => 0));
        exit();
    }

    /**
     * #Template master false
     * @param $api_key
     * @param $pdfID
     * @throws \Exception
     */
    public function Render($api_key, $pdfID)
    {
        $pdfRender = PdfModel::GetPdfRender($api_key, $pdfID)[0];

        //because render executed outside vars need to be re-supplied
        $this->vars = ConstantaModel::GetCollection($pdfRender['userID']);

        $this->outputmode = $pdfRender['outputmode'];
        $this->paper = $pdfRender['paper'];
        $this->orientation = $pdfRender['orientation'];
        $this->html = $pdfRender['html'];
        $this->css = $pdfRender['css'];
        $this->reportname = $pdfRender['reportname'];
        $this->requesttype = $pdfRender['requesttype'];
        $this->requestsample = $pdfRender['requestsample'];
        $this->cssexternal = $pdfRender['cssexternal'];
        $this->requesturl = $pdfRender['requesturl'];

        $script = $pdfRender['phpscript'];
        $php_script = $this->php_head . $script . $this->php_tail;

        $htmlFactory = $this->head . $this->css . $this->middle . $php_script . $this->cssexternal . $this->html . $this->tail;

        $coreData = (array)json_decode($pdfRender['requestsample']);

        if ($this->requesttype == 'POST') {
            $data['status'] = 'success';
            if (!isset($_POST['jsondata'])) {
                $data['status'] = 'failed';
                $data['reason'] = 'post data [jsondata] is not defined.';
                header('Content-Type: application/json');
                die(json_encode($data));
            }
            $coreData = (array)json_decode($_POST['jsondata'], true);
        }

        if ($this->requesttype == 'URL') {
            $data['status'] = 'success';
            if ($this->requesturl == '') {
                $data['status'] = 'failed';
                $data['reason'] = 'request URL not defined.';
                header('Content-Type: application/json');
                die(json_encode($data));
            }
            $fetch = file_get_contents($this->requesturl);
            if (!$fetch) {
                $data['status'] = 'failed';
                $data['reason'] = 'url return zero data.';
                header('Content-Type: application/json');
                die(json_encode($data));
            }

            $coreData = (array)json_decode($fetch, true);
        }

        $response = new Response();
        $response->useMasterLayout = false;

        $render = new Pte(false);
        if ($response->useMasterLayout) {
            $render->SetMaster($response->htmlMaster);
        }
        $render->SetValue($coreData);
        $render->SetHtml($htmlFactory, true);
        $template = $render->Output($this, Pte::VIEW_HTML);

        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Author: Anywhere 0.1");
        header('Content-Type: application/pdf');

        $this->dompdf->setPaper($this->paper, $this->orientation);
        $this->dompdf->loadHtml($template);
        $this->dompdf->render();

        $user = UserModel::UserIdByApiKey($api_key);
        LogPdf::Create([
            'PDFID' => $pdfID,
            'userid' => ((int)$user > 0) ? $user : 0,
            'sentat' => $this->GetServerDateTime(),
            'jsondata' => json_encode($coreData, true),
            'creatorinfo' => isset($_POST['creator']) ? $_POST['creator'] : null,
            'processingtime' => $render->GetElapsedTime(),
        ]);

        if ($this->outputmode == 'Inline') {
            $this->dompdf->stream($this->reportname . '.pdf', array("Attachment" => 0));
        }
        if ($this->outputmode == 'Download') {
            $this->dompdf->stream($this->reportname . '.pdf', array("Attachment" => 1));
        }
        exit();
    }

    /**
     * @param $logID
     * @param $api_key
     * @param $pdfID
     * @throws \pte\exception\PteException
     */
    public function TimelineRender($logID, $api_key, $pdfID)
    {
        $pdfRender = PdfModel::GetPdfRender($api_key, $pdfID)[0];
        $logData = LogPdf::GetLogPdf($logID)[0];

        //because render executed outside vars need to be re-supplied
        $this->vars = ConstantaModel::GetCollection($pdfRender['userID']);

        $this->outputmode = $pdfRender['outputmode'];
        $this->paper = $pdfRender['paper'];
        $this->orientation = $pdfRender['orientation'];
        $this->html = $pdfRender['html'];
        $this->css = $pdfRender['css'];
        $this->reportname = $pdfRender['reportname'];
        $this->requesttype = $pdfRender['requesttype'];
        $this->requestsample = $pdfRender['requestsample'];
        $this->cssexternal = $pdfRender['cssexternal'];
        $this->requesturl = $pdfRender['requesturl'];

        $script = $pdfRender['phpscript'];
        $php_script = $this->php_head . $script . $this->php_tail;

        $htmlFactory = $this->head . $this->css . $this->middle . $php_script . $this->cssexternal . $this->html . $this->tail;

        $coreData = (array)json_decode($logData['jsondata'], true);

        $response = new Response();
        $response->useMasterLayout = false;

        $render = new Pte(false);
        if ($response->useMasterLayout) {
            $render->SetMaster($response->htmlMaster);
        }
        $render->SetValue($coreData);
        $render->SetHtml($htmlFactory, true);
        $template = $render->Output($this, Pte::VIEW_HTML);

        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Author: Anywhere 0.1");
        header('Content-Type: application/pdf');

        $this->dompdf->setPaper($this->paper, $this->orientation);
        $this->dompdf->loadHtml($template);
        $this->dompdf->render();

        if ($this->outputmode == 'Inline') {
            $this->dompdf->stream($this->reportname . '.pdf', array("Attachment" => 0));
        }
        if ($this->outputmode == 'Download') {
            $this->dompdf->stream($this->reportname . '.pdf', array("Attachment" => 1));
        }
        exit();
    }

    /**
     * @param $id_pdf
     * @return mixed
     * @throws \Exception
     * #Master master-codes.html
     * #Auth session true
     */
    public function timeline($id_pdf)
    {
        $session = Session::Get(AnywhereAuthenticator::Instance())->GetLoginData();
        $dataPDF = $session;

        $stats = LogPdf::GetPdfStats($id_pdf)[0];
        $dataPDF['generated'] = number_format($stats['generated']);
        $lastprinted = \DateTime::createFromFormat('Y-m-d H:i:s', $stats['lastprinted']);
        $dataPDF['lastprinted'] = $lastprinted->format('d F, Y (H:i:s)');

        $dataPDF['pdf'] = PdfModel::GetPdfPage($id_pdf);
        $dataPDF['PageTitle'] = $dataPDF['pdf'][0]['reportname'];

        if (isset($_POST['dates'])) {
            $dataPDF['dates'] = $_POST['dates'];

            $range = explode(' - ', $_POST['dates']);
            $start = \DateTime::createFromFormat('d/m/Y', $range[0]);
            $end = \DateTime::createFromFormat('d/m/Y', $range[1]);
            $timeline = LogPdf::GetPdfTimeline($id_pdf, $start->format('Y-m-d'), $end->format('Y-m-d'));

            foreach ($timeline as $key => $val) {
                $val['preview'] = Framework::$factory->getBase() . "pdf/timeline/{$val['logid']}/{$session['apikey']}/{$id_pdf}";
                $timeline[$key] = $val;
            }

            $dataPDF['timeline'] = $timeline;
        }

        return $dataPDF;
    }

    public function Limitations()
    {

    }

}