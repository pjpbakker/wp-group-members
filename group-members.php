<?php
/*
Plugin Name: Group Members
Plugin URI: https://github.com/pjpbakker/group-members
Description: Displays list of users from Wordpress' User database
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
			'selectfield' => 'Unkown_field',
			'selectvalues' => 'Unknown Group',
			'display' => 'table',
			'fields' => ''
		), $attrs
	));
	global $wpdb;

	$groups = split(',', $selectvalues);
	$group_string = implode("','", $groups);
	$group_string = "'" . $group_string . "'";
	$query = "SELECT user_id FROM wp_usermeta " .
			"where meta_key = '".$selectfield."' AND meta_value in (".$group_string.")";
	
	$userIDs = $wpdb->get_results($query);

	$fieldlist = split(",", $fields);
	
	if ($userIDs) {
		if ($display == 'table') {
			$content = "<div class='group-list'><table>";
			foreach( $userIDs as $userStdObj ) {
				foreach ($userStdObj as $key => $userID) {
					$content .= "<tr>";
					$user = get_user_by('id', $userID);
					$content .= "<td><a href='mailto:" . $user->user_email . "'>" . $user->user_firstname . " " . $user->user_lastname . "</a></td>";
					foreach ($fieldlist as $field) {
						$content .= "<td>" . get_user_meta($userID, $field, true) . "</td>";
					}
					$content .= "</tr>";
				}
			}
			$content .= "</table></div>";
		} else {
			$content = "<div class='group-list'><ul>";
			foreach( $userIDs as $userStdObj ) {
				foreach ($userStdObj as $key => $userID) {
					$user = get_user_by('id', $userID);

					$content .= "<li>";
					$content .= "<a href='mailto:" . $user->user_email . "'>" . $user->user_firstname . " " . $user->user_lastname . "</a>";
					foreach ($fieldlist as $field) {
						$content .= ", " . get_user_meta($userID, $field, true);
					}
					$content .= "</li>";
				}
			}
			$content .= "</ul></div>";
		}
	} else {
		$content = "<div class='group-list group-list-none'>Geen leden gevonden</div>";
	}
	return $content;
}

add_shortcode('group-list', 'my_group_list_shortcode');
?>
