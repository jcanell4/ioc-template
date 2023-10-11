<?php
/**
 * ExpiringCalc: Funcions d'utilitat pel control del bloqueix
 *
 * @author josep
 */
class ExpiringCalc {

    /**
     * Calcula el temps que falta per expirar el bloqueix
     * @param type $responseData
     * @param {integer} $for : 0=locker, 1=requirer. Afegeix 1 minut si es el requeridor o 0 si es locker
     * @return {time}
     */
    static public function getExpiringData($responseData, $for = 0) {
        $addSecs = ($for === 1) ? 60 : -60;
        $val = ($responseData) ? $responseData["lockInfo"]["locker"]["time"] : time();
        if (!$val && is_numeric($responseData))
            $val = $responseData;
        return $val + WikiGlobalConfig::getConf("locktime") + $addSecs;
    }

    /**
     * Retorna els segons que falten per expirar el bloqueix
     * @param type $responseData
     * @param {integer} $for : 0=locker, 1=requirer. Afegeix 1 minut si es el requeridor o 0 si es locker
     * @return {integer} segundos
     */
    public static function getExpiringTime($responseData, $for = 0) {
        return (self::getExpiringData($responseData, $for) - time()) * 1000;
    }

}
