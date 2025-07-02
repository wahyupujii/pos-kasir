<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Validation\ValidationException;

class Pos extends Component
{
    public $search = '';
    public $product;
    public $order;
    public $total_price;
    public $paid_amount;

    public function render()
    {
        $this->order = Order::where('done_at', null)
            ->where('user_id', auth()->id())
            ->with('orderProducts')
            ->latest()
            ->first();
        $this->total_price = $this->order->total_price ?? 0;
        return view('livewire.pos', [
            'products' => Product::search($this->search)->paginate(12),
            'order' => $this->order
        ]);
    }

    public function createOrder()
    {
        $this->order = Order::where('done_at', null)
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        if ($this->order ==  null) {
            $this->order = Order::create([
                'user_id' => auth()->id(),
                'invoice_number' => $this->generateUniqueCode()
            ]);
        }
        session()->flash('message', 'Sukses mulai transaksi, silakan pilih produk.');
    }

    public function updateCart($productId, $isAdded = true)
    {
        try {
            if ($this->order) {
                $product = Product::findOrFail($productId);
                $orderProduct = OrderProduct::where('order_id', $this->order->id)
                    ->where('product_id', $productId)
                    ->first();

                if ($orderProduct) {
                    if ($isAdded) {
                        $orderProduct->increment('quantity', 1);
                    } else {
                        $orderProduct->decrement('quantity', 1);
                        if ($orderProduct->quantity < 1) {
                            $orderProduct->delete();
                            session()->flash('message', 'Produk berhasil dihapus dari keranjang');
                            return;
                        }
                    }
                    $orderProduct->save();
                } else {
                    if ($isAdded) {
                        OrderProduct::create([
                            'order_id' => $this->order->id,
                            'product_id' => $product->id,
                            'unit_price' => $product->selling_price,
                            'quantity' => 1
                        ]);
                    }
                }
                $this->total_price = $this->order->total_price ?? 0;

                session()->flash('message', $isAdded ? 'Produk berhasil ditambahkan' : 'Produk berhasil dihapus dari keranjang');
            } else {
                session()->flash('message', 'Klik Mulai Transaksi Dahulu');
            }
        } catch (ValidationException $e) {
            dd($e);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function payment()
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $this->order->id,
                'gross_amount' => $this->order->total_price,
            ),
            'customer_details' => array(
                'first_name' => $this->order->user->name,
                'email' => $this->order->user->email,
            ),
        );

        $orderProductList = OrderProduct::with('product')->where('order_id', $this->order->id);

        $params['item_details'] = $orderProductList->get()->map(function ($orderProduct) {
            return [
                'price' => $orderProduct->unit_price,
                'quantity' => $orderProduct->quantity,
                'name' => $orderProduct->product->name,
            ];
        })->toArray();

        $snapUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

        $this->order->update([
            'payment_url' => $snapUrl
        ]);

        return redirect($snapUrl);
    }

    public function done()
    {
        $this->validate([
            'paid_amount' => 'required'
        ]);

        $this->order->update([
            'paid_amount' => $this->paid_amount,
            'done_at' => now()
        ]);

        session()->flash('message', 'Order/Transaksi selesai');
        return redirect()->route('order');
    }

    function generateUniqueCode($length = 6)
    {
        $number = uniqid();
        $varray = str_split($number);
        $len = sizeof($varray);
        $uniq = array_slice($varray, $len - 6, $len);
        $uniq = implode(",", $uniq);
        $uniq = str_replace(',', '', $uniq);

        return $uniq;
    }
}
