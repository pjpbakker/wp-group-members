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

function my_group_list_shortcode($attrs) {

    extract(shortcode_atts(
    	array(
        'group' => 'No Group'
      ), $attrs
		));
    global $wpdb;

    $taxonomy = 'user-group';

    	$query = "SELECT ug.user_id FROM wp_groups_rs as g, wp_user2group_rs as ug " .
			"where g.group_name = '" .$group . "' AND ug.group_id = g.ID";
    
    $userIDs = $wpdb->get_results($query);

    if ($userIDs) {
        $content = "<div class='group-list'><ul>";
        foreach( $userIDs as $userStdObj ) {
        	foreach ($userStdObj as $key => $userID) {
            $user = get_user_by('id', $userID);
            $content .= "<li>";
            $content .= "" . $user->user_firstname . " " . $user->user_lastname . "";
            $content .= "</li>";
        	}
        }
        $content .= "</ul></div>";
    } else {
        $content = "<div class='group-list group-list-none'>Geen leden gevonden</div>";
    }
    return $content;
}

add_shortcode('group-list', 'my_group_list_shortcode');
?>