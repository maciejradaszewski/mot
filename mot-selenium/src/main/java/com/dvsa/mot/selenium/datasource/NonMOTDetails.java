package com.dvsa.mot.selenium.datasource;

public class NonMOTDetails {
    //public static final NonMOTDetails Non_MOT_Details_1 = new NonMOTDetails("FNZ6110","Partial Vin", "1M8GDM9AXKP042788", "Renault", "Clio","V1264","Bristol");
    //public static final NonMOTDetails Non_MOT_Details_1 = new NonMOTDetails("DII4454","Partial Vin", "1M7GDM9AXKP042777", "Renault", "Clio","V1264","Bristol");
    public static final NonMOTDetails Non_MOT_Details_1 =
            new NonMOTDetails("FSR6110", "Partial Vin", "1M8GDM9MFSP042788", "Renault", "Clio",
                    "V1264", "Bristol");
    public static final NonMOTDetails Non_MOT_Invalid_Details_LN =
            new NonMOTDetails("", "", "", "", "", "V111", "");
    public static final NonMOTDetails Non_MOT_Invalid_Details_Spl_Chars =
            new NonMOTDetails("", "", "", "", "", "£%£$%$^%$!@£@$£%", "");
    public static final NonMOTDetails Non_MOT_Invalid_Details_Num_Spl_Chars =
            new NonMOTDetails("", "", "", "", "", "123$%^%&^*&*", "");
    public static final NonMOTDetails Non_MOT_Invalid_Details_Letters_Spl_Chars =
            new NonMOTDetails("", "", "", "", "", "DFDFDVbgbgfb£$£%$%$^&", "");
    //public static final NonMOTDetails Non_MOT_Details_2 = new NonMOTDetails("FNZ6110","Partial Vin","1M8GDM9AXKP042788","Renault","Clio","V12346","");
    public static final NonMOTDetails Non_MOT_Details_2 =
            new NonMOTDetails("DII4454", "Partial Vin", "1M7GDM9AXKP042777", "Renault", "Clio",
                    "V12346", "");

    public final String regNumber;
    public final String VinChaType;
    public final String VinChassisNumber;
    public final String Make;
    public final String Model;
    public final String SiteNumber;
    public final String Location;

    //public NonMOTDetails(String regNumber, String VinChaType, String VinChassisNumber, String Make, String Model, String odometerReading, String brakeType, String vehicleWeightFront, String vehicleWeightRear, String riderWeight, String isSidecarAttached, String sidecarWeight)
    public NonMOTDetails(String regNumber, String VinChaType, String VinChassisNumber, String Make,
            String Model, String SiteNumber, String Location) {
        this.regNumber = regNumber;
        this.VinChaType = VinChaType;
        this.VinChassisNumber = VinChassisNumber;
        this.Make = Make;
        this.Model = Model;
        this.SiteNumber = SiteNumber;
        this.Location = Location;
    }
}
