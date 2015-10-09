CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM factor_resource WHERE id = 1;
UPDATE tmptable_1 SET id = NULL;
INSERT INTO factor_resource SELECT * FROM tmptable_1;
DROP TEMPORARY TABLE IF EXISTS tmptable_1;

UPDATE factor_resource 
SET 
title = CONCAT("Godzilla Trailer #", id, " - Test")
WHERE link_type = 4