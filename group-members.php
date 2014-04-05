<?php
/*
Plugin Name: Group Members
Plugin URI: https://github.com/pjpbakker/group-members
Description: Displays list of users from Wordpress' User database (Requires Role Scoper)
Version: 1.0.0
Author: Paul Bakker
Author URI: https://github.com/pjpbakker
License: GPL2

Copyright 2014  Paul Bakker

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
Found the code for this at http://wordpress.org/support/topic/plugin-user-groups-list-group-members
 */

function my_group_list_shortcode($attrs) {

    extract(shortcode_atts(
    	array(
        'group' => 'No Group' // No Group is a defined user-group
      ), $attrs
		));
    global $wpdb;

    $taxonomy = 'user-group';

    // Use a dBase query to get the ID of the user group
    	$query = "SELECT ug.user_id FROM wp_groups_rs as g, wp_user2group_rs as ug " .
			"where g.group_name = '" .$group . "' AND ug.group_id = g.ID";
    
    $userIDs = $wpdb->get_results($query);

    // Check if any user IDs were returned; if so, display!
    // If not, notify visitor none were found.
    if ($userIDs) {
        $content = "<div class='group-list'><ul>";
			// $content .= $userIDs;
        foreach( $userIDs as $userStdObj ) {
        	foreach ($userStdObj as $key => $userID) {
            // $user = get_user_by('id', $userID);
						// $content .= $user -> user_firstname;
            $user = get_user_by('id', $userID);
            $content .= "<li>";
            $content .= "" . $user->user_firstname . " " . $user->user_lastname . "";
//            $content .= "<h3>" . $user->user_firstname . " " . $user->user_lastname . " <a href='mailto:" . $user -> user_email . "'>Mail</a></h3>";
            $content .= "</li>";
        	}
        }
        $content .= "</ul></div>";
    } else {
        $content =
        "<div class='group-list group-list-none'>Geen resultaten</div>";

    }
    return $content;
}

function niet_gebruikte_functie($add) {
    // Dit impliceert dat er een taxonomie user-group wordt gemaakt. Kan ik nog proberen

    // Get the global $wpdb object
    global $wpdb;

    // Extract the parameters and set the default
    extract ( shortcode_atts( array(
        'group' => 'No Group' // No Group is a defined user-group
        ), $atts ) );

    // The taxonomy name will be used to get the objects assigned to that group
    $taxonomy = 'user-group';

    // Use a dBase query to get the ID of the user group
    $userGroupID = $wpdb->get_var(
                    $wpdb->prepare("SELECT term_id
                        FROM {$wpdb->terms} t
                        WHERE t.name = %s", $group));

    // Now grab the object IDs (aka user IDs) associated with the user-group
    $userIDs = get_objects_in_term($userGroupID, $taxonomy);

    // Check if any user IDs were returned; if so, display!
    // If not, notify visitor none were found.
    if ($userIDs) {
        $content = "<div class='group-list'> <ul>";
        foreach( $userIDs as $userID ) {
            $user = get_user_by('id', $userID);
            $content .= "<li>";
            $content .= get_avatar( $user->ID, 70 );
            $content .= "<h3>" . $user->display_name . "</h3>";
            $content .= "<p><a href='". get_author_posts_url( $user->ID ) . "' class='more-info-icon'>More info</a>";
            $content .= "<!-- add more here --></p>";
            $content .= "</li>";
        }
        $content .= "</ul></div>";
    } else {
        $content =
        "<div class='group-list group-list-none'>Returned no results</div>";

    }
    return $content;
}

add_shortcode('group-list', 'my_group_list_shortcode');
?>