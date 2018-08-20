<?php
/**
 * COINS.LOVE API库
 */

class Request {
    private $host = 'https://uapi.coins95.com';
    private $version = '1.0';
    private $sign = '';
    private $ip = '';//外网ip
    private $timestamp = '';
    public $api_method = '';
    public $req_method = '';
    public $param = [];

    function __construct() {
        date_default_timezone_set('Asia/Shanghai');
        $this->timestamp =  date('Y-m-d\TH:i:s', time());
        $this->param = [
            'AccessKey' => ACCESS_KEY,
            'Version' => $this->version,
            'Timestamp' =>$this->timestamp,
            'IP' => $this->ip,
        ];
    }

    /**
     * 创建订单
     * @param $symbol(币对)  必传项
     * @param $type（交易方式：1：限价交易 2:市价交易） 必传项
     * @param $deal_type（买卖方向 BUY/SELL） 必传项
     * @param $price(价格，当限价交易时必填) 可选项
     * @param $num（数量） 必传项
     * @return array
     *
     */
    public function add_order($symbol,$type,$deal_type,$price,$num){
        $this->api_method = '/t/order/add';
        $this->req_method = 'POST';
        $postparam = ['symbol' => $symbol, 'type' => $type,'dealType' => $deal_type,'price' => $price,'num' => $num];
        $url = $this->create_sign_url();
        return json_decode($this->curl($url,$postparam),true);
    }

    /**
     * 撤销订单
     * @param $symbol（币对） 必传项
     * @param $order_id（订单id） 必传项
     * @return array
     */
    public function cancel_order($symbol,$order_id){
        $this->api_method = '/t/order/cancel';
        $this->req_method = 'POST';
        $postparam = ['symbol' => $symbol, 'orderId' => $order_id];
        $url = $this->create_sign_url();
        return json_decode($this->curl($url,$postparam),true);
    }

    /**
     * 查询订单详情
     * @param $symbol(币对) 必传项
     * @param $oid（订单id） 必传项
     * @return array
     */
    public function t_order($symbol,$oid){
        $this->api_method = '/t/order';
        $this->req_method = 'GET';
        $getparam = ['symbol' => $symbol, 'oid' => $oid];
        $url = $this->create_sign_url($getparam);
        return json_decode($this->curl($url),true);
    }

    /**
     * 查询订单交易明细
     * @param $symbol（币对） 必传项
     * @param $oid（订单id） 必传项
     * @return array
     */
    public function t_order_trades($symbol,$oid){
        $this->api_method = "/t/order/{$symbol}/{$oid}/trades";
        $this->req_method = 'GET';
        $url = $this->create_sign_url();
        return json_decode($this->curl($url),true);
    }

    /**
     *  查询订单信息
     * @param $symbol:币对 必传项
     * @param $states：状态 必传项
     * @param $type ：委托类型（1：限价委托 2：市价委托） 可选项
     * @param $side：买卖方向（buy:买入 sell:卖出） 可选项
     * @param $stard_date ：查询开始时间，日期格式Y-m-d H:i:s 默认查询30天内的，最多只能查询180天内 可选项
     * @param $end_date ：查询结束时间，日期格式Y-m-d H:i:s 可选项
     * @param $direct 查询方向（prev:向前 next:向后） 默认值next 可选项
     * @param $size 查询数据大小（范围[0,500] ） 默认10 可选项
     * @return array
     */
    public function order_orders($symbol,$states,$type='',$side='',$stard_date='',$end_date='',$direct='',$size=''){
        $this->api_method = '/t/order/orders';
        $this->req_method = 'POST';
        $postparam = ['symbol' => $symbol,'states' => $states,'type' => $type,'side' => $side, 'stardDate' => $stard_date,'endDate' => $end_date,'direct' => $direct,'size' => $size];
        $postparam = array_filter($postparam);
        $url = $this->create_sign_url();
        return json_decode($this->curl($url,$postparam),true);
    }

    /**
     * 查询交易记录
     * @param $symbol （币对） 必传项
     * @param $start_date （查询开始时间，日期格式  Y-m-d H:i:s） 可选项
     * @param $end_date （查询结束时间 Y-m-d H:i:s） 可选项
     * @param $direct（查询方向 prev:向前 next:向后） 默认next 可选项
     * @param $size（查询数据大小 默认10  范围[0,500]） 可选项
     * @return array
     */
    public function trade_trades($symbol,$start_date='',$end_date='',$direct='',$size=''){
        $this->api_method = '/t/trade/trades';
        $this->req_method = 'POST';
        $postparam = ['symbol' => $symbol, 'startDate' => $start_date,'endDate' => $end_date,'direct' => $direct,'size' => $size];
        $postparam = array_filter($postparam);
        $url = $this->create_sign_url();
        return json_decode($this->curl($url,$postparam),true);
    }

    /**
     * 查询交易详情
     * @param $symbol(币对) 必传项
     * @param $tid(交易id) 必传项
     * @return array
     */
    public function trade($symbol,$tid){
        $this->api_method = '/t/trade';
        $this->req_method = 'GET';
        $getparam = ['symbol' => $symbol, 'tid' => $tid];
        $url = $this->create_sign_url($getparam);
        return json_decode($this->curl($url),true);
    }


    /**
     * 获取平台交易币对
     * @return array
     */
    public function symbol(){
        $this->api_method = '/market/symbol';
        $this->req_method = 'GET';
        $getparam = [];
        $url = $this->create_sign_url($getparam);
        return json_decode($this->curl($url),true);
    }

    /**
     * 平台交易币对合并深度
     * @param $symbol(币对)  必传项
     * @return array
     */
    public function symbol_precision($symbol){
        $this->api_method = '/market/depth/symbol/precision';
        $this->req_method = 'GET';
        $getparam = ['symbol' => $symbol];
        $url = $this->create_sign_url($getparam);
        return json_decode($this->curl($url),true);
    }

    /**
     * 查询平台深度订单
     * @param $symbol(币对)  必传项
     * @return array
     */
    public function depth_symbol($symbol){
        $this->api_method = '/market/depth/symbol';
        $this->req_method = 'GET';
        $getparam = ["symbol" => $symbol];
        $url = $this->create_sign_url($getparam);
        return json_decode($this->curl($url),true);
    }

    /**
     * 查询账户
     * @param string $symbol(币对) 可选
     * @param string $type(账户类型) 可选
     * @return array
     */
    public function account_accounts($symbol='',$type=''){
        $this->api_method = '/a/account/accounts';
        $this->req_method = 'POST';
        $postparam = ['symbol' => $symbol, 'type' => $type];
        $url = $this->create_sign_url();
        return json_decode($this->curl($url,$postparam),true);
    }

    /**
     * 查询账户详情
     * @param $id(账户id)  必传项
     * @return array
     */
    public function account($id){
        $this->api_method = '/a/account';
        $this->req_method = 'GET';
        $getparam = ["id" => $id];
        $url = $this->create_sign_url($getparam);
        return json_decode($this->curl($url),true);
    }


    /**
     * 查询平台交易币对交易信息
     * @param $symbol(币对) 可选
     * @return array
     */
    public function market_exchange($symbol){
        $this->api_method = '/market/exchange';
        $this->req_method = 'GET';
        $getparam = ["symbol" => $symbol];
        $url = $this->create_sign_url($getparam);
        return json_decode($this->curl($url),true);
    }


    /**
     * 类库方法
     */
    // 生成验签URL
    function create_sign_url($getparam = []) {
        // 验签参数
        $url = $this->host.$this->api_method.$this->bind_param( $getparam);
        foreach($this->param as $k => $v){
            $header_param[] = $k.'='.urlencode($v);
        }
        $this->create_sig($header_param,$getparam);
        return $url;
    }

    // 组合参数
    function bind_param($getparam) {
        count($getparam) > 0 ? $bind_param = '/'.implode('/', $getparam) : $bind_param = "";
        return $bind_param;
    }

    // 生成签名
    function create_sig($param,$getparam = []) {
        $u = [];
        foreach($param as $k=>$v) {
            $u[] = $k."=".urlencode($v);
        }
        $sign_param_1 = strtoupper($this->req_method)."\n".strtolower($this->host)."\n".$this->api_method.$this->bind_param( $getparam)."\n".implode('&', $param);
        $signature = hash_hmac('sha256', $sign_param_1, SECRET_KEY,true);
        $this->sign = base64_encode($signature);
    }

    function curl($url, $postdata=[]){

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        if ($this->req_method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
        }
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,$this->req_method);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_TIMEOUT,60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, [
            "AccessKey:".ACCESS_KEY,
            "Version:".$this->version,
            "Timestamp:".$this->timestamp,
            "Sign:".$this->sign,
            "Content-Type: application/json",
        ]);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return $output;
    }


}

?>
