<?php

namespace SDK;

class BaseClient
{
  protected $config;

  public function __construct(Config $config)
  {
    $this->config = $config;
  }

  /**
   * 生成签名
   * Signature = md5(AppId + SignatureNonce + ServerSecret + Timestamp)
   *
   * @param string $signatureNonce
   * @param int $timeStamp
   * @return string
   */
  protected function generateSignature($signatureNonce, $timeStamp)
  {
    $str = $this->config->getAppId() . $signatureNonce . $this->config->getServerSecret() . $timeStamp;
    return md5($str);
  }

  /**
   * 生成请求参数，包括公共参数和 API 专属参数
   *
   * @param array $params API 专属参数
   * @return array
   */
  protected function buildRequestParams($params)
  {
    // 生成随机数作为 SignatureNonce
    $signatureNonce = bin2hex(random_bytes(8));

    // 获取当前的时间戳（秒级）
    $timeStamp = time();

    // 生成签名
    $signature = $this->generateSignature($signatureNonce, $timeStamp);

    // 公共参数
    $commonParams = [
      'AppId'           => $this->config->getAppId(),
      'Signature'       => $signature,
      'SignatureNonce'  => $signatureNonce,
      'SignatureVersion' => '2.0',
      'Timestamp'       => $timeStamp,
    ];

    // 合并公共参数和 API 专属参数
    return array_merge($commonParams, $params);
  }

  /**
   * 发起 API 请求
   *
   * @param array $params 请求参数
   * @return array|false 返回结果或者 false 表示请求失败
   * @throws \Exception
   */
  public function makeRequest($params = [], $body = [])
  {
    print(json_encode($body, JSON_UNESCAPED_UNICODE) . PHP_EOL);
    if (!empty($params['CustomReason'])) {
        $params['CustomReason'] = urlencode($params['CustomReason']);
    }
    // 构建完整的请求参数
    $requestParams = $this->buildRequestParams($params);
  
    // 构建查询字符串
    $queryString = http_build_query($requestParams);
    $queryString = preg_replace('/%5B\d+%5D/', '%5B%5D', $queryString);
    // 拼接完整的 API 请求 URL
    $url = $this->config->getBaseUrl() . '?' . $queryString;
    print($queryString . PHP_EOL);
    // 初始化 CURL
    $ch = curl_init();
    print($url . PHP_EOL);
    // 设置 CURL 选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 超时时间 5 秒
    if (!empty($body)) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    // 执行请求
    $response = curl_exec($ch);

    // 检查 CURL 错误
    if (curl_errno($ch)) {
      throw new \Exception('CURL Error: ' . curl_error($ch));
    }

    // 关闭 CURL
    curl_close($ch);
    print($response . PHP_EOL);
    // 解析 JSON 响应
    $decodedResponse = json_decode($response, true);

    // 检查返回值是否解析成功
    if ($decodedResponse === null && json_last_error() !== JSON_ERROR_NONE) {
      throw new \Exception('Failed to decode JSON response: ' . json_last_error_msg());
    }

    return $decodedResponse;
  }
}
