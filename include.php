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
}
function test_hello()
{
  global $zbp;
  // 当网址参数中没有test时跳过接口执行
  // /?test ←← 这种情况取值为空字符串，并不严格等于null
  // var_dump(GetVars("test", "GET"));
  if (GetVars("test", "GET") === null) {
    return;
  }
  $zbp->header .= '<script>alert("hello")</script>';
}
function test_debug()
{
  global $zbp;
  if (GetVars("debug", "GET") === null) {
    return;
  }
  // 这里可以用来测试某些东西
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
  if (!$zbp->HasConfig('test')) {
    $zbp->Config('test')->version = 1;
    $zbp->SaveConfig('test');
  }
}
function UninstallPlugin_test()
{
}
