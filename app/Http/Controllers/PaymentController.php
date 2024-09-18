<?php

namespace App\Http\Controllers;

use App\Models\CoinPackage;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    // Crea il pagamento per un pacchetto selezionato
    public function createPayment(Request $request)
    {
        // Valida l'input per assicurarsi che venga inviato un ID di pacchetto valido
        $request->validate([
            'package_id' => 'required|exists:coin_packages,id',
        ]);

        // Recupera il pacchetto dal database usando l'ID
        $package = CoinPackage::findOrFail($request->package_id);

        // Imposta PayPal
        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $paypalToken = $paypal->getAccessToken();
        $paypal->setAccessToken($paypalToken);

        // Crea l'ordine PayPal
        $order = $paypal->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "EUR",
                        "value" => $package->price,  // Prezzo del pacchetto
                    ]
                ]
            ]
        ]);

        return response()->json($order);
    }

    // Esegue il pagamento
    public function executePayment(Request $request)
    {
        // Valida l'input per assicurarsi che l'ID dell'ordine PayPal sia presente
        $request->validate([
            'orderID' => 'required',
            'package_id' => 'required|exists:coin_packages,id',
        ]);

        // Recupera il pacchetto acquistato
        $package = CoinPackage::findOrFail($request->package_id);

        // Imposta PayPal
        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $paypalToken = $paypal->getAccessToken();
        $paypal->setAccessToken($paypalToken);

        // Cattura il pagamento dall'ordine
        $result = $paypal->capturePaymentOrder($request->orderID);

        if ($result['status'] === 'COMPLETED') {
            // Crea un record dell'acquisto nella tabella purchases
            $purchase = Purchase::create([
                'user_id' => auth()->id(),
                'transaction_id' => $result['id'],
                'amount' => $result['purchase_units'][0]['amount']['value'],
                'coins' => $package->coins,  // Monete del pacchetto acquistato
                'status' => 'completed',
            ]);

            // Aggiorna il saldo di monete dell'utente
            $user = auth()->user();
            $user->coins += $package->coins;
            $user->save();

            return response()->json(['message' => 'Payment successful']);
        }

        return response()->json(['message' => 'Payment failed'], 500);
    }
}