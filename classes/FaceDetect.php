<?php
/**
 * Created by PhpStorm.
 * User: ATasker
 * Date: 9/21/18
 * Time: 6:06 PM
 */

// Be careful updating "ridvanbaluyos/face": "v1.1" in composer
// It might affect the functionality of this class

include __DIR__ . '/../inc.php';
use Ridvanbaluyos\Face\FaceDetection as FaceDetection;

class FaceDetect extends FaceDetection {

    public $image;
    public $faceDetect;

    public function __construct($image) {
        $this->image = ["url" => $image];
        $this->faceDetect = new FaceDetection($this->image);
    }

    public function analyzeFace() {
        try {
            $face = $this->faceDetect->analyzeAll()->getFaces();
        } catch (Exception $e) {
            return 'Caught exception: '.  $e->getMessage(). "\n";
        }
        $array = json_decode($face, true);
        return $array;
    }

}