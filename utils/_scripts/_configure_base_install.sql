 # This is a sql file - copy this in to yog and hit f9

SET @lc_name = 'christianthreads';
SET @uc_name = 'ChristianThreads';
SET @mixc_name = 'Christian Threads';

UPDATE configuration SET `value` = REPLACE(`value`, '[WEBSITE]', @uc_name);
UPDATE configuration SET `value` = REPLACE(`value`, '[website]', @lc_name);

UPDATE forms SET `email` = REPLACE(`email`, '[website]', @lc_name);
UPDATE content SET body = REPLACE(body, '[website]', @lc_name);
UPDATE content SET body = REPLACE(body, '[Website]', @mixc_name);

UPDATE `configuration` SET `value`='down' WHERE `group`= '4' AND `name` = 'OFFLINE';
UPDATE `configuration` SET `value`='213.246.138.31' WHERE `group`= '4' AND `name` = 'OFFLINE_IP';

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