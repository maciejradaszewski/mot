package uk.gov.dvsa.domain.model.vehicle;

import uk.gov.dvsa.helper.RandomDataGenerator;

public class DefaultVehicleDataRandomizer implements IVehicleDataRandomizer {

    public synchronized String nextVin() {
        return nextVin(17);
    }

    public synchronized String nextVin(int length) {
        return RandomDataGenerator.generateRandomAlphaNumeric(length, System.nanoTime()).toUpperCase();
    }

    public synchronized String nextReg() {
        return nextReg(7);
    }

    public synchronized String nextReg(int length) {
        return RandomDataGenerator.generateRandomAlphaNumeric(length, System.nanoTime()).toUpperCase();
    }
}

