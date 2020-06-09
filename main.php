<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
  $zbp->ShowError(6);
  die();
}
if (!$zbp->CheckPlugin('test')) {
  $zbp->ShowError(48);
  die();
}
// 保存配置项，$suc只是个人习惯，大部分时候都用不上
$act = GetVars('act', 'GET');
// $suc = GetVars('suc', 'GET');
if (GetVars('act', 'GET') == 'save') {
  // 安全检查，配合下边的BuildSafeURL()使用
  CheckIsRefererValid();
  // 遍历提交内容并处理
  foreach ($_POST as $key => $val) {
    if ($key == "str") {
      // 保存前可以按需要替换数据内容
      // strtr()	转换字符串中特定的字符。
      $map = array('{$name}' => $zbp->name, '{$host}' => $zbp->host);
      $val = strtr($val, $map); // 效果等同于下边两行；
      // $val = str_replace('{$name}', $zbp->name, $val);
      // $val = str_replace('{$host}', $zbp->host, $val);
      $zbp->Config('test')->$key = $val;
      continue;
    }
    if ($key == "num") {
      $zbp->Config('test')->$key = (int) $val;
      continue;
    }
    if ($key == "arr1") {
      $val = str_replace("，", ",", $val);
      // 转换成数组后直接写入
      $zbp->Config('test')->$key = explode(",", $val);
      continue;
    }
    if ($key == "arr2") {
      $val = str_replace("，", ",", $val);
      // 保存为字符串，使用时转换
      $zbp->Config('test')->$key = $val;
      continue;
    }
    $zbp->Config('test')->$key = trim($val);
  }
  // 保存
  $zbp->SaveConfig('test');
  // 重建模板，即使功能上不需要习惯性写上也不会损失什么
  $zbp->BuildTemplate();
  // 保存后给出操作成功的提示
  $zbp->SetHint('good');
  // 重定向一次页面，不然地址栏会是main.php?act=save&csrfToken=cbb7ce8bd23
  // 一是视觉上乱，二是刷新页面会有重复提交的提示
  Redirect('./main.php');
  // Redirect('./main.php' . ($suc == null ? '' : '?act=$suc'));
}

$blogtitle = 'ZBlog插件开发演示';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle; ?><small><a title="刷新" href="main.php" style="font-size: 16px;display: inline-block;margin-left: 5px;">刷新</a></small></div>
  <div class="SubMenu">
    <a href="main.php" title="首页"><span class="m-left m-now">首页</span></a>
    <?php require_once "about.php"; ?>
  </div>
  <div id="divMain2">
    <?php
    if (is_file(test_Path("doc-html"))) {
      $docUrl = test_Path("doc-html", "host");
    } else {
      $docUrl = "https://github.com/wdssmq/test-for-zblog/tree/master/docs#readme";
    }
    ?>
    <p>教程文档：<?php echo test_a($docUrl, "教程文档", 0, 1); ?></p>
    <p>效果查看1：<?php echo test_a($bloghost . "?test", "效果查看1", 0, 1); ?></p>
    <p>效果查看2：<?php echo test_a($bloghost . "zb_users/plugin/test/api.php", "效果查看2"); ?></p>
    <p><?php echo GetGuestAgent() ?></p>
    <p>以下内容请在代码编辑器中查看，并配合教程文档</p>
    <!--
      按照【参考文档→编辑器→工作区】一节配置，然后使用编辑的【在文件中查找】功能分别搜索 BuildSafeURL 和 zbpform 来查看各自有什么用以及如何用，【看不懂就没办法了
     -->
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
          $str = strtr($zbp->Config("test")->str, array_flip($map)); // 效果等同于下边两行；
          // $str = str_replace($zbp->name, '{$name}', $zbp->Config("test")->str);
          // $str = str_replace($zbp->host, '{$host}', $str);
          ?>
          <td><?php echo zbpform::text("str", $str, "90%"); ?></td>
          <td>可尝试写入`{$name}`或`{$host}`<br>输出效果：<?php echo $zbp->Config("test")->str; ?></td>
        </tr>
        <tr>
          <td>数字</td>
          <td><?php echo zbpform::text("num", $zbp->Config("test")->num, "90%"); ?></td>
          <td>可以留空或输入非数字查看效果</td>
        </tr>
        <tr>
          <td>开关（布尔）</td>
          <td><?php echo zbpform::zbradio('isOn', $zbp->Config("test")->isOn); ?></td>
          <td>选择状态：<?php echo $zbp->Config("test")->isOn ? "开" : "关"; ?></td>
        </tr>
        <!-- 数组有两种方案 -->
        <!-- 1、数据库持有数组，使用时直接调用，配置页回显时转为字符串 -->
        <?php
        // 直接存数组需要初始化赋值，否则在转字符串时会报错
        // 初始化操作一般是写在include.php，InstallPlugin_test()中
        if (!$zbp->Config("test")->HasKey("arr1")) {
          $zbp->Config("test")->arr1 = array();
        }; ?>
        <tr>
          <td>数组1</td>
          <td><?php echo zbpform::text("arr1", join(",", $zbp->Config("test")->arr1), "90%"); ?></td>
          <td>请输入使用逗号分隔的内容[，,]皆可<br>
            <!-- 直接作为数组使用，无需转换 -->
            <?php var_dump($zbp->Config('test')->arr1); ?>
          </td>
        </tr>
        <!--  2、数据库持有字符串，使用时需要转换，配置页回显时直接输出 -->
        <tr>
          <td>数组2</td>
          <td><?php echo zbpform::text("arr2", $zbp->Config("test")->arr2, "90%"); ?></td>
          <td>请输入使用逗号分隔的内容[，,]皆可<br>
            <!-- 直接输出是字符串，使用时要转换 -->
            <?php var_dump($zbp->Config('test')->arr2); ?>
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
function test_a($href, $title, $text = "", $newWindow = 0)
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
