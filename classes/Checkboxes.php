<?php
namespace a11y_for_fwp;

use FacetWP_Facet_Checkboxes;

class Checkboxes extends FacetWP_Facet_Checkboxes {


    /**
     * Generate the facet HTML
     */
    function render( $params ) {

        $facet = $params['facet'];
       
        if ( FWP()->helper->facet_is( $facet, 'hierarchical', 'yes' ) ) {
            return $this->render_hierarchy( $params );
        }

        $output = '';
        $values = (array) $params['values'];
        $selected_values = (array) $params['selected_values'];
        $soft_limit = empty( $facet['soft_limit'] ) ? 0 : (int) $facet['soft_limit'];

        $key = 0;
        foreach ( $values as $key => $result ) {
            if ( 0 < $soft_limit && $key == $soft_limit ) {
                $output .= '<div class="facetwp-overflow facetwp-hidden">';
            }
            $selected = in_array( $result['facet_value'], $selected_values ) ? ' checked' : '';
            $selected .= ( 0 == $result['counter'] && '' == $selected ) ? ' disabled' : '';
            $output .= '<div class="facetwp-checkbox' . $selected . '" data-value="' . esc_attr( $result['facet_value'] ) . '">';
            $output .= esc_html( $result['facet_display_value'] ) . ' <span class="facetwp-counter">(' . $result['counter'] . '<span class="screen-reader-text"> matching results</span>)</span>';
            $output .= '</div>';
        }

        if ( 0 < $soft_limit && $soft_limit <= $key ) {
            $output .= '</div>';
            $output .= '<a class="facetwp-toggle">' . __( 'See {num} more', 'fwp-front' ) . '</a>';
            $output .= '<a class="facetwp-toggle facetwp-hidden">' . __( 'See less', 'fwp-front' ) . '</a>';
        }

        return $output;
    }


    /**
     * Generate the facet HTML (hierarchical taxonomies)
     */
    function render_hierarchy( $params ) {

        $output = '';
        $facet = $params['facet'];
        $selected_values = (array) $params['selected_values'];
        $values = FWP()->helper->sort_taxonomy_values( $params['values'], $facet['orderby'] );

        $init_depth = -1;
        $last_depth = -1;

        foreach ( $values as $result ) {
            $depth = (int) $result['depth'];

            if ( -1 == $last_depth ) {
                $init_depth = $depth;
            }
            elseif ( $depth > $last_depth ) {
                $output .= '<div class="facetwp-depth">';
            }
            elseif ( $depth < $last_depth ) {
                for ( $i = $last_depth; $i > $depth; $i-- ) {
                    $output .= '</div>';
                }
            }

            $selected = in_array( $result['facet_value'], $selected_values ) ? ' checked' : '';
            $selected .= ( 0 == $result['counter'] && '' == $selected ) ? ' disabled' : '';
            $output .= '<div class="facetwp-checkbox' . $selected . '" data-value="' . esc_attr( $result['facet_value'] ) . '">';
            $output .= esc_html( $result['facet_display_value'] ) . ' <span class="facetwp-counter">(' . $result['counter'] . '<span class="screen-reader-text"> matching results</span>)</span>';
            $output .= '</div>';

            $last_depth = $depth;
        }

        for ( $i = $last_depth; $i > $init_depth; $i-- ) {
            $output .= '</div>';
        }

        return $output;
    }

    /**
     * Output admin settings HTML
     */
    function settings_html() {
       
        $this->render_setting( 'parent_term' );
        $this->render_setting( 'modifiers' );
        $this->render_setting( 'hierarchical' );
        $this->render_setting( 'show_expanded' );
        
        ?>
        <div class="facetwp-row">
            <div><?php _e( 'Ghosts', 'fwp' ); ?>:</div>
            <p><?php _e( 'The Settings for Ghost have been removed by Accessibility Fixes for FacetWP' ); ?></p>
        </div>
        <?php
        $this->render_setting( 'operator' );
       
        ?>
        <div class="facetwp-row">
            <div><?php _e( 'Sort by', 'fwp' ); ?>:</div>
            <div>
                <select class="facet-orderby">
                    <option value="display_value"><?php _e( 'Display Value', 'fwp' ); ?></option>
                    <option value="raw_value"><?php _e( 'Raw Value', 'fwp' ); ?></option>
                    <option value="term_order"><?php _e( 'Term Order', 'fwp' ); ?></option>
                </select>
            </div>
        </div>
        <?php
        $this->render_setting( 'count' );
        $this->render_setting( 'soft_limit' );
    }
}
