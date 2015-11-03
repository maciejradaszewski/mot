create table if not exists `database_version` (
  `id` bigint unsigned not null auto_increment,
  `version_name` varchar(255) not null,
  `applied_on` timestamp(6) not null default current_timestamp(6),
  primary key (`id`)
);