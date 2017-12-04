# yuncms-doing

适用于yuncms的动态模块

[![Latest Stable Version](https://poser.pugx.org/yuncms/yuncms-doing/v/stable.png)](https://packagist.org/packages/yuncms/yuncms-doing)
[![Total Downloads](https://poser.pugx.org/yuncms/yuncms-doing/downloads.png)](https://packagist.org/packages/yuncms/yuncms-doing)
[![Build Status](https://img.shields.io/travis/yuncms/yuncms-doing.svg)](http://travis-ci.org/yuncms/yuncms-doing)
[![License](https://poser.pugx.org/yuncms/yuncms-doing/license.svg)](https://packagist.org/packages/yuncms/yuncms-doing)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require yuncms/yuncms-doing
```

or add

```
"yuncms/yuncms-doing": "~2.0.0"
```

to the `require` section of your `composer.json` file.

## Feed设计与实现

Feed，在社交和信息推荐的App与网站中，基本都会用到的。例如常用的新浪微博，用户登录进入后，展现给我们的就是feed信息流。新浪微博的信息，来自于你关注人所发布的内容。还有微信的朋友圈，今日头条的信息流，好友发布的美拍等，这些都是Feed。玩过知乎的人应该知道，在知乎Feed中，会显示某某关注了某某话题，某某点赞或者赞同了某个回答。广义来讲，这些也算是一种Feed。

本文会先介绍几种不同的Feed设计，让大家对Feed实现有初步的了解。其次会对我们采用的Feed方案作出详细的解答。

### 推方式

推方式，是发生在用户触发行为（发布新的动态，关注某个人，点赞）的时候。在触发时，用户的自身行为会记录到对应的行为表中，其次用户的行为也会记录到自己的粉丝对应动态表中。

- 用户A发布新的帖子（动态），帖子记录到帖子表（主表）中。
- 发帖行为塞到队列（Redis List）中。触发异步操作，消费者会先读取用户的粉丝列表（uid分表），依次写入到用户的动态表（uid分表）中。
- 前端读取用户动态Feed，使用过滤条件，读取用户的动态表（关联查询帖子表）。
- 使用推方式，对需求变更是易适应的。为什么这么说呢？因为用户每一次的行为，我们都有存储相应的数据（数据模型）。即使变更，只需更改逻辑层代码。另外性能较好，后台数据已经准备好了，无需复杂的SQL查询。当然这样做，也存在很多弊端。1. 如果在用户A发完动态后，其粉丝B取消关注了A。在这个时间差内，内容已经推送给粉丝B了。2. 数据量存储成本较大，假如一个用户的粉丝数是100万，在发帖后会写入100万条数据。

### 拉方式

拉方式，是发生在粉丝拉取Feed时。粉丝拉取自己的动态，首先会检索自己的关注用户（uid分表）。得到关注的uid之后，再根据uid去查询关注用户发布的帖子。

拉的模式相对是比较简单易实现的，另外对用户关系变更（新增，删除用户）是敏感的。其次也不存在数据存储压力。但在查询的时候，对帖子表本身压力是很大的。尤其是用户本身关注的人很多的话，会有很严重的性能问题。

### 拉方式优化-伪实时拉取

用户在登录APP时，会发送用户活跃态到服务端。活跃信号塞到队列中，消费者依次读取活跃态uid，得到用户的关注者列表。得到关注者列表后，会去帖子表，查询关注人的发布的帖子。写到用户自己的Feed中。
这里写图片描述

这种方式和对拉方式而言，能有效避免接口性能问题，相当于通过定时任务提前把用户的动态Feed跑出来。和推方式比较，推是比较盲目的，这种方式只需针对活跃用户即可，能避免存储浪费。缺点在于实时性不好，用户登录APP后马上进入自己的Feed页，此时如果后台用户动态还没跑完，接口读取的就是历史数据了。当然这种方式不适合知乎，微博这种类型的APP的。

### 拉方式优化-分区拉取

分区拉取，是为了避免频繁查询单一帖子表所采用的一种优化手段。通过对帖子按照时间片分表，每次查询都能均摊到不同的表中，以此减轻主表的压力。

### 推方式优化-定时推

定时推，是以常驻进程的方式读取用户的发帖行为，再批量写入到粉丝的动态表中。这种方式和推方式差不多，只不过可以对多个发帖行为做聚合。

### 推方式优化-特定用户推

特定用户推，是推方式的一种优化方法。用户发送帖子时，只对活跃的粉丝用户写入。当然活跃用户的判定策略，是需要商定的。

## thx 

[gglinux's blog](http://gglinux.com/2017/03/06/feed_design/)

## License

This is released under the MIT License. See the bundled [LICENSE](LICENSE.md)
for details.