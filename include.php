<?php
#注册杒件
RegisterPlugin("test", "ActivePlugin_test");

// echo $xnxf;

function ActivePlugin_test()
{
  Add_Filter_Plugin('Filter_Plugin_Index_Begin', 'test_hello');
  // 同一个函数可以供多个接口调用↓↓↓
  // Add_Filter_Plugin('Filter_Plugin_Feed_Begin', 'test_hello');
  // ----
  Add_Filter_Plugin('Filter_Plugin_Index_Begin', 'test_debug');
  // 当重建模板时执行↓
  Add_Filter_Plugin('Filter_Plugin_Zbp_BuildTemplate', 'test_SetTPL');
}
function test_hello()
{
  global $zbp;
  // 当网址参数中没有test时跳过接口执行
  // /?test ←← 这种情况取值为空字符串，并不严格等于null
  // var_dump(GetVars("test", "GET"));
  $test = GetVars("test", "GET");
  if ($test === null) {
    return;
  } else if ($test == "display") {
    $zbp->template->SetTags('path', str_replace($zbp->path, "/", test_Path("tpl-display")));
    $zbp->template->SetTags('wev', "20201206213158");
    $zbp->template->SetTemplate('plugin_test_display');
    $zbp->template->Display();
    die();
  }
  // $zbp->Config('test')->str 的值在InstallPlugin_test()中初始化，并且可以在main.php中编辑
  $zbp->header .= "<script>alert(\"hello {$zbp->Config('test')->str}\")</script>";
}
function test_debug()
{
  global $zbp;
  if (GetVars("debug", "GET") === null) {
    return;
  }
  // 这里可以用来测试某些东西
}
// 重建模板的接口函数
function test_SetTPL(&$templates)
{
  global $zbp;
  // 创建供插件自身调用的模板
  $tplFile = test_Path("tpl-display");
  $templates['plugin_test_display'] = file_get_contents($tplFile);
  $templates['plugin_test_other'] = "这是另一个模板的内容";
  // 也可以这样向模板内添加内容↓↓
  $templates['header'] = str_replace('{$header}', '{$header}' . "<!--要添加的内容-->", $templates['header']);;
  $templates['footer'] = str_replace('{$footer}', '{$footer}' . "<script>console.log(\"hello {$zbp->Config('test')->str}\")</script><!--By Plugin Test-->\n", $templates['footer']);
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
function test_Path($file, $t = 'path')
{
  global $zbp;
  $result = $zbp->$t . 'zb_users/plugin/test/';
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
function InstallPlugin_test()
{
  global $zbp;
  // 创建并初始化配置项
  if (!$zbp->HasConfig('test')) {
    $zbp->Config('test')->version = 1;
    $zbp->Config('test')->str = $zbp->name . "后台配置字段";
    $zbp->SaveConfig('test');
  }
  // 创建自定义模块
  $mod = new Module();
  $mod->Type = 'div'; // 可选 ul
  $mod->Name = "[插件开发演示]自定义模块";
  $mod->FileName = "testMod"; // 插件id+功能区分，比如热门文章testHot
  $mod->HtmlID = "{$mod->Type}{$mod->FileName}"; // 作为HTML选择器id，保证页面唯一+有意义就行
  $mod->Source = "test"; // 模块来源一般使用当前主题/插件id
  $mod->Content = "插件创建模块演示"; // 内容
  $mod->Save();
  // 更科学的写法是先判断是否存在同名的模块
}
function UninstallPlugin_test()
{
}
