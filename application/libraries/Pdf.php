<?php defined('BASEPATH') OR exit('No direct script access allowed');

// reference the Dompdf namespace
use Dompdf\Dompdf;

class Pdf
{
    public function __construct(){
        
        // include autoloader
        require_once dirname(__FILE__).'/dompdf/autoload.inc.php';
        
        // instantiate and use the dompdf class
        $pdf = new DOMPDF();
        
        $CI =& get_instance();
        $CI->dompdf = $pdf;
        
    }
}
?>