create table o2o_category(
id int(11) unsigned not null auto_increment primary key,
name varchar(50) not null default '',
parent_id int(10) unsigned not null default 0,
listorder int(8) unsigned not null default 0,
status tinyint(1) not null default 0,
create_time int(11) unsigned not null default 0,
update_time int(11) unsigned not null default 0,
key parent_id(`parent_id`)
) ENGINE=InnoDB;

create table o2o_city(
id int(11) unsigned not null auto_increment primary key,
name varchar(50) not null default '',
uname varchar(50) not null default '',
parent_id int(10) unsigned not null default 0,
is_default tinyint(3) unsigned not null default 0,
listorder int(8) unsigned not null default 0,
status tinyint(1) not null default 0,
create_time int(11) unsigned not null default 0,
update_time int(11) unsigned not null default 0,
key parent_id(`parent_id`),
UNIQUE key uname(`uname`)
) ENGINE=InnoDB;

create table o2o_area(
id int(11) unsigned not null auto_increment primary key,
name varchar(50) not null default '',
city_id int(10) unsigned not null default 0,
parent_id int(10) unsigned not null default 0,
listorder int(8) unsigned not null default 0,
status tinyint(1) not null default 0,
create_time int(11) unsigned not null default 0,
update_time int(11) unsigned not null default 0,
key parent_id(`parent_id`),
key city_id(`city_id`)
) ENGINE=InnoDB;

create table o2o_bis(
id int(11) unsigned not null auto_increment primary key,
name varchar(50) not null default '',
email varchar(50) not null default '',
logo varchar(255) not null default '',
licence_logo varchar(255) not null default '',
description text not null,
city_id int(10) unsigned not null default 0,
city_path varchar(50) not null default '',
bank_info varchar(50) not null default '',
money decimal(20,2) not null default '0.00',
bank_name varchar(50) not null default '',
bank_user varchar(50) not null default '',
faren varchar(20) not null default '',
faren_tel varchar(20) not null default '',
listorder int(8) unsigned not null default 0,
status tinyint(1) not null default 0,
create_time int(11) unsigned not null default 0,
update_time int(11) unsigned not null default 0,
key name(`name`),
key city_id(`city_id`)
) ENGINE=InnoDB;

create table o2o_bis_account(
id int(11) unsigned not null auto_increment primary key,
username varchar(50) not null default '',
password char(32) not null default '',
code varchar(10) not null default '',
bis_id int(11) unsigned not null default 0,
last_login_ip varchar(20) not null default '',
last_login_time int(11) unsigned not null default 0,
is_main tinyint(1) unsigned not null default 0,
listorder int(8) unsigned not null default 0,
status tinyint(1) not null default 0,
create_time int(11) unsigned not null default 0,
update_time int(11) unsigned not null default 0,
key username(`username`),
key bis_id(`bis_id`)
) ENGINE=InnoDB;

create table o2o_bis_location(
id int(11) unsigned not null auto_increment primary key,
name varchar(50) not null default '',
logo varchar(255) not null default '',
address varchar(255) not null default '',
tel varchar(20) not null default '',
contact varchar(20) not null default '',
xpoint varchar(20) not null default '',
ypoint varchar(20) not null default '',
bis_id int(11) unsigned not null default 0,
open_time int(11) unsigned not null default 0,
content text not null,
is_main tinyint(1) unsigned not null default 0,
api_address varchar(255) not null default '',
city_id int(10) unsigned not null default 0,
city_path varchar(50) not null default '',
category_id int(11) unsigned not null default 0,
category_path varchar(50) not null default '',
listorder int(8) unsigned not null default 0,
status tinyint(1) not null default 0,
create_time int(11) unsigned not null default 0,
update_time int(11) unsigned not null default 0,
key name(`name`),
key city_id(`city_id`),
key bis_id(`bis_id`),
key category_id(`category_id`)
) ENGINE=InnoDB;

create table if not exists o2o_deal(
id int(11) unsigned not null auto_increment primary key,
name varchar(100) not null default '',
category_id int(11) unsigned not null default 0,
se_category_id varchar(50) not null default '',
bis_id int(11) unsigned not null default 0,
location_ids varchar(100) not null default '',
image varchar(200) not null default '',
description text not null,
start_time int(11) unsigned not null default 0,
end_time int(11) unsigned not null default 0,
origin_price decimal(20,2) not null default '0.00',
current_price decimal(20,2) not null default '0.00',
city_id int(11) unsigned not null default 0,
se_city_id int(11) unsigned not null default 0,
buy_count int(11) unsigned not null default 0,
total_count int(11) unsigned not null default 0,
listorder int(11) unsigned not null default 0,
coupons_begin_time int(11) unsigned not null default 0,
coupons_end_time int(11) unsigned not null default 0,
status tinyint(1) not null default 0,
create_time int(11) unsigned not null default 0,
update_time int(11) unsigned not null default 0,
xpoint varchar(20) not null default '',
ypoint varchar(20) not null default '',
bis_account_id int(10) not null default 0,
balance_price decimal(20,2) not null default '0.00',
notes text not null,
key city_id(`city_id`),
key bis_id(`bis_id`),
key category_id(`category_id`),
key se_category_id(`category_id`),
key start_time(`start_time`),
key end_time(`end_time`),
key current_price(`current_price`)
) ENGINE=InnoDB;

create table o2o_user(
id int(11) unsigned not null auto_increment primary key,
username varchar(50) not null default '',
password char(32) not null default '',
code varchar(10) not null default '',
last_login_ip varchar(20) not null default '',
last_login_time int(11) unsigned not null default 0,
email varchar(50) not null default '',
mobile varchar(50) not null default '',
status tinyint(1) not null default 0,
create_time int(11) unsigned not null default 0,
update_time int(11) unsigned not null default 0,
UNIQUE key username(`username`),
UNIQUE key email(`email`)
) ENGINE=InnoDB;

create table o2o_featured(
id int(11) unsigned not null auto_increment primary key,
type tinyint(1) unsigned not null default 0,
title varchar(200) not null default '',
image varchar(200) not null default '',
url varchar(200) not null default '',
description varchar(200) not null default '',
status tinyint(1) not null default 0,
create_time int(11) unsigned not null default 0,
update_time int(11) unsigned not null default 0,
listorder int(11) unsigned not null default 0
) ENGINE=InnoDB;

create table o2o_order(
id int(11) unsigned not null auto_increment primary key,
out_trade_no varchar(100) not null default '',
transaction varchar(100) not null default '',
user_id int(11) unsigned not null default 0,
user_name varchar(50) not null default '',
pay_time varchar(20) not null default '',
payment_id tinyint(1) not null default 1,
deal_id int(11) unsigned not null default 0,
deal_count int(11) unsigned not null default 0,
pay_status tinyint(1) not null default 0 COMMENT '支付状态 0:未支付 1:支付成功 2:支付失败 3:其他',
total_price decimal(20,2) not null default '0.00',
pay_amount decimal(20,2) not null default '0.00',
create_time int(11) unsigned not null default 0,
update_time int(11) unsigned not null default 0,
status tinyint(1) not null default 1,
referer varchar(255) not null default '',
UNIQUE key out_trade_no(`out_trade_no`),
key user_id(`user_id`),
key create_time(`create_time`),
key pay_time(`pay_time`)
) ENGINE=InnoDB;