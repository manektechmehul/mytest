SET @needle = '%threads%';

SELECT * FROM content WHERE body LIKE @needle ;
SELECT * FROM configuration WHERE `value` LIKE @needle;
SELECT * FROM forms WHERE `preamble` LIKE  @needle;
SELECT * FROM forms WHERE `thankyou` LIKE  @needle;
SELECT * FROM shop_member_page WHERE `body` LIKE  @needle;
