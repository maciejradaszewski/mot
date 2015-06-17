package com.dvsa.mot.selenium.datasource;

public class VTSDetails {

    public final static VTSDetails VTSDetails1 =
            new VTSDetails("test_vts", "55", "Lower Castle", "Old Market", "Bristol", "Bs13AD",
                    "01180987655", "testvts@email.com");
    public final String vtsName;
    public final String address1;
    public final String address2;
    public final String address3;
    public final String town;
    public final String postCode;
    public final String phoneNo;
    public final String emailAdd;

    public VTSDetails(String vtsName, String address1, String address2, String address3,
            String town, String postCode, String phoneNo, String emailAdd) {
        super();
        this.vtsName = vtsName;
        this.address1 = address1;
        this.address2 = address2;
        this.address3 = address3;
        this.town = town;
        this.postCode = postCode;
        this.phoneNo = phoneNo;
        this.emailAdd = emailAdd;

    }
}
