=== Advanced H2H for SportsPress ===
Contributors: savvasha
Tags: table, h2h, head to head, sorting, sort
Requires at least: 5.3
Tested up to: 7.1
Stable tag: 2.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Give your league managers the option to use more advanced Sorting Criteria for your League Tables.

== Description ==

This plugin is an extension for [SportsPress](https://wordpress.org/plugins/sportspress/).

Give your league managers the option to use more advanced Sorting Criteria for your League Tables.
Now is possible to select different Sorting Criteria for each League Table!


== Installation ==

1.  Install and activate Advanced H2H for SportsPress plugin.
2.  Create your own Sorting Criterion at `SportsPress->Teams->Sorting Criteria`.
3.  Select your preferred Sorting Criterion for your League Table
4.  Enjoy!

= Credits =

* [Themeboy](https://www.themeboy.com/)

== How to Use ==

= Step 1: Create Sorting Criteria =

1. Go to **SportsPress -> Teams -> Sorting Criteria** in your WordPress admin.
2. Click **"Add New Criterion"**.
3. Give your criterion a descriptive name (e.g., "Premier League Style", "Champions League Rules").

= Step 2: Configure Regular Order =

In the **"Regular Order"** section:

1. Drag and drop the columns in the order you want them to be considered for initial sorting.
2. Toggle the switch to disable criteria you do not want.
3. Set sort direction (Ascending/Descending) for each column.

This determines the primary sorting order when teams have different points.

**Important:** The Tiebreak Order is only applied between teams that are equal on *every* column enabled in the Regular Order. If you want the head-to-head rules to decide between teams that are level on points, keep **Points** as the only column in the Regular Order and move everything else (including overall Goal Difference and Goals For) to the Tiebreak Order. If you leave Goal Difference in the Regular Order, teams that are level on points but have a different Goal Difference are separated *before* the head-to-head rules can be applied.

= Step 3: Configure Tiebreak Order =

In the **"Tiebreak Order"** section:

1. Drag and drop columns in the order they should be used for tiebreakers.
2. Toggle the switch to disable criteria you do not want.
3. Set sort direction for each column.
4. Enable the **"H2H Only"** checkbox for columns that should only consider head-to-head results (matches played between the tied teams). Leave it unchecked for a column that should use full-season stats.

This determines how teams are ranked when they are equal on the Regular Order (for example, when they have the same points).

= Step 4: Review the Summary =

The **"Sorting Criteria Summary"** sidebar shows:

* Your configured regular order.
* Your configured tiebreak order.
* A clear overview of your sorting rules.

= Step 5: Apply to League Tables =

1. Go to **SportsPress -> Tables**.
2. Edit an existing league table or create a new one.
3. In the sidebar, find the **"Advanced Sorting Criteria"** meta box.
4. Select your custom sorting criterion from the dropdown.
5. Save the league table.

= Examples =

**Premier League Style** (overall Goal Difference decides before head-to-head):

* Regular Order: Points (DESC), Goal Difference (DESC), Goals For (DESC)
* Tiebreak Order: Points (DESC), Goal Difference (DESC), Goals For (DESC), Goals Away (DESC)

**Champions League Style** (head-to-head decides before overall Goal Difference):

* Regular Order: Points (DESC)
* Tiebreak Order: Points [H2H Only] (DESC), Goal Difference [H2H Only] (DESC), Goals For [H2H Only] (DESC), Goal Difference (DESC), Goals For (DESC)

Note: for the Champions League style, **Points must be the only column in the Regular Order**. Overall Goal Difference and Goals For go in the Tiebreak Order (with "H2H Only" unchecked) so they are only used after the head-to-head criteria. Putting Goal Difference in the Regular Order would separate teams that are level on points before the head-to-head rules are applied.

== Developer Hooks ==

The bundled `league-table.php` template exposes five generic filters so you can extend the output (row/cell highlighting, badges, data-* attributes for JS, inline styles, extra legends, etc.) without editing the template. Defaults produce exactly the same markup as before, so existing sites are unaffected until you hook in.

Note on types: the row data is keyed by team post ID as **integers**, while `get_post_meta( ..., 'sp_team' )` returns **strings**. Cast before comparing, e.g. `in_array( (int) $team_id, array_map( 'intval', $teams ), true )`.

**sah2h_league_table_rows** — `( array $data, int $table_id )`
Change the row data just before the loop (reorder, add or remove rows). Keys are team post IDs.

`add_filter( 'sah2h_league_table_rows', function ( $data, $table_id ) {
    return $data; // e.g. custom re-ordering
}, 10, 2 );`

**sah2h_league_table_row_attributes** — `( array $attr, int $team_id, array $row, int $table_id )`
Add any attribute to the `<tr>`. `$attr['class']` is an array of CSS classes.

`add_filter( 'sah2h_league_table_row_attributes', function ( $attr, $team_id, $row, $table_id ) {
    $attr['class'][] = 'highlighted';      // colours the whole row
    $attr['data-team'] = $team_id;          // custom data-* hook for JS
    return $attr;
}, 10, 4 );`

**sah2h_league_table_cell_attributes** — `( array $attr, string $column, int $team_id, array $row, int $table_id )`
Add attributes to an individual `<td>`. `$column` is `'rank'`, `'name'`, or the data key (e.g. `'pts'`). `$attr['class']` is an array; `$attr['data-label']` is the responsive label.

`add_filter( 'sah2h_league_table_cell_attributes', function ( $attr, $column, $team_id, $row, $table_id ) {
    if ( 'name' === $column ) {
        $attr['class'][] = 'sp-highlight';
    }
    return $attr;
}, 10, 5 );`

**sah2h_league_table_row_html** — `( string $html, int $team_id, array $row, int $table_id )`
Filter the fully assembled `<tr>...</tr>` markup for a single row.

`add_filter( 'sah2h_league_table_row_html', function ( $html, $team_id, $row, $table_id ) {
    return $html; // e.g. append a sub-row or wrap the markup
}, 10, 4 );`

**sah2h_league_table_html** — `( string $output, int $table_id )`
Catch-all filter on the complete table markup before it is echoed.

`add_filter( 'sah2h_league_table_html', function ( $output, $table_id ) {
    return $output . '<p class="legend">Bold = teams in this match</p>';
}, 10, 2 );`

== Screenshots ==

1.  The new CPT Sorting Criteria at SportsPress Teams Menu
2.  "Regular" Order priorities (Pts, GD, F) i.e. Premier League
3.  Tiebreak Order priorities (Pts, Goal Away) i.e. Premier League
4.  Sorting Criteria Summary i.e. Premier League
5.  Selection of Sorting Criterion at your League Table Edit Page.

== Changelog ==

= 2.2.0 =

* NEW: The league table template now exposes five generic, backward-compatible filters (`sah2h_league_table_rows`, `sah2h_league_table_row_attributes`, `sah2h_league_table_cell_attributes`, `sah2h_league_table_row_html`, `sah2h_league_table_html`) so highlighting, badges, data-* hooks, extra legends, etc. can be added without editing the template. Default output is byte-for-byte unchanged. See "Developer Hooks".

= 2.1.3 =

* FIX: Tiebreak columns without "H2H Only" now correctly fall back to full-season stats instead of the (empty) head-to-head values. Previously, teams that were level on points but had not played each other were sorted alphabetically instead of by the configured tiebreak column (e.g. Goal Difference), because an unchecked "H2H Only" checkbox is not submitted at all and the missing key was not treated as "not H2H only".
* DOC: Corrected the "Champions League Style" example. Overall Goal Difference must be placed in the Tiebreak Order (not the Regular Order) so the head-to-head criteria are applied first, and clarified that the Tiebreak Order only applies between teams equal on every Regular Order column.

= 2.1.2 =

* FIX: Fatal TypeError "Unsupported operand types: int + string" on PHP 8+ when `sp_minutes` event meta is empty or non-numeric.

= 2.1.1 =

* FIX: Highlight team feature is not working.

= 2.1.0 =

* UPDATE: Update league-table template to latest version.

= 2.0.2 =

* FIX: Avoid blank page if SportsPress is not activated.

= 2.0.1 =

* FIX: Error if the plugin is loaded before SportsPress.

= 2.0.0 =

* NEW: Sorting Criteria Custom Post Type (see screenshots)
* NEW: Ability to choose for each League Table which ordering criteria to use! (see screenshots)

= 1.0.0 =

* First Release!
