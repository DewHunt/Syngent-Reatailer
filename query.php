<?php
	$rsm_query = "
		SELECT `rsms`.`id`,`rsms`.`code`,`rsms`.`import_code`,`rsms`.`asm`,`rsms`.`tso`,`rsms`.`email_address`,`rsms`.`mobile_no`,`rsms`.`zone`,`rsms`.`distributor_name`,`rsms`.`district`,`dealer_informations`.`dealer_name`,`dealer_informations`.`dealer_address`,`dealer_informations`.`city`,`dealer_informations`.`division`,`dealer_informations`.`dealer_phone_number`,`dealer_informations`.`dealer_type`
		FROM `rsms`
		LEFT JOIN `distributors` ON `rsms`.`import_code` = `distributors`.`import_code`
		LEFT JOIN `dealer_informations` ON `dealer_informations`.`dealer_code` = `distributors`.`import_code` OR `dealer_informations`.`alternate_code` = `distributors`.`import_code`
	";


	$rsm_view = "
		CREATE VIEW `view_rsm_delar_info` AS
		SELECT `rsms`.`id`,`rsms`.`code`,`rsms`.`import_code`,`rsms`.`asm`,`rsms`.`tso`,`rsms`.`email_address`,`rsms`.`mobile_no`,`rsms`.`zone`,`rsms`.`distributor_name`,`rsms`.`district`,`dealer_informations`.`dealer_name`,`dealer_informations`.`dealer_address`,`dealer_informations`.`city`,`dealer_informations`.`division`,`dealer_informations`.`dealer_phone_number`,`dealer_informations`.`dealer_type`
		FROM `rsms`
		LEFT JOIN `distributors` ON `rsms`.`import_code` = `distributors`.`import_code`
		LEFT JOIN `dealer_informations` ON `dealer_informations`.`dealer_code` = `distributors`.`import_code` OR `dealer_informations`.`alternate_code` = `distributors`.`import_code`

	";

	$dealer_information_list = "
		CREATE VIEW view_dealer_information_list AS
		SELECT `dealerinfo`.`id` AS `id`,`dealerinfo`.`dealer_id` AS `dealer_id`,`dealerinfo`.`dealer_code`,`dealerinfo`.`alternate_code`,`dealerinfo`.`dealer_name`,`dealerinfo`.`dealer_address`,`dealerinfo`.`zone`,`dealerinfo`.`city`,`dealerinfo`.`division`,`dealerinfo`.`dealer_phone_number`,`dealerinfo`.`dealer_type`,`dealerinfo`.`status`,`rsms`.`rsm`
		FROM `dealer_informations` AS `dealerinfo` LEFT JOIN `rsms` ON `dealerinfo`.`dealer_code`=`rsms`.`import_code` OR `dealerinfo`.`alternate_code`=`rsms`.`import_code`
	";

	$product_information_view = "
		CREATE OR REPLACE VIEW `view_product_master` AS 
		SELECT `product_masters`.`product_master_id`,
		`product_masters`.`product_id`,
		`product_masters`.`product_code`,
		`product_masters`.`product_type`,
		`product_masters`.`product_model`,
		`product_masters`.`category2`,
		`product_masters`.`status`,
		`product_master_prices`.`mrp_price`,
		`product_master_prices`.`msdp_price`,
		`product_master_prices`.`msrp_price`,
		`retailer_product_stocks`.`quantity` AS `stock_qty`
		FROM `product_masters`
		LEFT JOIN `product_master_prices` ON `product_master_prices`.`product_id` = `product_masters`.`product_master_id`
		LEFT JOIN `retailer_product_stocks` ON `retailer_product_stocks`.`product_id` = `product_masters`.`product_master_id`
		ORDER BY `product_masters`.`product_master_id` ASC
	";


	$employee_information_view = "
		CREATE VIEW view_employee_list AS
		SELECT `employees`.`id`,`employees`.`photo`,`employees`.`employee_id`,`employees`.`name`,`employees`.`designation`,`employees`.`education`,`employees`.`responsibility`,`employees`.`joining_date`,`employees`.`mobile_number`,`employees`.`email`,`employees`.`operating_unit`,`employees`.`product`,`employees`.`department`,`employees`.`section`,`employees`.`sub_section`,`employees`.`status`
		FROM `employees`
		ORDER BY `employees`.`status` ASC;
	";

	$zone_list_view = "
		CREATE VIEW view_zone_list AS
		SELECT * FROM `zones` ORDER BY ID ASC;
	";

	$retailer_list_view = "
		CREATE VIEW view_retailer_list AS
		SELECT `retailers`.*,`dealer_informations`.`dealer_name`,`zones`.`zone_name`
		FROM `retailers`
		LEFT JOIN `dealer_informations` ON `dealer_informations`.`dealer_code` = `retailers`.`distributor_code` OR `dealer_informations`.`alternate_code` = `retailers`.`distributor_code2`
		LEFT JOIN `zones` ON `zones`.`zone_id`= `retailers`.`zone_id`
	";

	$retailer_list_view = "
		CREATE OR REPLACE VIEW `view_retailer_list` AS
		SELECT `retailers`.`id` AS `id`,`retailers`.`retailer_id`,`retailers`.`category_id`,`retailers`.`zone_id`,`retailers`.`division_id`,`retailers`.`distric_id`,`retailers`.`thana_id`,`retailers`.`retailer_name`,`retailers`.`retailder_address`,`retailers`.`owner_name`,`retailers`.`phone_number`,`retailers`.`agent_name`,`retailers`.`bank_name`,`retailers`.`payment_type`,`retailers`.`payment_number`,`retailers`.`division_name` ,`retailers`.`distric_name`,`retailers`.`police_station`,`retailers`.`distributor_code`,`retailers`.`distributor_code2`,`retailers`.`status`,`retailers`.`shop_start_time`,`retailers`.`start_time_ampm`,`retailers`.`shop_end_time`,`retailers`.`end_time_ampm`,`dealer_informations`.`dealer_name`,`zones`.`zone_name`,`bp_retailer_categories`.`name` AS `category_name`
		FROM `retailers`
		LEFT JOIN `dealer_informations` ON `dealer_informations`.`dealer_code` = `retailers`.`distributor_code`
		LEFT JOIN `zones` ON `zones`.`id` = `retailers`.`zone_id`
		LEFT JOIN `bp_retailer_categories` ON `retailers`.`category_id` = `bp_retailer_categories`.`id`
	";

	$brand_promoter_list_view = "
		CREATE VIEW view_brand_promoter_list AS
		SELECT * FROM `brand_promoters`
	";

	$api_user_check_before_login = "
		CREATE VIEW `view_check_login_user` 
		AS
		SELECT `retail_gear`.`users`.`id` AS `id`,`retail_gear`.`users`.`name` AS `name`,`retail_gear`.`users`.`employee_id` AS `employee_id`,`retail_gear`.`users`.`brand_promoter_id` AS `brand_promoter_id`,`retail_gear`.`users`.`retailer_id` AS `retailer_id`,`retail_gear`.`users`.`email` AS `email`,`retail_gear`.`users`.`password` AS `password`,`retail_gear`.`users`.`activation_key` AS `activation_key`,`retail_gear`.`employees`.`mobile_number` AS `employee_phone`,`retail_gear`.`brand_promoters`.`bp_phone` AS `brand_promoter_phone`,`retail_gear`.`retailers`.`phone_number` AS `retailer_phone` FROM (((`retail_gear`.`users` 
		LEFT JOIN `retail_gear`.`employees` ON(`retail_gear`.`employees`.`employee_id` = `retail_gear`.`users`.`employee_id`)) 
		LEFT JOIN `retail_gear`.`brand_promoters` ON(`retail_gear`.`brand_promoters`.`bp_id` = `retail_gear`.`users`.`brand_promoter_id`)) 
		LEFT JOIN `retail_gear`.`retailers` ON(`retail_gear`.`retailers`.`retailer_id` = `retail_gear`.`users`.`retailer_id`))
	";

	$sales_report_view = "
		CREATE OR REPLACE VIEW `view_sales_reports` AS
		SELECT `sales`.`id` AS `id`,
		`sales`.`retailer_id` AS `retailer_id`,
		`sales`.`dealer_code` AS `dealer_code`,
		`sales`.`sale_date` AS `sale_date`,
		`sales`.`invoice_number` AS `invoice_number`,
		`sales`.`customer_name` AS `customer_name`,
		`sales`.`customer_phone` AS `customer_phone`,
		`sales`.`total_qty` AS `total_qty`,
		`sales`.`total_amount` AS `total_amount`,
		`sales`.`photo` AS `photo`,
		`sales`.`status` AS `status`,
		`sales`.`note` AS `note`,
		`sales`.`order_type` AS `order_type`,
		`retailers`.`retailer_name` AS `retailer_name`,
		`retailers`.`retailder_address` AS `retailder_address`,
		`retailers`.`phone_number` AS `retailer_phone_number`,
		`retailers`.`category_id` AS `retailer_category_id`,
		`retailers`.`distric_name` AS `retailer_distric`,
		`retailers`.`zone_id` AS `zone_id`,
		`dealer_informations`.`dealer_name` AS `dealer_name`,
		`dealer_informations`.`dealer_phone_number` AS `dealer_phone_number`,
		`dealer_informations`.`dealer_address` AS `dealer_address`,
		`dealer_informations`.`alternate_code` AS `alternate_code`
		FROM `sales`
		LEFT JOIN `retailers` on `retailers`.`id` = `sales`.`retailer_id`
		LEFT JOIN `dealer_informations` on `dealer_informations`.`dealer_code` = `sales`.`dealer_code`
	";

	$sales_product_report_view = "
		CREATE OR REPLACE VIEW `view_sales_product_reports` AS
		SELECT `sales`.`id` AS `sale_id`,
		`sale_products`.`id` AS `sale_product_id`,
		`sales`.`retailer_id` AS `retailer_id`,
		`sales`.`dealer_code` AS `dealer_code`,
		`sales`.`sale_date` AS `sale_date`,
		`sale_products`.`product_master_id` AS `product_master_id`,
		`sale_products`.`product_id` AS `product_id`,
		`sale_products`.`product_code` AS `product_code`,
		`sale_products`.`product_model` AS `product_model`,
		`sale_products`.`category` AS `product_category`,
		`sale_products`.`sale_price` AS `sale_price`,
		`sale_products`.`sale_qty` AS `sale_qty`,
		`retailers`.`retailer_name` AS `retailer_name`,
		`retailers`.`retailder_address` AS `retailder_address`,
		`retailers`.`phone_number` AS `retailer_phone_number`,
		`retailers`.`category_id` AS `retailer_category_id`,
		`retailers`.`distric_name` AS `retailer_distric`,
		`retailers`.`zone_id` AS `zone_id`,
		`dealer_informations`.`dealer_name` AS `dealer_name`,
		`dealer_informations`.`dealer_phone_number` AS `dealer_phone_number`,
		`dealer_informations`.`dealer_address` AS `dealer_address`,
		`dealer_informations`.`alternate_code` AS `alternate_code`
		FROM `sale_products`
		LEFT JOIN `sales` on `sales`.`id` = `sale_products`.`sales_id`
		LEFT JOIN `retailers` on `retailers`.`id` = `sale_products`.`retailer_id`
		LEFT JOIN `dealer_informations` on `dealer_informations`.`dealer_code` = `sale_products`.`dealer_code`
	";

	$bk_sales_report_view = "
		CREATE OR REPLACE VIEW `view_sales_reports` AS
		SELECT `sales`.`id` AS `id`,
		`sales`.`invoice_number` AS `invoice_number`,
		`sales`.`customer_name` AS `customer_name`,
		`sales`.`customer_phone` AS `customer_phone`,
		`sales`.`bp_id` AS `bp_id`,
		`sales`.`retailer_id` AS `retailer_id`,
		`sales`.`sale_date` AS `sale_date`,
		`sales`.`photo` AS `photo`,
		`sales`.`status` AS `status`,
		`sales`.`note` AS `note`,
		`sales`.`order_type` AS `order_type`,
		`sale_products`.`product_master_id` AS `product_master_id`,
		`sale_products`.`dealer_code` AS `dealer_code`,
		`sale_products`.`product_id` AS `product_id`,
		`sale_products`.`product_code` AS `product_code`,
		`sale_products`.`ime_number` AS `ime_number`,
		`sale_products`.`alternate_imei` AS `alternate_imei`,
		`sale_products`.`product_type` AS `product_type`,
		`sale_products`.`product_model` AS `product_model`,
		`sale_products`.`product_color` AS `product_color`,
		`sale_products`.`category` AS `category`,
		`sale_products`.`mrp_price` AS `mrp_price`,
		`sale_products`.`msdp_price` AS `msdp_price`,
		`sale_products`.`msrp_price` AS `msrp_price`,
		`sale_products`.`sale_price` AS `sale_price`,
		`sale_products`.`sale_qty` AS `sale_qty`,
		`sale_products`.`product_status` AS `product_status`,
		`retailers`.`retailer_name` AS `retailer_name`,
		`retailers`.`retailder_address` AS `retailder_address`,
		`retailers`.`phone_number` AS `retailer_phone_number`,
		`retailers`.`category_id` AS `retailer_category_id`,
		`retailers`.`distric_name` AS `retailer_distric`,
		`retailers`.`zone_id` AS `zone_id`,
		`brand_promoters`.`bp_name` AS `bp_name`,
		`brand_promoters`.`bp_phone` AS `bp_phone`,
		`brand_promoters`.`distributor_code` AS `distributor_code`,
		`brand_promoters`.`distributor_code2` AS `distributor_code2`,
		`brand_promoters`.`distric_name` AS `bp_distric`,
		`brand_promoters`.`category_id` AS `bp_category_id`,
		`dealer_informations`.`dealer_name` AS `dealer_name`,
		`dealer_informations`.`dealer_phone_number` AS `dealer_phone_number`,
		`dealer_informations`.`dealer_address` AS `dealer_address`,
		`dealer_informations`.`alternate_code` AS `alternate_code`
		FROM `sales`
		LEFT JOIN `sale_products` on `sale_products`.`sales_id` = `sales`.`id`
		LEFT JOIN `brand_promoters` on `brand_promoters`.`id` = `sales`.`bp_id`
		LEFT JOIN `retailers` on `retailers`.`retailer_id` = `sales`.`retailer_id`
		LEFT JOIN `dealer_informations` on `dealer_informations`.`dealer_code` = `sale_products`.`dealer_code`
		GROUP BY `sales`.`id`
	";

	$incentive_view_list = "
		CREATE VIEW `view_incentive_list` 
		AS 
		SELECT `incentives`.`id`,`incentives`.`incentive_group`,`incentives`.`incentive_title`,`incentives`.`product_model`,`incentives`.`incentive_type`,`incentives`.`zone`,`incentives`.`incentive_amount`,`incentives`.`min_qty`,`incentives`.`start_date`,`incentives`.`end_date`,`incentives`.`status` 
		FROM `incentives` 
		ORDER BY `incentives`.`id` ASC
	";

	$sales_incentive_report_list = "
		CREATE VIEW `view_sales_incentive_reports` 
		AS 
		SELECT `sale_incentives`.`id`,`sale_incentives`.`ime_number`,`sale_incentives`.`sale_id`,`sale_incentives`.`bp_id`,`sale_incentives`.`retailer_id`,`sale_incentives`.`incentive_title`,`sale_incentives`.`product_model`,`sale_incentives`.`zone`,`sale_incentives`.`incentive_amount`,`sale_incentives`.`incentive_min_qty`,`sale_incentives`.`incentive_sale_qty`,`sale_incentives`.`start_date`,`sale_incentives`.`end_date`,`sale_incentives`.`incentive_status` 
		FROM `sale_incentives` 
		ORDER BY `sale_incentives`.`id` ASC
	";

	$bp_attendance_report = "
		CREATE VIEW `view_bp_attendance_report`
		AS
		SELECT `bp_attendances`.`id`,`bp_attendances`.`bp_id`,`bp_attendances`.`location`,`bp_attendances`.`selfi_pic`,`bp_attendances`.`date`,`bp_attendances`.`remarks`,`brand_promoters`.`bp_id`,`brand_promoters`.`bp_name` 
		FROM `bp_attendances`
		LEFT JOIN `bp_attendances` ON `bp_attendances`.`bp_id` = `brand_promoters`.`bp_id`
		ORDER BY `bp_attendances`.`id` ASC
	";

	$bp_leave_report = "
		CREATE VIEW `view_bp_leave_report`
		AS
		SELECT `retail_gear`.`bp_leaves`.`bp_id` AS `id`,`retail_gear`.`bp_leaves`.`apply_date` AS `apply_date`,`retail_gear`.`bp_leaves`.`start_date` AS `start_date`,`retail_gear`.`bp_leaves`.`start_time` AS `start_time`,`retail_gear`.`bp_leaves`.`total_day` AS `total_day`,`retail_gear`.`bp_leaves`.`reason` AS `reason`,`retail_gear`.`brand_promoters`.`bp_name` AS `bp_name`,`retail_gear`.`leave_types`.`name` AS `leave_type`
		FROM `retail_gear`.`bp_leaves`
		LEFT JOIN `retail_gear`.`brand_promoters` ON `retail_gear`.`bp_leaves`.`bp_id` = `retail_gear`.`brand_promoters`.`bp_id`
		LEFT JOIN `retail_gear`.`leave_types` ON `retail_gear`.`bp_leaves`.`leave_type` = `retail_gear`.`leave_types`.`id`
		ORDER BY `retail_gear`.`bp_leaves`.`id` ASC
	";


	$modify_sales_report_view = "
		CREATE VIEW `view_sales_reports`
		AS
		SELECT `sales`.`id`,`sales`.`customer_name`,`sales`.`customer_phone`,`sales`.`bp_id`,`sales`.`retailer_id`,`sales`.`sale_date`,`sales`.`photo`,`sales`.`status`,`sale_products`.`product_id`,`sale_products`.`product_code`,`sale_products`.`ime_number`,`sale_products`.`product_type`,`sale_products`.`product_model`,`sale_products`.`category`,`sale_products`.`mrp_price`,`sale_products`.`msdp_price`,`sale_products`.`msrp_price`,`sale_products`.`sale_price`,`sale_products`.`sale_qty`,`sale_products`.`product_status`,`retailers`.`retailer_name`,`brand_promoters`.`bp_name`
		FROM `sales`
		LEFT JOIN `sale_products` ON `sale_products`.`sales_id` = `sales`.`id`
		LEFT JOIN `retail_gear`.`brand_promoters` ON `retail_gear`.`sales`.`bp_id` = `retail_gear`.`brand_promoters`.`bp_id`
		LEFT JOIN `retail_gear`.`retailers` ON `retail_gear`.`sales`.`retailer_id` = `retail_gear`.`retailers`.`retailer_id`

	";

	$modify_sales_incentive_report_view = "
		CREATE VIEW `view_sales_incentive_reports` 
		AS 
		SELECT `sale_incentives`.`id`,`sale_incentives`.`ime_number`,`sale_incentives`.`sale_id`,`sale_incentives`.`bp_id`,`sale_incentives`.`retailer_id`,`sale_incentives`.`incentive_title`,`sale_incentives`.`product_model`,`sale_incentives`.`zone`,`sale_incentives`.`incentive_amount`,`sale_incentives`.`incentive_min_qty`,`sale_incentives`.`incentive_sale_qty`,`sale_incentives`.`start_date`,`sale_incentives`.`end_date`,`sale_incentives`.`incentive_status`,`retailers`.`retailer_name`,`brand_promoters`.`bp_name` 
		FROM `sale_incentives`
		LEFT JOIN `retail_gear`.`brand_promoters` ON `retail_gear`.`sale_incentives`.`bp_id` = `retail_gear`.`brand_promoters`.`bp_id`
		LEFT JOIN `retail_gear`.`retailers` ON `retail_gear`.`sale_incentives`.`retailer_id` = `retail_gear`.`retailers`.`retailer_id` 
		ORDER BY `sale_incentives`.`id` ASC
	";
?>