<?php
if (!isset($_GET['type'])) {
  die("Invalid report type");
}

$type = $_GET['type'];
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename={$type}_report.pdf");

// Dummy PDF content
echo "%PDF-1.4
%âãÏÓ
1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj
2 0 obj
<< /Type /Pages /Kids [3 0 R] /Count 1 >>
endobj
3 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792]
/Contents 4 0 R >>
endobj
4 0 obj
<< /Length 44 >>
stream
BT
/F1 24 Tf
100 700 Td
(Report: {$type}) Tj
ET
endstream
endobj
xref
0 5
0000000000 65535 f 
0000000010 00000 n 
0000000063 00000 n 
0000000114 00000 n 
0000000213 00000 n 
trailer
<< /Root 1 0 R /Size 5 >>
startxref
310
%%EOF";
