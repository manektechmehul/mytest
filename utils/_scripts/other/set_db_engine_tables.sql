
-- See what state the db is in 
SET @DATABASE_NAME = 'codebase';
-- set character set & collate for international
ALTER DATABASE codebase DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
-- set default storage engine so all new tables are in the right format
SET storage_engine=MYISAM;
-- show me all tables
SHOW TABLE STATUS;



-- create update statements to fix db table
SET @DATABASE_NAME = 'codebase';
SELECT  CONCAT('ALTER TABLE ', table_name, ' ENGINE=MyISAM;') AS sql_statements,
 CONCAT('ALTER TABLE ', table_name, ' DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci; ') AS col
FROM    information_schema.tables AS tb
WHERE   table_schema = @DATABASE_NAME
-- AND     `ENGINE` = 'InnoDB'
AND     `TABLE_TYPE` = 'BASE TABLE'
ORDER BY table_name DESC;
-- now paste out all results to a new window and run. 
-- THIS WILL TAKES AFEW MINUITES - BE PATIENT !!!

/*
Setting default engine in the my.cnf

If you omit the ENGINE option, the default storage engine is used. Normally, this is MyISAM, but you can change it by using the --default-storage-engine server startup option, or by setting the default-storage-engine option in the my.cnf configuration file.
You may also want to change the default storage engine just for the current session. You can do this by setting the storage_engine variable:
SET storage_engine=MyISAM;
*/