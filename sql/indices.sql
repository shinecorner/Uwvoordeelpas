ALTER TABLE `favorite_affiliates` 
ADD INDEX `IX_AFFILIATE` USING BTREE (`affiliate_id` ASC, `user_id` ASC);

ALTER TABLE `preferences` 
ADD INDEX `idx_category` USING BTREE (`category_id` ASC);

ALTER TABLE `affiliates` ADD INDEX `affiliate_noshow` USING BTREE (`no_show` ASC, `clicks` ASC);
