CREATE TABLE `manghui_query_datas`  (
  `serverid` varchar(255) primary key,
  `data` mediumtext,
  `updatetime` bigint(10)
);
CREATE TABLE `manghui_query_users`  (
  `id` varchar(255),
  `key` varchar(255)
);

manghui_ 为前缀 可以更改