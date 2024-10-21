<?php

namespace SDK\APIs;

use SDK\Config;
use SDK\BaseClient;

class RoomAPI extends BaseClient
{
  public function __construct(Config $config)
  {
    parent::__construct($config);
  }

  // 关闭房间
  public function closeRoom($roomId, $customReason, $roomCloseCallback)
  {
    $params = [
      'Action' => 'CloseRoom',
      'RoomId' => $roomId,
      'CustomReason' => $customReason,
      'RoomCloseCallback' => $roomCloseCallback ? 'true' : 'false'
    ];
    return $this->makeRequest($params);
  }

  // 踢出房间用户
  public function kickoutUser($roomId, $userIds, $customReason)
  {
    $params = [
      'Action' => 'KickoutUser',
      'RoomId' => $roomId,
      'UserId' => $userIds,
      'CustomReason' => $customReason
    ];
    return $this->makeRequest($params);
  }
  // 设置用户封禁规则
  public function setForbidUserRule($ruleType, $roomId, $userId, $disabledPrivileges, $effectiveTime, $ip = null)
  {
    $params = [
      'Action' => 'SetForbidUserRule',
      'RuleType' => $ruleType,
      'RoomId' => $roomId,
      'UserId' => $userId,
      'DisabledPrivilege' => $disabledPrivileges,
      'EffectiveTime' => $effectiveTime
    ];
   
    // IP 参数仅在 RuleType 为 1 时必填
    if ($ruleType == 1 && !is_null($ip)) {
      $params['IP'] = $ip;
    }
   
    return $this->makeRequest($params);
  }

  // 查询用户封禁规则
  public function describeForbidUserRules($ruleType)
  {
    $params = [
      'Action' => 'DescribeForbidUserRules',
      'RuleType' => $ruleType,
    ];

    return $this->makeRequest($params);
  }

  // 删除用户封禁规则
  public function delForbidUserRule($ruleType, $roomId, $userId = null, $ip = null)
  {
    $params = [
      'Action' => 'DelForbidUserRule',
      'RuleType' => $ruleType,
      'RoomId' => $roomId,
    ];

    // 只有在 RuleType 为 3 或 4 时，UserId 参数才是必填的
    if (in_array($ruleType, [3, 4]) && !is_null($userId)) {
      $params['UserId'] = $userId;
    }

    // IP 参数在 RuleType 为 1 时是必填
    if ($ruleType == 1 && !is_null($ip)) {
      $params['IP'] = $ip;
    }

    return $this->makeRequest($params);
  }
}
