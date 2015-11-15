create table if not exists coupons (
    id int(11) not null auto_increment primary key,
    code varchar(10) not null,
    `timestamp` timestamp default current_timestamp(),
    status enum('deleted', 'inactive', 'active', 'used') default 'active',
    unique(code)
);