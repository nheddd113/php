create table uqee_admin(
uid int not null auto_increment,
username varchar(30) not null ,
password varchar(32) not null,
createtime timestamp not null default now(),
primary key uid,
unique index uid_idx(uid) using btree
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;