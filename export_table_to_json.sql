	select concat(json_object(
		'FoodID', FoodID, 
		'FoodCode', FoodCode, 
		'FoodDescription', FoodDescription,
		'FoodGroupID', FoodGroupID
	), ",")
	from foodnames
	;

show variables LIKE "secure_file_priv";

SELECT 
    CONCAT("[",
         GROUP_CONCAT(
              CONCAT("{FoodID:'",FoodID,"'"),
              CONCAT("FoodDescription:'",FoodDescription,"'"),
              CONCAT(",FoodGroupID:'",FoodGroupID,"'}")
         )
    ,"]");
AS json FROM foodnames
INTO OUTFILE '/usr/local/exports/foodnames.json';

select 
CONCAT(
	'{"FoodID":', "'", FoodID, "',",
	'"FoodDescription":', "'", FoodDescription,"'},")
AS json 
FROM foodnames
INTO OUTFILE '/usr/local/exports/foodnames.json';

select json_quote("")
FROM foodnames
INTO OUTFILE '/usr/local/exports/foods.json';

select FoodDescription from foodnames where FoodID=2676;

select group_concat("[", 
	select concat(json_object(
		'FoodID', FoodID, 
		'FoodCode', FoodCode, 
		'FoodDescription', FoodDescription,
		'FoodGroupID', FoodGroupID
	), ",")
	from foodnames
, "]");

	select concat(json_object(
		'FoodID', FoodID, 
		'FoodCode', FoodCode, 
		'FoodDescription', FoodDescription,
		'FoodGroupID', FoodGroupID
	), ",")
	from foodnames
INTO OUTFILE '/usr/local/exports/foods.json';

SELECT CONCAT('id', `FoodID`, 'name', `FoodDescription`)
FROM foodnames
WHERE FoodID = 2676
INTO OUTFILE '/usr/local/exports/foods.json'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"', ESCAPED BY ''
LINES TERMINATED BY '\n';

SELECT 
    CONCAT("[",
         GROUP_CONCAT(
              CONCAT("{FoodID:'",FoodID,"'")
         )
    ,"]");
INTO OUTFILE '/usr/local/exports/foodnames.json';

TABLE foodgroups ORDER BY FoodGroupName LIMIT 1000
    INTO OUTFILE '/usr/local/exports/foogroupd.json'
    FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"', ESCAPED BY '\'
    LINES TERMINATED BY '\n';
    
