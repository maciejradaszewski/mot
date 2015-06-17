-- VM-9568
-- To avoid many rows with the same combination of role_id and permission_id we should delete all duplicated rows and add unique constraint.

-- delete all duplicated rows
DELETE FROM `role_permission_map` WHERE `id` IN (
  SELECT * FROM (
    SELECT
        `map2`.`id` AS `id_of_duplicated_row`
      FROM
        `role_permission_map` `map1`
      JOIN `permission` `permission` ON `permission`.`id` = `map1`.`permission_id`
      JOIN `role` `role` ON `role`.`id` = `map1`.`role_id`
      JOIN `role_permission_map` `map2` ON (
          `map1`.`id` < `map2`.`id`
            AND
          `map2`.`permission_id` = `map1`.`permission_id`
            AND
          `map2`.`role_id` = `map1`.`role_id`
      )
  ) AS x # please see http://stackoverflow.com/questions/4429319/you-cant-specify-target-table-for-update-in-from-clause
         # to find out why I did it this way
);

-- block from adding duplicated rows in the future
ALTER TABLE `role_permission_map`
  ADD UNIQUE KEY `uk_role_permission_map_role_id_permission_id` (`role_id`, `permission_id`);
