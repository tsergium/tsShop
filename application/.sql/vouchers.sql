create table if not exists vouchers (
    id int(11) not null auto_increment primary key,
    isProcentual boolean not null,
    `value` decimal (6, 2),
    `timestamp` timestamp default current_timestamp(),
    status enum('deleted', 'inactive', 'active') default 'active',
    unique(isProcentual, `value`, status)
);