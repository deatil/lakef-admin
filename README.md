## Server-log


### 项目介绍

*  基于swoole，使用 hyperf 框架开发的服务日志系统
*  完善的 admin 通用后台管理
*  日志基于微服务构建，与 admin 系统相隔离
*  日志基于 kafka 中间件接收日志信息
*  接收日志及分析日志


### 环境要求

 - PHP >= 7.3.0
 - Hyperf >= 2.1.0
 - Swoole >= 4.1.0


### 截图预览

![ServeLog-login](https://user-images.githubusercontent.com/24578855/115948429-efff4880-a500-11eb-8698-9d2937e15e71.png)

![ServeLog6](https://user-images.githubusercontent.com/24578855/115948433-f7beed00-a500-11eb-8f4b-318bb28bdc46.png)

![ServeLog](https://user-images.githubusercontent.com/24578855/115424780-93b2d500-a231-11eb-91a8-ee6fcb33ac06.png)

![ServeLog2](https://user-images.githubusercontent.com/24578855/115423964-d4f6b500-a230-11eb-9885-09ebee2de5a2.png)

![ServeLog3](https://user-images.githubusercontent.com/24578855/115423972-d58f4b80-a230-11eb-93df-9789d1ea0d89.png)

![ServeLog4](https://user-images.githubusercontent.com/24578855/115423979-d627e200-a230-11eb-8c99-8278bfc350af.png)

![ServeLog5](https://user-images.githubusercontent.com/24578855/115423988-d6c07880-a230-11eb-9303-903458993797.png)


### 安装步骤

1. 首先下载

```php
git clone https://github.com/deatil/lakef-serverlog
```

2. 导入数据文件

```
docs/server-log.sql
```

3. 修改数据库链接信息、redis配置信息、kafka配置信息等等

```
config/autoload/
```

4. 下载依赖包

```
composer install
```

5. 后台登陆

```
http://yourdomain.com/admin/index
```

登陆账号: `admin` 和密码: `123456`

6. 日志记录接口

```
http://yourdomain.com/server-log/add
```

6. 日志记录事件选择

```
config/autoload/listeners.php
```


### 特别鸣谢

感谢以下的项目,排名不分先后

 - hyperf/hyperf

 - donjan-deng/hyperf-permission

 - gregwar/captcha
 
 - layui
 
 - layuimini


### 开源协议

*  `lakef-serverlog` 遵循 `Apache2` 开源协议发布，在保留本系统版权的情况下提供个人及商业免费使用。 


### 版权

*  该系统所属版权归 deatil(https://github.com/deatil) 所有。
