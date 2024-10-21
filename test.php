<?php

// 引入自动加载文件（假设你使用 Composer 来管理项目依赖）
require_once __DIR__ . '/vendor/autoload.php';

// 引入 SDK 中的类
use SDK\Config;
use SDK\APIs\RoomAPI;
use SDK\APIs\StreamAPI;

try {
  // 初始化配置
  $config = new Config(
    '860587201',      // 替换为你的 AppId
    '83c4643854f1f0b8940045e20814a750' // 替换为你的 ServerSecret
  );

  // 创建 API 客户端
  $roomApi = new RoomAPI($config);
  $streamApi = new StreamAPI($config);

  // // 调试接口 1: 设置流封禁规则
  // $response = $streamApi->setForbidStreamRule(
  //   '23333',           // RoomId
  //   'webrtc1728815658539',       // StreamId
  //   1,                 // DisableAudio: 1 表示禁用音频
  //   1,                 // DisableVideo: 1 表示禁用视频
  //   3600               // EffectiveTime: 封禁时间，单位为秒
  // );

  // echo "SetForbidStreamRule Response:\n";
  // print_r($response);

  // // 调试接口 2: 查询流封禁规则
  // $response = $streamApi->describeForbidStreamRules();

  // echo "DescribeForbidStreamRules Response:\n";
  // print_r($response);

  // // 调试接口 3: 删除流封禁规则
  // $response = $streamApi->delForbidStreamRule(
  //   '2333',         // RoomId
  //   'webrtc1728808686400'      // StreamId
  // );

  // echo "DelForbidStreamRule Response:\n";
  // print_r($response);

  // 调试接口 4: 关闭房间
  $response = $roomApi->closeRoom(
    '23333',         // RoomId
    'clear',         // CustomReason: 自定义关闭房间的原因
    false            // RoomCloseCallback: 是否回调关闭房间事件
  );

  echo "CloseRoom Response:\n";
  print_r($response);

  // 调试接口 5: 踢出房间用户
  $response = $roomApi->kickoutUser(
    '23333',         // RoomId
    ['sample1728815658540','b'],      // UserId: 需要踢出的用户 ID 数组
    '违反规则'        // CustomReason: 自定义踢出用户的原因
  );

  // echo "KickoutUser Response:\n";
  // print_r($response);
  // 调试接口 1: 设置用户封禁规则
  $response = $roomApi->setForbidUserRule(
    2,                   // RuleType: 2 表示基于房间的封禁规则
    '23333',             // RoomId: 房间 ID
    null,                // UserId: 不传递，因为 RuleType 为 2
    [1, 2],              // DisabledPrivilege: 禁用的权限，1 表示禁用发言，2 表示禁用视频
    1800                // EffectiveTime: 封禁时长，单位为秒
  );

  echo "SetForbidUserRule Response:\n";
  print_r($response);

  // // 调试接口 2: 查询用户封禁规则
  $response = $roomApi->describeForbidUserRules(
    2   // RuleType: 查询房间封禁规则
  );

  echo "DescribeForbidUserRules Response:\n";
  print_r($response);

  // // 调试接口 3: 删除用户封禁规则
  $response = $roomApi->delForbidUserRule(
    2,                // RuleType: 2 表示基于房间的封禁规则
    '23333'           // RoomId: 房间 ID
  );

  echo "DelForbidUserRule Response:\n";
  print_r($response);
} catch (Exception $e) {
  // 捕获任何异常并输出错误信息
  echo 'Error: ' . $e->getMessage() . "\n";
}
