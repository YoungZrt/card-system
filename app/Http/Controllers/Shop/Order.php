<?php
namespace App\Http\Controllers\Shop; use App\System; use Carbon\Carbon; use Illuminate\Database\Eloquent\Relations\Relation; use Illuminate\Http\Request; use App\Http\Controllers\Controller; use App\Library\Response; use App\Library\Geetest; use Illuminate\Support\Facades\Cookie; class Order extends Controller { function get(Request $spf09a96) { if (\App\System::_getInt('vcode_shop_search') === 1) { $this->validateCaptcha($spf09a96); } $sp40bc20 = \App\Order::where('created_at', '>=', (new Carbon())->addDay(-\App\System::_getInt('order_query_day', 30))); $sp2add78 = $spf09a96->post('type', ''); if ($sp2add78 === 'cookie') { $spfc475f = Cookie::get('customer'); if (strlen($spfc475f) !== 32) { return Response::success(); } $sp40bc20->where('customer', $spfc475f); } elseif ($sp2add78 === 'order_no') { $sp71c458 = $spf09a96->post('order_no', ''); if (strlen($sp71c458) !== 19) { return Response::success(); } $sp40bc20->where('order_no', $sp71c458); } elseif ($sp2add78 === 'contact') { $spf72833 = $spf09a96->post('contact', ''); if (strlen($spf72833) < 6) { return Response::success(); } $sp40bc20->where('contact', $spf72833); if (System::_getInt('order_query_password_open')) { $sp0b8b61 = $spf09a96->post('query_password', ''); if (strlen($sp0b8b61) < 6) { return Response::success(); } $sp40bc20->where('query_password', $sp0b8b61); } } else { return Response::fail(trans('shop.search_type.required')); } $sp0e47f2 = array('id', 'created_at', 'order_no', 'contact', 'status', 'send_status', 'count', 'paid'); if (1) { $sp0e47f2[] = 'product_name'; $sp0e47f2[] = 'contact'; $sp0e47f2[] = 'contact_ext'; } $sp3fe1fa = $sp40bc20->orderBy('id', 'DESC')->get($sp0e47f2); $spb49ac3 = ''; return Response::success(array('list' => $sp3fe1fa, 'msg' => count($sp3fe1fa) ? $spb49ac3 : '')); } }