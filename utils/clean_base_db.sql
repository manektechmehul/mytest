/*
 Clean out base  script
 This just clears out log tables. could probably do with a clear down and insert cms data too at some point.
*/
DELETE FROM admin_log;
DELETE FROM `log`;
DELETE FROM `usage_log`;
DELETE FROM `preview`;
DELETE FROM `metatag`;
-- DELETE FROM `member_user`;
-- DELETE FROM `member_page`;
DELETE FROM `changes`;
DELETE FROM `form_values`;
DELETE FROM `diary_item`;
