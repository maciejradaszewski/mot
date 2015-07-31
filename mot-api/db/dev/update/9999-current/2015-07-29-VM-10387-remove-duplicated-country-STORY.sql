/*
Remove duplicate countries in the country lookup table.
This has been approved by Ana Rotstein, Gordan Bobic and Arron Cupid.
*/

SET @id1 = (select max(id) from country_lookup where name = 'Guernsey' having count(id) > 1);
SET @id2 = (select max(id) from country_lookup where name = 'Jersey' having count(id) > 1);
SET @id3 = (select max(id) from country_lookup where name = 'Isle of Man' having count(id) > 1);

DELETE FROM country_lookup WHERE id IN (@id1, @id2, @id3);
