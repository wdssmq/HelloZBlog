<?php
#注册杒件
RegisterPlugin("HelloZBlog", "ActivePlugin_HelloZBlog");

// echo $xnxf;

function ActivePlugin_HelloZBlog()
{
  // 代码片段 Add_Filter_Plugin
  Add_Filter_Plugin('Filter_Plugin_Index_Begin', 'HelloZBlog_hello');
  // 同一个函数可以供多个接口调用↓↓↓
  // Add_Filter_Plugin('Filter_Plugin_Feed_Begin', 'HelloZBlog_hello');
  // ----
  Add_Filter_Plugin('Filter_Plugin_Index_Begin', 'HelloZBlog_debug');
  // 当重建模板时执行↓
  Add_Filter_Plugin('Filter_Plugin_Zbp_BuildTemplate', 'HelloZBlog_SetTPL');
}
function HelloZBlog_hello()
{
  global $zbp;
  // 当网址参数中没有HelloZBlog时跳过接口执行
  // /?HelloZBlog ←← 这种情况取值为空字符串，并不严格等于null
  // var_dump(GetVars("HelloZBlog", "GET"));
  $HelloZBlog = GetVars("HelloZBlog", "GET");
  if ($HelloZBlog === null) {
    return;
  } else if ($HelloZBlog == "display") {
    $zbp->template->SetTags('path', str_replace($zbp->path, "/", HelloZBlog_Path("tpl-display")));
    $zbp->template->SetTags('wev', "20201206213158");
    $zbp->template->SetTemplate('plugin_HelloZBlog_display');
    $zbp->template->Display();
    die();
  }
  // $zbp->Config('HelloZBlog')->str 的值在InstallPlugin_HelloZBlog()中初始化，并且可以在main.php中编辑
  $zbp->header .= "<script>alert(\"hello {$zbp->Config('HelloZBlog')->str}\")</script>";
}
function HelloZBlog_debug()
{
  global $zbp;
  if (GetVars("debug", "GET") === null) {
    return;
  }
  // 这里可以用来测试某些东西
}
// 重建模板的接口函数
function HelloZBlog_SetTPL(&$templates)
{
  global $zbp;
  // 创建供插件自身调用的模板
  $tplFile = HelloZBlog_Path("tpl-display");
  $templates['plugin_HelloZBlog_display'] = file_get_contents($tplFile);
  $templates['plugin_HelloZBlog_other'] = "这是另一个模板的内容";
  // 也可以这样向模板内添加内容↓↓
  $templates['header'] = str_replace('{$header}', '{$header}' . "<!--要添加的内容-->", $templates['header']);;
  $templates['footer'] = str_replace('{$footer}', '{$footer}' . "<script>console.log(\"hello {$zbp->Config('HelloZBlog')->str}\")</script><!--By Plugin HelloZBlog-->\n", $templates['footer']);
  // 适用于明确可以不经判断直接引入的内容,比如由插件提供的回到顶部等，理论上更省资源，因为线上环境的模板重建是有间隔的
}
/**
 * 一个获取插件内文件路径的方法
 * 这个函数可以通过代码片段功能快速生成
 *
 * @param string $file 要获取的文件路径或标识别名
 * @param string $t    path|host path用于文件读写等操作，host用于获取网址路径
 * @return void
 */
// 代码片段 fnpath
function HelloZBlog_Path($file, $t = 'path')
{
  global $zbp;
  $result = $zbp->$t . 'zb_users/plugin/HelloZBlog/';
  switch ($file) {
    case 'doc-html':
      return $result . 'docs/README.html';
      break;
    case 'tpl-display':
      return $result . 'tpl/display.php';
      break;
    case 'usr':
      return $result . 'usr/';
      break;
    case 'var':
      return $result . 'var/';
      break;
    case 'main':
      return $result . 'main.php';
      break;
    default:
      return $result . $file;
  }
}
function InstallPlugin_HelloZBlog()
{
  global $zbp;
  // 代码片段 init_cfg
  // 创建并初始化配置项
  if (!$zbp->HasConfig('HelloZBlog')) {
    $zbp->Config('HelloZBlog')->version = 1;
    $zbp->Config('HelloZBlog')->str = $zbp->name . "丨后台配置字段";
    $zbp->SaveConfig('HelloZBlog');
  }
  // // 判断版本号追加内容之类的
  // if ($zbp->Config('HelloZBlog')->version <= 2){
  //   $zbp->Config('HelloZBlog')->version = 2;
  //   // 其他项目或操作
  //   $zbp->SaveConfig('HelloZBlog');
  // }
  // 创建自定义模块
  $mod = new Module();
  $mod->Type = 'div'; // 可选 ul
  $mod->Name = "[插件开发演示]自定义模块";
  $mod->FileName = "HelloZBlogMod"; // 插件id+功能区分，比如热门文章HelloZBlogHot
  $mod->HtmlID = "{$mod->Type}{$mod->FileName}"; // 作为HTML选择器id，保证页面唯一+有意义就行
  $mod->Source = "HelloZBlog"; // 模块来源一般使用当前主题/插件id
  $mod->Content = "插件创建模块演示"; // 内容
  $mod->Save();
  // 更科学的写法是先判断是否存在同名的模块
}
function UninstallPlugin_HelloZBlog()
{
}
