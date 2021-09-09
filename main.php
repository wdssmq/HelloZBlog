<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
  $zbp->ShowError(6);
  die();
}
if (!$zbp->CheckPlugin('HelloZBlog')) {
  $zbp->ShowError(48);
  die();
}
// 代码片段 cfg_save
// 保存配置项，$suc只是个人习惯，大部分时候都用不上
$act = GetVars('act', 'GET');
// $suc = GetVars('suc', 'GET');
if ($act == 'save') {
  // 安全检查，配合下边的BuildSafeURL()使用
  CheckIsRefererValid();

  // 如果你的配置项都是纯文本并且不需要额外处理的话，可以直接用简化版
  // // 简化版赋值
  // foreach ($_POST as $key => $val) {
  //   $zbp->Config('HelloZBlog')->$key = trim($val);
  // }
  // // 保存
  // $zbp->SaveConfig('HelloZBlog');
  // // 然后接下边的 $zbp->BuildTemplate();

  // 针对特定的字段需要处理后再传递给config
  foreach ($_POST as $key => $val) {
    if ($key == "str") {
      // 保存前可以按需要替换数据内容
      // strtr()	转换字符串中特定的字符。
      $map = array('{$name}' => $zbp->name, '{$host}' => $zbp->host);
      $val = strtr($val, $map); // 效果等同于下边两行；
      // $val = str_replace('{$name}', $zbp->name, $val);
      // $val = str_replace('{$host}', $zbp->host, $val);
      $zbp->Config('HelloZBlog')->$key = $val;
      continue;
    }
    if ($key == "num") {
      $zbp->Config('HelloZBlog')->$key = (int) $val;
      continue;
    }
    if ($key == "arr1") {
      $val = str_replace("，", ",", $val);
      // 转换成数组后直接写入
      $zbp->Config('HelloZBlog')->$key = explode(",", $val);
      continue;
    }
    if ($key == "arr2") {
      $val = str_replace("，", ",", $val);
      // 保存为字符串，使用时转换
      $zbp->Config('HelloZBlog')->$key = $val;
      continue;
    }
    $zbp->Config('HelloZBlog')->$key = trim($val);
  }
  // 保存
  $zbp->SaveConfig('HelloZBlog');

  // 重建模板，即使功能上不需要习惯性写上也不会损失什么
  $zbp->BuildTemplate();
  // 保存后给出操作成功的提示
  $zbp->SetHint('good');
  // 重定向一次页面，不然地址栏会是main.php?act=save&csrfToken=cbb7ce8bd23
  // 一是视觉上乱，二是刷新页面会有重复提交的提示
  Redirect('./main.php');
  // Redirect('./main.php' . ($suc == null ? '' : '?act=$suc'));
}

if ($act == 'devOn') {
  // 应用中心开发模式开启
  $zbp->Config('AppCentre')->enabledevelop = true;
  $zbp->SaveConfig('AppCentre');
  // zblog 开发模式
  $zbp->option['ZC_DEBUG_MODE'] = true;
  $zbp->SaveOption();
  $zbp->SetHint("good", "已经启用开发模式");
  Redirect("./main.php");
}
// 开发中会在插件启用后才写初始化功能，在管理页调用保证执行；
InstallPlugin_HelloZBlog();
$blogtitle = 'ZBlog插件开发演示';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle; ?><small><a title="刷新" href="main.php" style="font-size: 16px;display: inline-block;margin-left: 5px;">刷新</a></small></div>
  <div class="SubMenu">
    <a href="main.php" title="首页"><span class="m-left m-now">首页</span></a>
    <a href="main.php?act=devOn" title="启用开发模板"><span class="m-right">启用开发模式</span></a>
    <?php require_once "about.php"; ?>
  </div>
  <div id="divMain2">
    <?php
    // 判断本地文件是否存在，否则显示远程链接
    // 可以将使用typora将`docs/README.md`导出为html格式在本地查看
    if (is_file(HelloZBlog_Path("doc-html"))) {
      $docUrl = str_replace("index.html", "", HelloZBlog_Path("doc-html", "host"));
    } else {
      $docUrl = "https://github.com/wdssmq/HelloZBlog/tree/master/docs#readme";
    }
    ?>
    <p>教程文档：<?php echo HelloZBlog_a($docUrl, "教程文档", 0, 1); ?></p>
    <p>效果查看1：<?php echo HelloZBlog_a($bloghost . "?HelloZBlog", "效果查看1", 0, 1); ?></p>
    <p>效果查看2：<?php echo HelloZBlog_a($bloghost . "?HelloZBlog=display", "效果查看2"); ?></p>
    <p>效果查看3：<?php echo HelloZBlog_a($bloghost . "zb_users/plugin/HelloZBlog/api.php", "效果查看3"); ?></p>
    <!-- 输出某些信息 -->
    <p> - <?php echo GetGuestAgent() ?></p>
    <p> - zblog调试：<?php echo $zbp->option['ZC_DEBUG_MODE'] ? "on" : "off" ?> 应用中心开发：<?php echo $zbp->Config('AppCentre')->enabledevelop ? "on" : "off" ?></p>
    <p>以下内容请在代码编辑器中查看，并配合教程文档</p>
    <!--
      按照【参考文档→编辑器→工作区】一节配置，然后使用编辑的【在文件中查找】功能分别搜索 BuildSafeURL 和 zbpform 来查看各自有什么用以及如何用，【看不懂就没办法了
     -->
    <!-- 代码片段 cfg_form -->
    <form action="<?php echo BuildSafeURL("main.php?act=save"); ?>" method="post">
      <table width="100%" class="tableBorder">
        <tr>
          <th width="10%">项目</th>
          <th>内容</th>
          <th width="45%">说明</th>
        </tr>
        <tr>
          <td>字符串字段</td>
          <?php
          // 允许使用“标签”的内容，在编辑器要替换回标签方便编辑
          // array_flip()	交换数组中的键和值。
          $map = array('{$name}' => $zbp->name, '{$host}' => $zbp->host);
          $str = strtr($zbp->Config("HelloZBlog")->str, array_flip($map));
          // 效果等同于下边两行；
          // $str = str_replace($zbp->name, '{$name}', $zbp->Config("HelloZBlog")->str);
          // $str = str_replace($zbp->host, '{$host}', $str);
          ?>
          <td><?php echo zbpform::text("str", $str, "90%"); ?></td>
          <td>可尝试写入`{$name}`或`{$host}`<br>输出效果：<?php echo $zbp->Config("HelloZBlog")->str; ?></td>
        </tr>
        <tr>
          <td>数字</td>
          <td><?php echo zbpform::text("num", $zbp->Config("HelloZBlog")->num, "90%"); ?></td>
          <td>可以留空或输入非数字查看效果</td>
        </tr>
        <tr>
          <td>开关（布尔）</td>
          <td><?php echo zbpform::zbradio('isOn', $zbp->Config("HelloZBlog")->isOn); ?></td>
          <td>选择状态：<?php echo $zbp->Config("HelloZBlog")->isOn ? "开" : "关"; ?></td>
        </tr>
        <!-- 数组有两种方案 -->
        <!-- 1、数据库持有数组，使用时直接调用，配置页回显时转为字符串 -->
        <?php
        // 直接存数组需要初始化赋值，否则在转字符串时会报错
        // 初始化操作一般是写在include.php，InstallPlugin_HelloZBlog()中
        if (!$zbp->Config("HelloZBlog")->HasKey("arr1")) {
          $zbp->Config("HelloZBlog")->arr1 = array();
        }; ?>
        <tr>
          <td>数组1</td>
          <td><?php echo zbpform::text("arr1", join(",", $zbp->Config("HelloZBlog")->arr1), "90%"); ?></td>
          <td>请输入使用逗号分隔的内容[，,]皆可<br>
            <!-- 直接作为数组使用，无需转换 -->
            <?php var_dump($zbp->Config('HelloZBlog')->arr1); ?>
          </td>
        </tr>
        <!--  2、数据库持有字符串，使用时需要转换，配置页回显时直接输出 -->
        <tr>
          <td>数组2</td>
          <td><?php echo zbpform::text("arr2", $zbp->Config("HelloZBlog")->arr2, "90%"); ?></td>
          <td>请输入使用逗号分隔的内容[，,]皆可<br>
            <!-- 直接输出是字符串，使用时要转换 -->
            <?php var_dump($zbp->Config('HelloZBlog')->arr2); ?>
          </td>
        </tr>
        <tr>
          <td></td>
          <td colspan="2"><input type="submit" value="提交" /></td>
        </tr>
      </table>
    </form>
    ------
    <p>赞赏</p>
    <p class="js-qr">
      <img width="256" src="img/qr-qq.png" alt="qq">
      <img width="256" src="img/qr-wx.png" alt="微信">
      <img width="256" src="img/qr-ali.png" alt="支付宝">
    </p>
  </div>
</div>
<?php
function HelloZBlog_a($href, $title, $text = "", $newWindow = 1)
{
  if (empty($text)) {
    $text = $href;
  }
  $target = $newWindow ? "target=\"_blank\"" : "";
  return "<a {$target} href=\"{$href}\" title=\"{$title}\">$text</a>";
}
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>
