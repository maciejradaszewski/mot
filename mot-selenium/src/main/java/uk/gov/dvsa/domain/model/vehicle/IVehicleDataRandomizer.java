package uk.gov.dvsa.domain.model.vehicle;

public interface IVehicleDataRandomizer {

    public String nextVin();

    public String nextReg();

    public String nextVin(int length);

    public String nextReg(int length);
}

