LANKERS
===================================
 http://lanunion.cqu.edu.cn/<br>
CQU-LANKERS<br>
重庆大学蓝盟，服务全体师生<br>
重庆大学蓝盟（英文名lanunion）是在重庆大学信息与网络中心指导下成立的校级学生社团，是一个为重庆大学A、B、C、D区师生免费提供计算机系统、软件与网络维护服务的志愿者组织。“蓝客”即蓝盟的正式成员，他们利用自己的电脑知识无偿为大家进行计算机系统、软件与网络维护。
# 配制说明
## apache等web服务器设置目录
## mysql设置数据库目录
地址：*/LANKERS/data*
## php框架配制
地址：*/LANKERS/lankers/config.php* <br>
*** php

    //---------------数据库配置------------------
    define('iPHP_DB_TYPE','mysql');//数据库类型 mysql sqlite (SQLite3)
    define('iPHP_DB_HOST','localhost');// 服务器名或服务器ip,一般为localhost
    define('iPHP_DB_PORT','3306'); //数据库端口
    define('iPHP_DB_USER','root');// 数据库用户
    define('iPHP_DB_PASSWORD','mys*****A');//数据库密码
    define('iPHP_DB_NAME','test'); //数据库名
    define('iPHP_DB_PREFIX','i_');// 表名前缀, 同一数据库安装多个请修改此处

// php
<br>
# 实现机制
    php框架后端：` iCMS: ` *https://github.com/TREYWANGCQU/iCMS*
    
    前端：` bootstrap: `*https://github.com/TREYWANGCQU/bootstrap*

        此致

