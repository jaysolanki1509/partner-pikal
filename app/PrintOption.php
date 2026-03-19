<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class PrintOption extends Model {

    public $template;

    public function setText($text) {

        $this->template .= $text;

    }

    #TODO: new line function
    public function newLine() {

        $new_line_arr = array(10);
        $new_line = implode(array_map("chr", $new_line_arr));

        $this->template .="".$new_line;
    }

    #TODO: font size function
    public function fontSize($size) {

        if ( $size == 5 ) {
            $size_array = array(27, 33, 32);
        } else {
            $size_array = array(27, 33, 0);
        }

        $size = implode(array_map("chr", $size_array));

        $this->template .="".$size;
    }

    #TODO: display text center
    public function alignCenter() {

        $center_arr = array(27, 97, 49);
        $center = implode(array_map("chr", $center_arr));

        $this->template .="".$center;
    }

    #TODO: display text left
    public function alignLeft() {

        $left_arr = array(27, 97, 48);
        $left = implode(array_map("chr", $left_arr));

        $this->template.="".$left;
    }

    #TODO: display text right
    public function alignRight() {

        $right_arr = array(27, 97, 50);
        $right = implode(array_map("chr", $right_arr));

        $this->template.="".$right;
    }

    #TODO: get template
    public function getTemplate() {
        return $this->template;
    }

    public function  printTab( $a, $b)
    {
        $tab_arr = array(27, 68, $a, $b);
        $tab = implode(array_map("chr", $tab_arr));

        $this->template .= "" . $tab;

    }

    public function addLineSeperator() {

        $lineSpace = "------------------------------------------------";
        $this->template .= "" . $lineSpace;
    }

    public function addSmallLineSeperator() {

        $lineSpace = "------------------------";
        $this->template .= "" . $lineSpace;

    }

    public function addStarSeperator() {

        $lineSpace = "************************************************";
        $this->template .= "" . $lineSpace;

    }

    public function paperCut() {

        $cut_arr = array(29, 'V', 66, 0);
        $cut = implode(array_map("chr", $cut_arr));

        $this->template .= "".$cut;
    }

    public function justify() {

        $justify_arr = array(27, 97, 1);
        $just = implode(array_map("chr", $justify_arr));
        $this->template .= "".$just;

    }


}