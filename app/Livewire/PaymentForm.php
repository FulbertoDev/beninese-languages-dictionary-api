<?php

namespace App\Livewire;

use App\Helpers\MonerooHelpers;
use App\Models\Payment;
use Faker\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class PaymentForm extends Component
{

    public $lastName;
    public $firstName;
    public $phone;
    public $deviceUuid;

    public $amount;

    public $reason;
    public $minAmount;


    public function mount($data)
    {
        $this->lastName = $data['last_name'];
        $this->firstName = $data['first_name'];
        $this->phone = $data['contact'];
        $this->deviceUuid = $data['deviceUuid'];
        $this->amount = (int)$data['amount'];
        $this->reason = $data['reason'];
        if ($this->reason == "gift") {
            $this->minAmount = 100;
        } else {
            $this->minAmount = 995;
        }
    }

    #[Computed]
    protected function isFormValid(): bool
    {
        return isset($this->firstName) &&
            isset($this->lastName) &&
            isset($this->deviceUuid) &&
            isset($this->amount) &&
            isset($this->reason) &&
            isset($this->phone) && $this->amount >= $this->minAmount;
    }

    public function generatePaymentLink()
    {

        try {
            $payment = new Payment();
            $payment->last_name = $this->lastName;
            $payment->first_name = $this->firstName;
            $payment->amount = $this->amount;
            $payment->deviceUuid = $this->deviceUuid;;
            $payment->contact = $this->phone;
            $payment->reason = $this->reason;
            $payment->saveOrFail();

            Log::critical('$payment save passed');


            $headers = array("Authorization" => "Bearer " . env('MONEROO_SECRET_LIVE_KEY'));
            $faker = Factory::create();

            $data = [
                "amount" => $payment->amount,
                "currency" => MonerooHelpers::currency,
                "description" => $this->reason ?: "Paiement #" . $payment->id,
                "customer" => [
                    "email" => $faker->email(),
                    "first_name" => $this->firstName,
                    "last_name" => $this->lastName,
                    "phone" => (int)$this->phone,
                ],
                "return_url" => route('support.thanks'),
                "metadata" => [
                    "payment" => $payment->id,
                ],
            ];

            Log::critical('Payload to send ' . json_encode($data));

            $response = Http::withHeaders($headers)->post(env('MONEROO_BASE_URL') . MonerooHelpers::paymentInitUrl, $data);

            if ($response->status() == 201) {
                $jsonData = $response->json();
                $data = $jsonData["data"];
                $url = $data["checkout_url"];
                $this->redirect($url);
            }


        } catch (\Exception $exception) {
            Log::critical('Error when initializing moneroo payment: ' . $exception);
            Toaster::error('Une erreur est survenue lors de la génération du lien de paiement.');
        }

    }

    public function render()
    {
        return view('livewire.payment-form');
    }
}
