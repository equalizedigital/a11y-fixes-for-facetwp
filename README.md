# a11y-fixes-for-facetwp
A WordPress plugin that addresses some of the common accessibility issues when using FacetWP 
## Addressing missing labels on facets

Uses JS to take the data-name from the facet wrapper and apply to selects for facet type of dropdown and pager.

The sort facet does not have a UI interface in the admin and doesn't have a data-name attribute.  I modified this via the 'facetwp_sort_html' filter with a generic `aria-label` of 'sort by' In order to make this more flexible a filter for this aria label was created.  
This can be implemented like this:

`add_filter( 'a11y_fixes_for_fwp_sort_by_label', function( $label ){
    return 'Text you want for filter';
    }
);`

For the checkboxes - added role="group" to the wrapping div and added an aria-label as well.

## Existing a11y features from FacetWP
FacetWP has a11y features that are added via JS.  This is done by using a filter. 

`add_filter( 'facetwp_load_a11y', '__return_true' );`

If this is enabled by the user, this plugin disables this with by running late on the filter.

`add_filter( 'facetwp_load_a11y', [ $this, 'disable_fwp_a11y' ], 9999 );`

There is a small probablitly that if the priority is higher than 9999 than this will require adjustment.

## Search Facet

The FacetWP a11y fix used the placeholder as an aria-label.  This plugin keeps that, but adds a fallback to use the name of the facet if the placeholder is empty.
