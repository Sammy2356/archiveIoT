<?php

class ApiClient {
    private $api_url;
    private $api_key;

    public function __construct($api_url, $api_key) {
        $this->api_url = $api_url;
        $this->api_key = $api_key;
    }

    public function getApiData() {
        // Initialize cURL session
        $curl = curl_init();

        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url,
            CURLOPT_RETURNTRANSFER => true,  // Return response as a string instead of outputting it
            CURLOPT_FOLLOWLOCATION => true,  // Follow redirects
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json', // Set the Content-Type header if needed
                'Authorization: Bearer ' . $this->api_key, // Add any necessary authentication headers
            ),
        ));

        // Execute the request and get the response
        $response = curl_exec($curl);

        // Check for errors
        if ($response === false) {
            $error_message = curl_error($curl);
            // Handle the error
            return "Error: " . $error_message;
        }

        // Close cURL session
        curl_close($curl);

        // Process the response
        if ($response) {
            $data = json_decode($response, true); // Convert JSON response to an associative array
            return $data;
        } else {
            // Handle empty response
            return "Empty response";
        }
    }
}

// Example usage:
// $api_url = 'https://api.example.com/data';
// $api_key = 'YOUR_API_KEY';

// $apiClient = new ApiClient($api_url, $api_key);
// $result = $apiClient->getApiData();
// print_r($result);

?>
