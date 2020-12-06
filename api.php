<?php
// 引入必要的系统文件
require '../../../zb_system/function/c_system_base.php';
// 初始化
$zbp->Load();
// 判断当前所属性插件是否启用
if (!$zbp->CheckPlugin('HelloZBlog')) {
  $zbp->ShowError(48);
  die();
}
// 根据功能可能需要鉴权，本示例为任何人知道地址就能访问并查看本页的运行结果
// http://127.0.0.1/zb_users/plugin/HelloZBlog/api.php

// 在访问中传入参数
// http://127.0.0.1/zb_users/plugin/HelloZBlog/api.php?id=1;
$id = GetVars('id', 'GET');
if (!empty($id)) {
  $post = GetPost($id);

  // debug
  // ob_clean();
  echo __LINE__ . ":<br>\n";
  var_dump($post->Title);
  echo "<br><br>\n\n";
  // die();
  // debug
}
// 取最新10篇文章
$articles = GetList(10);
$pagebar = new Pagebar($zbp->option['ZC_INDEX_REGEX'], true, true);
// 要应用主题模板必须指定pagebar标签，省略了很多属性值，所以结果会是第0页
$zbp->template->SetTags('articles', $articles);
$zbp->template->SetTags('pagebar', $pagebar);
$zbp->template->SetTemplate('index');
$zbp->template->Display();
