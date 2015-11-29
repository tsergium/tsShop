INSERT INTO `ts_settings` (`id`, `module`, `name`, `const`, `value`) VALUES (NULL, 'default', 'Facebook App Id', 'FACEBOOK_APP_ID', '139587929735516');
INSERT INTO `ts_settings` (`id`, `module`, `name`, `const`, `value`) VALUES (NULL, 'default', 'Facebook App Secret', 'FACEBOOK_APP_SECRET', '16ba028800b38985d599a4b9a43d4638');
INSERT INTO `ts_settings` (`id`, `module`, `name`, `const`, `value`) VALUES (NULL, 'default', 'Facebook Redirect Url', 'FACEBOOK_REDIRECT_URL', 'https://shoprange.org/facebook/');
INSERT INTO `ts_settings` (`id`, `module`, `name`, `const`, `value`) VALUES (NULL, 'default', 'Facebook Category Id', 'FACEBOOK_CATEGORY_ID', '379');
ALTER TABLE  `fp_products` DROP FOREIGN KEY  `fp_products_ibfk_1` ;