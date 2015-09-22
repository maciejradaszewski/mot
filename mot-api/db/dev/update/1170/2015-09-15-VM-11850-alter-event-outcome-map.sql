-- VM-11850 Create manual event
-- Adding new events, create a unique key across event type, outcome and category

ALTER TABLE `event_type_outcome_category_map` ADD UNIQUE INDEX `uk_event_type_outcome_category_map_type_outcome_category` (`event_type_id`, `event_outcome_id`, `event_category_id`);