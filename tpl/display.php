<!DOCTYPE html>
<html lang="{$language}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>这是一个自定义的模板页面！_{$name}</title>
</head>
<body>
  <p>站点名：{$name}</p>
  <p>域名为：{$host}</p>
  <p>当前页面的模板路径是：{$path}</p>
  <p>变量$wev的值是：{$wev}</p>
  <p>直接使用配置项`$zbp->Config('HelloZBlog')->str`：{$zbp->Config('HelloZBlog')->str}</p>
  <p>引用另一个模板`template:plugin_HelloZBlog_other`：{template:plugin_HelloZBlog_other}</p>
</body>
</html>
