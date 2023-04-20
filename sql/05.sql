WITH RECURSIVE kitchen_categories AS (
  SELECT id, name
  FROM categories
  WHERE name = 'Для кухни'
  UNION
  SELECT c.id, c.name
  FROM categories c
  JOIN kitchen_categories kc ON c.parent_category_id = kc.id
)
SELECT COUNT(*) as slave_count
FROM slaves
WHERE category_id IN (SELECT id FROM kitchen_categories);
