SELECT MIN(cost), MAX(cost), AVG(cost)
FROM slaves
WHERE weight > 60;
