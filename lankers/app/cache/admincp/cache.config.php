<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
defined('iPHP') OR exit('What are you doing?');
?>
<div class="input-prepend"> <span class="add-on">缓存引擎</span>
  <select name="config[cache][engine]" id="cache_engine" class="chosen-select">
    <option value="file">文件缓存 FileCache</option>
    <option value="memcached">分布式缓存 memcached</option>
    <option value="redis">分布式缓存 Redis</option>
  </select>
</div>
<script>$(function(){iCMS.select('cache_engine',"<?php echo $config['cache']['engine'] ; ?>");});</script>
<span class="help-inline">Memcache,Redis 需要服务器支持,如果不清楚请询问管理员,iCMS推荐使用Redis</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend"> <span class="add-on">缓存配置</span>
  <textarea name="config[cache][host]" id="cache_host" class="span6" style="height: 150px;"><?php echo $config['cache']['host'] ; ?></textarea>
</div>
<span class="help-inline">
文件缓存目录:文件层级(data:1) 默认为空<hr />
memcached服务器IP:每行一个,带端口. <br />
例:127.0.0.1:11211<br />
127.0.0.2:11211<hr />
Redis UNIX SOCK<br />
unix:///tmp/redis.sock@db:1 <br />
127.0.0.1:6379@db:1</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend input-append"> <span class="add-on">缓存时间</span>
  <input type="text" name="config[cache][time]" class="span1" id="cache_time" value="<?php echo $config['cache']['time'] ; ?>"/>
  <span class="add-on" style="width:24px;">秒</span>
</div>
<div class="clearfloat mb10"></div>
<div class="input-prepend"> <span class="add-on">数据压缩</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[cache][compress]" id="cache_compress" <?php echo $config['cache']['compress']?'checked':''; ?>/>
  </div>
</div>
<hr />
<div class="clearfloat mb10"></div>
<div class="input-prepend input-append">
  <span class="add-on">分页缓存</span>
  <input type="text" name="config[cache][page_total]" class="span1" id="page_total" value="<?php echo $config['cache']['page_total']?:$config['cache']['time']; ?>"/>
  <span class="add-on" style="width:24px;">秒</span>
</div>
<span class="help-inline">设置分页总数缓存时间,设置此项分页性能将会有极大的提高.</span>
