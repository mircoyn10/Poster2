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
        // Valida l'input per il pacchetto selezionato
        $request->validate([
            'package_id' => 'required|exists:coin_packages,id',
        ]);

        // Salva il package_id nella sessione
        session()->put('package_id', $request->package_id);

        // Crea l'ordine PayPal
        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $paypalToken = $paypal->getAccessToken();
        $paypal->setAccessToken($paypalToken);

        $package = CoinPackage::findOrFail($request->package_id);

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
            ],
            "application_context" => [
                "return_url" => route('payment.success'),  // Assicurati che questo sia corretto
                "cancel_url" => route('payment.cancel'),   // Assicurati che questo sia corretto
            ]
        ]);

        return response()->json($order);
    }


    // Esegue il pagamento
    public function executePayment(Request $request)
    {
        // PayPal fornisce un token nell'URL come query string
        $orderID = $request->query('token');  // 'token' è usato da PayPal per identificare l'ordine

        \Log::info('Token ricevuto da PayPal:', ['token' => $orderID]);

        // Verifica se il token esiste
        if (!$orderID) {
            \Log::error('Token mancante nel reindirizzamento di PayPal');
            return redirect()->route('dashboard')->with('error', 'Token mancante per il pagamento');
        }

        // Recupera il pacchetto dalla sessione
        $packageId = session()->get('package_id');
        \Log::info('Package ID recuperato dalla sessione:', ['package_id' => $packageId]);

        if (!$packageId) {
            \Log::error('Package ID mancante nella sessione');
            return redirect()->route('dashboard')->with('error', 'Errore con il pacchetto di acquisto');
        }

        $package = CoinPackage::findOrFail($packageId);

        try {
            // Imposta PayPal
            $paypal = new PayPalClient;
            $paypal->setApiCredentials(config('paypal'));
            $paypalToken = $paypal->getAccessToken();
            \Log::info('Access Token PayPal ricevuto:', ['token' => $paypalToken]);

            $paypal->setAccessToken($paypalToken);

            // Cattura il pagamento
            $result = $paypal->capturePaymentOrder($orderID);
            \Log::info('Risultato della cattura del pagamento PayPal:', $result);

            if (isset($result['status']) && $result['status'] === 'COMPLETED') {
                // Recupera l'importo del pagamento catturato
                $amount = $result['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                \Log::info('Importo catturato:', ['amount' => $amount]);

                // Salva l'acquisto nel database
                Purchase::create([
                    'user_id' => auth()->id(),
                    'transaction_id' => $result['purchase_units'][0]['payments']['captures'][0]['id'],
                    'amount' => $amount,
                    'coins' => $package->coins,
                    'status' => 'completed',
                ]);

                // Aggiorna i coin dell'utente
                $user = auth()->user();
                $user->coin += $package->coins;  // Usa 'coin' al posto di 'coins'
                $user->save();
                \Log::info('Pagamento completato con successo e saldo aggiornato.');

                return redirect()->route('dashboard')->with('success', 'Pagamento completato con successo!');
            } else {
                \Log::error('Cattura del pagamento fallita:', $result);
                return redirect()->route('dashboard')->with('error', 'Cattura del pagamento fallita');
            }
        } catch (\Exception $e) {
            \Log::error('Errore durante la cattura del pagamento:', ['error' => $e->getMessage()]);
            return redirect()->route('dashboard')->with('error', 'Si è verificato un errore durante la cattura del pagamento');
        }
    }







}