<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExpiringCalc
 *
 * @author josep
 */
class ExpiringCalc {
    static public function getExpiringData($responseData, /*0 locker, 1 requirer*/
                                      $for = 0)
    {
        $addSecs = -60;
        if ($for == 1) {
            $addSecs = 60;
        }
        return $responseData["lockInfo"]["locker"]["time"] + WikiGlobalConfig::getConf("locktime") + $addSecs;
    }

    public static function getExpiringTime($responseData, /*0 locker, 1 requirer*/
                                      $for = 0)
    { // afegeix 1 minut si es tracta del requeridor o 0 minuts si es locker
        return (self::getExpiringData($responseData, $for) - time()) * 1000;
    }


}
