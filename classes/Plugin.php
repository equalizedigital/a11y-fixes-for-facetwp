<?php
namespace a11y_for_fwp;

use a11y_for_fwp\Pager;
use a11y_for_fwp\Sort;

class Plugin {

    public function __construct() {
        $this->facets();
        $this->sort();
        $this->register();
    }

    public function register() {
        add_filter( 'facetwp_assets', [$this, 'front_scripts' ] );
        add_filter( 'facetwp_load_a11y', [ $this, 'disable_fwp_a11y' ], 9999 );
        add_filter( 'facetwp_facets', [ $this, 'force_ghosts' ] );
    }

    public function force_ghosts( $facets ) {
        array_walk( $facets, function(&$key){
            if ( 'checkboxes' == $key['type'] ) {
                $key['ghosts'] = 'yes';
                $key['preserve_ghosts'] = 'yes';
            }
        });
        return $facets;
    }

    protected function facets() {
        add_filter( 'facetwp_facet_types', [ $this, 'replace_facets' ] );
    }

    public function front_scripts( $assets ) {
        $assets['a11y-fixes.js'] = A11Y_FOR_FWP_CORE_URL . 'assets/js/a11y-fixes.js';
        return $assets;
    }

    public function replace_facets( $facet_types ) {
       //remove facets native pager and replace with ours
        if( $facet_types['pager']){
            unset($facet_types['pager']);
            $facet_types['pager'] = new Pager();
        }
        if( $facet_types['checkboxes']){
            unset($facet_types['checkboxes']);
            $facet_types['checkboxes'] = new Checkboxes();
        }
        return $facet_types;
    }

    public function sort() {
        return new Sort();
    }

    //remove FacetWP a11y fixes if in place
    public function disable_fwp_a11y( $enabled ) {
        return false;
    }
   
}