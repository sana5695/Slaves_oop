SELECT category, SUM(cost) as total_cost
FROM slaves
GROUP BY category
ORDER BY total_cost DESC
LIMIT 1;
