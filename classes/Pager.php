<?php
namespace a11y_for_fwp;

use FacetWP_Facet_Pager;

class Pager extends FacetWP_Facet_Pager {

    public $pager_args;

    public $facet;

    /**
     * Generate the facet HTML
     */
    function render( $params ) {
        $facet = $params['facet'];
        $pager_type = $facet['pager_type'];
        $this->pager_args = FWP()->facet->pager_args;
        $this->facet = $facet;

        $method = 'render_' . $pager_type;
        if ( method_exists( $this, $method ) ) {
            $output = $this->$method( $facet );

            if ( 'numbers' == $pager_type ) {
                $output = '<nav class="facetwp-pager">' . $output . '</nav>';
            }
            
            return $output;
        }
    }

    function render_numbers( $facet ) {
        $inner_size = (int) $facet['inner_size'];
        $dots_label = facetwp_i18n( $facet['dots_label'] );
        $prev_label = facetwp_i18n( $facet['prev_label'] );
        $next_label = facetwp_i18n( $facet['next_label'] );

        $output = '';
        $page = $this->pager_args['page'];
        $total_pages = $this->pager_args['total_pages'];
        $inner_first = max( $page - $inner_size, 2 );
        $inner_last = min( $page + $inner_size, $total_pages - 1 );

        if ( 1 < $total_pages ) {

            // Prev button
            if ( 1 < $page && '' != $prev_label ) {
                $output .= $this->render_page( $page - 1, $prev_label, 'prev' );
            }

            // First page
            $output .= $this->render_page( 1, false, 'first' );

            // Dots
            if ( 2 < $inner_first && '' != $dots_label ) {
                $output .= $this->render_page( '', $dots_label, 'dots' );
            }

            for ( $i = $inner_first; $i <= $inner_last; $i++ ) {
                $output .= $this->render_page( $i );
            }

            // Dots
            if ( $inner_last < $total_pages - 1 && '' != $dots_label ) {
                $output .= $this->render_page( '', $dots_label, 'dots' );
            }

            // Last page
            $output .= $this->render_page( $total_pages, false, 'last' );

            // Next button
            if ( $page < $total_pages && '' != $next_label ) {
                $output .= $this->render_page( $page + 1, $next_label, 'next' );
            }
        }

        return $output;
    }

    function render_page( $page, $label = false, $extra_class = false ) {
        $label = ( false === $label ) ? $page : $label;
        $class = 'facetwp-page';
        $tag = ( 'dots' == $extra_class ) ? 'span' : 'a';
        
        if ( ! empty( $extra_class ) ) {
            $class .= ' ' . $extra_class;
        }

        $data = empty( $page ) ? '' : ' data-page="' . $page . '"';
        if ( 'a' == $tag ) {
            $data .= ' href="javascript:"';
        }

        $aria_label = $this->facet['number_aria_label'];
        $aria_label = str_replace( '[page]', $page, $aria_label );
        $aria_label = str_replace( '[pages]', $this->pager_args['total_pages'], $aria_label );
        if( ! in_array($extra_class, ['dots', 'next', 'prev']) ) {
            $data .= ' aria-label="' . $aria_label . '"';
        }
        
        if ( $page == $this->pager_args['page'] ) {
            $class .= ' active';
            $data .= ' aria-current="true"';
        } 
                  
        return '<' . $tag . ' class="' . $class . '"' . $data . '>' . $label . '</' . $tag . '>';
    }


    /**
     * Output admin settings HTML
     */
    function settings_html() {
?>
        <div class="facetwp-row">
            <div><?php _e('Pager type', 'fwp'); ?>:</div>
            <div>
                <select class="facet-pager-type">
                    <option value="numbers"><?php _e( 'Page numbers', 'fwp' ); ?></option>
                    <option value="counts"><?php _e( 'Result counts', 'fwp' ); ?></option>
                    <option value="load_more"><?php _e( 'Load more', 'fwp' ); ?></option>
                    <option value="per_page"><?php _e( 'Per page', 'fwp' ); ?></option>
                </select>
            </div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'numbers'">
            <div>
                <div class="facetwp-tooltip">
                    <?php _e('Inner size', 'fwp'); ?>:
                    <div class="facetwp-tooltip-content"><?php _e( 'Number of pages to show on each side of the current page', 'fwp' ); ?></div>
                </div>
            </div>
            <div><input type="text" class="facet-inner-size" value="2" /></div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'numbers'">
            <div>
                <div class="facetwp-tooltip">
                    <?php _e('Dots label', 'fwp'); ?>:
                    <div class="facetwp-tooltip-content"><?php _e( 'The filler between the inner and outer pages', 'fwp' ); ?></div>
                </div>
            </div>
            <div><input type="text" class="facet-dots-label" value="…" /></div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'numbers'">
            <div>
                <div class="facetwp-tooltip">
                    <?php _e('Prev button label', 'fwp'); ?>:
                    <div class="facetwp-tooltip-content"><?php _e( 'Leave blank to hide', 'fwp' ); ?></div>
                </div>
            </div>
            <div><input type="text" class="facet-prev-label" value="« Prev" /></div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'numbers'">
            <div>
                <div class="facetwp-tooltip">
                    <?php _e('Next button label', 'fwp'); ?>:
                    <div class="facetwp-tooltip-content"><?php _e( 'Leave blank to hide', 'fwp' ); ?></div>
                </div>
            </div>
            <div><input type="text" class="facet-next-label" value="Next »" /></div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'numbers'">
            <div>
                <div class="facetwp-tooltip">
                    <?php _e('Number aria-label', 'a11y-for-fwp'); ?>:
                    <div class="facetwp-tooltip-content"><?php _e( 'Available tags: [page], and [pages]', 'a11y-for-fwp' ); ?></div>
                </div>
            </div>
            <div><input type="text" class="facet-number-aria-label" value="Go to [page] of [pages] of search results" /></div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'counts'">
            <div>
                <div class="facetwp-tooltip">
                    <?php _e('Count text (plural)', 'fwp'); ?>:
                    <div class="facetwp-tooltip-content"><?php _e( 'Available tags: [lower], [upper], and [total]', 'fwp' ); ?></div>
                </div>
            </div>
            <div><input type="text" class="facet-count-text-plural" value="[lower] - [upper] of [total] results" /></div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'counts'">
            <div><?php _e('Count text (singular)', 'fwp'); ?>:</div>
            <div><input type="text" class="facet-count-text-singular" value="1 result" /></div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'counts'">
            <div><?php _e('Count text (no results)', 'fwp'); ?>:</div>
            <div><input type="text" class="facet-count-text-none" value="No results" /></div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'load_more'">
            <div><?php _e('Load more text', 'fwp'); ?>:</div>
            <div><input type="text" class="facet-load-more-text" value="Load more" /></div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'load_more'">
            <div><?php _e('Loading text', 'fwp'); ?>:</div>
            <div><input type="text" class="facet-loading-text" value="Loading..." /></div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'per_page'">
            <div><?php _e('Default label', 'fwp'); ?>:</div>
            <div><input type="text" class="facet-default-label" value="Per page" /></div>
        </div>
        <div class="facetwp-row" v-show="facet.pager_type == 'per_page'">
            <div>
                <div class="facetwp-tooltip">
                    <?php _e('Per page options', 'fwp'); ?>:
                    <div class="facetwp-tooltip-content"><?php _e( 'A comma-separated list of choices', 'fwp' ); ?></div>
                </div>
            </div>
            <div><input type="text" class="facet-per-page-options" value="10, 25, 50, 100" /></div>
        </div>
<?php
    }
}
