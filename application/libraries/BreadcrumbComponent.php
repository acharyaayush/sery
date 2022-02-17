<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class BreadcrumbComponent
{
    private $breadcrumbs = array();
    private $separator = '  /  '; // Choose your wish separator
    private $start = '<div id="breadcrumb">';
    private $end = '</div>';
    
    public function __construct($params = array())
    {
        if (count($params) > 0) {
            $this->initialize($params);
        }
    }
    
    private function initialize($params = array())
    {
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                if (isset($this->{'_' . $key})) {
                    $this->{'_' . $key} = $val;
                }
            }
        }
    }
    
    function add($title, $href)
    {
        if (!$title OR !$href)
            return;
        
        $this->breadcrumbs[] = array(
            'title' => $title,
            'href' => $href
        );
    }

    function output()
    {
        if ($this->breadcrumbs) {
            $output = $this->start;
            foreach ($this->breadcrumbs as $key => $crumb) {
                if ($key) {
                    $output .= $this->separator;
                }
                $keys = array_keys($this->breadcrumbs);
                if (end($keys) == $key) {
                    $output .= '<span>' . $crumb['title'] . '</span>';
                } else {
                    $output .= '<a href="' . $crumb['href'] . '">' . $crumb['title'] . '</a>';
                }
            }
            return $output . $this->end . PHP_EOL;
        }
        return '';
    }
}
?>
