<?php

function lastItemArray( $arrayArg ) {
    foreach($arrayArg as $key=>$value) {
      //something
    }
    return $key;
}

function showLastFilterActionRegistered() {
    global $wp_filter;
    $lastItem = lastItemArray($wp_filter);
    echo "last filter/action registered: $lastItem";
    var_dump($wp_filter[$lastItem]);
}

function prettyPrintWpFilter() {

	echo '<ul>';
	/* Each [tag] */
	foreach ( $GLOBALS['wp_filter'] as $tag => $priority_sets ) {
	  echo '<li><strong>' . $tag . '</strong><ul>';

	  /* Each [priority] */
	  foreach ( $priority_sets as $priority => $idxs ) {
	    echo '<li>' . $priority . '<ul>';

	    /* Each [callback] */
	    foreach ( $idxs as $idx => $callback ) {
	      if ( gettype($callback['function']) == 'object' ) $function = '{ closure }';
	      else if ( is_array( $callback['function'] ) ) {
	        $function = print_r( $callback['function'][0], true );
	        $function .= ':: '.print_r( $callback['function'][1], true );
	      }
	      else $function = $callback['function'];
	      echo '<li>' . $function . '<i>(' . $callback['accepted_args'] . ' arguments)</i></li>';
	    }
	    echo '</ul></li>';
	  }
	  echo '</ul></li>';
	}
	echo '</ul>';
}

// TO-READ
// https://www.smashingmagazine.com/2012/02/inside-wordpress-actions-filters
// http://www.rarst.net/wordpress/debug-wordpress-hooks/


/* Hook to the 'all' action */
//add_action( 'all', 'backtrace_filters_and_actions');

function backtrace_filters_and_actions() {
  /* The arguments are not truncated, so we get everything */
  $arguments = func_get_args();
  $tag = array_shift( $arguments ); /* Shift the tag */

  /* Get the hook type by backtracing */
  $backtrace = debug_backtrace();
  $hook_type = $backtrace[3]['function'];

  echo "<pre>";
  echo "<i>$hook_type</i> <b>$tag</b>\n";
  foreach ( $arguments as $argument )
    echo "\t\t" . htmlentities(var_export( $argument, true )) . "\n";

    echo "\n";
    echo "</pre>";
}




/*
function output_into_footer() {
	global $wp_filter;
	var_dump($wp_filter);
	echo 'whatever';
}
add_action('wp_footer','output_into_footer');
*/