<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private $dompdf;
    public function __construct()
    {
        // instantiate and use the dompdf class
        $this->dompdf = new Dompdf(array('enable_remote' => true));
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $this->dompdf->setOptions($options);
    }
    public function showpdf($html)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        ob_get_clean();
        $this->dompdf->stream("details.pdf", [
            'Attachment' => false
        ]);
    }
   
}
