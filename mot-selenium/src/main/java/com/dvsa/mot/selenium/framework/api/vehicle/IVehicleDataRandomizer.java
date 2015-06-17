package com.dvsa.mot.selenium.framework.api.vehicle;


public interface IVehicleDataRandomizer {

    public String nextVin();

    public String nextReg();

    public String nextVin(int length);

    public String nextReg(int length);
}
