create table uqee_iplist (
id int not null auto_increment primary key,
mainip varchar(20) not null default '' COMMENT '电信IP',
mainmask varchar(20) not null default '' COMMENT '电信掩码',
maingw varchar(20) not null default '' COMMENT '电信网关',
subip varchar(20) not null default '' COMMENT '双线第二IP',
submask varchar(20) not null default '' COMMENT '双线第二IP掩码',
state tinyint not null default 0 COMMENT '0:未使用,1:已使用',
houid int not null ,
createtime TIMESTAMP  not null default now()
)engine=myisam;  

create table uqee_ops (
id int not null auto_increment PRIMARY KEY,
seq int not null DEFAULT 0 COMMENT '后台传过来的运营商ID',
name VARCHAR(30) not null DEFAULT '',
gameid int not null COMMENT '游戏ID'
)engine=myisam;


create TABLE uqee_log(
id int not null auto_increment PRIMARY KEY,
hostid VARCHAR(30) not null DEFAULT '' COMMENT '资产编码',
chghostid VARCHAR(30) not null DEFAULT '' COMMENT '交换的服务器id',
hostdbid  int not NULL COMMENT '主机数据ID',
serip VARCHAR(20) NOT NULL DEFAULT '' COMMENT '服务器IP',
content VARCHAR(500) not null default '' COMMENT '日志内容',
handler VARCHAR(20) not NULL DEFAULT '',
logtime TIMESTAMP not null default now()
)engine=myisam; 

CREATE TABLE uqee_user(
id int not NULL auto_increment primary key ,
loginname VARCHAR(30) not null DEFAULT '',
password VARCHAR(32) not null DEFAULT '',
realname varchar(30) not null default '',
lastloginip VARCHAR(20) not null DEFAULT '',
lastlogintime int(10) not null default 0,
level int not null DEFAULT 1,
createtime TIMESTAMP not null default now()
)engine=myisam;

CREATE TABLE uqee_house(
id int NOT NULL auto_increment PRIMARY KEY,
houname VARCHAR(60) not NULL DEFAULT '',
company VARCHAR(60) not NULL DEFAULT '',
address VARCHAR(200) not null DEFAULT '',
telphone VARCHAR(15) not null DEFAULT '',
linkqq VARCHAR(15) not null DEFAULT '',
contact VARCHAR(30) not null DEFAULT '',
remark VARCHAR(100) not null DEFAULT '',
createtime TIMESTAMP not null DEFAULT now(),
bandwidth int(5) not null default 0,
price int(5) not null default 0,
changetime int(10) not null DEFAULT 0
)engine=myisam;

CREATE TABLE uqee_seat(
id int not null auto_increment PRIMARY KEY,
cupid int not null default 0,
seatid int not null default 0,
state tinyint default 0 COMMENT '0:未使用,1:已使用',
createtime TIMESTAMP not null default now(),
changetime int(10) not null default 0
)engine=myisam;

CREATE TABLE uqee_cupboard(
id int not null auto_increment PRIMARY KEY,
cupname VARCHAR(50) not null default 0 COMMENT '机柜名',
houid int not null default 0 COMMENT '机房ID',
seatnum int not null default 0 COMMENT '总有多少个机位',
createtime TIMESTAMP not NULL DEFAULT now(),
changetime int(10) not null default 0,
price int(10) not null default 0,
remark VARCHAR(100) not null default ''
)engine=myisam;

CREATE TABLE uqee_hostlist(
id int not null auto_increment PRIMARY KEY,
sertag VARCHAR(20) not null default '' COMMENT 'DELL服务编码',
houid int not null DEFAULT 0 COMMENT '机房ID',
cupid int not null DEFAULT 0 COMMENT '机柜ID',
seatid int not null DEFAULT 0 COMMENT '机位',
hostid VARCHAR(30) not null DEFAULT '' COMMENT '资产编码',
mainip VARCHAR(20) not null DEFAULT '',
subip VARCHAR(20) not null DEFAULT '',
innip VARCHAR(20) NOT NULL DEFAULT '',
cpu VARCHAR(20) NOT NULL DEFAULT '',
mem VARCHAR(20) not NULL DEFAULT '',
disk VARCHAR(20) not null DEFAULT '',
system VARCHAR(20) not null default '', 
status tinyint not null DEFAULT 0 COMMENT '0下架,1闲置,2上架',  
hostype tinyint not null default 0 COMMENT '0未运营,1申请,2运营',
owner VARCHAR(20) not null DEFAULT '' COMMENT '运营商ID',
ishost tinyint not null default 0 COMMENT '0:服务器,1:宿主机,2:虚拟机,3:数据中心',
parentid int not null default 0 COMMENT '宿主机ID',
ismanager tinyint not null default 0 COMMENT '0非托管,1托管',
gameid int not null default 0,
pretime VARCHAR(30) not null DEFAULT '' COMMENT '预计开服时间',
starttime VARCHAR(30) not null default '',
changetime int(10) not null default 0,
remark VARCHAR(100) not null default '',
createtime TIMESTAMP  not null default now(),
templateid	int	not null default 0
)engine=myisam;

CREATE TABLE uqee_game(
id int not null auto_increment PRIMARY KEY,
name VARCHAR(20) not null DEFAULT '' comment '' ,
alias VARCHAR(20) not null DEFAULT '',
addtime TIMESTAMP not null default now(),
handler VARCHAR(20) not null DEFAULT '' COMMENT '填加者'
)engine=myisam;

CREATE TABLE uqee_hostamount(
time varchar(20) not null primary key,
data text not null,
updatetime TIMESTAMP  not null default now()
)engine=myisam;

create table uqee_hosttemplate(
id int not null auto_increment primary key,
name varchar(50) not null,
cpu varchar(20) not null default '' comment '模板CUP',
disk int(5) not null ,
mem int(5) not null,
price int(10) not null default 18000,
one int(5) not null default 45,
two int(5) not null default 30,
three int(5) not null default 25,
other int(5) not null default 0
)engine=myisam;

CREATE TABLE uqee_monitor(
id int not null auto_increment primary key,
name varchar(50) not null default '' comment '监控名称',
nagios varchar(100) not null default '',
cacti varchar(100) not null default '',
createtime int(10) not null default 0
)engine=myisam;

CREATE TABLE uqee_roles(
id int not null auto_increment primary key,
rolename varchar(30) not null default '',
createtime timestamp not null default now()
)engine=myisam;

create table uqee_user_roles(
userid int not null,
roleid int not null
)engine=myisam;

CREATE TABLE uqee_session (
session_id varchar(255) NOT NULL,
session_expire int(11) NOT NULL,
session_data blob,
UNIQUE KEY `session_id` (`session_id`)
);