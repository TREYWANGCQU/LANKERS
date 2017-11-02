<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/

class tag {
    public static $appid      = '1';
    public static $field      = 'tags';
    public static $remove     = true;
    public static $add_status = '1';

	public static function data($vars,$limit=0){
		// $sql      = $fv ? "where `$field`='$fv'":'';
        $sql = iSQL::where($vars);
		$limitSQL = $limit ? "LIMIT $limit ":'';
	    return iDB::all("SELECT * FROM `#iCMS@__tag` {$sql} order by id DESC {$limitSQL}");
	}
	public static function cache($value=0,$field='id'){
        return;
		$rs     = self::data(array($field=>$value));
		$_count = count($rs);
	    for($i=0;$i<$_count;$i++) {
			$C              = categoryApp::get_cahce_cid($rs[$i]['cid']);
			$TC             = categoryApp::get_cahce_cid($rs[$i]['tcid']);
			$rs[$i]['iurl'] = iURL::get('tag',array($rs[$i],$C,$TC));
			$rs[$i]['url']  = $rs[$i]['iurl']->href;
			$tkey           = self::tkey($rs[$i]['cid']);
	        iCache::set($tkey,$rs[$i],0);
	    }
	}
    public static function tkey($cid){
		$ncid = abs(intval($cid));
		$ncid = sprintf("%08d", $ncid);
		$dir1 = substr($ncid, 0, 2);
		$dir2 = substr($ncid, 2, 3);
		$tkey = $dir1.'/'.$dir2.'/'.$cid;
        return 'iCMS/tag/'.$tkey;
    }

	public static function get_cache($tid){
		$tkey	= self::tkey($tid);
		return iCache::get($tkey);
	}

    public static function del_cache($tid) {
		$ids = implode(',',(array)$tid);
		iDB::query("DELETE FROM `#iCMS@__tag` WHERE `id` in ($ids) ");
		$c   = count($tid);
        for($i=0;$i<$c;$i++) {
			$tkey = self::tkey($tid[$i]);
			iCache::delete($tkey);
        }
    }
    public static function field($field){
        $self = new self();
        $self::$field = $field;
        return $self;
    }
	public static function add($tags,$uid="0",$iid="0",$cid='0',$tcid='0') {
		$a        = explode(',',$tags);
		$c        = count($a);
		$tag_array = array();
	    for($i=0;$i<$c;$i++) {
	        $tag_array[$i] = self::update($a[$i],$uid,$iid,$cid,$tcid);
	    }
	    return implode(',', (array)$tag_array);
	}
	public static function update($name,$uid="0",$iid="0",$cid='0',$tcid='0') {
	    if(empty($name)) return;
        $name = trim($name);
        $name = trim($name,"\0\n\r\t\x0B");
	    $name = htmlspecialchars_decode($name);
	    $name = preg_replace('/<[\/\!]*?[^<>]*?>/is','',$name);
	    $tid = iDB::value("SELECT `id` FROM `#iCMS@__tag` WHERE `name`='$name'");
	    if($tid) {
            $mapid = iDB::value("
                SELECT `id` FROM `#iCMS@__tag_map`
                WHERE `iid`='$iid'
                AND `node`='$tid'
                AND `appid`='".self::$appid."'
                AND `field`='".self::$field."'
            ");

            empty($mapid) && iDB::query("
                UPDATE `#iCMS@__tag`
                SET `count`=count+1,
                    `pubdate`='".time()."'
                WHERE `id`='$tid'
            ");
	    }else {
			$tkey   = iPinyin::get($name,iCMS::$config['tag']['tkey']);
			$data   = compact(array(
                'uid', 'cid', 'tcid', 'pid', 'tkey', 'name', 'field',
                'seotitle', 'subtitle', 'keywords', 'description',
                'haspic', 'pic', 'url', 'related', 'count', 'weight', 'tpl',
                'sortnum', 'pubdate', 'postime', 'status'
            ));
            $data['pid']     = '0';
            $data['count']   = '1';
            $data['weight']  = '0';
            $data['sortnum'] = '0';
            $data['pubdate'] = time();
            $data['postime'] = $data['pubdate'];
            $data['status']  = self::$add_status;
            $data['field']   = self::$field;

			$tid = iDB::insert('tag',$data);
	    }
        iMap::init('tag',self::$appid,self::$field);
        iMap::add($tid,$iid);
	    return $name;
	}
	public static function diff($Ntags,$Otags,$uid="0",$iid="0",$cid='0',$tcid='0') {
		$N        = explode(',', $Ntags);
		$O        = explode(',', $Otags);
		$diff     = array_diff_values($N,$O);
		$tag_array = array();
	    foreach((array)$N AS $i=>$tag) {//新增
            $tag_array[$i] = self::update($tag,$uid,$iid,$cid,$tcid);
		}
        iMap::init('tag',self::$appid,self::$field);

	    foreach((array)$diff['-'] AS $tag) {//减少
	        $ot	= iDB::row("
                SELECT `id`,`count`
                FROM `#iCMS@__tag`
                WHERE `name`='$tag' LIMIT 1;
            ");

	        if($ot->count<=1) {
	            iDB::query("DELETE FROM `#iCMS@__tag` WHERE `name`='$tag'");
	        }else {
	            iDB::query("
                    UPDATE `#iCMS@__tag`
                    SET  `count`=count-1,`pubdate`='".time()."'
                    WHERE `name`='$tag' and `count`>0
                ");
	        }
            iMap::diff('',$ot->id,$iid);
	   }
	   return implode(',', (array)$tag_array);
	}
	public static function del($tags,$field='name',$iid=0){
	    $tag_array	= explode(",",$tags);
	    $iid && $sql="AND `iid`='$iid'";
	    foreach($tag_array AS $k=>$v) {
	    	$tag	= iDB::row("SELECT * FROM `#iCMS@__tag` WHERE `$field`='$v' LIMIT 1;");
	    	$tRS	= iDB::all("
                SELECT `iid` FROM `#iCMS@__tag_map`
                WHERE `node`='$tag->id'
                AND `appid`='".self::$appid."'
                AND `field`='".self::$field."' {$sql}
            ");
            $ids = iSQL::values($tRS,'iid');
            if($ids){
                $app = apps::get_table(self::$appid);
                iDB::query("
                    UPDATE `".$app['table']."` SET
                    `".self::$field."`= REPLACE(".self::$field.", '$tag->name,',''),
                    `".self::$field."`= REPLACE(".self::$field.", ',$tag->name','')
                    WHERE id IN($ids)
                ");
            }
            self::$remove && iDB::query("DELETE FROM `#iCMS@__tag`  WHERE `$field`='$v'");
            iDB::query("
                DELETE FROM
                `#iCMS@__tag_map`
                WHERE `node`='$tag->id'
                AND `appid`='".self::$appid."'
                AND `field`='".self::$field."' {$sql}
            ");
            $ckey = self::tkey($tag->cid);
            // iCache::delete($ckey);
	    }
	}
    public function merge($tocid,$cid){
        iDB::query("UPDATE `#iCMS@__tag` SET `cid` ='$tocid' WHERE `cid` ='$cid'");
    }
}
