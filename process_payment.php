<?php
session_start();
header('Content-Type: application/json');

    class Avarda {
    private $base_url = "https://stage.checkout-api.avarda.com";
    private $client_id = "e2c163a3-4130-49da-9594-9c722f8f9993";
    private $client_secret = "q.31k.YlPLuzl6I5.~4ByH_d8eu4rfBj4D";
    
    // Function to get an access token from Avarda
    function getPartnerAccessToken() {
        $url = "{$this->base_url}/api/partner/tokens";
        $request_payload = json_encode([
            'clientId' => $this->client_id, 
            'clientSecret' => $this->client_secret
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_payload);
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log('cURL error: ' . curl_error($ch));
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        $data = json_decode($result, true);
        return $data['token'] ?? false;
    }

    // Function to initialize a payment using the access token
    function initializePayment($token, $orderData) {
        $url = "{$this->base_url}/api/partner/payments";
        $request_payload = json_encode($orderData);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Content-type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_payload);
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log("cURL error: " . curl_error($ch));
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return json_decode($result, true);
    }
}

// Usage of the class
$avarda = new Avarda();
$token = $avarda->getPartnerAccessToken();
if ($token) {
    $orderData = [
        'items' => [
            [
                'description' => 'Test Item',
                'amount' => 100,
                'quantity' => 1
            ]
        ],
        'checkoutSetup' => [
            'language' => 'English'
        ]
    ];
    $widgetData = $avarda->initializePayment($token, $orderData);
    if (!$widgetData) {
        echo json_encode(['error' => 'Error initializing payment with Avarda']);
    } else {
        echo json_encode($widgetData);
    }
} else {
    echo json_encode(['error' => 'Failed to obtain access token from Av']); 
}
?>
    

    