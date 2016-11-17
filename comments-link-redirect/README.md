**评论链接重定向**

Version: 1.1

插件地址：[http://www.wheatime.com/2009/06/comments-link-redirect.html](http://www.wheatime.com/2009/06/comments-link-redirect.html)

**使用方法：**

1. 激活插件
2. 到主题内找到评论用户的调用函数 `<?php comment_author_link() ?>`，在其中的 URL 前添加“http://你的博客地址/?r=”（不含引号）
3. 手工在 robots.txt 中添加 `Disallow: /?r=*`

— 麦子
