<?php
/**
 * Template helper functions for the Advanced H2H league table.
 *
 * These helpers are used by templates/league-table.php to build markup through
 * a set of generic, reusable filters. They are intentionally kept free of any
 * highlight-specific (or other feature-specific) logic: consumers hook the
 * filters to add their own classes, attributes, data-* hooks, cells, etc.
 *
 * @package advanced-h2h-for-sportspress
 * @author  savvasha
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'sah2h_html_attributes' ) ) {
	/**
	 * Turn an array of HTML attributes into an attribute string.
	 *
	 * - An array value is imploded with a single space (handy for `class`).
	 * - A null or false value skips the attribute entirely.
	 * - A true value renders a bare boolean attribute (e.g. `hidden`).
	 * - Any other value renders as key="value".
	 *
	 * The returned string has no leading or trailing space, so callers build
	 * tags as '<tr ' . sah2h_html_attributes( $attr ) . '>'.
	 *
	 * No escaping is applied on purpose: the league table template echoes its
	 * final output through wp_kses_post(), matching SportsPress core behaviour.
	 *
	 * @param array $attr Associative array of attribute name => value.
	 * @return string The assembled attribute string.
	 */
	function sah2h_html_attributes( $attr ) {
		$parts = array();

		foreach ( (array) $attr as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = implode( ' ', $value );
			}

			if ( null === $value || false === $value ) {
				continue;
			}

			if ( true === $value ) {
				$parts[] = $key;
				continue;
			}

			$parts[] = $key . '="' . $value . '"';
		}

		return implode( ' ', $parts );
	}
}

if ( ! function_exists( 'sah2h_league_table_cell' ) ) {
	/**
	 * Build a single league table <td> cell, passing its attributes through the
	 * sah2h_league_table_cell_attributes filter.
	 *
	 * @param string $column     Column slug ('rank', 'name', or the data key).
	 * @param string $content    Already-built cell inner HTML.
	 * @param array  $classes    Base CSS classes for the cell (e.g. array( 'data-name' )).
	 * @param string $data_label Value for the cell's data-label attribute.
	 * @param int    $team_id    Team post ID for the current row.
	 * @param array  $row        Raw row data for the current team.
	 * @param int    $table_id   The sp_table post ID being rendered.
	 * @return string The assembled <td>...</td> markup.
	 */
	function sah2h_league_table_cell( $column, $content, $classes, $data_label, $team_id, $row, $table_id ) {
		$attr = array(
			'class'      => $classes,
			'data-label' => $data_label,
		);

		/**
		 * Filter the attributes of a single league table cell.
		 *
		 * @param array  $attr     Attribute array ('class' => array, 'data-label' => string).
		 * @param string $column   Column slug ('rank', 'name', or the data key).
		 * @param int    $team_id  Team post ID for the current row.
		 * @param array  $row      Raw row data for the current team.
		 * @param int    $table_id The sp_table post ID being rendered.
		 */
		$attr = apply_filters( 'sah2h_league_table_cell_attributes', $attr, $column, $team_id, $row, $table_id );

		return '<td ' . sah2h_html_attributes( $attr ) . '>' . $content . '</td>';
	}
}
