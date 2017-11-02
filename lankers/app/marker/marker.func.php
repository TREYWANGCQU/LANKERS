<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
class markerFunc{

    public static function marker_html($vars){
        $marker = self::marker_array($vars);
        echo $marker['data'];
    }

    public static function marker_array($vars){
        $where_sql  = "WHERE `status`='1'";
        $vars['key'] OR iUI::warning('iCMS&#x3a;marker&#x3a;html 标签出错! 缺少"key"属性或"key"值为空.');
        if(isset($vars['cid']) && $vars['cid']!=''){
            $where_sql.= " AND `cid`='{$vars['cid']}'";
        }
        if(isset($vars['pid']) && $vars['pid']!=''){
            $where_sql.= " AND `pid`='{$vars['pid']}'";
        }
        if(isset($vars['key']) && $vars['key']!=''){
            $where_sql.= " AND `key`='{$vars['key']}'";
        }
        if(isset($vars['id']) && $vars['id']!=''){
            $where_sql.= " AND `id`='{$vars['id']}'";
        }
        $marker = (array)iDB::row("SELECT * FROM `#iCMS@__marker` {$where_sql}",ARRAY_A);
        return $marker;
    }
}

