<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/22/18
 * Time: 12:13 PM
 */

include __DIR__ . '/../../inc.php';

class Face {

    private $url;
    private $subscriptionKey;
    private $image;
    private $returnFaceId = false;
    private $returnFaceLandmarks = false;
    private $returnFaceAttributes = "";

    public function __construct($image) {
        $this->subscriptionKey = "4b60a27087dd44a683bfca479ee12eba";
        $this->url = "https://eastus.api.cognitive.microsoft.com/face/v1.0/detect";
        $this->image = $image;
    }

    public function getFaces() {
        $params = array(
            'returnFaceId' => $this->returnFaceId,
            'returnFaceLandmarks' => $this->returnFaceLandmarks,
            'returnFaceAttributes' => $this->returnFaceAttributes
        );

        $query = http_build_query($params);
        $image = json_encode($this->image);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . '?' . $query);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $image);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Ocp-Apim-Subscription-Key: ' . $this->subscriptionKey,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($image)
            )
        );
        $response = curl_exec($ch);

        return $response;
    }

    public function analyzeFaceLandmarks() {
        $this->returnFaceLandmarks = 'true';
        return $this;
    }

    public function analyzeAll() {
        $this->returnFaceLandmarks = 'false';
        $this->returnFaceAttributes = 'age,gender,headPose,smile,facialHair,glasses,emotion,blur,exposure,noise,makeup,accessories,occlusion,hair';
        return $this;
    }

}
