<?php
#注册杒件
RegisterPlugin("HelloZBlog", "ActivePlugin_HelloZBlog");

// echo $xnxf;

function ActivePlugin_HelloZBlog()
{
  // 代码片段 Add_Filter_Plugin
  Add_Filter_Plugin('Filter_Plugin_Index_Begin', 'HelloZBlog_hello');
  Add_Filter_Plugin('Filter_Plugin_Index_Begin', 'HelloZBlog_display');

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
  $HelloZBlog = GetVars("HelloZBlog", "GET");
  // /?HelloZBlog ←← 这种情况取值为空字符串，并不严格等于null
  // var_dump($HelloZBlog);
  // 当网址参数中没有 HelloZBlog 时跳过接口执行
  if ($HelloZBlog === null) {
    return;
  }

  // 代码片段 zbp-header
  // 代码片段 zbp-footer
  // $zbp->Config('HelloZBlog')->str 的值在InstallPlugin_HelloZBlog()中初始化，并且可以在main.php中编辑
  $str = $zbp->Config('HelloZBlog')->str;
  $title = $zbp->template->GetTags('title');
  $zbp->footer .= "<script>alert(\"hello {$str}\\n初始 title ${title}\")</script>";

  // 理论上只要能访问到 $zbp 并且 $zbp->template->Display() 被调用前，通过下边方法向前台传递或更新「模板变量」
  $zbp->template->SetTags('HelloTag', "2021年9月9日添加此部分");
  // 前台模板中可使用「{$HelloTag}」输出这个值
  // 上边 $title 获取到的值是 Good Luck To You!，因为当前挂的接口比较靠前，拿到的是初始值，后续流程才会更新

  // 上边 $zbp->footer 对应的是 {$footer}，同样还有 {$header} 都是可以单独访问的特例

  // 模板标签
  // https://docs.zblogcn.com/php/#/books/dev-app-theme?id=%e6%a8%a1%e6%9d%bf%e6%a0%87%e7%ad%be

  // 「折腾」Z-BlogPHP 模板机制讲解「简易版」_电脑网络_沉冰浮水
  // https://www.wdssmq.com/post/20201026266.html
}

function HelloZBlog_display()
{
  global $zbp;
  $HelloZBlog = GetVars("HelloZBlog", "GET");
  if ($HelloZBlog === "display") {
    // 拦截掉整个输出，改用指定模板；
    $zbp->template->SetTags('path', str_replace($zbp->path, "/", HelloZBlog_Path("tpl-display")));
    $zbp->template->SetTags('wev', "20201206213158");
    $zbp->template->SetTemplate('plugin_HelloZBlog_display');
    $zbp->template->Display();
    die();
  }
}

function HelloZBlog_debug()
{
  global $zbp;
  if (GetVars("debug", "GET") === null) {
    return;
  }

  // 这里可以用来测试某些东西

  if (1 == 2) {

    // 代码片段 ["log", "v_log", "var_dump", "print"]
    // 详细说明见：https://github.com/wdssmq/HelloZBlog/tree/master/docs#%E4%BB%A3%E7%A0%81%E7%89%87%E6%AE%B5
    // debug
    // ob_clean();
    echo __FILE__ . "丨" . __LINE__ . ":<br>\n";
    var_dump('因为1不等于2所以这里并不会输出，在实际代码中就可以确定问题是出在条件语句或更之前的地方');
    echo "<br><br>\n\n";
    // die();
    // debug

  }
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
  // 当然有些主题省掉了这两个标签就很纠心了，，但是我更选择去怼相应的作者加上。。
}

/**
 * 一个获取插件内文件路径的方法
 * 这个函数可以通过代码片段功能快速生成
 *
 * @param string $file 要获取的文件路径或标识别名
 * @param string $t    path|host path用于文件读写等操作，host用于获取网址路径
 * @return string
 */
// 代码片段 fnpath
function HelloZBlog_Path($file, $t = 'path')
{
  global $zbp;
  $result = $zbp->$t . 'zb_users/plugin/HelloZBlog/';
  switch ($file) {
    case 'doc-html':
      return $result . 'docs/index.html';
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

// 插件启用时执行
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
  // 插件后期更新加了新的配置项时要写进 UpdatePlugin_HelloZBlog() 让已经安装的更新到
  // 对于新安装的，可以在上边的初始化中合并，或者只写在 UpdatePlugin_HelloZBlog() 然后主动调用一次
  // UpdatePlugin_HelloZBlog()
  // ↑ 需要避免各种小心导致死循环调用

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

  // 强制重建模板缓存
  $zbp->BuildTemplate();
}

// 卸载插件
function UninstallPlugin_HelloZBlog()
{
  global $zbp;
  $zbp->DelConfig("HelloZBlog");
}

// 代码片段 fn_update
// 插件升级时执行
function UpdatePlugin_HelloZBlog()
{
  global $zbp;
  //$version = $zbp->Config('HelloZBlog')->version;
  //if ($version !== 1.1) {
  //  $zbp->Config('HelloZBlog')->version = 1.1;
  //  $zbp->SaveConfig('HelloZBlog');
  //}
  // cfg_haskey ← 单独使用
  if (!$zbp->Config('HelloZBlog')->HasKey("newKey")) {
    $zbp->Config('HelloZBlog')->newKey = 'value';
    $zbp->SaveConfig('HelloZBlog');
  }
}

// 旧版兼容
function HelloZBlog_Updated()
{
  UpdatePlugin_HelloZBlog();
}
