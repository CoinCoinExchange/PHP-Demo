<?php
/**
 * Created by PhpStorm.
 * User: Jkb
 * Date: 2018/8/11
 * Time: 11:17
 */

define('SECRET_KEY','');
define('ACCESS_KEY','');

include 'lib.php';
$Request = new Request();

//获取平台交易币对
$res = $Request->symbol();

//平台交易币对合并深度
//$res = $Request->symbol_precision("ethbtc");

//查询平台深度订单
//$res = $Request->depth_symbol("ethbtc");

//创建订单
//$res = $Request->add_order('ethbtc','1','SELL',1,0.0001);

//撤销订单(weiceshi)
//$res = $Request->cancel_order('ethbtc',2245);

//查询用户订单信息
//$res = $Request->order_orders('ethbtc','1');


//查询交易记录(n)
//$start_time = date('Y-m-d H:i:s',time()-7*86400);
//$end_date = date('Y-m-d H:i:s',time()+2*86400);
//$res = $Request->trade_trades('ethbtc',$start_time);

//查询订单详情
//$res = $Request->t_order('ethbtc',2245);

//查询订单交易明细
//$res = $Request->t_order_trades('ethbtc',2143);

//查询账户
//$res = $Request->account_accounts();

//查询账户详情
//$res = $Request->account(50918);

//查询平台交易币对交易信息
//$res = $Request->market_exchange('ethbtc');
echo('<pre>');
print_r($res);
echo('</pre>');


