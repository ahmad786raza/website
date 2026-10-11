<?php
/**
 * Back-compat alias: the loop card now lives in content-card.php.
 */
get_template_part( 'template-parts/content', 'card', isset( $args ) ? $args : array() );
