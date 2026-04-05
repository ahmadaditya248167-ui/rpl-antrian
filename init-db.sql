CREATE TABLE `counters` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `counter_service` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `counter_id` bigint NOT NULL,
  `service_id` bigint NOT NULL,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `queues` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` bigint,
  `service_id` bigint NOT NULL,
  `counter_id` bigint,
  `served_by` bigint,
  `queue_number` int NOT NULL,
  `appointment_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'waiting',
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `services` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `prefix` varchar(5) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `service_schedules` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `service_id` bigint NOT NULL,
  `date` date NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '1',
  `max_quota` int NOT NULL,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `users` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp,
  `password` varchar(255) NOT NULL,
  `plain_key` varchar(255),
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE UNIQUE INDEX `counter_service_counter_id_service_id_unique` ON `counter_service` (`counter_id`, `service_id`);

CREATE INDEX `counter_service_service_id_foreign` ON `counter_service` (`service_id`);

CREATE INDEX `queues_user_id_foreign` ON `queues` (`user_id`);

CREATE INDEX `queues_service_id_foreign` ON `queues` (`service_id`);

CREATE INDEX `queues_counter_id_foreign` ON `queues` (`counter_id`);

CREATE INDEX `queues_served_by_foreign` ON `queues` (`served_by`);

CREATE UNIQUE INDEX `services_slug_unique` ON `services` (`slug`);

CREATE UNIQUE INDEX `service_schedules_service_id_date_unique` ON `service_schedules` (`service_id`, `date`);

CREATE UNIQUE INDEX `users_email_unique` ON `users` (`email`);

ALTER TABLE `counter_service` ADD CONSTRAINT `counter_service_counter_id_foreign` FOREIGN KEY (`counter_id`) REFERENCES `counters` (`id`) ON DELETE CASCADE;

ALTER TABLE `counter_service` ADD CONSTRAINT `counter_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

ALTER TABLE `queues` ADD CONSTRAINT `queues_counter_id_foreign` FOREIGN KEY (`counter_id`) REFERENCES `counters` (`id`) ON DELETE SET NULL;

ALTER TABLE `queues` ADD CONSTRAINT `queues_served_by_foreign` FOREIGN KEY (`served_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `queues` ADD CONSTRAINT `queues_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

ALTER TABLE `queues` ADD CONSTRAINT `queues_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `service_schedules` ADD CONSTRAINT `service_schedules_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;
