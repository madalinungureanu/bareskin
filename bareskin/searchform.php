<?php
/**
 * The template for displaying Search Form.
 *
 * @package WordPress
 * @subpackage BareSkin 
 */

?>

<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
    <div>
        <input type="text" value="<?php $search_query = get_search_query(); if( !empty( $search_query ) ) echo $search_query; else echo "Search..." ; ?>" name="s" id="s" onfocus="if(this.value == 'Search...') this.value = '';" onblur="if(this.value == '' ) this.value='Search...'; " />
        <input type="submit" id="searchsubmit" value="Go" />
    </div>
</form>