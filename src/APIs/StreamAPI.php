<?php
/*
 * @Author: sunboylu 1044768335@qq.com
 * @Date: 2024-10-13 15:52:22
 * @LastEditors: sunboylu 1044768335@qq.com
 * @LastEditTime: 2024-10-13 15:52:38
 * @FilePath: /gitee/phpServerApi/sdk/src/APIs/StreamAPI.php
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */

namespace SDK\APIs;

use SDK\Config;
use SDK\BaseClient;

class StreamAPI extends BaseClient
{
    public function __construct(Config $config)
    {
        parent::__construct($config);
    }

    // 设置流封禁规则
    public function setForbidStreamRule($roomId, $streamId, $disableAudio, $disableVideo, $effectiveTime)
    {
        $params = [
            'Action' => 'SetForbidStreamRule',
            'RoomId' => $roomId,
            'StreamId' => $streamId,
            'DisableAudio' => $disableAudio,
            'DisableVideo' => $disableVideo,
            'EffectiveTime' => $effectiveTime
        ];
        return $this->makeRequest($params);
    }

    // 查询流封禁规则
    public function describeForbidStreamRules()
    {
        $params = ['Action' => 'DescribeForbidStreamRules'];
        return $this->makeRequest($params);
    }

    // 删除流封禁规则
    public function delForbidStreamRule($roomId, $streamId)
    {
        $params = [
            'Action' => 'DelForbidStreamRule',
            'RoomId' => $roomId,
            'StreamId' => $streamId
        ];
        return $this->makeRequest($params);
    }

    //开始录制
    public function startRecord(string $roomId, array $recordInputParams, array $storageParams, array $recordOutputParams = [])
    {
        $params = ['Action' => 'StartRecord'];
        $body = [
            'RoomId' => $roomId,
            'RecordInputParams' => $recordInputParams,
            'StorageParams' => $storageParams,
        ];
        if ($recordOutputParams)
        {
            $body['RecordOutputParams'] = $recordOutputParams;
        }
        return $this->makeRequest($params, $body);
    }

    //结束录制
    public function stopRecord(string $taskId)
    {
        $params = ['Action' => 'StopRecord'];
        $body = [
            'TaskId' => $taskId
        ];
        return $this->makeRequest($params, $body);
    }
}
