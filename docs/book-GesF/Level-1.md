<!-- 创建日期：2023-10-23 -->

## Part I

1. [ ] 下载并安装 [VSCode](https://code.visualstudio.com/ "VSCode")；
2. [ ] 几个相对常用的快捷键；
   - [ ] `Alt + Z`：查看：切换自动换行；
   - [ ] `Ctrl + \`：查看: 拆分编辑器；←「我自己总是记成 `|`」
   - [ ] `Ctrl + ~`：打开终端；←「这里其实是指反引号，Esc 下边那个键，和 `Ctrl + J` 有点区别」
   - [ ] `Ctrl + B`：查看: 切换主侧栏可见性；
   - [ ] `Ctrl + G`：转到行/列 ...
   - [ ] `Ctrl + N`：文件: 新的无标题文本文件；
   - [ ] `Ctrl + O`：文件: 打开文件...；
   - [ ] `Ctrl + P`：转到文件...—— 切换正在编辑或者**文件夹/工作区**中的文件；
   - [ ] `Ctrl + R`：最近打开的**文件夹/工作区**或**文件**；
   - [ ] `Ctrl + Shift + E`：查看: 显示 资源管理器；
   - [ ] `Ctrl + Shift + G`：查看: 显示 源代码管理；
   - [ ] `Ctrl + Shift + N`：新建窗口；
   - [ ] `Ctrl + Shift + P`：命令面板；
   - [ ] `Ctrl + Shift + X`：查看: 显示 扩展；
3. [ ] 下载并安装 [Git](https://git-scm.com/ "Git")；「参考：[安装指引](https://www.wdssmq.com/post/20140804123.html "安装指引")」
4. [ ] 设置 VSCode 的默认终端为 Git Bash；「参考：[Git Bash](https://www.wdssmq.com/post/20120915760.html "Git Bash")」
5. [ ] 生成 SSH 密钥，添加公钥至 Git 平台；「参考：[ssh-keygen](https://www.wdssmq.com/post/20201216004.html "ssh-keygen")」
6. [ ] 克隆仓库至本地；
7. [ ] 打开 `HelloZBlog.code-workspace` 工作区；
8. [ ] 安装**工作区内推荐的插件**；「参考：更多[VSCode 推荐插件](book-tips/2022-07?id=vscode-设置推荐插件 "VSCode 推荐插件")」
9. [ ] 标记以上任务为完成并提交修改；


<!-- 将下一个查找匹配项添加到选择 -->
<!-- 默认快捷键盘：Ctrl + D -->

**扩展阅读：**

- SSH 教程：[https://wangdoc.com/ssh/](https://wangdoc.com/ssh/ "SSH 教程 - 网道")
- 阮一峰：RSA 算法原理（一）：[https://ruanyifeng.com/blog/2013/06/rsa_algorithm_part_one.html](https://ruanyifeng.com/blog/2013/06/rsa_algorithm_part_one.html "RSA 算法原理（一） - 阮一峰的网络日志")
- 阮一峰：RSA 算法原理（二）：[https://ruanyifeng.com/blog/2013/07/rsa_algorithm_part_two.html](https://ruanyifeng.com/blog/2013/07/rsa_algorithm_part_two.html "RSA 算法原理（二） - 阮一峰的网络日志")
- editorconfig - 搜索：[https://cn.bing.com/search?q=editorconfig](https://cn.bing.com/search?q=editorconfig "editorconfig - 搜索")
- 「VSCode」快捷键备忘：[https://www.wdssmq.com/post/20130525410.html](https://www.wdssmq.com/post/20130525410.html "「VSCode」快捷键备忘\_电脑网络\_沉冰浮水")

## Part II

1. [ ] 安装 Node.js；「[Node.js](https://nodejs.org/en "Node.js")」
2. [ ] 安装 pnpm；「[pnpm](https://pnpm.io/zh "pnpm")」
3. [ ] 安装 docsify-cli；「[docsify-cli](https://docsify.js.org/#/zh-cn/quickstart "docsify-cli")」
4. [ ] 终端命令中启动预览服务；
5. [ ] 浏览器中查看预览效果；

```bash
npm install -g pnpm
pnpm i docsify-cli -g
# 需要在 /xxxx/HelloZBlog 目录下执行
docsify serve docs

# Serving D:\path\HelloZBlog\docs now.
# Listening at http://localhost:3000

```
