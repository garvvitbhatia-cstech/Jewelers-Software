<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/4/en/views.html#the-app-view
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
    }

    #encryptData
    public function encryptData($value = NULL){
        if(!empty($value)){
            $value = trim(preg_replace('/\s+/', ' ', $value));
           // date_default_timezone_set('UTC');
            $encryptionMethod = "AES-256-CBC";
            $secret = "MYSECURITYSS12020PKSEncryption19";  //must be 32 char length
            $iv = substr($secret, 0, 16);
            $encryptedText = openssl_encrypt($value, $encryptionMethod, $secret,0,$iv);
            $result = "";
            if($encryptedText != ""){
                $result = trim($encryptedText);
            }
            return $result;
        }else{
            return $value;
        }
    }

    #decryptData
    public function decryptData($value = NULL){
        if(!empty($value)){
            //date_default_timezone_set('UTC');
            $encryptionMethod = "AES-256-CBC";
            $secret = "MYSECURITYSS12020PKSEncryption19";  //must be 32 char length
            $iv = substr($secret, 0, 16);
            $decryptedText = openssl_decrypt($value, $encryptionMethod, $secret,0,$iv);
            $result = "";
            if($decryptedText != ""){
                $result = trim($decryptedText);
            }
            return $result;
        }else{
            return $value;
        }
    }
}
