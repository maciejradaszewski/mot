package com.dvsa.mot.selenium.framework.api.vehicle;

import org.apache.commons.lang3.StringUtils;

public class SequentialVehicleDataRandomizer implements IVehicleDataRandomizer {

    private long vinCounter = 1;
    private long regCounter = 1;
    private String prefix;


    public SequentialVehicleDataRandomizer(String prefix) {
        this.prefix = prefix;
    }

    public synchronized String nextVin() {
        return nextVin(17);
    }

    public synchronized String nextVin(int length) {
        int counterLength = length - prefix.length();
        return (prefix + StringUtils.leftPad(Long.toString(vinCounter++, 36), counterLength, '0'))
                .toUpperCase();
    }

    public synchronized String nextReg() {
        return nextReg(7);
    }

    public synchronized String nextReg(int length) {
        int counterLength = length - prefix.length();
        return (prefix + StringUtils.leftPad(Long.toString(regCounter++, 36), counterLength, '0'))
                .toUpperCase();
    }
}
