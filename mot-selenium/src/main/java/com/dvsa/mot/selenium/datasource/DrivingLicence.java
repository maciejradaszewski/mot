package com.dvsa.mot.selenium.datasource;

/**
 * Created by paweltom on 28/02/2014.
 */


/**
 * Driving Licence object
 */
public class DrivingLicence {
    public final String drivingLicenceNo;

    public DrivingLicence(String drivingLicenceNo) {
        super();
        this.drivingLicenceNo = drivingLicenceNo;
    }

    /**
     * Driving Licence test data (UK)
     */
    public static final DrivingLicence AEDM1_DRIVINGLICENCENO = new DrivingLicence("31209877");
    public static final DrivingLicence AEDM2_DRIVINGLICENCENO = new DrivingLicence("31298776");
    public static final DrivingLicence AEDM3_DRIVINGLICENCENO = new DrivingLicence("31233333");
    public static final DrivingLicence AEP1_DRIVINGLICENCENO = new DrivingLicence("3455676");

    /**
     * Driving Licence test data (UK)
     */
    public static final DrivingLicence DRIVINGLICENCENO_UK_VALID_1 =
            new DrivingLicence("ROBIN757025CJ99901");
    public static final DrivingLicence DRIVINGLICENCENO_UK_VALID_2 =
            new DrivingLicence("WERDE876545ER4RT");
    public static final DrivingLicence DRIVINGLICENCENO_UK_VALID_3 =
            new DrivingLicence("LYNHE234188TA9GG");
    public static final DrivingLicence DRIVINGLICENCENO_UK_INVALID_1 =
            new DrivingLicence("1234567");
    public static final DrivingLicence DRIVINGLICENCENO_UK_INVALID_2 =
            new DrivingLicence("098JUJ9");
    public static final DrivingLicence DRIVINGLICENCENO_UK_INVALID_3 =
            new DrivingLicence("44RR55R");

    /**
     * Driving Licence test data (NI)
     */
    public static final DrivingLicence DRIVINGLICENCENO_NI_VALID_1 = new DrivingLicence("12345678");
    public static final DrivingLicence DRIVINGLICENCENO_NI_VALID_2 = new DrivingLicence("09898773");
    public static final DrivingLicence DRIVINGLICENCENO_NI_VALID_3 = new DrivingLicence("23433231");
    public static final DrivingLicence DRIVINGLICENCENO_NI_INVALID_1 =
            new DrivingLicence("QW23344559092");
    public static final DrivingLicence DRIVINGLICENCENO_NI_INVALID_2 =
            new DrivingLicence("IU766776GGGTT");
    public static final DrivingLicence DRIVINGLICENCENO_NI_INVALID_3 =
            new DrivingLicence("HY65TXXXXXXXX");

    /**
     * Driving Licence test data (Other)
     */
    public static final DrivingLicence DRIVINGLICENCENO_OTHER_VALID_1 =
            new DrivingLicence("B072RRE2151");
    public static final DrivingLicence DRIVINGLICENCENO_OTHER_VALID_2 =
            new DrivingLicence("UI76TGW2211");
    public static final DrivingLicence DRIVINGLICENCENO_OTHER_VALID_3 =
            new DrivingLicence("LO34EVV3231");
    public static final DrivingLicence DRIVINGLICENCENO_OTHER_INVALID_1 = new DrivingLicence("123");
    public static final DrivingLicence DRIVINGLICENCENO_OTHER_INVALID_2 = new DrivingLicence("321");
    public static final DrivingLicence DRIVINGLICENCENO_OTHER_INVALID_3 = new DrivingLicence("XXX");

}
