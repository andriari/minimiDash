<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Models as Models;
use App\Exports\ExcelExport;
use App\Exports\ExcelFromViewExport;

use DB, Excel, Session;

class ExcelController extends Controller
{
	private $from_date;
	private $to_date;

	public function export(Request $request)
	{
		$params = $request->all();
		if ($request->has('from_date')) {
			$this->from_date = $params['from_date'];
			$this->to_date = $params['to_date'];
		}
		switch ($params['menu']) {
			case "user":
				$endpoint = $this->user();
				$export = new ExcelExport($endpoint['headings'], $endpoint['data']);
				break;
			case "content_post":
				$endpoint = $this->content_post();
				$export = new ExcelExport($endpoint['headings'], $endpoint['data']);
				break;
			case "product":
				$endpoint = $this->product();
				$export = new ExcelExport($endpoint['headings'], $endpoint['data']);
				break;
			case "product_sku":
				$endpoint = $this->product_sku();
				$export = new ExcelExport($endpoint['headings'], $endpoint['data']);
				break;
			case "product_raw":
				$endpoint = $this->product_raw();
				$export = new ExcelExport($endpoint['headings'], $endpoint['data']);
				break;
			case "order_verification":
				$endpoint = $this->order_verification(1, null, null, "asc");
				$export = new ExcelFromViewExport($endpoint['template'], $endpoint['data']);
				break;
			case "order_groupbuy":
				$endpoint = $this->order_verification(3, array(3), "Data Order Group Buy", "asc");
				$export = new ExcelFromViewExport($endpoint['template'], $endpoint['data']);
				break;
			case "order_delivery":
				$endpoint = $this->order_delivery();
				$export = new ExcelFromViewExport($endpoint['template'], $endpoint['data']);
				break;
			case "voucher_used":
				$endpoint = $this->voucher_used();
				$export = new ExcelExport($endpoint['headings'], $endpoint['data']);
				break;
			default:
				Session::flash('error', 'Export options does not found');
				return back();
		}
		// return response()->json(['result' => $endpoint]);
		return Excel::download($export, $endpoint['file_name'] . '.xlsx');
	}

	private function user()
	{
		$users = Models\User::select('user_id', 'fullname', 'email', 'created_at')
			->where('active', 1)
			->where('test_flag', 0)
			->orderBy('fullname', 'asc')
			->get();
		$return['file_name'] = "Data User " . date('d F Y');
		$return['data'] = $users;
		$return['headings'] = array('User ID', 'Full Name', 'Email', 'Created At');
		return $return;
	}

	private function content_post()
	{
		$contents = Models\Content::select('minimi_content_post.created_at', 'fullname', 'product_name', 'content_type', 'content_curated', 'content_rating')
			->leftJoin('minimi_user_data', 'minimi_content_post.user_id', '=', 'minimi_user_data.user_id')
			->leftJoin('minimi_product', 'minimi_content_post.product_id', '=', 'minimi_product.product_id')
			->whereNotIn('minimi_content_post.content_type', [3])
			->where('minimi_content_post.status', 1)
			->get();

		foreach ($contents as $row) {
			switch ($row->content_type) {
				case 1:
					$row->content_type = "Video";
					break;
				case 2:
					$row->content_type = "Review";
					break;
				case 3:
					$row->content_type = "Artikel";
					break;
				default:
					$row->content_type = "Propose Product";
					break;
			}

			switch ($row->content_curated) {
				case 1:
					$row->content_curated = "Approved";
					break;
				case 2:
					$row->content_curated = "Declined";
					break;
				default:
					$row->content_curated = "Waiting";
					break;
			}
		}
		$return['file_name'] = "Data Content Post " . date('d F Y');
		$return['data'] = $contents;
		$return['headings'] = array('Review Date', 'User Name', 'Product', 'Review Type', 'Review Status', 'Rating');
		return $return;
	}

	private function product()
	{
		$products = Models\Product::select('minimi_product.product_id', 'product_name', 'brand_name', 'category_name', DB::raw('COUNT(minimi_product_variant.variant_id) as total_variant'), DB::raw('SUM(minimi_product_variant.stock_count) as total_stock'), 'minimi_content_rating_tab.value', DB::raw('MIN(minimi_product_variant.stock_price)'), 'minimi_product.updated_at')
			->leftJoin('data_category', 'minimi_product.category_id', '=', 'data_category.category_id')
			->leftJoin('minimi_content_rating_tab', 'minimi_product.product_id', '=', 'minimi_content_rating_tab.product_id')
			->leftJoin('data_brand', 'minimi_product.brand_id', '=', 'data_brand.brand_id')
			->leftJoin('minimi_product_variant', 'minimi_product.product_id', '=', 'minimi_product_variant.product_id')
			->where('minimi_product.product_type', 1)
			->where('tag', 'review_count')
			->where('minimi_product.status', 1)
			->where('minimi_product_variant.status', 1)
			->orderBy('product_name', 'asc')
			->groupBy('minimi_product.product_id')
			->get();
		$return['file_name'] = "Data Product " . date('d F Y');
		$return['data'] = $products;
		$return['headings'] = array('Product ID', 'Name', 'Brand', 'Category', 'Variant', 'Stock', 'Review', 'Price', 'Last Updated');
		return $return;
	}

	private function product_sku()
	{
		$variants =  Models\Variant::select('minimi_product_variant.product_id', 'product_name', 'brand_name', 'category_name', 'minimi_product_variant.variant_name', 'minimi_product_variant.variant_sku', 'minimi_product_variant.stock_count', 'minimi_product_variant.stock_agent_price', 'minimi_product_variant.stock_price', 'minimi_product_variant.stock_price_gb', 'minimi_product_variant.publish', 'minimi_product_variant.updated_at')
			->join('minimi_product', 'minimi_product.product_id', '=', 'minimi_product_variant.product_id')
			->join('data_category', 'minimi_product.category_id', '=', 'data_category.category_id')
			->join('data_brand', 'minimi_product.brand_id', '=', 'data_brand.brand_id')
			->where('minimi_product.product_type', 1)
			->where('minimi_product.status', 1)
			->where('minimi_product_variant.status', 1)
			->orderBy('product_name', 'asc')
			->groupBy('minimi_product_variant.variant_id')
			->get();

		foreach ($variants as $row) {
			if ($row->publish == 0) {
				$row->publish = "Hidden";
			} else {
				$row->publish = "Shown";
			}
		}

		$return['file_name'] = "Data SKU Product" . date('d F Y');
		$return['data'] = $variants;
		$return['headings'] = array('Product ID', 'Name', 'Brand', 'Category', 'Variant', 'SKU', 'Stock', 'Agent Price', 'Price', 'Group Buy Price', 'Status', 'Last Updated');
		return $return;
	}

	private function product_raw()
	{
		$variants =  Models\Variant::select('minimi_product_variant.product_id', 'product_name', 'product_feed_desc', 'product_uri', 'minimi_product_variant.stock_price', 'minimi_product_variant.stock_price_gb', 'brand_name')
			->join('minimi_product', 'minimi_product.product_id', '=', 'minimi_product_variant.product_id')
			->join('data_category', 'minimi_product.category_id', '=', 'data_category.category_id')
			->join('data_brand', 'minimi_product.brand_id', '=', 'data_brand.brand_id')
			->where('minimi_product.product_type', 1)
			->where('minimi_product.status', 1)
			->where('minimi_product_variant.status', 1)
			->orderBy('product_name', 'asc')
			->groupBy('minimi_product_variant.variant_id')
			->get();

		foreach ($variants as $row) {
			$row->product_uri = config('env.FRONTEND_URL') . "/product/" . $row->product_uri;
		}
		$return['file_name'] = "Data Raw Product " . date('d F Y');
		$return['data'] = $variants;
		$return['headings'] = array('Product ID', 'Product Name', 'Product Description', 'Link', 'Price', 'Group Buy Price', 'Brand');
		return $return;
	}

	private function order_verification($mode = 1, $transaction_type = null, $file_name = null, $order_by = 'desc')
	{
		if ($transaction_type == null) {
			$transaction_type = array(1, 2);
		}

		$query = DB::table('commerce_booking')
			->select('commerce_booking.created_at', 'commerce_booking.cg_id', 'commerce_group_buy.status as groupbuy_status', 'commerce_group_buy.total_participant', 'commerce_booking.order_id', 'commerce_booking.cart_id', 'fullname', 'email', 'commerce_booking.delivery_amount', 'commerce_booking.delivery_discount_amount', 'commerce_booking.discount_amount', 'commerce_booking.insurance_amount')
			->leftJoin('minimi_user_data', 'commerce_booking.user_id', '=', 'minimi_user_data.user_id')
			->leftJoin('commerce_group_buy', 'commerce_booking.cg_id', '=', 'commerce_group_buy.cg_id')
			->where('paid_status', 1)
			->whereIn('transaction_type', $transaction_type)
			->where('cancel_status', 0)
			->orderBy('commerce_booking.created_at', $order_by);

		if ($this->from_date != null) {
			$query->where('commerce_booking.created_at', '>=', $this->from_date . ' 00:00:00');
			// if ($transaction_type == null) {
			// 	$query->where('commerce_booking.created_at', '>=', $this->from_date);
			// } else {
			// 	$query->where('commerce_group_buy.created_at', '>=', $this->from_date);
			// }
		}

		if ($this->to_date != null) {
			$query->where('commerce_booking.created_at', '<=', $this->to_date . ' 23:59:59');
			// if ($transaction_type == null) {
			// 	$query->where('commerce_booking.created_at', '<=', $this->to_date);
			// } else {
			// 	$query->where('commerce_group_buy.created_at', '>=', $this->to_date);
			// }
		}

		$orders = $query->get();
		$items = DB::table('commerce_shopping_cart_item')
			->select('cart_id', 'product_name', 'variant_name', 'commerce_shopping_cart_item.price_amount', 'commerce_shopping_cart_item.count', 'commerce_shopping_cart_item.total_amount')
			->leftJoin('minimi_product', 'commerce_shopping_cart_item.product_id', '=', 'minimi_product.product_id')
			->leftJoin('minimi_product_variant', 'commerce_shopping_cart_item.variant_id', '=', 'minimi_product_variant.variant_id')
			->where('commerce_shopping_cart_item.status', 1)
			->get();
		$items = collect($items);

		$data = [];
		$i = 0;
		foreach ($orders as $row) {
			$cart_id = $row->cart_id;
			$item = $items->filter(function ($item) use ($cart_id) {
				if ($item->cart_id == $cart_id) {
					return $item;
				}
			})->values();
			$counter = 0;
			foreach ($item as $col) {
				$data[] = new \stdClass;
				if ($counter == 0) {
					$discount_amount = $row->discount_amount;
					$delivery_amount = $row->delivery_amount - $row->delivery_discount_amount;
					$insurance_amount = $row->insurance_amount;
				} else {
					$discount_amount = 0;
					$delivery_amount = 0;
					$insurance_amount = 0;
				}
				if ($mode == 3) {
					$data[$i]->cg_id = $row->cg_id;
					switch ($row->groupbuy_status) {
						case 0:
							$groupbuy_status = "0 : Expired (Waiting for verification)";
							break;
						case 1:
							$groupbuy_status = "1 : Waiting For Participant Payment";
							break;
						case 2:
							$groupbuy_status = "2 : Participant Requirement Not Met";
							break;
						case 3:
							$groupbuy_status = "3 : Participant Met (Waiting for verification)";
							break;
						default:
							$groupbuy_status = "4 : Verified";
							break;
					}
					$data[$i]->groupbuy_status = $groupbuy_status;
					$data[$i]->total_participant = $row->total_participant;
				}
				$data[$i]->created_at = $row->created_at;
				$data[$i]->order_id = $row->order_id;
				$data[$i]->fullname = $row->fullname;
				$data[$i]->email = $row->email;
				$data[$i]->product_name = $col->product_name;
				$data[$i]->variant_name = $col->variant_name;
				$data[$i]->price_amount = $col->price_amount;
				$data[$i]->count = $col->count;
				$data[$i]->total_amount = $col->total_amount;
				$data[$i]->discount_amount = $discount_amount;
				$data[$i]->delivery_amount = $delivery_amount;
				$data[$i]->insurance_amount = $insurance_amount;
				$data[$i]->total = $col->total_amount + $delivery_amount + $insurance_amount - $discount_amount;

				$counter++;
				$i++;
			}
		}

		if ($file_name == null) {
			$file_name = "Data Order Verification";
		}

		if ($mode == 1) {
			$grand_total = app('App\Http\Controllers\Master\OrderController')->getGrandTotal();
		} elseif ($mode == 3) {
			$grand_total = app('App\Http\Controllers\Master\OrderController')->getGrandTotalGroupBuy();
		}

		$return['data']['grand_total'] = $grand_total;
		$return['file_name'] = $file_name . " " . date('d F Y');
		$return['data']['orders'] = $data;
		$return['data']['mode'] = $mode;
		$return['template'] = 'exports.order_verification';
		return $return;
	}

	private function order_delivery()
	{
		$orders = DB::table('commerce_booking')
			->select('commerce_booking.*', 'fullname', 'address_pic', 'address_phone', 'address_detail', 'address_subdistrict_name', 'address_city_name', 'address_postal_code', 'commerce_shopping_cart.total_weight')
			->leftJoin('minimi_user_data', 'commerce_booking.user_id', '=', 'minimi_user_data.user_id')
			->leftJoin('minimi_user_address', 'commerce_booking.address_id', '=', 'minimi_user_address.address_id')
			->leftJoin('commerce_shopping_cart', 'commerce_booking.cart_id', '=', 'commerce_shopping_cart.cart_id')
			->where('admin_verified', 1)
			->whereIn('transaction_type', [1, 3])
			->orderBy('commerce_booking.created_at', 'asc')
			->get();
		$items = DB::table('commerce_shopping_cart_item')
			->select('cart_id', 'product_name', 'variant_name', 'commerce_shopping_cart_item.count')
			->leftJoin('minimi_product', 'commerce_shopping_cart_item.product_id', '=', 'minimi_product.product_id')
			->leftJoin('minimi_product_variant', 'commerce_shopping_cart_item.variant_id', '=', 'minimi_product_variant.variant_id')
			->where('commerce_shopping_cart_item.status', 1)
			->get();
		$items = collect($items);

		foreach ($orders as $row) {
			$cart_id = $row->cart_id;
			$item = $items->filter(function ($item) use ($cart_id) {
				if ($item->cart_id == $cart_id) {
					return $item;
				}
			})->values();
			$row->items = $item;
		}

		$return['file_name'] = "Data Order Delivery " . date('d F Y');
		$return['data']['orders'] = $orders;
		$return['template'] = 'exports.order_delivery';
		return $return;
	}

	private function voucher_used()
	{
		$vouchers = Models\Cart::select('order_id', 'fullname', 'commerce_shopping_cart.created_at', 'voucher_code', 'voucher_type', 'commerce_shopping_cart.discount_amount')
			->leftJoin('commerce_booking', 'commerce_shopping_cart.cart_id', '=', 'commerce_booking.cart_id')
			->leftJoin('minimi_user_data', 'commerce_shopping_cart.user_id', '=', 'minimi_user_data.user_id')
			->leftJoin('commerce_voucher', 'commerce_shopping_cart.voucher_id', '=', 'commerce_voucher.voucher_id')
			->where('commerce_booking.paid_status', 1)
			->where('commerce_booking.cancel_status', 0)
			->whereNotNull('commerce_shopping_cart.voucher_id')
			->orderBy('commerce_booking.created_at', 'asc')
			->get();

		foreach ($vouchers as $row) {
			switch ($row->voucher_type) {
				case 1:
					$row->voucher_type = "Transaction";
					break;
				case 2:
					$row->voucher_type = "Item";
					break;
				default:
					$row->voucher_type = "Delivery";
					break;
			}
		}
		$return['file_name'] = "Used Voucher Data " . date('d F Y');
		$return['data'] = $vouchers;
		$return['headings'] = array('Order ID', 'Name', 'Transaction Date', 'Kode', 'Voucher Type', 'Nominal');
		return $return;
	}
}
