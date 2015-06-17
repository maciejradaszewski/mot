package com.dvsa.mot.selenium.datasource;

public class Business {

    public static final Business BUSINESS_1 =
            new Business(null, BusinessDetails.BUSINESS_DETAILS_1, Address.ADDRESS_ADDRESS1);
    public static final Business BUSINESS_2 =
            new Business(null, BusinessDetails.BUSINESS_DETAILS_2, Address.ADDRESS_ADDRESS2);
    public static final Business BUSINESS_3 =
            new Business(null, BusinessDetails.BUSINESS_DETAILS_3, Address.ADDRESS_ADDRESS3);
    public static final Business BUSINESS_4 =
            new Business(null, BusinessDetails.BUSINESS_DETAILS_4, Address.ADDRESS_ADDRESS4);
    public static final Business BUSINESS_5 =
            new Business(null, BusinessDetails.BUSINESS_DETAILS_1, Address.ADDRESS_UTF8);
    public static final Business BUSINESS_6 =
            new Business(null, BusinessDetails.BUSINESS_DETAILS_13_REG_COMPANY,
                    Address.ADDRESS_ADDRESS1);
    public static final Business EXAMPLE_AE_INC =
            new Business("9", BusinessDetails.EXAMPLE_AE_INC, Address.ADDRESS_EXAMPLE_AE_INC);
    public static final Business CRAZY_WHEELS_INC =
            new Business("10", BusinessDetails.CRAZY_WHEELS, Address.ADDRESS_CRAZY_WHEELS_INC);


    public final String busId;
    public final Address busAddress;
    public final BusinessDetails busDetails;

    public Business(String busId, BusinessDetails busDetails, Address busAddress) {
        super();
        this.busId = busId;
        this.busAddress = busAddress;
        this.busDetails = busDetails;
    }

    public String getBusId() {
        return busId;
    }
}
