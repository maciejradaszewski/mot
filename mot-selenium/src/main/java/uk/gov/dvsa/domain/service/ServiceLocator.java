package uk.gov.dvsa.domain.service;

public class ServiceLocator {

    public static UserService getUserService(){
        return new UserService();
    }

    public static VehicleService getVehicleService() {
        return new VehicleService();
    }

    public static MotTestService getMotTestService() {
        return new MotTestService();
    }

    public static AeService getAeService(){
        return new AeService();
    }

    public static SiteService getSiteService() {
        return new SiteService();
    }
}
