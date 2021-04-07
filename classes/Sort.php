<?php
namespace a11y_for_fwp;

class Sort {

    public function __construct() {
        add_filter( 'facetwp_sort_html', [ $this, 'add_aria_label' ], 10, 2 );
    }

    public function add_aria_label( $html, $params ) {
        $aria_label = apply_filters( 'a11y_fixes_for_fwp_sort_by_label', 'sort by' );
        $html = '<select class="facetwp-sort-select" aria-label="'. $aria_label .'">';
            foreach ( $params['sort_options'] as $key => $atts ) {
                $html .= '<option value="' . $key . '">' . $atts['label'] . '</option>';
            }
        $html .= '</select>';
        return $html;
    }
}