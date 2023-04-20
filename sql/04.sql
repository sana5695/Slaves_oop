SELECT category, COUNT(*) as male_count
FROM slaves
WHERE gender = 'male'
GROUP BY category
HAVING male_count > (SELECT COUNT(*) FROM slaves WHERE gender = 'female' AND category = slaves.category);
