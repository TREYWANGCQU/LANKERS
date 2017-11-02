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
<div class="iCMS-container">
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
    <h5><?php echo empty($this->id)?'添加':'修改' ; ?>标记</b></h5>
    <ul class="nav nav-tabs" id="-add-tab">
      <li class="active"><a href="#-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> 基本信息</a></li>
      <li><a href="#-add-custom" data-toggle="tab"><i class="fa fa-wrench"></i> 自定义</a></li>
    </ul>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
      <input name="id" type="hidden" value="<?php echo $this->id ; ?>" />
      <div class="tab-content">
        <div id="-add-base" class="tab-pane active">
          <div class="input-prepend">
            <span class="add-on">所属栏目</span>
            <select name="cid" id="cid" class="span3 chosen-select">
              <option value="0"> ==== 暂无所属栏目 ==== </option>
              <?php echo category::priv('ca')->select($rs['cid'],0,1,true);?>
            </select>
          </div>
          <span class="help-inline">本标记所属的栏目</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">标记属性</span>
            <select name="pid" id="pid" class="chosen-select span3" data-placeholder="请选择标记属性...">
              <option value="0">普通标记[pid='0']</option>
              <?php echo propAdmincp::get("pid") ; ?>
            </select>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">标记名称</span>
            <input type="text" name="name" class="span4" id="name" value="<?php echo $rs['name'];?>"/>
          </div>
          <span class="help-inline">可填写中文</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">标记key值</span>
            <input type="text" name="key" class="span4" id="key" value="<?php echo $rs['key'];?>"/>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">标记状态</span>
            <div class="switch" data-on-label="启用" data-off-label="禁用">
              <input type="checkbox" data-type="switch" name="status" id="status" <?php echo $rs['status']?'checked':''; ?>/>
            </div>
          </div>
          <div class="clearfloat mb10"></div>
          <script type="text/javascript" charset="utf-8" src="./app/admincp/ui/iCMS.ueditor.js"></script>
          <script type="text/javascript" charset="utf-8" src="./app/admincp/ui/ueditor/ueditor.all.min.js"></script>
          <a id="cleanupEditor-btn" class="btn btn-inverse hide" href="javascript:iCMS.editor.cleanup();"><i class="fa fa-magic"></i> 自动排版</a>
          <a id="createEditor-btn" class="btn btn-success" href="javascript:createEditor();"><i class="fa fa-check"></i> 使用编辑器</a>
          <a id="deleteEditor-btn" class="btn btn-inverse hide" href="javascript:deleteEditor();"><i class="fa fa-times"></i> 关闭编辑器</a>
          <div class="clearfix mt10"></div>
          <textarea type="text/plain" id="editor-body" name="data"><?php echo $rs['data'] ; ?></textarea>
          <div class="clearfix mt10"></div>
          <div class="alert alert-block">
            <h4>注意事项</h4>
            大文本段,支持HTML,至于干嘛用,我也不知道...你爱怎么用就怎么用!!
            <br />
            注:编辑器会把div转换成p
          </div>
        </div>
        <div id="-add-custom" class="tab-pane">
          <?php echo former::layout();?>
        </div>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 添加</button>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
$(function(){
  iCMS.select('cid',"<?php echo $rs['cid'] ; ?>");
  iCMS.select('pid',"<?php echo $rs['pid']?$rs['pid']:0 ; ?>");
});

function deleteEditor() {
    iCMS.editor.destroy();
    $("#cleanupEditor-btn").hide();
    $("#createEditor-btn").show();
    $("#deleteEditor-btn").hide();
}
function createEditor() {
    iCMS.editor.create('editor-body');
    $("#cleanupEditor-btn").show();
    $("#createEditor-btn").hide();
    $("#deleteEditor-btn").show();
}
</script>
<?php admincp::foot();?>
