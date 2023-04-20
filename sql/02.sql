SELECT category, COUNT(*) as slave_count
FROM slaves
GROUP BY category
HAVING slave_count > 10;
