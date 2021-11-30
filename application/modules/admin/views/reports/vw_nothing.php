
<?php
    $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->AddPage('P','Letter');
        ob_end_clean();
        $pdf->Output('GoodBuy_Report.pdf', 'I');




