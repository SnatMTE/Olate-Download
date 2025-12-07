<?php
/**********************************
* Olate Download 3.3.0
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 192 $
* @package od
*
* Updated: $Date: 2005-12-15 17:20:25 +0000 (Thu, 15 Dec 2005) $
*/

// Data
$data_sql[0] = "INSERT INTO `downloads_permissions` (`permission_id`, `name`, `setting`) 
VALUES (1, 'acp_view', 0),
(2, 'acp_main_settings', 0),
(3, 'acp_categories_add', 0),
(4, 'acp_categories_edit', 0),
(5, 'acp_categories_delete', 0),
(6, 'acp_files_add_file', 0),
(7, 'acp_files_edit_file', 0),
(8, 'acp_files_delete_file', 0),
(9, 'acp_files_add_agreement', 0),
(10, 'acp_files_edit_agreement', 0),
(11, 'acp_files_delete_agreement', 0),
(12, 'acp_users_add_user', 0),
(13, 'acp_users_edit_user', 0),
(14, 'acp_users_delete_user', 0),
(15, 'acp_users_add_group', 0),
(16, 'acp_users_edit_group', 0),
(17, 'acp_files_approve_comments', 0),
(18, 'acp_files_edit_comment', 0),
(19, 'acp_files_delete_comment', 0),
(20, 'acp_users_delete_group', 0),
(21, 'acp_categories_ordering', 0),
(22, 'acp_categories_delete_multiple', 0),
(23, 'acp_files_manage_comments', 0),
(24, 'acp_customfields_add', 0),
(25, 'acp_customfields_edit', 0),
(26, 'acp_customfields_delete', 0),
(27, 'acp_files_approve_files', 0),
(28, 'acp_ip_restrict_main', 0),
(29, 'acp_leech_settings', 0),
(30, 'acp_files_mass_move', 0),
(31, 'acp_files_mass_delete', 0),
(32, 'acp_languages', 0);";

$data_sql[1] = "INSERT INTO `downloads_usergroups` (`id`, `name`) 
VALUES (1, 'Members'),
(2, 'Admins');";

$data_sql[2] = "INSERT INTO `downloads_userpermissions` (`id`, `permission_id`, `type`, `type_value`, `setting`) VALUES (1, 1, 'user_group', 2, 1),
(2, 2, 'user_group', 2, 1),
(3, 3, 'user_group', 2, 1),
(4, 4, 'user_group', 2, 1),
(5, 5, 'user_group', 2, 1),
(6, 6, 'user_group', 2, 1),
(7, 7, 'user_group', 2, 1),
(8, 8, 'user_group', 2, 1),
(9, 9, 'user_group', 2, 1),
(10, 10, 'user_group', 2, 1),
(11, 11, 'user_group', 2, 1),
(12, 12, 'user_group', 2, 1),
(13, 13, 'user_group', 2, 1),
(14, 14, 'user_group', 2, 1),
(15, 15, 'user_group', 2, 1),
(16, 16, 'user_group', 2, 1),
(17, 17, 'user_group', 2, 1),
(18, 18, 'user_group', 2, 1),
(19, 19, 'user_group', 2, 1),
(20, 20, 'user_group', 2, 1),
(21, 21, 'user_group', 2, 1),
(22, 22, 'user_group', 2, 1),
(23, 23, 'user_group', 2, 1),
(24, 24, 'user_group', 2, 1),
(25, 25, 'user_group', 2, 1),
(26, 26, 'user_group', 2, 1),
(27, 27, 'user_group', 2, 1),
(28, 28, 'user_group', 2, 1),
(29, 29, 'user_group', 2, 1),
(30, 30, 'user_group', 2, 1),
(31, 31, 'user_group', 2, 1),
(32, 32, 'user_group', 2, 1);";

$data_sql[3] = 'INSERT INTO `downloads_languages` VALUES (\'\', \'English (British)\', 1, \'english.php\', 3, 4);';

?>