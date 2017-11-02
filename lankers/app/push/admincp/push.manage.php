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
admincp::head();
?>
<script type="text/javascript">
$(function(){
  <?php if(isset($_GET['pid']) && $_GET['pid']!='-1'){  ?>
  iCMS.select('pid',"<?php echo (int)$_GET['pid'] ; ?>");
  <?php } if($_GET['cid']){  ?>
  iCMS.select('cid',"<?php echo $_GET['cid'] ; ?>");
  <?php } if($_GET['pcid']){  ?>
  iCMS.select('pcid',"<?php echo $_GET['pcid'] ; ?>");
  <?php } if($_GET['orderby']){ ?>
  iCMS.select('orderby',"<?php echo $_GET['orderby'] ; ?>");
  <?php } if($_GET['psub']=="on"){ ?>
  iCMS.checked('#psub');
  <?php } if($_GET['sub']=="on"){ ?>
  iCMS.checked('#sub');
  <?php } if($_GET['nopic']=="on"){ ?>
  iCMS.checked('#nopic');
  <?php } ?>
  $("#<?php echo APP_FORMID;?>").batch({
    mvpcid: function(){
      var select  = $("#pcid").clone().show()
        .removeClass("chosen-select")
        .attr("id",iCMS.random(3));
      $("option:first",select).remove();
      return select;
    }
  });
});
</script>
<style>
hr { border-bottom:none; margin:4px 0px; }
</style>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5>搜索</h5>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <input type="hidden" name="userid" value="<?php echo $_GET['userid'] ; ?>" />
        <div class="input-prepend mb10"> <span class="add-on">推荐属性</span>
          <select name="pid" id="pid" class="span2 chosen-select">
            <option value="-1">所有推荐</option>
            <option value="0">普通推荐[pid='0']</option>
            <?php echo $pid_select = propAdmincp::get("pid") ; ?>
          </select>
        </div>
        <div class="input-prepend input-append mb10"> <span class="add-on">栏目</span>
          <select name="cid" id="cid" class="chosen-select">
            <option value="0">所有栏目</option>
            <?php echo $cid_select = category::priv('cs')->select() ; ?>
          </select>
          <span class="add-on">
          <input type="checkbox" name="sub" id="sub"/>
          子分类 </span> </div>
        <div class="input-prepend input-append"> <span class="add-on">分类</span>
          <select name="pcid" id="pcid" class="chosen-select">
            <option value="0">所有分类</option>
            <?php echo $pcid_select = category::appid($this->appid,'cs')->select() ;?>
          </select>
          <span class="add-on">
          <input type="checkbox" name="psub" id="psub"/>
          子分类 </span> </div>
        <div class="clearfloat"></div>
        <div class="input-prepend input-append"><span class="add-on"><i class="fa fa-calendar"></i></span>
          <input type="text" class="ui-datepicker" name="starttime" value="<?php echo $_GET['starttime'] ; ?>" placeholder="开始时间" />
          <span class="add-on">-</span>
          <input type="text" class="ui-datepicker" name="endtime" value="<?php echo $_GET['endtime'] ; ?>" placeholder="结束时间" />
          <span class="add-on"><i class="fa fa-calendar"></i></span> </div>
        <div class="input-prepend input-append"> <span class="add-on">每页</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
          <span class="add-on">条记录</span> </div>
        <div class="input-prepend input-append"> <span class="add-on">关键字</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> 搜 索</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5>文章列表</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th>排序</th>
              <th class="span6">标题</th>
              <th>栏目</th>
              <th>分类</th>
              <th>编辑</th>
              <th>日期</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $categoryArray  = category::appid(null)->multi_get($rs,'cid');
            $pcategoryArray = category::multi_get($rs,'pcid',$this->appid);
            for($i=0;$i<$_count;$i++){
              $C  = (array)$categoryArray[$rs[$i]['cid']];
              $PC = (array)$pcategoryArray[$rs[$i]['pcid']];
            ?>
            <tr id="id<?php echo $rs[$i]['id'] ; ?>">
              <td><input type="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
              <td class="sortnum"><input type="text" name="sortnum[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo $rs[$i]['sortnum'] ; ?>"/></td>
              <td>1.
                <?php if($rs[$i]['haspic'])echo '<img src="./app/admincp/ui/img/image.gif" align="absmiddle">'?>
                <a href="<?php echo $rs[$i]['url']; ?>" class="noneline" target="_blank"><?php echo $rs[$i]['title'] ; ?></a>
                <?php if($rs[$i]['title2']){?>
                <hr />
                2.
                <?php if($rs[$i]['pic2'])echo '<img src="./app/admincp/ui/img/image.gif" align="absmiddle">'?>
                <a href="<?php echo $rs[$i]['url2']; ?>" class="noneline" target="_blank"><?php echo $rs[$i]['title2'] ; ?></a>
                <?php }?>
                <?php if($rs[$i]['title3']){?>
                <hr />
                3.
                <?php if($rs[$i]['pic3'])echo '<img src="./app/admincp/ui/img/image.gif" align="absmiddle">'?>
                <a href="<?php echo $rs[$i]['url3']; ?>" class="noneline" target="_blank"><?php echo $rs[$i]['title3'] ; ?></a>
                <?php }?></td>
              <td><a href="<?php echo APP_DOURI; ?>&cid=<?php echo $rs[$i]['cid'] ; ?><?php echo $uri ; ?>"><?php echo $C['name'] ; ?></a></td>
              <td><a href="<?php echo APP_DOURI; ?>&pcid=<?php echo $rs[$i]['pcid'] ; ?><?php echo $uri ; ?>"><?php echo $PC['name'] ; ?></a></td>
              <td><a href="<?php echo APP_DOURI; ?>&userid=<?php echo $rs[$i]['userid'] ; ?><?php echo $uri ; ?>"><?php echo $rs[$i]['editor'] ; ?></a></td>
              <td><?php echo get_date($rs[$i]['addtime'],'Y-m-d H:i');?></td>
              <td><a href="<?php echo $rs[$i]['url']; ?>" class="btn btn-small" target="_blank"><i class="fa fa-eye"></i> 查看</a> <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> 编辑</a> <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $rs[$i]['id'] ; ?>" target="iPHP_FRAME" class="del btn btn-small" title='移动此推荐到回收站' /><i class="fa fa-trash-o"></i> 删除</a></td>
            </tr>
            <?php }  ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="8"><div class="pagination pagination-right" style="float:right;"><?php echo iUI::$pagenav ; ?></div>
                <div class="input-prepend input-append mt20"> <span class="add-on">全选
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> 批 量 操 作 </a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                    <li><a data-toggle="batch" data-action="move"><i class="fa fa-fighter-jet"></i> 移动栏目</a></li>
                    <li><a data-toggle="batch" data-action="mvpcid"><i class="fa fa-fighter-jet"></i> 移动分类</a></li>
                    <li><a data-toggle="batch" data-action="prop"><i class="fa fa-puzzle-piece"></i> 设置属性</a></li>
                    <li class="divider"></li>
                    <li><a data-toggle="batch" data-action="dels"><i class="fa fa-trash-o"></i> 删除</a></li>
                    </ul>
                  </div>
                </div></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
