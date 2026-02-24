<?php
/**
 * Exam Slip Controller
 *
 * Handles two routes that the Step 4 view links to:
 *
 *   GET /apply/print-exam-slip
 *       Serves the full printable HTML view (exam-slip-print.php) in a popup.
 *       Validates CSRF, loads slip data, increments download counter.
 *
 *   GET /apply/download-exam-slip
 *       Sends the exam slip as a downloadable file.
 *       Tries PDF libraries in order: mPDF → Dompdf → TCPDF → wkhtmltopdf
 *       Falls back to a self-contained print-ready HTML file if none available.
 *       In every case, sends correct Content-Disposition: attachment headers
 *       so the browser opens a Save dialog instead of rendering in the tab.
 *
 * INSTALLATION:
 *   1. Place this file at:  app/controllers/ExamSlipController.php
 *   2. Register routes (add to your routes file):
 *        $router->get('/apply/print-exam-slip',    'ExamSlipController@printSlip');
 *        $router->get('/apply/download-exam-slip', 'ExamSlipController@downloadSlip');
 *
 * @package FCT_CNS
 */

require_once CORE_PATH . '/Controller.php';

class ExamSlipController extends Controller {

    /** @var ExamSlipModel */
    private $examSlipModel;

    /** @var object Application model */
    private $applicationModel;

    /** @var object Applicant model */
    private $applicantModel;

    /** @var object O'Level result model */
    private $olevelModel;

    // ─────────────────────────────────────────────────────────────────
    public function __construct() {
        parent::__construct();

        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        require_once MODELS_PATH . '/application/ApplicationModel.php';
        require_once MODELS_PATH . '/application/ApplicantModel.php';
        require_once MODELS_PATH . '/application/OlevelResultModel.php';

        $this->examSlipModel    = new ExamSlipModel();
        $this->applicationModel = new ApplicationModel();
        $this->applicantModel   = new ApplicantModel();
        $this->olevelModel      = new OlevelResultModel();
    }

    // ═════════════════════════════════════════════════════════════════
    // PUBLIC ACTIONS
    // ═════════════════════════════════════════════════════════════════

    /**
     * GET /apply/print-exam-slip
     *
     * Renders the printable exam slip view in a popup window.
     * The view file is your existing exam-slip-print.php — unchanged.
     */
    public function printSlip() {
        if (!$this->validateCsrf()) {
            $this->csrfError();
            return;
        }

        $data = $this->loadSlipData();
        if (!$data) {
            $this->notFoundError('Exam slip not found.');
            return;
        }

        // Increment view/print counter
        $this->examSlipModel->incrementDownloadCount($data['exam_slip']['id']);

        // Expose variables for the view
        extract($data);
        $baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';

        // Load your existing print view
        $viewFile = APP_PATH . '/views/pages/apply/exam-slip-print.php';

        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            // Inline fallback if the view file is missing
            $this->renderFallbackPrintView($data, $baseUrl);
        }
    }

    /**
     * GET /apply/download-exam-slip
     *
     * Sends the exam slip as a file download with correct headers.
     *
     * Strategy (tries each in order until one succeeds):
     *   1. mPDF    (composer: mpdf/mpdf)
     *   2. Dompdf  (composer: dompdf/dompdf)
     *   3. TCPDF   (composer: tecnickcom/tcpdf)
     *   4. wkhtmltopdf CLI (if installed on the server)
     *   5. Print-ready HTML sent as attachment (universal fallback)
     *      → User opens the .html file in a browser → Ctrl+P → Save as PDF
     */
    public function downloadSlip() {
        if (!$this->validateCsrf()) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Session expired. Please go back and try again.']);
            return;
        }

        $data = $this->loadSlipData();
        if (!$data) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Exam slip not found.']);
            return;
        }

        $slipNumber = $data['exam_slip']['slip_number'] ?? 'exam-slip';
        $baseUrl    = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $html       = $this->buildSlipHtml($data, $baseUrl);

        // Safe filename — strip characters that cause problems in file names
        $safeSlip    = preg_replace('/[^A-Za-z0-9\-]/', '-', $slipNumber);
        $pdfFilename = 'exam-slip-' . $safeSlip . '.pdf';

        // ── Attempt 1: mPDF ──────────────────────────────────────────
        if (class_exists('\Mpdf\Mpdf')) {
            try {
                $mpdf = new \Mpdf\Mpdf([
                    'mode'                => 'utf-8',
                    'format'              => 'A4',
                    'margin_top'          => 0,
                    'margin_bottom'       => 0,
                    'margin_left'         => 0,
                    'margin_right'        => 0,
                    'setAutoTopMargin'    => false,
                    'setAutoBottomMargin' => false,
                ]);
                $mpdf->SetTitle('Examination Slip — ' . $slipNumber);
                $mpdf->WriteHTML($html);

                $this->examSlipModel->incrementDownloadCount($data['exam_slip']['id']);

                $mpdf->Output($pdfFilename, \Mpdf\Output\Destination::DOWNLOAD);
                return;
            } catch (\Exception $e) {
                error_log('ExamSlipController mPDF error: ' . $e->getMessage());
            }
        }

        // ── Attempt 2: Dompdf ────────────────────────────────────────
        if (class_exists('\Dompdf\Dompdf')) {
            try {
                $options = new \Dompdf\Options();
                $options->set('isRemoteEnabled', true);
                $options->set('isPhpEnabled', false);

                $dompdf = new \Dompdf\Dompdf($options);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->loadHtml($html, 'UTF-8');
                $dompdf->render();

                $this->examSlipModel->incrementDownloadCount($data['exam_slip']['id']);

                $dompdf->stream($pdfFilename, ['Attachment' => true]);
                return;
            } catch (\Exception $e) {
                error_log('ExamSlipController Dompdf error: ' . $e->getMessage());
            }
        }

        // ── Attempt 3: TCPDF ─────────────────────────────────────────
        if (class_exists('TCPDF')) {
            try {
                $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
                $pdf->SetTitle('Examination Slip — ' . $slipNumber);
                $pdf->SetMargins(0, 0, 0);
                $pdf->SetAutoPageBreak(true, 0);
                $pdf->AddPage();
                $pdf->writeHTML($html, true, false, false, false, '');

                $this->examSlipModel->incrementDownloadCount($data['exam_slip']['id']);

                $pdf->Output($pdfFilename, 'D'); // D = force download
                return;
            } catch (\Exception $e) {
                error_log('ExamSlipController TCPDF error: ' . $e->getMessage());
            }
        }

        // ── Attempt 4: wkhtmltopdf CLI ───────────────────────────────
        $wkhtmltopdf = $this->findWkhtmltopdf();
        if ($wkhtmltopdf) {
            $tmpHtml = tempnam(sys_get_temp_dir(), 'slip_') . '.html';
            $tmpPdf  = tempnam(sys_get_temp_dir(), 'slip_') . '.pdf';

            try {
                file_put_contents($tmpHtml, $html);

                $cmd = escapeshellcmd($wkhtmltopdf)
                     . ' --page-size A4'
                     . ' --margin-top 0 --margin-bottom 0'
                     . ' --margin-left 0 --margin-right 0'
                     . ' --encoding utf-8'
                     . ' --quiet'
                     . ' ' . escapeshellarg($tmpHtml)
                     . ' ' . escapeshellarg($tmpPdf)
                     . ' 2>&1';

                exec($cmd, $cmdOutput, $exitCode);

                if ($exitCode === 0 && file_exists($tmpPdf) && filesize($tmpPdf) > 0) {
                    $this->examSlipModel->incrementDownloadCount($data['exam_slip']['id']);

                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
                    header('Content-Length: ' . filesize($tmpPdf));
                    header('Cache-Control: no-cache, no-store, must-revalidate');
                    header('Pragma: no-cache');
                    header('Expires: 0');

                    readfile($tmpPdf);

                    @unlink($tmpHtml);
                    @unlink($tmpPdf);
                    return;
                }

                error_log('ExamSlipController wkhtmltopdf failed (exit ' . $exitCode . '): ' . implode("\n", $cmdOutput));
            } catch (\Exception $e) {
                error_log('ExamSlipController wkhtmltopdf exception: ' . $e->getMessage());
            } finally {
                @unlink($tmpHtml);
                @unlink($tmpPdf);
            }
        }

        // ── Fallback: print-ready HTML as attachment ──────────────────
        // No PDF library is available on this server.
        // We send the HTML file with Content-Disposition: attachment.
        // The user opens the downloaded file in their browser,
        // then does Ctrl+P → "Save as PDF" to get a proper PDF.
        error_log('ExamSlipController: No PDF library found. Sending HTML attachment.');

        $htmlFilename = 'exam-slip-' . $safeSlip . '.html';

        $this->examSlipModel->incrementDownloadCount($data['exam_slip']['id']);

        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $htmlFilename . '"');
        header('Content-Length: ' . strlen($html));
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $html;
    }

    // ═════════════════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ═════════════════════════════════════════════════════════════════

    /**
     * Load and validate all data needed to render the exam slip.
     * Returns null if the session is missing, the applicant is not found,
     * the application is not found, or no exam slip exists.
     *
     * @return array|null  Keys: exam_slip, application, applicant, olevel_results
     */
    private function loadSlipData(): ?array {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $applicantId = (int)($_SESSION['applicant_id'] ?? 0);
        if (!$applicantId) {
            return null;
        }

        $applicant = $this->applicantModel->find($applicantId);
        if (!$applicant) {
            return null;
        }

        $application = $this->applicationModel->getByApplicantId($applicantId);
        if (!$application) {
            return null;
        }

        $examSlip = $this->examSlipModel->getByApplicationId($application['id']);
        if (!$examSlip) {
            return null;
        }

        $olevelResults = $this->olevelModel->getByApplicationId($application['id']) ?? [];

        return [
            'exam_slip'      => $examSlip,
            'application'    => $application,
            'applicant'      => $applicant,
            'olevel_results' => $olevelResults,
            'applicant_name' => trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '')),
        ];
    }

    /**
     * Validate the CSRF token passed in the query string.
     * Tokens are stored in $_SESSION['csrf_tokens'] as token => timestamp.
     *
     * @return bool
     */
    private function validateCsrf(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_GET['csrf'] ?? $_POST['csrf_token'] ?? '';
        if (empty($token)) {
            return false;
        }

        // Token must exist in the session
        if (empty($_SESSION['csrf_tokens'][$token])) {
            return false;
        }

        // Token must not be older than 1 hour
        if (time() - (int)$_SESSION['csrf_tokens'][$token] > 3600) {
            unset($_SESSION['csrf_tokens'][$token]);
            return false;
        }

        return true;
    }

    /**
     * Send a 403 CSRF error response.
     */
    private function csrfError(): void {
        http_response_code(403);
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8">
              <title>Session Expired</title>
              <style>body{font-family:sans-serif;padding:2rem;color:#333;}
              a{color:#6B4E9B;}</style></head><body>
              <h2>Session Expired</h2>
              <p>Your session has expired or the request was invalid.</p>
              <p><a href="/apply/step/4">&larr; Go back and try again</a></p>
              </body></html>';
    }

    /**
     * Send a 404 not-found response.
     */
    private function notFoundError(string $message = 'Not found.'): void {
        http_response_code(404);
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8">
              <title>Not Found</title>
              <style>body{font-family:sans-serif;padding:2rem;color:#333;}
              a{color:#6B4E9B;}</style></head><body>
              <h2>Not Found</h2>
              <p>' . htmlspecialchars($message) . '</p>
              <p><a href="/apply/step/4">&larr; Go back</a></p>
              </body></html>';
    }

    /**
     * Locate the wkhtmltopdf binary on the server.
     * Returns the full path, or null if not found.
     *
     * @return string|null
     */
    private function findWkhtmltopdf(): ?string {
        $candidates = [
            '/usr/bin/wkhtmltopdf',
            '/usr/local/bin/wkhtmltopdf',
            '/opt/homebrew/bin/wkhtmltopdf',
            '/snap/bin/wkhtmltopdf',
        ];

        foreach ($candidates as $path) {
            if (is_executable($path)) {
                return $path;
            }
        }

        // Try PATH via shell
        $which = @shell_exec('which wkhtmltopdf 2>/dev/null');
        if ($which) {
            $path = trim($which);
            if ($path && is_executable($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Build a complete, self-contained HTML document representing the exam slip.
     * This is used both for PDF generation and for the HTML attachment fallback.
     * It mirrors the layout of your existing exam-slip-print.php view but is
     * inline so it works without any external file dependencies.
     *
     * @param  array   $data     Keys: exam_slip, application, applicant, olevel_results
     * @param  string  $baseUrl  e.g. https://fctcns.edu.ng
     * @return string  Complete HTML document
     */
    private function buildSlipHtml(array $data, string $baseUrl): string {
        $slip          = $data['exam_slip'];
        $application   = $data['application'];
        $applicant     = $data['applicant'];
        $olevelResults = $data['olevel_results'];

        $slipNumber = $slip['slip_number'] ?? '';
        $logoUrl    = $baseUrl . '/assets/images/logo/logo.png';

        // Full name
        $fullName = trim(
            ($applicant['title']      ?? '') . ' ' .
            ($applicant['first_name'] ?? '') . ' ' .
            ($applicant['last_name']  ?? '')
        );
        if (!$fullName) {
            $fullName = trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''));
        }

        // Dates
        $rawDate  = $slip['exam_date'] ?? '';
        $examDate = ($rawDate && $rawDate !== 'To be announced')
                    ? date('l, jS F Y', strtotime($rawDate))
                    : 'To be announced';

        $generatedAt = !empty($slip['generated_at'])
                       ? date('d F Y, h:i A', strtotime($slip['generated_at']))
                       : date('d F Y, h:i A');

        $examTime      = $slip['exam_time']      ?? '10:00 AM';
        $reportingTime = $slip['reporting_time'] ?? '8:00 AM';
        $venue         = $slip['exam_venue']     ?? 'FCT College of Nursing Sciences, Gwagwalada (within UATH)';
        $seatNumber    = $slip['seat_number']    ?? 'TBA';
        $appNumber     = $application['application_number'] ?? '';
        $jambNumber    = $application['jamb_number']        ?? '';
        $programme     = $application['program_choice_1']   ?? '';

        // Grade colour helper
        $creditGrades = ['A1', 'B2', 'B3', 'C4', 'C5', 'C6'];
        $gradeStyle   = function (string $g) use ($creditGrades): string {
            if (in_array($g, $creditGrades))        return 'color:#2e7d32;font-weight:700';
            if (in_array($g, ['D7', 'E8']))         return 'color:#f57c00;font-weight:400';
            return 'color:#c62828;font-weight:400';
        };

        // O'Level table rows
        $subjects = [
            'english'     => 'English Language',
            'mathematics' => 'Mathematics',
            'biology'     => 'Biology',
            'chemistry'   => 'Chemistry',
            'physics'     => 'Physics',
        ];

        $olevelHtml = '';
        if (!empty($olevelResults)) {
            foreach ($olevelResults as $si => $sitting) {
                $type = htmlspecialchars($sitting['exam_type'] ?? 'WAEC');
                $year = htmlspecialchars($sitting['exam_year'] ?? '');
                $olevelHtml .= '<p style="font-size:8pt;font-weight:700;margin:6px 0 2px;">
                    Sitting ' . ($si + 1) . ': ' . $type . ' (' . $year . ')</p>
                    <table style="width:100%;border-collapse:collapse;font-size:8pt;margin-bottom:6px;">
                    <tr style="background:#f9edf9;">
                        <th style="border:1px solid #ccc;padding:5px 7px;text-align:left;">Subject</th>
                        <th style="border:1px solid #ccc;padding:5px 7px;text-align:center;width:80px;">Grade</th>
                    </tr>';

                foreach ($subjects as $key => $label) {
                    $g     = $sitting[$key . '_grade'] ?? '';
                    $style = $g ? $gradeStyle($g) : 'color:#bbb';
                    $badge = ($g && in_array($g, $creditGrades)) ? ' ✓' : '';

                    $olevelHtml .= '<tr>
                        <td style="border:1px solid #ccc;padding:5px 7px;">' . htmlspecialchars($label) . '</td>
                        <td style="border:1px solid #ccc;padding:5px 7px;text-align:center;">
                            <span style="' . $style . '">' . htmlspecialchars($g ?: '—') . $badge . '</span>
                        </td>
                    </tr>';
                }

                $olevelHtml .= '</table>';
            }
        } else {
            $olevelHtml = '<p style="font-size:8pt;color:#999;padding:8px 0;">No O\'Level results recorded.</p>';
        }

        // QR URL — no CSRF in QR (public verification endpoint)
        $qrUrl = $baseUrl . '/application-verify/generate-qr/' . urlencode($slipNumber);

        // Passport photo
        $passportSrc  = $application['passport_photo'] ?? '';
        $passportHtml = $passportSrc
            ? '<img src="' . htmlspecialchars($passportSrc) . '" alt="Passport Photo"
                    style="width:100%;height:100%;object-fit:cover;display:block;">'
            : '<span style="font-size:7pt;color:#999;text-align:center;padding:8px;display:block;">No Photo</span>';

        ob_start();
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Examination Slip — <?php echo htmlspecialchars($slipNumber); ?></title>
<style>
  @page { size: A4 portrait; margin: 0; }
  *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Times New Roman', Times, serif;
    background: #fff;
    color: #111;
    width: 210mm;
    padding: 12mm 14mm 10mm;
  }

  /* Double border */
  .border-outer { border: 3px solid #4A3B6B; padding: 3px; }
  .border-inner { border: 1.5px solid #8A6FB0; padding: 10px 13px 8px; }

  /* Header */
  .inst-header {
    display: flex; align-items: center; gap: 10px;
    padding-bottom: 10px;
    border-bottom: 3px double #4A3B6B;
    margin-bottom: 12px;
  }
  .logo-img { width: 64px; height: 64px; object-fit: contain; flex-shrink: 0; }
  .inst-text { flex: 1; text-align: center; }
  .inst-name {
    font-size: 14pt; font-weight: 900; text-transform: uppercase;
    color: #4A3B6B; letter-spacing: .04em; line-height: 1.2;
  }
  .inst-addr { font-size: 8pt; color: #555; margin: 4px 0; }
  .slip-badge {
    display: inline-block; background: #4A3B6B; color: #fff;
    font-size: 7pt; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; padding: 2px 10px;
  }

  /* Slip number bar */
  .slip-bar {
    background: #f9edf9; border: 1px solid #dbb8db;
    border-left: 4px solid #6B4E9B;
    padding: 6px 10px; display: flex;
    justify-content: space-between; align-items: center;
    margin-bottom: 12px; font-size: 8pt;
  }
  .slip-num { font-weight: 900; color: #4A3B6B; font-size: 10pt; letter-spacing: .06em; }

  /* Media row */
  .media-row { display: flex; gap: 10px; margin-bottom: 12px; }

  .photo-box {
    width: 96px; height: 116px; border: 2px solid #4A3B6B;
    display: flex; align-items: center; justify-content: center;
    background: #f5f5f5; flex-shrink: 0; overflow: hidden;
  }
  .qr-box {
    width: 96px; height: 96px; border: 2px solid #4A3B6B;
    padding: 4px; display: flex; align-items: center;
    justify-content: center; flex-shrink: 0;
  }
  .qr-box img { width: 84px; height: 84px; display: block; }
  .media-caption {
    font-size: 6pt; color: #888; text-align: center;
    margin-top: 3px; text-transform: uppercase; letter-spacing: .05em;
  }

  /* Info table */
  .info-table { flex: 1; border-collapse: collapse; font-size: 8.5pt; }
  .info-table td { border: 1px solid #ccc; padding: 8px 6px; vertical-align: middle; }
  .info-label {
    background: #f9edf9; font-size: 7pt; font-weight: 700;
    text-transform: uppercase; color: #4A3B6B; width: 36%;
  }

  /* Section title bar */
  .section-title {
    background: #4A3B6B; color: #fff; font-size: 7.5pt; font-weight: 700;
    text-transform: uppercase; letter-spacing: .1em; padding: 5px 10px;
    margin-bottom: 0;
  }

  /* Exam details */
  .exam-cols { display: flex; gap: 8px; margin-bottom: 12px; }
  .exam-table { flex: 1; border-collapse: collapse; font-size: 8.5pt; }
  .exam-table td { border: 1px solid #ccc; padding: 8px 7px; vertical-align: middle; }
  .et-label {
    background: #f9edf9; font-weight: 700; color: #4A3B6B;
    font-size: 7pt; text-transform: uppercase; width: 40%;
  }
  .et-highlight { font-weight: 900; color: #4A3B6B; font-size: 9.5pt; }
  .et-danger    { color: #b00020; font-weight: 700; }
  .seat-num     { font-size: 15pt; font-weight: 900; color: #4A3B6B; letter-spacing: .08em; }

  /* Instructions */
  .instructions { border: 1.5px solid #4A3B6B; margin-top: 12px; }
  .instr-header {
    background: #4A3B6B; color: #fff; font-size: 7.5pt; font-weight: 700;
    text-transform: uppercase; letter-spacing: .1em; padding: 5px 10px;
  }
  .instructions ol { padding: 10px 8px 10px 24px; }
  .instructions ol li { font-size: 8pt; padding: 4px 0; line-height: 1.6; }

  /* Signatures */
  .sig-row { display: flex; justify-content: space-between; margin-top: 20px; align-items: flex-end; }
  .sig-block { text-align: center; width: 140px; }
  .sig-line { border-bottom: 1px solid #000; height: 36px; margin-bottom: 4px; }
  .sig-name  { font-size: 7pt; font-weight: 700; text-transform: uppercase; color: #555; }
  .sig-title { font-size: 6.5pt; color: #888; margin-top: 2px; }
  .stamp-circle {
    width: 64px; height: 64px; border-radius: 50%;
    border: 2px dashed #c0a0c0;
    display: flex; align-items: center; justify-content: center;
    flex-direction: column; color: #c0a0c0;
    font-size: 5pt; text-transform: uppercase;
    letter-spacing: .05em; text-align: center; margin: 0 auto;
  }

  /* Footer */
  .footer {
    margin-top: 16px; padding-top: 6px; border-top: 2px solid #4A3B6B;
    display: flex; justify-content: space-between;
    font-size: 6.5pt; color: #888; line-height: 1.9;
  }
  .footer-right { text-align: right; }
  .verify-url  { font-size: 7pt; color: #4A3B6B; word-break: break-all; }

  /* Gold strip */
  .gold-strip {
    height: 4px; margin-top: 10px;
    background: linear-gradient(90deg, #6B4E9B 0%, #8A6FB0 50%, #6B4E9B 100%);
  }

  /* Print */
  @media print {
    @page { size: A4 portrait; margin: 0; }
    body  { width: 210mm; min-height: 297mm; }
  }
</style>
</head>
<body>
<div class="border-outer">
<div class="border-inner">

  <!-- Institution header -->
  <div class="inst-header">
    <img src="<?php echo htmlspecialchars($logoUrl); ?>"
         alt="FCT CNS Logo" class="logo-img"
         onerror="this.style.display='none'">
    <div class="inst-text">
      <div class="inst-name">FCT College of Nursing Sciences</div>
      <div class="inst-addr">Gwagwalada, Abuja — Federal Capital Territory</div>
      <div class="slip-badge">Official Examination Slip &mdash; 2025/2026 Admissions Screening Exercise</div>
    </div>
    <div style="width:64px;flex-shrink:0;"></div>
  </div>

  <!-- Slip number bar -->
  <div class="slip-bar">
    <span>SLIP NO: <span class="slip-num"><?php echo htmlspecialchars($slipNumber); ?></span></span>
    <span>Generated: <?php echo htmlspecialchars($generatedAt); ?></span>
  </div>

  <!-- Media row: photo | QR | candidate info -->
  <div class="media-row">

    <div>
      <div class="photo-box"><?php echo $passportHtml; ?></div>
      <div class="media-caption">Passport Photo</div>
    </div>

    <div>
      <div class="qr-box">
        <img src="<?php echo htmlspecialchars($qrUrl); ?>" alt="QR Code"
             onerror="this.parentNode.innerHTML='<span style=\'font-size:7pt;color:#999;text-align:center;padding:8px;\'>QR N/A</span>'">
      </div>
      <div class="media-caption">Scan to Verify</div>
    </div>

    <table class="info-table">
      <tr>
        <td class="info-label">Full Name</td>
        <td style="font-weight:700;color:#4A3B6B;font-size:9.5pt;"><?php echo htmlspecialchars($fullName); ?></td>
      </tr>
      <tr>
        <td class="info-label">Application No.</td>
        <td><?php echo htmlspecialchars($appNumber); ?></td>
      </tr>
      <tr>
        <td class="info-label">JAMB Reg. No.</td>
        <td><?php echo htmlspecialchars($jambNumber); ?></td>
      </tr>
      <tr>
        <td class="info-label">Programme</td>
        <td style="color:#6B4E9B;font-style:italic;"><?php echo htmlspecialchars($programme); ?></td>
      </tr>
    </table>

  </div><!-- /media-row -->

  <!-- O'Level results -->
  <div class="section-title">O&rsquo;Level Examination Results</div>
  <?php echo $olevelHtml; ?>

  <!-- Exam details -->
  <div class="section-title">Examination Details</div>
  <div class="exam-cols">
    <table class="exam-table">
      <tr>
        <td class="et-label">Exam Date</td>
        <td class="et-highlight"><?php echo htmlspecialchars($examDate); ?></td>
      </tr>
      <tr>
        <td class="et-label">Exam Time</td>
        <td><?php echo htmlspecialchars($examTime); ?></td>
      </tr>
      <tr>
        <td class="et-label">Reporting Time</td>
        <td class="et-danger">&#9888; <?php echo htmlspecialchars($reportingTime); ?>
          <span style="font-size:7pt;font-weight:400;"> (Arrive 30 mins early)</span>
        </td>
      </tr>
    </table>
    <table class="exam-table">
      <tr>
        <td class="et-label">Venue</td>
        <td><?php echo htmlspecialchars($venue); ?></td>
      </tr>
      <tr>
        <td class="et-label">Seat Number</td>
        <td><span class="seat-num"><?php echo htmlspecialchars($seatNumber); ?></span></td>
      </tr>
    </table>
  </div>

  <!-- Instructions -->
  <div class="instructions">
    <div class="instr-header">Important Instructions &mdash; Please Read Carefully</div>
    <ol>
      <li>Bring this printed slip to the examination venue — it is required for entry.</li>
      <li>Arrive at least <strong>30 minutes</strong> before the scheduled reporting time. Latecomers will not be admitted.</li>
      <li>Come with writing materials: pen, pencil, and eraser.</li>
      <li>Present a valid photo ID — National ID Card, Driver&rsquo;s Licence, or International Passport.</li>
      <li>Electronic devices including mobile phones, calculators, and smartwatches are <strong>strictly prohibited</strong> inside the hall.</li>
      <li>The QR code on this slip will be scanned at the entrance for identity verification.</li>
      <li>Candidates must sit only at the seat number assigned on this slip.</li>
      <li>Original O&rsquo;Level certificates/result slips must be presented at the screening centre.</li>
    </ol>
  </div>

  <!-- Signatures -->
  <div class="sig-row">
    <div class="sig-block">
      <div class="sig-line"></div>
      <div class="sig-name">Hall Name</div>
      <div class="sig-title">Examination Hall</div>
    </div>
    <div class="sig-block">
      <div class="stamp-circle">Official<br>Stamp</div>
    </div>
    <div class="sig-block">
      <div class="sig-line"></div>
      <div class="sig-name">Verification Officer</div>
      <div class="sig-title">Signature</div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <div>
      This slip is computer-generated and does not require a handwritten signature.<br>
      Any alteration or falsification of this document is a criminal offence.<br>
      Enquiries: admissions@fctcns.edu.ng &nbsp;|&nbsp; 07039837749
    </div>
    <div class="footer-right">
      Verification URL:<br>
      <span class="verify-url"><?php echo htmlspecialchars($baseUrl . '/application-verify/slip/' . $slipNumber); ?></span>
    </div>
  </div>

  <div class="gold-strip"></div>

</div><!-- /border-inner -->
</div><!-- /border-outer -->
</body>
</html>
        <?php
        return ob_get_clean();
    }

    /**
     * Render a minimal fallback print view inline (used if exam-slip-print.php
     * does not exist on disk). Injects a toolbar above the slip document.
     *
     * @param array  $data
     * @param string $baseUrl
     */
    private function renderFallbackPrintView(array $data, string $baseUrl): void {
        $slipNumber = $data['exam_slip']['slip_number'] ?? '';
        $html       = $this->buildSlipHtml($data, $baseUrl);

        $toolbar = '<div style="position:sticky;top:0;background:#4A3B6B;color:#fff;
                    padding:10px 20px;display:flex;justify-content:space-between;
                    align-items:center;font-family:Arial,sans-serif;font-size:13px;
                    z-index:999;box-shadow:0 2px 8px rgba(0,0,0,0.4);">
            <strong>&#128220; Examination Slip &mdash; ' . htmlspecialchars($slipNumber) . '</strong>
            <div style="display:flex;gap:10px;">
                <button onclick="window.print()"
                    style="background:#fff;color:#4A3B6B;border:none;
                           padding:8px 18px;border-radius:5px;font-weight:700;cursor:pointer;">
                    &#128438; Print / Save PDF
                </button>
                <button onclick="window.opener ? window.close() : history.back()"
                    style="background:rgba(255,255,255,.18);color:#fff;border:none;
                           padding:8px 18px;border-radius:5px;font-weight:700;cursor:pointer;">
                    &#10005; Close
                </button>
            </div>
        </div>';

        // Inject toolbar right after <body>
        echo str_replace('<body>', '<body>' . $toolbar, $html);
    }
}